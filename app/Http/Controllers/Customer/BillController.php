<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Hoadon;
use App\Models\Khach;

class BillController extends Controller
{
    public function index()
    {
        if (!Session::has('UserID')) {
            Session::flash('ShowLogin', true);
            return redirect()->route('home.index');
        }

        $userId = Session::get('UserID');
        $bills = Hoadon::with(['khach', 'thanhtoan', 'cthds.ve.chuyendi.xe.loaixe'])
            ->where('makh', $userId)
            ->orderBy('thoigian', 'desc')
            ->get();

        return view('bill.search', compact('bills'));
    }

    public function search(Request $request)
    {
        if (!Session::has('UserID')) {
            return redirect()->route('home.index');
        }

        $userId = Session::get('UserID');
        $mahd = $request->input('MaHD');

        $query = Hoadon::with(['khach', 'thanhtoan', 'cthds.ve.chuyendi.xe.loaixe'])
            ->where('makh', $userId);

        if (empty($mahd)) {
            Session::flash('error', 'Vui lòng nhập mã hóa đơn cần tra cứu.');
            $bills = $query->orderBy('thoigian', 'desc')->get();
        } else {
            $query->where('mahd', $mahd);
            $bills = $query->get();
            if ($bills->isEmpty()) {
                Session::flash('error', 'Không tìm thấy hóa đơn này.');
            }
        }

        return view('bill.search', compact('bills'));
    }

    public function chiTietHoaDon($id)
    {
        if (!Session::has('UserID')) {
            Session::flash('ShowLogin', true);
            return redirect()->route('home.index');
        }

        // 👇 SỬA LỖI Ở ĐÂY: 'cthd' -> 'cthds'
        $bill = Hoadon::with(['khach', 'thanhtoan', 'cthds.ve.chuyendi.xe.loaixe'])
            ->findOrFail($id);

        if ($bill->makh != Session::get('UserID')) {
            Session::flash('error', 'Bạn không có quyền xem hóa đơn này.');
            return redirect()->route('bill.index');
        }

        // Tạo dữ liệu cho QR code
        $qrData = [
            'mahd' => $bill->mahd,
            'khach' => $bill->khach->ten ?? 'N/A',
            'sdt' => $bill->khach->sdt ?? 'N/A',
            'thoigian' => $bill->thoigian->format('d/m/Y H:i'),
            'thanhtien' => number_format($bill->thanhtien, 0, ',', '.') . 'đ',
            'trangthai' => $bill->trangthai,
        ];

        // Thêm thông tin chuyến đi nếu có
        $firstTicket = $bill->cthds->first();
        if ($firstTicket && $firstTicket->ve && $firstTicket->ve->chuyendi) {
            $trip = $firstTicket->ve->chuyendi;
            $qrData['chuyen'] = $trip->tenchuyen;
            $qrData['ngaydi'] = \Carbon\Carbon::parse($trip->thoigiandi)->format('d/m/Y H:i');
        }

        $qrContent = json_encode($qrData, JSON_UNESCAPED_UNICODE);

        return view('bill.detail', compact('bill', 'qrContent'));
    }
}
