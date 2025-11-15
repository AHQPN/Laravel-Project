<?php

namespace App\Http\Controllers\NhanVienBanVe;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Nhanvien;
use App\Models\Chuyendi;
use App\Models\Lotrinh;
use App\Models\Ve;
use App\Models\Xe;
use App\Models\Khach;
use App\Models\Hoadon;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\NhanVienBanVe\UpdateProfileRequest;
use App\Http\Requests\NhanVienBanVe\UpdatePasswordRequest;

class NhanVienBanVeController extends Controller
{
    /**
     * Tạo mã hóa đơn ngắn (tối đa 10 ký tự) phù hợp schema: string(10)
     * Định dạng: HD + yymmdd + 2 random base36
     */
    private function generateShortMaHoaDon(): string
    {
        do {
            $datePart = now()->format('ymd'); // 6
            $randPart = strtoupper(substr(base_convert(rand(0, 1295), 10, 36), 0, 2)); // ~2
            $ma = 'HD' . $datePart . $randPart; // 2 + 6 + 2 = 10
        } while (\App\Models\Hoadon::where('mahd', $ma)->exists());
        return $ma;
    }
    /**
     * Display the dashboard for ticket sellers.
     */
    public function dashboard()
    {
        $nhanvien = session('nhanvien');
        
        // Thống kê vé trong tháng hiện tại
        $veThangNay = Ve::with('chuyendi')->whereHas('chuyendi', function($q) {
            $q->whereMonth('thoigiandi', Carbon::now()->month)
              ->whereYear('thoigiandi', Carbon::now()->year);
        })->get();
        
        $tongVeBan = $veThangNay->count();
        // Tính doanh thu từ giá chuyến đi (Ve không có cột gia)
        $tongDoanhThu = $veThangNay->sum(function($ve) {
            return $ve->chuyendi ? $ve->chuyendi->gia : 0;
        });
        $veHomNay = Ve::whereHas('chuyendi', function($q) {
            $q->whereDate('thoigiandi', Carbon::today());
        })->count();
        
        // Chuyến đi sắp tới (7 ngày) - chỉ lấy chuyến có thời gian khởi hành > hiện tại
        $now = Carbon::now();
        $chuyenDiSapToi = Chuyendi::with(['lotrinhs.tinhthanh', 'xe'])
            ->where('thoigiandi', '>', $now)
            ->where('thoigiandi', '<=', $now->copy()->addDays(7))
            ->orderBy('thoigiandi', 'asc')
            ->limit(5)
            ->get()
            ->map(function($cd) {
                $firstPoint = $cd->lotrinhs->sortBy('trinhtu')->first();
                $lastPoint = $cd->lotrinhs->sortBy('trinhtu')->last();
                return [
                    'tuyen' => ($firstPoint->tinhthanh->ten ?? '') . ' → ' . ($lastPoint->tinhthanh->ten ?? ''),
                    'thoigian' => Carbon::parse($cd->thoigiandi)->format('H:i d/m/Y'),
                    'ghe_trong' => $cd->SLgheconlai ?? 0,
                ];
            });
        
        // Thống kê vé theo ngày (7 ngày gần nhất)
        $veTheoNgay = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $count = Ve::whereHas('chuyendi', function($q) use ($date) {
                $q->whereDate('thoigiandi', $date);
            })->count();
            $veTheoNgay[] = [
                'ngay' => $date->format('d/m'),
                'so_ve' => $count,
            ];
        }
        
