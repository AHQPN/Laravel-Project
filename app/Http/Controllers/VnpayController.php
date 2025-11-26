<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\Hoadon;
use App\Models\Khach;
use App\Models\Thanhtoan;
use App\Models\Ve;
use App\Models\CTHD;
use App\Models\Chuyendi;
use Illuminate\Support\Str;

class VnpayController extends Controller
{
    // Tạo mã hóa đơn (HD + yymmdd + 2 ký tự random)
    private function generateShortMaHoaDon(): string
    {
        do {
            $datePart = now()->format('ymd');
            $randPart = strtoupper(substr(base_convert(rand(0, 1295), 10, 36), 0, 2));
            $ma = 'HD' . $datePart . $randPart;
        } while (Hoadon::where('mahd', $ma)->exists());
        return $ma;
    }

    // Tạo URL thanh toán VNPay
    public function createPayment(Request $request)
    {
        try {
            // Validate dữ liệu đầu vào
            $validated = $request->validate([
                'machuyendi' => 'required',
                'seats' => 'required|string',
                'gia_ve' => 'required|numeric',
                'kh_hoten' => 'required|string',
                'kh_sdt' => 'required|string',
                'kh_email' => 'nullable|email',
                'ghi_chu' => 'nullable|string',
            ]);

            // Tính tổng tiền
            $seats = explode(',', $validated['seats']);
            $totalAmount = count($seats) * $validated['gia_ve'];

            // Lấy ID nhân viên từ session
            $nhanvien = session()->get('nhanvien');
            if (!$nhanvien) {
                return redirect()->route('nhan-vien-ban-ve.dang-nhap')
                    ->with('error', 'Phiên đăng nhập hết hạn. Vui lòng đăng nhập lại.');
            }

            // Lưu thông tin đặt vé vào session
            Session::put('vnpay_booking_data', [
                'machuyendi' => $validated['machuyendi'],
                'seats' => $validated['seats'],
                'gia_ve' => $validated['gia_ve'],
                'kh_hoten' => $validated['kh_hoten'],
                'kh_sdt' => $validated['kh_sdt'],
                'kh_email' => $validated['kh_email'] ?? '',
                'ghi_chu' => $validated['ghi_chu'] ?? '',
                'total_amount' => $totalAmount,
                'nhanvien_id' => $nhanvien->manv,
            ]);

            // Lấy cấu hình VNPay
            $vnp_TmnCode = env('VNP_TMN_CODE');
            $vnp_HashSecret = env('VNP_HASH_SECRET');
            $vnp_Url = env('VNP_URL');
            $vnp_Returnurl = route('vnpay.return', [], true);

            // Tạo mã đơn hàng duy nhất
            $vnp_TxnRef = 'VE' . date('YmdHis');
            $vnp_Amount = $totalAmount;
            $vnp_IpAddr = $request->ip();

            // Thông tin đơn hàng
            $orderInfo = "Thanh toan ve xe - " . $validated['kh_hoten'] . " - Ghe: " . $validated['seats'];

            $inputData = array(
                "vnp_Version" => "2.1.0",
                "vnp_TmnCode" => $vnp_TmnCode,
                "vnp_Amount" => $vnp_Amount * 100,
                "vnp_Command" => "pay",
                "vnp_CreateDate" => date('YmdHis'),
                "vnp_CurrCode" => "VND",
                "vnp_IpAddr" => $vnp_IpAddr,
                "vnp_Locale" => "vn",
                "vnp_OrderInfo" => $orderInfo,
                "vnp_OrderType" => "billpayment",
                "vnp_ReturnUrl" => $vnp_Returnurl,
                "vnp_TxnRef" => $vnp_TxnRef
            );

            // Sắp xếp tham số theo thứ tự alphabet
            ksort($inputData);
            $query = "";
            $i = 0;
            $hashdata = "";
            
            foreach ($inputData as $key => $value) {
                if ($i == 1) {
                    $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
                } else {
                    $hashdata .= urlencode($key) . "=" . urlencode($value);
                    $i = 1;
                }
                $query .= urlencode($key) . "=" . urlencode($value) . '&';
            }

            // Tạo URL thanh toán
            $vnp_Url = $vnp_Url . "?" . $query;
            if (isset($vnp_HashSecret)) {
                $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
                $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
            }

            Log::info('VNPay Payment URL Created', [
                'txn_ref' => $vnp_TxnRef,
                'amount' => $vnp_Amount,
                'return_url' => $vnp_Returnurl,
                'customer' => $validated['kh_hoten']
            ]);

            return redirect($vnp_Url);

        } catch (\Exception $e) {
            Log::error('VNPay Create Payment Error: ' . $e->getMessage());
            return redirect()->route('nhan-vien-ban-ve.dat-ve.create')
                ->with('error', 'Có lỗi xảy ra khi tạo thanh toán VNPay: ' . $e->getMessage());
        }
    }

