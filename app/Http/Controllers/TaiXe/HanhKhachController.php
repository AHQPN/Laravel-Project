<?php

namespace App\Http\Controllers\TaiXe;

use App\Http\Controllers\Controller;
use App\Models\Chuyendi;
use App\Models\Ve;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HanhKhachController extends Controller
{
    public function show(string $machuyendi): \Illuminate\View\View
    {
        $taixe = session('taixe');

        $trip = Chuyendi::with([
                'xe',
                'lotrinhs.tinhthanh',
                'ves.cthds.hoadon.khach',
            ])
            ->where('machuyendi', $machuyendi)
            ->whereHas('xe', function ($query) use ($taixe) {
                $query->where('manv', $taixe->manv);
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

        return view('TaiXe.ChiTietHanhKhach', [
            'trip' => [
                'machuyendi' => $trip->machuyendi,
                'tuyen' => $startPoint . ' → ' . $endPoint,
                'gio_khoi_hanh' => optional($trip->thoigiandi)->format('H:i d/m'),
                'bien_so' => optional($trip->xe)->soxe ?? 'Chưa gán xe',
                'so_ghe_trong' => $trip->SLgheconlai ?? 0,
                'tong_ghe' => optional($trip->xe?->loaixe)->soghe ?? null,
            ],
            'passengers' => $passengers,
        ]);
    }

    public function togglePickup(Request $request, string $mave): JsonResponse
    {
        $request->validate([
            'trangthai_don' => 'required|in:chua_don,da_don',
        ]);

        $taixe = session('taixe');
        $ve = Ve::with('chuyendi.xe')
            ->where('mave', $mave)
            ->whereHas('chuyendi.xe', function ($query) use ($taixe) {
                $query->where('manv', $taixe->manv);
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

