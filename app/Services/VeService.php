<?php

namespace App\Services;

use App\Models\Ve;
use App\Models\Khach;
use App\Models\Chuyendi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VeService
{
    /**
     * Create offline tickets with transaction
     *
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function createOfflineTickets(array $data): array
    {
        return DB::transaction(function () use ($data) {
            // Step 1: Find or create customer
            $khach = $this->findOrCreateKhach($data);

            // Step 2: Validate seat availability
            $chuyendi = Chuyendi::with('xe.loaixe')->findOrFail($data['id_chuyendi']);
            $selectedSeats = explode(',', $data['seats']);
            
            $this->validateSeatsAvailable($chuyendi, $selectedSeats);

            // Step 3: Create tickets
            $createdTickets = $this->createTickets($chuyendi, $khach, $selectedSeats, $data);

            // Step 4: Update available seats
            $chuyendi->decrement('SLgheconlai', count($selectedSeats));

            return [
                'success' => true,
                'tickets' => $createdTickets,
                'customer' => $khach,
                'count' => count($createdTickets),
            ];
        });
    }

    /**
     * Find existing customer or create new one
     *
     * @param array $data
     * @return Khach
     */
    protected function findOrCreateKhach(array $data): Khach
    {
        return Khach::firstOrCreate(
            ['sdt' => $data['kh_sdt']],
            [
                'ten' => $data['kh_hoten'],
                'email' => $data['kh_email'] ?? null,
                'matkhau' => bcrypt('123456'), // Default password
                'trangthai' => 1,
            ]
        );
    }

    /**
     * Validate if seats are available with pessimistic locking
     *
     * @param Chuyendi $chuyendi
     * @param array $seats
     * @throws \Exception
     */
    protected function validateSeatsAvailable(Chuyendi $chuyendi, array $seats): void
    {
        // Check if seats are already booked using scope with locking
        $bookedSeats = Ve::where('machuyendi', $chuyendi->machuyendi)
            ->whereIn('soghe', $seats)
            ->unavailable()
            ->lockForUpdate()
            ->pluck('soghe')
            ->toArray();

        if (!empty($bookedSeats)) {
            throw new \Exception('Ghế số ' . implode(', ', $bookedSeats) . ' đã được đặt.');
        }

        // Check if number of seats exceeds available seats
        if (count($seats) > $chuyendi->SLgheconlai) {
            throw new \Exception('Số ghế vượt quá số ghế còn lại (' . $chuyendi->SLgheconlai . ').');
        }
    }

    /**
     * Create ticket records
     *
     * @param Chuyendi $chuyendi
     * @param Khach $khach
     * @param array $seats
     * @param array $data
     * @return array
     */
    protected function createTickets(Chuyendi $chuyendi, Khach $khach, array $seats, array $data): array
    {
        $createdTickets = [];

        foreach ($seats as $seatNumber) {
            $ve = Ve::create([
                'mave' => $this->generateTicketCode(),
                'machuyendi' => $chuyendi->machuyendi,
                'makh' => $khach->makh,
                'soghe' => trim($seatNumber),
                'gia' => $data['gia_ve'],
                'trangthai' => 'approved', // Offline tickets auto-approved
                'loaive' => 'offline',
                'manv' => $data['manv'] ?? null, // Staff who created the ticket
                'ghichu' => $data['ghi_chu'] ?? null,
            ]);
            
            $createdTickets[] = [
                'mave' => $ve->mave,
                'soghe' => $ve->soghe,
                'gia' => $ve->gia,
            ];
        }

        return $createdTickets;
    }

    /**
     * Generate unique ticket code
     *
     * @return string
     */
    protected function generateTicketCode(): string
    {
        do {
            $code = 'VE' . strtoupper(Str::random(8));
        } while (Ve::where('mave', $code)->exists());

        return $code;
    }

    /**
     * Cancel ticket with transaction
     *
     * @param string $mave
     * @param string|null $reason
     * @return bool
     * @throws \Exception
     */
    public function cancelTicket(string $mave, ?string $reason = null): bool
    {
        return DB::transaction(function () use ($mave, $reason) {
            $ve = Ve::where('mave', $mave)->firstOrFail();

            // Check if ticket can be cancelled
            if ($ve->trangthai === 'cancelled') {
                throw new \Exception('Vé đã bị hủy trước đó.');
            }

            if ($ve->trangthai === 'used') {
                throw new \Exception('Không thể hủy vé đã sử dụng.');
            }

            // Update ticket status
            $ve->update([
                'trangthai' => 'cancelled',
                'ghichu' => $reason ? "Hủy: {$reason}" : 'Đã hủy',
            ]);

            // Return seat to available pool
            $chuyendi = Chuyendi::where('machuyendi', $ve->machuyendi)->first();
            if ($chuyendi) {
                $chuyendi->increment('SLgheconlai');
            }

            return true;
        });
    }

    /**
     * Get tickets with filters
     *
     * @param array $filters
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getTicketsWithFilters(array $filters = [], int $perPage = 10)
    {
        $query = Ve::with(['chuyendi.lotrinhs.tinhthanh', 'khach', 'hoadon'])
            ->where('loaive', 'offline');

        // Apply filters
        if (!empty($filters['ngay_di'])) {
            $query->whereHas('chuyendi', function ($q) use ($filters) {
                $q->whereDate('thoigiandi', $filters['ngay_di']);
            });
        }

        if (!empty($filters['chuyen_di'])) {
            $query->where('machuyendi', $filters['chuyen_di']);
        }

        if (!empty($filters['trang_thai'])) {
            $query->where('trangthai', $filters['trang_thai']);
        }

        if (!empty($filters['search'])) {
            $searchTerm = $filters['search'];
            $query->where(function ($q) use ($searchTerm) {
                $q->where('mave', 'LIKE', "%{$searchTerm}%")
                    ->orWhereHas('khach', function ($qr) use ($searchTerm) {
                        $qr->where('ten', 'LIKE', "%{$searchTerm}%")
                           ->orWhere('sdt', 'LIKE', "%{$searchTerm}%");
                    });
            });
        }

        return $query->latest()->paginate($perPage);
    }
}
