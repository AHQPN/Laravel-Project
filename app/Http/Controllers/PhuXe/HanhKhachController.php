<?php

namespace App\Http\Controllers\PhuXe;

use App\Http\Controllers\Controller;
use App\Models\Chuyendi;
use App\Models\Ve;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class HanhKhachController extends Controller
{
    public function index()
    {
        $phuxe = session('phuxe');
        
        $chuyenDis = Chuyendi::with(['xe', 'lotrinhs.tinhthanh'])
            ->whereHas('xe', function($q) use ($phuxe) {
                $q->where('manvpx', $phuxe->manv);
            })
            ->whereBetween('thoigiandi', [Carbon::today()->subDays(1), Carbon::today()->addDays(3)])
            ->orderBy('thoigiandi', 'desc')
            ->get()
            ->map(function($cd) {
                $firstPoint = $cd->lotrinhs->sortBy('trinhtu')->first();
                $lastPoint = $cd->lotrinhs->sortBy('trinhtu')->last();
                
                return [
                    'machuyendi' => $cd->machuyendi,
                    'tuyen' => ($firstPoint->tinhthanh->ten ?? '') . ' → ' . ($lastPoint->tinhthanh->ten ?? ''),
                    'thoi_gian_day_du' => Carbon::parse($cd->thoigiandi)->format('H:i d/m/Y'),
                    'bien_so' => $cd->xe->soxe ?? 'N/A',
                ];
            });

        return view('PhuXe.DanhSachHanhKhach', compact('chuyenDis'));
    }

    public function show(string $machuyendi)
    {
        $phuxe = session('phuxe');

        $trip = Chuyendi::with(['xe', 'lotrinhs.tinhthanh', 'ves.cthds.hoadon.khach'])
            ->where('machuyendi', $machuyendi)
            ->whereHas('xe', function ($query) use ($phuxe) {
                $query->where('manvpx', $phuxe->manv);
            })
            ->firstOrFail();

        $routePoints = $trip->lotrinhs->sortBy('trinhtu');
        $startPoint = optional(optional($routePoints->first())->tinhthanh)->ten ?? 'Chưa cập nhật';
        $endPoint = optional(optional($routePoints->last())->tinhthanh)->ten ?? 'Chưa cập nhật';

        $passengers = $trip->ves->map(function (Ve $ve) {
            $cthd = $ve->cthds->first();
            $invoice = $cthd?->hoadon;
            $customer = $invoice?->khach;

            return [
                'mave' => $ve->mave,
                'so_ghe' => $ve->maghe,
                'ten_khach' => $customer->ten ?? 'Chưa có thông tin',
                'sdt' => $customer->sdt ?? '---',
                'trangthai_don' => $ve->trangthai_don ?? 'chua_don',
                'thoidiem_don' => optional($ve->thoidiem_don)->translatedFormat('H:i d/m') ?? null,
            ];
        });

        return view('PhuXe.ChiTietHanhKhach', [
            'trip' => [
                'machuyendi' => $trip->machuyendi,
                'tuyen' => $startPoint . ' → ' . $endPoint,
                'gio_khoi_hanh' => optional($trip->thoigiandi)->format('H:i d/m'),
                'bien_so' => optional($trip->xe)->soxe ?? 'Chưa gán xe',
            ],
            'passengers' => $passengers,
        ]);
    }

    public function togglePickup(Request $request, string $mave): JsonResponse
    {
        $request->validate([
            'trangthai_don' => 'required|in:chua_don,da_don',
        ]);

        $phuxe = session('phuxe');
        $ve = Ve::with('chuyendi.xe')
            ->where('mave', $mave)
            ->whereHas('chuyendi.xe', function ($query) use ($phuxe) {
                $query->where('manvpx', $phuxe->manv);
            })
            ->firstOrFail();

        $ve->trangthai_don = $request->trangthai_don;
        $ve->thoidiem_don = $request->trangthai_don === 'da_don' ? Carbon::now() : null;
        $ve->save();

        return response()->json([
            'status' => 'success',
            'message' => $request->trangthai_don === 'da_don'
                ? 'Đã đánh dấu khách đã đón.'
                : 'Đã chuyển về trạng thái chưa đón.',
            'thoidiem_don' => optional($ve->thoidiem_don)->translatedFormat('H:i d/m'),
        ]);
    }
}
