<?php

namespace App\Http\Controllers\PhuXe;

use App\Http\Controllers\Controller;
use App\Models\Chuyendi;
use App\Models\Ve;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $phuxe = session('phuxe');
        
        // Lấy các chuyến đi mà phụ xe được phân công (qua xe)
        $chuyenDiHomNay = Chuyendi::with(['xe', 'lotrinhs.tinhthanh', 'ves'])
            ->whereDate('thoigiandi', Carbon::today())
            ->whereHas('xe', function($q) use ($phuxe) {
                $q->where('manv', $phuxe->manv);
            })
            ->orderBy('thoigiandi', 'asc')
            ->get()
            ->map(function($cd) {
                $firstPoint = $cd->lotrinhs->sortBy('trinhtu')->first();
                $lastPoint = $cd->lotrinhs->sortBy('trinhtu')->last();
                
                return [
                    'machuyendi' => $cd->machuyendi,
                    'tuyen' => ($firstPoint->tinhthanh->ten ?? '') . ' → ' . ($lastPoint->tinhthanh->ten ?? ''),
                    'thoigian' => Carbon::parse($cd->thoigiandi)->format('H:i'),
                    'bien_so' => $cd->xe->soxe ?? 'N/A',
                    'tong_ghe' => $cd->ves->count(),
                    'ghe_da_don' => $cd->ves->where('trangthai_don', 'da_don')->count(),
                ];
            });

        return view('PhuXe.TrangChu', compact('chuyenDiHomNay'));
    }
}
