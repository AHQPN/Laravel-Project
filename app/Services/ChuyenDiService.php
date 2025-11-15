<?php

namespace App\Services;

use App\Models\Chuyendi;
use App\Models\Lotrinh;
use App\Models\Xe;
use App\Models\Ve;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ChuyenDiService
{
    // Tạo chuyến đi mới kèm lộ trình (sử dụng transaction)
    public function createTrip(array $data): Chuyendi
    {
        return DB::transaction(function () use ($data) {
            // Bước 1: Kiểm tra xe có sẵn không
            $xe = Xe::with('loaixe')->findOrFail($data['maxe']);
            $this->validateVehicleAvailable($xe, $data['thoigiandi']);

            // Bước 2: Tạo chuyến đi
            $chuyendi = Chuyendi::create([
                'machuyendi' => $this->generateTripCode(),
                'tenchuyen' => $data['tenchuyen'],
                'maxe' => $data['maxe'],
                'SLgheconlai' => $xe->loaixe->soghe ?? 40,
                'thoigiandi' => $data['thoigiandi'],
                'thoigiandichuyen' => $data['thoigiandichuyen'],
                'gia' => $data['gia'],
                'trangthai' => 'sap_chay',
            ]);

            // Bước 3: Tạo các điểm dừng trong lộ trình
            if (!empty($data['lotrinh']) && is_array($data['lotrinh'])) {
                $this->createRoutePoints($chuyendi, $data['lotrinh']);
            }

            return $chuyendi->fresh('lotrinhs');
        });
    }

    // Cập nhật thông tin chuyến đi
    public function updateTrip(string $machuyendi, array $data): Chuyendi
    {
        return DB::transaction(function () use ($machuyendi, $data) {
            $chuyendi = Chuyendi::where('machuyendi', $machuyendi)->firstOrFail();

            // Kiểm tra có được phép cập nhật không
            if ($chuyendi->trangthai === 'da_chay') {
                throw new \Exception('Không thể cập nhật chuyến đã hoàn thành.');
            }

            // Nếu đổi xe, kiểm tra và cập nhật số ghế
            if (isset($data['maxe']) && $data['maxe'] !== $chuyendi->maxe) {
                $xe = Xe::with('loaixe')->findOrFail($data['maxe']);
                $this->validateVehicleAvailable($xe, $data['thoigiandi'] ?? $chuyendi->thoigiandi);
                
                $bookedSeats = Ve::where('machuyendi', $machuyendi)
                    ->whereIn('trangthai', ['approved', 'pending'])
                    ->count();
                
                $newCapacity = $xe->loaixe->soghe ?? 40;
                if ($bookedSeats > $newCapacity) {
                    throw new \Exception('Xe mới không đủ chỗ cho số vé đã đặt.');
                }
                
                $data['SLgheconlai'] = $newCapacity - $bookedSeats;
            }

            // Cập nhật chuyến đi
            $chuyendi->update(array_filter($data, function ($key) {
                return in_array($key, ['tenchuyen', 'maxe', 'thoigiandi', 'thoigiandichuyen', 'gia', 'SLgheconlai', 'trangthai']);
            }, ARRAY_FILTER_USE_KEY));

            // Cập nhật lộ trình nếu có
            if (isset($data['lotrinh']) && is_array($data['lotrinh'])) {
                Lotrinh::where('machuyendi', $machuyendi)->delete();
                $this->createRoutePoints($chuyendi, $data['lotrinh']);
            }

            return $chuyendi->fresh('lotrinhs');
        });
    }

    /**
     * Cancel trip with transaction
     *
     * @param string $machuyendi
     * @param string|null $reason
     * @return Chuyendi
     * @throws \Exception
     */
    public function cancelTrip(string $machuyendi, ?string $reason = null): Chuyendi
    {
        return DB::transaction(function () use ($machuyendi, $reason) {
            $chuyendi = Chuyendi::where('machuyendi', $machuyendi)->firstOrFail();

            if ($chuyendi->trangthai === 'da_chay') {
                throw new \Exception('Không thể hủy chuyến đã hoàn thành.');
            }

            // Cancel all tickets for this trip
            $tickets = Ve::where('machuyendi', $machuyendi)
                ->whereIn('trangthai', ['approved', 'pending'])
                ->get();

            foreach ($tickets as $ticket) {
                $ticket->update([
                    'trangthai' => 'cancelled',
                    'ghichu' => "Chuyến bị hủy: {$reason}",
                ]);
            }

            // Update trip status
            $chuyendi->update([
                'trangthai' => 'da_huy',
                'ghichu' => $reason,
            ]);

            return $chuyendi;
        });
    }

    /**
     * Start trip (change status to running)
     *
     * @param string $machuyendi
     * @return Chuyendi
     * @throws \Exception
     */
    public function startTrip(string $machuyendi): Chuyendi
    {
        $chuyendi = Chuyendi::where('machuyendi', $machuyendi)->firstOrFail();

        if ($chuyendi->trangthai !== 'sap_chay') {
            throw new \Exception('Chỉ có thể bắt đầu chuyến đang ở trạng thái "Sắp chạy".');
        }

        $chuyendi->update(['trangthai' => 'dang_chay']);

        return $chuyendi;
    }

    /**
     * Complete trip
     *
     * @param string $machuyendi
     * @return Chuyendi
     * @throws \Exception
     */
    public function completeTrip(string $machuyendi): Chuyendi
    {
        $chuyendi = Chuyendi::where('machuyendi', $machuyendi)->firstOrFail();

        if ($chuyendi->trangthai !== 'dang_chay') {
            throw new \Exception('Chỉ có thể hoàn thành chuyến đang chạy.');
        }

        // Mark all approved tickets as used
        Ve::where('machuyendi', $machuyendi)
            ->where('trangthai', 'approved')
            ->update(['trangthai' => 'used']);

        $chuyendi->update(['trangthai' => 'da_chay']);

        return $chuyendi;
    }

    /**
     * Validate vehicle is available for the time slot
     *
     * @param Xe $xe
     * @param string $thoigiandi
     * @throws \Exception
     */
    protected function validateVehicleAvailable(Xe $xe, string $thoigiandi): void
    {
        $tripTime = Carbon::parse($thoigiandi);
        
        // Check if vehicle is already assigned to another trip at the same time
        $conflict = Chuyendi::where('maxe', $xe->maxe)
            ->where('trangthai', '!=', 'da_huy')
            ->where(function ($query) use ($tripTime) {
                $query->whereBetween('thoigiandi', [
                    $tripTime->copy()->subHours(4),
                    $tripTime->copy()->addHours(4),
                ]);
            })
            ->exists();

        if ($conflict) {
            throw new \Exception('Xe đã có lịch chạy trong khoảng thời gian này.');
        }
    }

    /**
     * Create route points for trip
     *
     * @param Chuyendi $chuyendi
     * @param array $routePoints
     */
    protected function createRoutePoints(Chuyendi $chuyendi, array $routePoints): void
    {
        foreach ($routePoints as $index => $matinh) {
            Lotrinh::create([
                'machuyendi' => $chuyendi->machuyendi,
                'matinh' => $matinh,
                'thutu' => $index + 1,
                'khoangcach' => 0, // Can be calculated later
            ]);
        }
    }

    /**
     * Generate unique trip code
     *
     * @return string
     */
    protected function generateTripCode(): string
    {
        do {
            $code = 'CD' . date('Ymd') . rand(1000, 9999);
        } while (Chuyendi::where('machuyendi', $code)->exists());

        return $code;
    }

    /**
     * Get trips with filters and eager loading
     *
     * @param array $filters
     * @param array $sort
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getTripsWithFilters(array $filters = [], array $sort = [], int $perPage = 10)
    {
        $query = Chuyendi::with(['xe.loaixe', 'lotrinhs.tinhthanh']);

        // Apply filters
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('machuyendi', 'LIKE', "%{$search}%")
                    ->orWhere('tenchuyen', 'LIKE', "%{$search}%");
            });
        }

        if (!empty($filters['trangthai'])) {
            $query->where('trangthai', $filters['trangthai']);
        }

        if (!empty($filters['from_date'])) {
            $query->whereDate('thoigiandi', '>=', $filters['from_date']);
        }

        if (!empty($filters['to_date'])) {
            $query->whereDate('thoigiandi', '<=', $filters['to_date']);
        }

        // Apply sorting
        $sortColumn = $sort['column'] ?? 'thoigiandi';
        $sortDirection = $sort['direction'] ?? 'desc';
        $query->orderBy($sortColumn, $sortDirection);

        return $query->paginate($perPage);
    }
}
