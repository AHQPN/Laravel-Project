<?php

namespace App\Services;

use App\Models\Chuyendi;
use App\Models\Ve;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TripService
{
    /**
     * Release expired pending seats for a trip (Pending past pending_expires_at)
     */
    public function releaseExpiredPendingSeats(string $tripId): int
    {
        // Sử dụng scope ExpiredPending để tìm vé pending hết hạn
        $expired = Ve::where('machuyendi', $tripId)
            ->expiredPending()
            ->get();

        if ($expired->isEmpty()) return 0;

        $count = $expired->count();

        foreach ($expired as $seat) {
            $seat->trangthai = 'Available';
            $seat->pending_expires_at = null;
            $seat->save();
        }

        // update chuyendi SLgheconlai increment by released seats
        Chuyendi::where('machuyendi', $tripId)->increment('SLgheconlai', $count);

        return $count;
    }

    /**
     * Mark seats as Pending for a trip. Returns list of successfully marked seat numbers.
     */
    public function markSeatsPending(string $tripId, array $seatNums, int $minutes = 15): array
    {
        return DB::transaction(function () use ($tripId, $seatNums, $minutes) {
            $now = Carbon::now();
            $expires = $now->addMinutes($minutes);

            $booked = [];

            foreach ($seatNums as $seatNum) {
                // Sử dụng lockForUpdate để tránh race condition
                $seat = Ve::where('machuyendi', $tripId)
                    ->where('maghe', $seatNum)
                    ->lockForUpdate()
                    ->first();
                    
                if (!$seat) continue;

                // only mark if available using scope
                if ($seat->trangthai === 'Available' || is_null($seat->trangthai)) {
                    $seat->trangthai = 'Pending';
                    $seat->pending_expires_at = $expires;
                    $seat->save();
                    $booked[] = $seatNum;
                    // decrement remaining seats
                    Chuyendi::where('machuyendi', $tripId)->decrement('SLgheconlai', 1);
                }
            }

            return $booked;
        });
    }

    /**
     * Update seat statuses from one state to another (e.g., Pending -> Booked or Pending -> Available)
     */
    public function updateSeatStatus(string $tripId, array $seatNums, string $from, string $to): int
    {
        $q = Ve::where('machuyendi', $tripId)
            ->whereIn('maghe', $seatNums)
            ->where('trangthai', $from);

        $seats = $q->get();
        if ($seats->isEmpty()) return 0;

        $count = $seats->count();

        foreach ($seats as $seat) {
            $seat->trangthai = $to;
            if ($to !== 'Pending') $seat->pending_expires_at = null;
            $seat->save();
        }

        // adjust SLgheconlai depending on transition
        if ($from === 'Pending' && $to === 'Available') {
            Chuyendi::where('machuyendi', $tripId)->increment('SLgheconlai', $count);
        }

        return $count;
    }
}
