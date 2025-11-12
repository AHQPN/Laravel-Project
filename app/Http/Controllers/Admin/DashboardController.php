<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Nhanvien;
use App\Models\Khach;
use App\Models\Chuyendi;
use App\Models\Hoadon;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Thống kê tổng quan
        $totalNhanvien = Nhanvien::where('trangthai', 1)->count();
        $totalKhach = Khach::count();
        $totalChuyendi = Chuyendi::whereDate('thoigiandi', '>=', Carbon::today())->count();
        $totalDonHang = Hoadon::where('trangthai', 'Đã duyệt')->count();

        // Doanh thu tháng này
        $doanhThuThang = Hoadon::where('trangthai', 'Đã duyệt')
            ->whereMonth('thoigian', Carbon::now()->month)
            ->whereYear('thoigian', Carbon::now()->year)
            ->sum('thanhtien');

        // Đơn hàng chờ duyệt
        $donChoXuly = Hoadon::where('trangthai', 'Chờ duyệt')->count();

        // Chuyến đi hôm nay
        $chuyenDiHomNay = Chuyendi::whereDate('thoigiandi', Carbon::today())
            ->with(['xe.loaixe', 'lotrinhs.tinhthanh'])
            ->orderBy('thoigiandi')
            ->get();

        // Đơn hàng gần đây
        $donHangGanDay = Hoadon::with(['khach', 'nhanvien', 'thanhtoan'])
            ->orderBy('thoigian', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalNhanvien',
            'totalKhach',
            'totalChuyendi',
            'totalDonHang',
            'doanhThuThang',
            'donChoXuly',
            'chuyenDiHomNay',
            'donHangGanDay'
        ));
    }
}
