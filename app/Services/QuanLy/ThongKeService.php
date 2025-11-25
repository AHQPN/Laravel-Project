<?php

namespace App\Services\QuanLy;

use App\Models\Hoadon;
use App\Models\Ve;
use App\Models\Khach;
use App\Models\Nhanvien;
use App\Models\Chuyendi;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ThongKeService
{
    /**
     * Get dashboard statistics.
     *
     * @return array
     */
    public function getDashboardStats(): array
    {
        return [
            'totalNhanvien' => Nhanvien::where('trangthai', 1)->count(),
            'totalKhach' => Khach::count(),
            'totalChuyendi' => Chuyendi::where('ngaydi', '>=', Carbon::today())->count(),
            'donChoXuly' => Hoadon::where('trangthai', 'Chờ xử lý')->count(),
            'doanhThuThang' => $this->getDoanhThuThang(),
            'totalDonHang' => Hoadon::where('trangthai', 'Đã thanh toán')
                ->whereMonth('created_at', Carbon::now()->month)
                ->count(),
        ];
    }

    /**
     * Get revenue for current month.
     *
     * @return float
     */
    private function getDoanhThuThang(): float
    {
        return Hoadon::where('trangthai', 'Đã thanh toán')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('tongtien') ?? 0;
    }

    /**
     * Get revenue by date range.
     *
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return float
     */
    public function getDoanhThuByDateRange(Carbon $startDate, Carbon $endDate): float
    {
        return Hoadon::where('trangthai', 'Đã thanh toán')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('tongtien') ?? 0;
    }

    /**
     * Get top selling routes.
     *
     * @param int $limit
     * @return \Illuminate\Support\Collection
     */
    public function getTopSellingRoutes(int $limit = 5)
    {
        return DB::table('ve')
            ->join('chuyendi', 've.machuyendi', '=', 'chuyendi.machuyendi')
            ->join('lotrinh', 'chuyendi.malotrinh', '=', 'lotrinh.malotrinh')
            ->select('lotrinh.tenchuyen', DB::raw('COUNT(ve.mave) as total_tickets'))
            ->groupBy('lotrinh.malotrinh', 'lotrinh.tenchuyen')
            ->orderByDesc('total_tickets')
            ->limit($limit)
            ->get();
    }
}
