<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hoadon;
use App\Models\Chuyendi;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ThongKeController extends Controller
{
    public function index(Request $request)
    {
        // Mặc định thống kê tháng này
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // Tổng doanh thu
        $totalRevenue = Hoadon::where('trangthai', 'Đã duyệt')
            ->whereBetween('thoigian', [$startDate, $endDate])
            ->sum('thanhtien');

        // Số đơn hàng
        $totalOrders = Hoadon::where('trangthai', 'Đã duyệt')
            ->whereBetween('thoigian', [$startDate, $endDate])
            ->count();

        // Số vé đã bán
        $totalTickets = Hoadon::where('trangthai', 'Đã duyệt')
            ->whereBetween('thoigian', [$startDate, $endDate])
            ->sum('soluong');

        // Doanh thu theo ngày
        $dailyRevenue = Hoadon::select(
                DB::raw('DATE(thoigian) as date'),
                DB::raw('SUM(thanhtien) as total'),
                DB::raw('COUNT(*) as orders')
            )
            ->where('trangthai', 'Đã duyệt')
            ->whereBetween('thoigian', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Top 10 tuyến đường có doanh thu cao nhất
        $topRoutes = DB::table('hoadon as h')
            ->join('cthd as c', 'h.mahd', '=', 'c.mahd')
            ->join('ve as v', 'c.mave', '=', 'v.mave')
            ->join('chuyendi as cd', 'v.machuyendi', '=', 'cd.machuyendi')
            ->where('h.trangthai', 'Đã duyệt')
            ->whereBetween('h.thoigian', [$startDate, $endDate])
            ->select(
                'cd.tenchuyen',
                DB::raw('COUNT(c.mave) as tickets_sold'),
                DB::raw('SUM(c.dongia) as revenue')
            )
            ->groupBy('cd.machuyendi', 'cd.tenchuyen')
            ->orderBy('revenue', 'desc')
            ->limit(10)
            ->get();

        // Phương thức thanh toán
        $paymentMethods = Hoadon::select(
                'thanhtoan.ptthanhtoan',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(hoadon.thanhtien) as total')
            )
            ->join('thanhtoan', 'hoadon.matt', '=', 'thanhtoan.matt')
            ->where('hoadon.trangthai', 'Đã duyệt')
            ->whereBetween('hoadon.thoigian', [$startDate, $endDate])
            ->groupBy('thanhtoan.matt', 'thanhtoan.ptthanhtoan')
            ->get();

        return view('admin.ThongKe.Index', compact(
            'startDate',
            'endDate',
            'totalRevenue',
            'totalOrders',
            'totalTickets',
            'dailyRevenue',
            'topRoutes',
            'paymentMethods'
        ));
    }
}
