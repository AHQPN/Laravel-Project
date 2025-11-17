<?php

namespace App\Http\Controllers\NhanVienBanVe;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Chuyendi;
use App\Models\Ve;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Lấy chuyến đi sắp tới (từ bây giờ trở đi)
        $now = Carbon::now();
        
        $chuyenDiSapToi = Chuyendi::with(['lotrinh.diemdi', 'lotrinh.diemden', 'xe.loaixe'])
            ->whereRaw('CONCAT(ngaydi, " ", giodi) > ?', [$now->format('Y-m-d H:i:s')])
            ->orderByRaw('CONCAT(ngaydi, " ", giodi)')
            ->limit(10)
            ->get()
            ->map(function($chuyen) {
                $tongGhe = $chuyen->xe->loaixe->soghe ?? 0;
                $gheDaDat = Ve::where('machuyendi', $chuyen->machuyendi)
                    ->whereIn('trangthai', ['Đã đặt', 'Đã thanh toán'])
                    ->count();
                
                return [
                    'tuyen' => ($chuyen->lotrinh->diemdi->tentt ?? 'N/A') . ' → ' . ($chuyen->lotrinh->diemden->tentt ?? 'N/A'),
                    'thoigian' => Carbon::parse($chuyen->ngaydi . ' ' . $chuyen->giodi)->format('H:i d/m/Y'),
                    'ghe_trong' => max(0, $tongGhe - $gheDaDat),
                    'machuyendi' => $chuyen->machuyendi,
                ];
            });

        // Thống kê vé bán 7 ngày gần nhất
        $veTheoNgay = Ve::whereBetween('ngaydat', [
                Carbon::now()->subDays(6)->startOfDay(),
                Carbon::now()->endOfDay()
            ])
            ->selectRaw('DATE(ngaydat) as ngay, COUNT(*) as soluong')
            ->groupBy('ngay')
            ->orderBy('ngay')
            ->get();

        return view('NhanVienBanVe.TrangChu', compact('chuyenDiSapToi', 'veTheoNgay'));
    }
}
