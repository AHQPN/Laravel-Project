<?php

namespace App\Http\Controllers\TaiXe;

use App\Http\Controllers\Controller;
use App\Models\Chuyendi;
use App\Models\Xe;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ChuyenController extends Controller
{
    public function today(): \Illuminate\View\View
    {
        $taixe = session('taixe');
        $trips = $this->fetchTripsForDriver($taixe->manv, Carbon::today(), Carbon::today());

        return view('TaiXe.ChuyenDiHomNay', [
            'trips' => $trips,
            'driver' => $taixe,
        ]);
    }

    public function passengerIndex(): \Illuminate\View\View
    {
        $taixe = session('taixe');
        // Chỉ lấy các chuyến có thời gian khởi hành từ hiện tại trở đi (trong vòng 7 ngày tới)
        $trips = $this->fetchTripsForDriver($taixe->manv, Carbon::now(), Carbon::now()->addDays(7));

        return view('TaiXe.DanhSachHanhKhach', [
            'trips' => $trips,
        ]);
    }

    public function start(Request $request, string $machuyendi): JsonResponse
    {
        $taixe = session('taixe');
        $trip = Chuyendi::where('machuyendi', $machuyendi)
            ->whereIn('maxe', $this->driverVehicleIds($taixe->manv))
            ->firstOrFail();

        if ($trip->trangthai === 'hoan_thanh') {
            return response()->json([
                'status' => 'error',
                'message' => 'Chuyến đi này đã hoàn thành trước đó.',
            ], 422);
        }

        if ($trip->trangthai === 'dang_chay') {
            return response()->json([
                'status' => 'info',
                'message' => 'Chuyến đi đã ở trạng thái đang chạy.',
            ]);
        }

        $trip->update([
            'trangthai' => 'dang_chay',
            'batdau_luc' => now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Đã bắt đầu chuyến đi thành công.',
        ]);
    }

    public function end(Request $request, string $machuyendi): JsonResponse
    {
        $taixe = session('taixe');
        $trip = Chuyendi::where('machuyendi', $machuyendi)
            ->whereIn('maxe', $this->driverVehicleIds($taixe->manv))
            ->firstOrFail();

        if ($trip->trangthai === 'hoan_thanh') {
            return response()->json([
                'status' => 'info',
                'message' => 'Chuyến đi này đã hoàn thành trước đó.',
            ]);
        }

        if ($trip->trangthai !== 'dang_chay') {
            return response()->json([
                'status' => 'error',
                'message' => 'Chỉ có thể kết thúc chuyến đi đang chạy.',
            ], 422);
        }

        $trip->update([
            'trangthai' => 'hoan_thanh',
            'ketthuc_luc' => now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Đã kết thúc chuyến đi thành công.',
        ]);
    }

    /**
     * Lấy danh sách chuyến theo tài xế trong khoảng ngày.
     */
    protected function fetchTripsForDriver(string $driverId, Carbon $from, Carbon $to): Collection
    {
        $xeIds = $this->driverVehicleIds($driverId);

        $trips = Chuyendi::with([
                'xe',
                'lotrinhs.tinhthanh',
                'ves'
            ])
            ->whereIn('maxe', $xeIds)
            ->whereBetween('thoigiandi', [$from->startOfDay(), $to->endOfDay()])
            ->orderBy('thoigiandi')
            ->get()
            ->map(function (Chuyendi $trip) {
                $routePoints = $trip->lotrinhs->sortBy('trinhtu');
                $startPoint = optional(optional($routePoints->first())->tinhthanh)->ten ?? 'Chưa cập nhật';
                $endPoint = optional(optional($routePoints->last())->tinhthanh)->ten ?? 'Chưa cập nhật';

                $status = $this->resolveStatus($trip);
                $departure = $trip->thoigiandi ? Carbon::parse($trip->thoigiandi) : null;
                
                // Tính tổng số khách (vé đã đặt, không tính vé đã hủy)
                $tongKhach = $trip->ves->where('trangthai', '!=', 'Đã hủy')->count();

                return [
                    'machuyendi' => $trip->machuyendi,
                    'tenchuyen' => $trip->tenchuyen,
                    'tuyen' => $startPoint . ' → ' . $endPoint,
                    'gio_xuat_phat' => $departure ? $departure->format('H:i') : '--:--',
                    'thoi_gian_day_du' => $departure ? $departure->translatedFormat('H:i d/m') : 'Chưa cập nhật',
                    'bien_so' => optional($trip->xe)->soxe ?? 'Chưa gán xe',
                    'so_ghe_trong' => $trip->SLgheconlai,
                    'tong_khach' => $tongKhach,
                    'trang_thai' => $status['label'],
                    'badge' => $status['badge'],
                    'raw_status' => $status['key'],
                ];
            });

        return $trips;
    }

    protected function driverVehicleIds(string $driverId): Collection
    {
        return Xe::where('manv', $driverId)->pluck('maxe');
    }

    protected function resolveStatus(Chuyendi $trip): array
    {
        $status = $trip->trangthai ?? 'sap_chay';
        $now = now();
        $start = Carbon::parse($trip->thoigiandi);

        if ($status === 'sap_chay') {
            if ($now->greaterThanOrEqualTo($start)) {
                $status = 'dang_chay';
            }
        }

        if ($status === 'dang_chay') {
            $durationMinutes = ($trip->thoigiandichuyen ?? 240);
            $expectedFinish = $trip->ketthuc_luc;
            if (!$expectedFinish && $trip->batdau_luc) {
                $expectedFinish = $trip->batdau_luc->copy()->addMinutes($durationMinutes);
            }
            if ($expectedFinish && $now->greaterThan($expectedFinish)) {
                $status = 'hoan_thanh';
                if (!$trip->ketthuc_luc) {
                    $trip->update([
                        'trangthai' => 'hoan_thanh',
                        'ketthuc_luc' => $now,
                    ]);
                }
            }
        }

        return match ($status) {
            'dang_chay' => ['key' => 'dang_chay', 'label' => 'Đang chạy', 'badge' => '🔵'],
            'hoan_thanh' => ['key' => 'hoan_thanh', 'label' => 'Đã hoàn thành', 'badge' => '🔴'],
            default => ['key' => 'sap_chay', 'label' => 'Sắp chạy', 'badge' => '🟢'],
        };
    }
}