    // Xử lý kết quả trả về từ VNPay
    public function vnpayReturn(Request $request)
    {
        try {
            // Lấy hash secret
            $vnp_HashSecret = env('VNP_HASH_SECRET');
            $inputData = array();
            
            // Lấy tất cả tham số vnp_
            foreach ($request->all() as $key => $value) {
                if (substr($key, 0, 4) == "vnp_") {
                    $inputData[$key] = $value;
                }
            }
            
            // Tách secure hash ra
            $vnp_SecureHash = $inputData['vnp_SecureHash'] ?? '';
            unset($inputData['vnp_SecureHash']);
            ksort($inputData);
            
            // Tạo lại hash data để verify
            $i = 0;
            $hashData = "";
            foreach ($inputData as $key => $value) {
                if ($i == 1) {
                    $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
                } else {
                    $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                    $i = 1;
                }
            }

            $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
            
            // Kiểm tra chữ ký
            if ($secureHash != $vnp_SecureHash) {
                Log::error('VNPay Invalid Signature');
                return redirect()->route('nhan-vien-ban-ve.dat-ve.create')
                    ->with('error', 'Chữ ký không hợp lệ! Giao dịch có thể bị giả mạo.');
            }

            // Kiểm tra mã phản hồi
            if ($request->vnp_ResponseCode == '00') {
                // Thanh toán thành công
                $bookingData = Session::get('vnpay_booking_data');
                
                if (!$bookingData) {
                    Log::error('VNPay: Booking data not found in session');
                    return redirect()->route('nhan-vien-ban-ve.dat-ve.create')
                        ->with('error', 'Không tìm thấy thông tin đặt vé. Vui lòng thử lại.');
                }

                DB::beginTransaction();
                try {
                    $chuyendi = Chuyendi::where('machuyendi', $bookingData['machuyendi'])->first();
                    if (!$chuyendi) {
                        throw new \Exception('Không tìm thấy chuyến đi.');
                    }

                    // Tìm hoặc tạo khách hàng
                    $khach = Khach::firstOrCreate(
                        ['sdt' => $bookingData['kh_sdt']],
                        [
                            'makh' => 'KH' . strtoupper(Str::random(6)),
                            'hoten' => $bookingData['kh_hoten'],
                            'email' => $bookingData['kh_email'],
                            'password' => \Hash::make('123456'),
                            'trangthai' => 'hoat-dong',
                        ]
                    );

                    // Tạo hoặc lấy phương thức thanh toán VNPay
                    $thanhtoan = Thanhtoan::firstOrCreate(
                        ['matt' => 'VP'],
                        ['ptthanhtoan' => 'VNPay']
                    );

                    $selectedSeats = explode(',', $bookingData['seats']);
                    $soluong = count($selectedSeats);
                    $thanhtien = $bookingData['total_amount'];

                    // Tạo hóa đơn
                    $hoadon = Hoadon::create([
                        'mahd' => $this->generateShortMaHoaDon(),
                        'makh' => $khach->makh,
                        'manv' => $bookingData['nhanvien_id'],
                        'thoigian' => now(),
                        'matt' => 'VP',
                        'soluong' => $soluong,
                        'thanhtien' => $thanhtien,
                        'trangthai' => 'Đã duyệt',
                    ]);

                    // Tạo vé và CTHD
                    $createdTickets = [];
                    foreach ($selectedSeats as $seatNumber) {
                        $datePartVe = now()->format('ymd');
                        $randVe = strtoupper(substr(base_convert(rand(0,1295),10,36),0,2));
                        $mave = 'VE' . $datePartVe . $randVe;

                        $seatCode = trim($seatNumber);

                        $ve = Ve::create([
                            'mave' => $mave,
                            'machuyendi' => $chuyendi->machuyendi,
                            'maghe' => $seatCode,
                            'trangthai' => 'Booked',
                        ]);

                        CTHD::create([
                            'mahd' => $hoadon->mahd,
                            'mave' => $ve->mave,
                            'dongia' => $bookingData['gia_ve'],
                        ]);

                        $createdTickets[] = $ve->mave;
                    }

                    DB::commit();
                    
                    // Xóa session
                    Session::forget('vnpay_booking_data');
                    
                    Log::info('VNPay Payment Success', [
                        'txn_ref' => $request->vnp_TxnRef,
                        'transaction_no' => $request->vnp_TransactionNo,
                        'invoice' => $hoadon->mahd,
                        'seats' => count($createdTickets)
                    ]);

                    return redirect()->route('nhan-vien-ban-ve.ve.index')
                        ->with('success', 'Thanh toán VNPay thành công! Mã hóa đơn: ' . $hoadon->mahd . '. Đã tạo ' . count($createdTickets) . ' vé.');

                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error('VNPay Save Ticket Error: ' . $e->getMessage());
                    
                    return redirect()->route('nhan-vien-ban-ve.dat-ve.create')
                        ->with('error', 'Thanh toán thành công nhưng có lỗi khi lưu vé: ' . $e->getMessage());
                }

            } else {
                // Thanh toán thất bại
                Session::forget('vnpay_booking_data');
                
                $errorMessages = [
                    '07' => 'Giao dịch bị nghi ngờ gian lận',
                    '09' => 'Thẻ chưa đăng ký Internet Banking',
                    '10' => 'Xác thực thông tin thẻ không đúng quá 3 lần',
                    '11' => 'Đã hết hạn chờ thanh toán',
                    '12' => 'Thẻ bị khóa',
                    '13' => 'Sai mật khẩu xác thực giao dịch',
                    '24' => 'Khách hàng hủy giao dịch',
                    '51' => 'Tài khoản không đủ số dư',
                    '65' => 'Tài khoản vượt quá hạn mức giao dịch',
                    '75' => 'Ngân hàng thanh toán đang bảo trì',
                    '79' => 'Giao dịch vượt quá số lần nhập sai mật khẩu',
                    'default' => 'Giao dịch thất bại'
                ];
                
                $errorMsg = $errorMessages[$request->vnp_ResponseCode] ?? $errorMessages['default'];
                
                Log::warning('VNPay Payment Failed', [
                    'txn_ref' => $request->vnp_TxnRef,
                    'response_code' => $request->vnp_ResponseCode,
                    'message' => $errorMsg
                ]);

                return redirect()->route('nhan-vien-ban-ve.dat-ve.create')
                    ->with('error', 'Thanh toán VNPay thất bại: ' . $errorMsg . ' (Mã lỗi: ' . $request->vnp_ResponseCode . ')');
            }

        } catch (\Exception $e) {
            Log::error('VNPay Return Handler Error: ' . $e->getMessage());
            return redirect()->route('nhan-vien-ban-ve.dat-ve.create')
                ->with('error', 'Có lỗi xảy ra khi xử lý kết quả thanh toán: ' . $e->getMessage());
        }
    }
}