        return view('NhanVienBanVe.TrangChu', compact(
            'tongVeBan',
            'tongDoanhThu',
            'veHomNay',
            'chuyenDiSapToi',
            'veTheoNgay'
        ));
    }

    /**
     * (A) List invoices handled by current ticket seller.
     */
    public function indexHoadon(Request $request)
    {
        $nhanvien = session('nhanvien');

        $query = Hoadon::with(['khach', 'thanhtoan'])
            ->where('manv', $nhanvien->manv);

        if ($request->filled('trang_thai')) {
            $query->where('trangthai', $request->trang_thai);
        }
        if ($request->filled('ngay_lap')) {
            $query->whereDate('thoigian', $request->ngay_lap);
        }

        $hoadons = $query->orderByDesc('thoigian')->paginate(10);

        return view('NhanVienBanVe.HoaDon', compact('hoadons'));
    }

    /**
     * (B) Show the offline ticket booking page.
     */
    public function createDatVe()
    {
        // Logic to show the booking form will be added here.
        return view('NhanVienBanVe.DatVeOffline');
    }

    /**
     * (B) Store a new offline ticket.
     */
    public function storeDatVe(Request $request)
    {
        $request->validate([
            'machuyendi' => 'required|string|exists:chuyendi,machuyendi',
            'seats' => 'required|string',
            'gia_ve' => 'required|numeric',
            'kh_hoten' => 'required|string|max:255',
            'kh_sdt' => 'required|string|max:15',
            'kh_email' => 'nullable|email|max:255',
            'phuongthuc_thanhtoan' => 'required|in:tien-mat,chuyen-khoan,the',
            'ghi_chu' => 'nullable|string|max:500',
        ]);

        try {
            \DB::beginTransaction();

            // Step 1: Find chuyendi
            $chuyendi = Chuyendi::where('machuyendi', $request->machuyendi)->first();
            if (!$chuyendi) {
                throw new \Exception('Không tìm thấy chuyến đi.');
            }

            // Step 2: Find or create customer
            $khach = Khach::firstOrCreate(
                ['sdt' => $request->kh_sdt],
                [
                    'makh' => 'KH' . strtoupper(Str::random(6)),
                    'hoten' => $request->kh_hoten,
                    'email' => $request->kh_email,
                    'password' => \Hash::make('123456'),
                    'trangthai' => 'hoat-dong',
                ]
            );

            // Step 3: Find or create payment method
            $mattMap = [
                'tien-mat' => 'TM',
                'chuyen-khoan' => 'CK',
                'the' => 'THE',
            ];
            $matt = $mattMap[$request->phuongthuc_thanhtoan] ?? 'TM';

            // Ensure payment method exists
            $thanhtoan = \App\Models\Thanhtoan::firstOrCreate(
                ['matt' => $matt],
                ['ptthanhtoan' => ucfirst(str_replace('-', ' ', $request->phuongthuc_thanhtoan))]
            );

            // Step 4: Create invoice
            $selectedSeats = explode(',', $request->seats);
            $soluong = count($selectedSeats);
            $thanhtien = $soluong * $request->gia_ve;

            // Generate mahd max length 10 per migration (HD + 8 digits + 2 random)
            $hoadon = \App\Models\Hoadon::create([
                'mahd' => $this->generateShortMaHoaDon(),
                'makh' => $khach->makh,
                'manv' => session('nhanvien')->manv,
                'thoigian' => now(),
                'matt' => $matt,
                'soluong' => $soluong,
                'thanhtien' => $thanhtien,
                'trangthai' => 'Đã duyệt', // Auto-approve offline bookings
            ]);

            // Step 5: Create tickets and CTHD
            $createdTickets = [];
            foreach ($selectedSeats as $seatNumber) {
                // Tạo mã vé 10 ký tự: VE + yymmdd + 2 base36
                $datePartVe = now()->format('ymd');
                $randVe = strtoupper(substr(base_convert(rand(0,1295),10,36),0,2));
                $mave = 'VE' . $datePartVe . $randVe; // 10

                // Lưu nguyên mã ghế được chọn (string) để không mất prefix
                $seatCode = trim($seatNumber);

                $ve = Ve::create([
                    'mave' => $mave,
                    'machuyendi' => $chuyendi->machuyendi,
                    'maghe' => $seatCode,
                ]);

                // Create CTHD entry
                \App\Models\CTHD::create([
                    'mahd' => $hoadon->mahd,
                    'mave' => $ve->mave,
                    'dongia' => $request->gia_ve,
                ]);

                $createdTickets[] = $ve->mave;
            }

            \DB::commit();

            $message = 'Đặt vé thành công! Mã hóa đơn: ' . $hoadon->mahd . '. Đã tạo ' . count($createdTickets) . ' vé.';
            return redirect()->route('nhan-vien-ban-ve.ve.index')->with('success', $message);

        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('error', 'Lỗi khi đặt vé: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * (C) Show the list of offline tickets.
     */
    public function indexVe(Request $request)
    {
        $query = Ve::with(['chuyendi.lotrinhs.tinhthanh', 'chuyendi.xe', 'hoadon.khach']);

        // Filtering
        if ($request->filled('ngay_di')) {
            $query->whereHas('chuyendi', function ($q) use ($request) {
                $q->whereDate('thoigiandi', $request->ngay_di);
            });
        }
        if ($request->filled('chuyen_di')) {
            $query->where('machuyendi', $request->chuyen_di);
        }
        if ($request->filled('trang_thai')) {
            $query->whereHas('hoadon', function ($q) use ($request) {
                $q->where('trangthai', $request->trang_thai);
            });
        }

        // Searching
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('mave', 'LIKE', "%{$searchTerm}%")
                  ->orWhereHas('hoadon.khach', function($subq) use ($searchTerm) {
                      $subq->where('ten', 'LIKE', "%{$searchTerm}%")
                           ->orWhere('sdt', 'LIKE', "%{$searchTerm}%");
                  });
            });
        }

        $ves = $query->latest('mave')->paginate(10);

        // Data for filters
        $chuyenDis = Chuyendi::with('lotrinhs.tinhthanh')
            ->whereDate('thoigiandi', '>=', today())
            ->get()
            ->map(function($cd) {
                $firstPoint = $cd->lotrinhs->sortBy('trinhtu')->first();
                $lastPoint = $cd->lotrinhs->sortBy('trinhtu')->last();
                $cd->tuyen_duong = ($firstPoint->tinhthanh->ten ?? '') . ' → ' . ($lastPoint->tinhthanh->ten ?? '');
                return $cd;
            });

        return view('NhanVienBanVe.QuanLyVe', compact('ves', 'chuyenDis'));
    }

    /**
     * (C) Show details of a specific ticket.
     */
    public function showVe($id)
    {
        $ve = Ve::with(['chuyendi.lotrinhs.tinhthanh', 'chuyendi.xe', 'hoadon.khach'])
            ->where('mave', $id)
            ->firstOrFail();

        $chuyendi = $ve->chuyendi;
        $firstPoint = $chuyendi->lotrinhs->sortBy('trinhtu')->first();
        $lastPoint = $chuyendi->lotrinhs->sortBy('trinhtu')->last();

        $tuyen = ($firstPoint->tinhthanh->ten ?? 'N/A') . ' → ' . ($lastPoint->tinhthanh->ten ?? 'N/A');

        return view('NhanVienBanVe.partials.TicketDetail', [
            've' => $ve,
            'tuyen' => $tuyen,
        ]);
    }

    /**
     * (C) Cancel/delete a ticket.
     */
    public function destroyVe($id)
    {
        $ve = Ve::where('mave', $id)->firstOrFail();

        if ($ve->trangthai === 'Đã hủy') {
            return back()->with('error', 'Vé này đã được hủy từ trước.');
        }

        // Cập nhật trạng thái vé
        $ve->trangthai = 'Đã hủy';
        $ve->save();

        // Cập nhật lại số ghế còn lại trong Chuyendi nếu có cột SLgheconlai
        $chuyendi = $ve->chuyendi;
        if ($chuyendi && isset($chuyendi->SLgheconlai)) {
            $chuyendi->SLgheconlai = max(0, ($chuyendi->SLgheconlai ?? 0) + 1);
            $chuyendi->save();
        }

        return back()->with('success', 'Đã hủy vé ' . $ve->mave . ' thành công.');
    }

    /**
     * (D) Show the trip monitoring page.
     */
    public function indexChuyenDi(Request $request)
    {
        // If a specific date is provided, show trips of that date; otherwise only upcoming (now and future)
        if ($request->filled('date')) {
            $date = Carbon::parse($request->date);
            $query = Chuyendi::with(['xe.loaixe', 'lotrinhs.tinhthanh', 'ves'])
                ->whereDate('thoigiandi', $date)
                ->orderBy('thoigiandi', 'asc');
        } else {
            $query = Chuyendi::with(['xe.loaixe', 'lotrinhs.tinhthanh', 'ves'])
                ->where('thoigiandi', '>=', Carbon::now())
                ->orderBy('thoigiandi', 'asc');
        }

        // Apply route filter if provided
        if ($request->filled('route')) {
            [$start, $end] = explode('-', $request->route);
            $query->whereHas('lotrinhs', function ($q) use ($start) {
                $q->where('matinh', $start)->where('trinhtu', 1);
            })->whereHas('lotrinhs', function ($q) use ($end) {
                $q->where('matinh', $end)
                    ->whereRaw('trinhtu = (SELECT MAX(trinhtu) FROM Lotrinh WHERE machuyendi = Chuyendi.machuyendi)');
            });
        }
        
        $chuyenDis = $query->get()->map(function ($chuyen) {
            $firstPoint = $chuyen->lotrinhs->sortBy('trinhtu')->first();
            $lastPoint = $chuyen->lotrinhs->sortBy('trinhtu')->last();
            $chuyen->tuyen_duong = ($firstPoint->tinhthanh->ten ?? 'N/A') . ' → ' . ($lastPoint->tinhthanh->ten ?? 'N/A');
            // Số ghế đã đặt tính từ bảng Ve, bỏ qua vé đã hủy nếu có cột trạngthai
            $chuyen->so_ghe_da_dat = $chuyen->ves
                ->filter(function ($ve) {
                    return !isset($ve->trangthai) || $ve->trangthai !== 'Đã hủy';
                })
                ->count();
            
            // Determine status based on thoigiandi
            $thoiGianDi = Carbon::parse($chuyen->thoigiandi);
            if (now()->lt($thoiGianDi)) {
                $chuyen->status_display = 'Sắp khởi hành';
                $chuyen->status_key = 'sap-khoi-hanh';
            } elseif (now()->between($thoiGianDi, $thoiGianDi->copy()->addMinutes($chuyen->thoigiandichuyen ?? 240))) {
                $chuyen->status_display = 'Đang chạy';
                $chuyen->status_key = 'dang-chay';
            } else {
                $chuyen->status_display = 'Đã hoàn thành';
                $chuyen->status_key = 'hoan-thanh';
            }

            return $chuyen;
        });

        // Apply status filter after mapping
        if ($request->filled('status')) {
            $chuyenDis = $chuyenDis->filter(function($chuyen) use ($request) {
                return $chuyen->status_key === $request->status;
            });
        }

        // Get unique routes for filter dropdown
        $routes = Chuyendi::with(['lotrinhs.tinhthanh'])
            ->whereDate('thoigiandi', '>=', now()->subDays(30))
            ->get()
            ->map(function ($cd) {
                $firstPoint = $cd->lotrinhs->sortBy('trinhtu')->first();
                $lastPoint  = $cd->lotrinhs->sortBy('trinhtu')->last();
                if ($firstPoint && $lastPoint) {
                    return [
                        'value' => $firstPoint->matinh . '-' . $lastPoint->matinh,
                        'label' => ($firstPoint->tinhthanh->ten ?? '') . ' → ' . ($lastPoint->tinhthanh->ten ?? ''),
                    ];
                }
                return null;
            })
            ->filter()
            ->unique('value')
            ->values();

        return view('NhanVienBanVe.TheoDoiChuyenDi', compact('chuyenDis', 'routes'));
    }
    
    public function getChuyenDiDetails($machuyendi)
    {
        $chuyendi = Chuyendi::with(['xe.loaixe', 'lotrinhs.tinhthanh', 'ves.hoadon.khach'])
            ->where('machuyendi', $machuyendi)
            ->firstOrFail();
        
        $firstPoint = $chuyendi->lotrinhs->sortBy('trinhtu')->first();
        $lastPoint = $chuyendi->lotrinhs->sortBy('trinhtu')->last();
        
        // Chỉ tính những vé thực sự đã được giữ/đặt (trạng thái khác "Đã hủy")
        // Lấy danh sách ghế đã đặt giữ nguyên định dạng trong DB (chỉ chuẩn hóa uppercase & trim)
        $bookedSeats = $chuyendi->ves
            ->where('trangthai', '!=', 'Đã hủy')
            ->pluck('maghe')
            ->map(function($seat) {
                if (is_string($seat)) {
                    return strtoupper(trim($seat));
                }
                return (string)$seat; // nếu là số thì trả về chuỗi số
            })
            ->toArray();
        
        $passengers = $chuyendi->ves->map(function($ve) {
            // Giữ nguyên định dạng mã ghế (uppercase nếu là string)
            $seatCode = is_string($ve->maghe) ? strtoupper(trim($ve->maghe)) : (string)$ve->maghe;
            return [
                'mave' => $ve->mave,
                'maghe' => $seatCode,
                'ten_khach' => $ve->hoadon?->khach?->ten ?? 'Chưa có thông tin',
                'sdt' => $ve->hoadon?->khach?->sdt ?? '---',
            ];
        });
        
        return response()->json([
            'trip' => [
                'machuyendi' => $chuyendi->machuyendi,
                'tuyen' => ($firstPoint->tinhthanh->ten ?? 'N/A') . ' → ' . ($lastPoint->tinhthanh->ten ?? 'N/A'),
                'gio_khoi_hanh' => Carbon::parse($chuyendi->thoigiandi)->format('H:i d/m/Y'),
                'bien_so' => $chuyendi->xe->soxe ?? 'N/A',
            ],
            'loaixe' => [
                // Tổng số ghế thực tế từ loại xe
                'tong_so_ghe' => $chuyendi->xe->loaixe->tong_so_ghe ?? 0,
            ],
            'booked_seats' => $bookedSeats,
            'passengers' => $passengers,
        ]);
    }

    // =================================================================
    // ==                     API METHODS                             ==
    // =================================================================

    public function getChuyenDiApi(Request $request)
    {
        $now = Carbon::now();
        // Only routes that still have upcoming departures (thoigiandi >= now)
        $chuyenDis = Chuyendi::with(['lotrinhs.tinhthanh'])
            ->where('thoigiandi', '>=', $now)
            ->get();
        
        // Group by route (first point -> last point)
        $routes = [];
        $routesMap = [];
        
        foreach ($chuyenDis as $cd) {
            $firstPoint = $cd->lotrinhs->sortBy('trinhtu')->first();
            $lastPoint = $cd->lotrinhs->sortBy('trinhtu')->last();
            $routeKey = ($firstPoint->matinh ?? '') . '-' . ($lastPoint->matinh ?? '');
            
            if (!isset($routesMap[$routeKey])) {
                $routesMap[$routeKey] = [
                    'value' => $routeKey,
                    'label' => ($firstPoint->tinhthanh->ten ?? 'N/A') . ' → ' . ($lastPoint->tinhthanh->ten ?? 'N/A'),
                    'start' => $firstPoint->matinh ?? '',
                    'end' => $lastPoint->matinh ?? '',
                ];
            }
        }
        
        return response()->json(array_values($routesMap));
    }
    
    public function getGioKhoiHanhApi(Request $request)
    {
        $request->validate(['route' => 'required|string']);

        [$start, $end] = explode('-', $request->route);

        $now = Carbon::now();

        $chuyenDis = Chuyendi::with(['lotrinhs.tinhthanh', 'xe.loaixe'])
            ->where('thoigiandi', '>=', $now) // chỉ lấy chuyến từ thời điểm hiện tại trở đi
            ->get()
            ->filter(function ($cd) use ($start, $end) {
                $firstPoint = $cd->lotrinhs->sortBy('trinhtu')->first();
                $lastPoint  = $cd->lotrinhs->sortBy('trinhtu')->last();
                return ($firstPoint->matinh ?? '') == $start && ($lastPoint->matinh ?? '') == $end;
            });

        // Mỗi option giờ khởi hành gắn trực tiếp với một machuyendi
        $timeSlots = $chuyenDis->map(function ($cd) {
                $datetime = Carbon::parse($cd->thoigiandi);
                return [
                    'value'       => $cd->machuyendi,
                    'label'       => $datetime->format('H:i d/m/Y'),
                    'machuyendi'  => $cd->machuyendi,
                    'timestamp'   => $datetime->timestamp,
                ];
            })
            ->sortBy('timestamp')
            ->values();

        return response()->json($timeSlots);
    }

    public function getVehiclesApi(Request $request)
    {
        $request->validate(['machuyendi' => 'required|string']);

        $cd = Chuyendi::with(['xe.loaixe', 'lotrinhs.tinhthanh', 'ves'])
            ->where('machuyendi', $request->machuyendi)
            ->firstOrFail();

        // Bỏ chuyến thiếu thông tin xe hoặc loại xe
        if (!($cd->xe && $cd->xe->loaixe && ($cd->xe->loaixe->tong_so_ghe ?? 0) > 0)) {
            return response()->json([]);
        }

        $ve_count = $cd->ves->count();
        $total_seats = $cd->xe?->loaixe?->tong_so_ghe ?? 0;
        $available_seats = max(0, $total_seats - $ve_count);

        $firstPoint = $cd->lotrinhs->sortBy('trinhtu')->first();
        $lastPoint = $cd->lotrinhs->sortBy('trinhtu')->last();
        $tuyen = ($firstPoint?->tinhthanh?->ten ?? 'N/A') . '          ' . ($lastPoint?->tinhthanh?->ten ?? 'N/A');

        $vehicle = [
            'value' => $cd->machuyendi,
            'label' => ($cd->xe?->soxe ?? 'N/A') . ' - ' . ($cd->xe?->loaixe?->tenloai ?? 'N/A') . ' (' . $available_seats . '/' . $total_seats . ' ghế trống)',
            'biensoxe' => $cd->xe?->soxe ?? 'N/A',
            'loaixe' => $cd->xe?->loaixe?->tenloai ?? 'N/A',
            'ghe_trong' => $available_seats,
            'tuyen' => $tuyen,
            'gia' => $cd->gia ?? 0,
        ];

        return response()->json([$vehicle]);
    }

    public function getSoDoGheApi(Request $request)
    {
        $request->validate([
            'machuyendi' => 'required|string',
        ]);

        // Find the corresponding Chuyendi (match exactly the machuyendi returned in vehicles API)
        $chuyendi = Chuyendi::where('machuyendi', $request->machuyendi)->first();

        if (!$chuyendi) {
            return response()->json(['error' => 'Không tìm thấy chuyến đi phù hợp. Vui lòng kiểm tra lại thông tin.'], 404);
        }

        $xe = Xe::with('loaixe')->where('maxe', $chuyendi->maxe)->first();
        $ves = Ve::where('machuyendi', $chuyendi->machuyendi)->get();

        // booked_seats: lấy từ Ve.maghe, bỏ qua vé đã hủy nếu có cột trangthai
        // Chuẩn hóa về dạng A01..An để khớp với sơ đồ ghế A01..An trên FE
        $rawBookedSeats = $ves
            ->filter(function ($ve) {
                return !isset($ve->trangthai) || $ve->trangthai !== 'Đã hủy';
            })
            ->pluck('maghe')
            ->filter()
            ->map(function ($code) {
                $code = strtoupper((string) $code);
                // Nếu mã ghế dạng B10, P03... thì chỉ lấy phần số và map sang Axx
                if (preg_match('/^[A-Z](\d{1,2})$/', $code, $matches)) {
                    $num = (int) $matches[1];
                    return 'A' . str_pad($num, 2, '0', STR_PAD_LEFT);
                }
                // Nếu đã đúng dạng A01..An thì giữ nguyên
                if (preg_match('/^A\d{2}$/', $code)) {
                    return $code;
                }
                return $code;
            })
            ->values()
            ->toArray();

        $bookedSeats = array_values(array_unique($rawBookedSeats));

        // Tổng số ghế lấy từ loaixe.soghe (hoặc accessor tong_so_ghe)
        $totalSeats = $xe->loaixe->soghe ?? ($xe->loaixe->tong_so_ghe ?? 0);

        // Luôn sinh đầy đủ danh sách ghế dựa vào tổng số ghế của loại xe.
        // Ở đây chuẩn hóa thành A01..An để phù hợp với sơ đồ hiện tại.
        $allSeatCodes = [];
        if ($totalSeats > 0) {
            for ($i = 1; $i <= $totalSeats; $i++) {
                $allSeatCodes[] = 'A' . str_pad($i, 2, '0', STR_PAD_LEFT);
            }
        }

        // Chuẩn hóa danh sách ghế trả về cho FE
        $seats = array_map(function ($code) use ($bookedSeats) {
            return [
                'code' => $code,
                'booked' => in_array($code, $bookedSeats, true),
            ];
        }, $allSeatCodes);

        return response()->json([
            'machuyendi' => $chuyendi->machuyendi,
            'gia_ve' => $chuyendi->gia,
            'loaixe' => [
                'tong_so_ghe' => $totalSeats,
            ],
            'booked_seats' => $bookedSeats,
            'seats' => $seats,
        ]);
    }

    /**
     * Hiển thị trang hồ sơ cá nhân
     */
    public function profile()
    {
        $nhanvien = Nhanvien::with('chucvu')->find(session('nhanvien')->manv);
        
        return view('NhanVienBanVe.HoSo', compact('nhanvien'));
    }

    /**
     * Hiển thị form chỉnh sửa hồ sơ
     */
    public function editProfile()
    {
        return view('NhanVienBanVe.HoSoChinhSua');
    }

    /**
     * Cập nhật thông tin cá nhân
     */
    public function updateProfile(UpdateProfileRequest $request)
    {
        try {
            $nhanvien = Nhanvien::find(session('nhanvien')->manv);
            
            if (!$nhanvien) {
                return redirect()->route('nhan-vien-ban-ve.dang-nhap')
                    ->with('error', 'Không tìm thấy thông tin nhân viên');
            }

            // Cập nhật thông tin (chỉ update field có giá trị)
            if ($request->filled('hoten')) {
                $nhanvien->ten = $request->input('hoten'); // Form gửi 'hoten', DB dùng 'ten'
            }
            
            if ($request->filled('email')) {
                $nhanvien->email = $request->input('email');
            }
            
            if ($request->filled('sdt')) {
                $nhanvien->sdt = $request->input('sdt');
            }
            
            if ($request->filled('diachi')) {
                $nhanvien->diachi = $request->input('diachi');
            }
            
            if ($request->filled('ngaysinh')) {
                $nhanvien->ngaysinh = $request->input('ngaysinh');
            }
            
            $nhanvien->save();

            // Cập nhật lại session với thông tin mới
            $updatedNhanvien = Nhanvien::with('chucvu')->find($nhanvien->manv);
            session(['nhanvien' => $updatedNhanvien]);

            return redirect()->route('nhan-vien-ban-ve.ho-so')
                ->with('success', 'Cập nhật thông tin thành công!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Upload avatar
     */
    public function uploadAvatar(Request $request)
    {
        try {
            $request->validate([
                'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
            ], [
                'avatar.required' => 'Vui lòng chọn ảnh',
                'avatar.image' => 'File phải là ảnh',
                'avatar.mimes' => 'Ảnh phải có định dạng: jpeg, png, jpg, gif',
                'avatar.max' => 'Kích thước ảnh không được vượt quá 2MB'
            ]);

            $nhanvien = Nhanvien::find(session('nhanvien')->manv);
            
            if (!$nhanvien) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy thông tin nhân viên'
                ], 404);
            }

            // Xóa ảnh cũ nếu có (trừ ảnh mặc định)
            if ($nhanvien->hinhanh && $nhanvien->hinhanh !== 'default-avatar.jpg') {
                $oldImagePath = public_path('storage/avatars/' . $nhanvien->hinhanh);
                if (file_exists($oldImagePath)) {
                    @unlink($oldImagePath);
                }
            }

            // Lưu ảnh mới
            $image = $request->file('avatar');
            $filename = 'avatar_' . $nhanvien->manv . '_' . time() . '.' . $image->getClientOriginalExtension();
            
            // Tạo thư mục nếu chưa tồn tại
            $avatarPath = public_path('storage/avatars');
            if (!file_exists($avatarPath)) {
                mkdir($avatarPath, 0755, true);
            }
            
            $image->move($avatarPath, $filename);

            // Cập nhật database
            $nhanvien->hinhanh = $filename;
            $nhanvien->save();

            // Cập nhật session
            $updatedNhanvien = Nhanvien::with('chucvu')->find($nhanvien->manv);
            session(['nhanvien' => $updatedNhanvien]);

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật ảnh đại diện thành công!',
                'avatar_url' => asset('storage/avatars/' . $filename)
            ]);
                
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cập nhật mật khẩu
     */
    public function updatePassword(UpdatePasswordRequest $request)
    {
        try {
            $nhanvien = Nhanvien::find(session('nhanvien')->manv);
            
            if (!$nhanvien) {
                return redirect()->route('nhan-vien-ban-ve.dang-nhập')
                    ->with('error', 'Không tìm thấy thông tin nhân viên');
            }

            // Kiểm tra mật khẩu hiện tại
            if (!Hash::check($request->input('current_password'), $nhanvien->password)) {
                return redirect()->back()
                    ->with('error', 'Mật khẩu hiện tại không đúng');
            }

            // Cập nhật mật khẩu mới với Hash
            $nhanvien->password = Hash::make($request->input('new_password'));
            $nhanvien->save();

            return redirect()->route('nhan-vien-ban-ve.ho-so')
                ->with('success', 'Đổi mật khẩu thành công');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}