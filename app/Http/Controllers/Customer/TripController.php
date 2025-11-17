<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Chuyendi;
use App\Models\Tinhthanh;
use App\Models\Ve;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class TripController extends Controller
{
    public function gFindTrip(Request $request) {
        return $this->findTripInternalRoute($request);
    }

    public function findTrip(Request $request) {
        return $this->findTripInternalRoute($request);
    }

    private function findTripInternalRoute(Request $request)
    {
        $fromCityName = $request->input('FromCity');
        $toCityName = $request->input('ToCity');
        $txtDate = $request->input('txtDate');
        $soVe = $request->input('SoVe');

        $startDate = Carbon::parse($txtDate)->startOfDay();
        $endDate = $startDate->copy()->addDay();
        $now = Carbon::now();

        // 1. Lấy chuyến đi, eager load các quan hệ cần thiết
        $tripsToday = Chuyendi::with(['xe.loaixe', 'lotrinhs.tinhthanh', 'ves'])
            ->whereBetween('thoigiandi', [$startDate, $endDate])
            ->when($startDate->isSameDay($now), function ($q) use ($now) {
                $q->where('thoigiandi', '>=', $now);
            })
            ->get();

        // 2. Lọc các chuyến đi thỏa mãn lộ trình
        $validTrips = $tripsToday->filter(function ($trip) use ($fromCityName, $toCityName) {

            if (!$trip->lotrinhs) {
                return false;
            }
            

            $routePoints = $trip->lotrinhs; // Đã orderBy('trinhtu') trong Model

            $fromIndex = -1;
            $toIndex = -1;

            foreach ($routePoints as $index => $point) {
                if ($point->tinhthanh && $point->tinhthanh->ten == $fromCityName) {
                    $fromIndex = $index;
                }
                if ($point->tinhthanh && $point->tinhthanh->ten == $toCityName) {
                    $toIndex = $index;
                }
            }

            return $fromIndex != -1 && $toIndex != -1 && $fromIndex < $toIndex;
        });

        // 3. Tạo ViewModel
        $result = new Collection();
        foreach ($validTrips as $trip) {
            echo($validTrips);

            $bookedCount = $trip->ves
                ->whereIn('trangthai', ['Booked', 'Pending'])
                ->count();

            $totalSeats = $trip->xe->loaixe->soghe ?? 0;
            $emptySeats = $totalSeats - $bookedCount;

            // $emptySeats = $trip->SLgheconlai;

            $result->push([
                'Trip' => $trip,
                'EmptySeats' => $emptySeats,
                'VehicleType' => $trip->xe->loaixe->tenloai ?? 'N/A',
                // 👇 ĐÃ THAY ĐỔI TÊN QUAN HỆ Ở ĐÂY
                'RoadMapCities' => $trip->lotrinhs->map(function ($lr) {
                    return $lr->tinhthanh->ten ?? 'N/A';
                })->all()
            ]);
        }

        return view('trip.find-trip', [
            'results' => $result,
            'fromCity' => $fromCityName,
            'toCity' => $toCityName,
            'date' => $txtDate,
            'soVe' => $soVe
        ]);
    }
}
