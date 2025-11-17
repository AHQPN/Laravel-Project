<?php

namespace App\Http\Services\NhanVienBanVe;

use App\Models\Ve;
use App\Models\Hoadon;
use App\Models\CTHD;
use App\Models\Khach;
use App\Models\Chuyendi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DatVeService
{
    /**
     * Create a booking (hoa don + ve) with transaction.
     *
     * @param array $data
     * @param string $manv
     * @return Hoadon
     * @throws \Exception
     */
    public function createBooking(array $data, string $manv): Hoadon
    {
        return DB::transaction(function () use ($data, $manv) {
            // 1. Find or create customer
            $khach = $this->findOrCreateKhach([
                'sdt' => $data['sdt_khach'],
                'ten' => $data['ten_khach'],
                'email' => $data['email_khach'] ?? null,
            ]);

            // 2. Get chuyen di info
            $chuyendi = Chuyendi::findOrFail($data['machuyendi']);

            // 3. Create hoa don
            $hoadon = Hoadon::create([
                'mahd' => $this->generateMaHoaDon(),
                'makh' => $khach->makh,
                'manv' => $manv,
                'ngaylap' => Carbon::now(),
                'tongtien' => $chuyendi->giave * count($data['soghe']),
                'trangthai' => 'Đã thanh toán',
                'phuongthucthanhtoan' => $data['phuongthucthanhtoan'] ?? 'Tiền mặt',
            ]);

            // 4. Create tickets (ve) for each seat
            foreach ($data['soghe'] as $soghe) {
                $ve = Ve::create([
                    'mave' => $this->generateMaVe(),
                    'machuyendi' => $data['machuyendi'],
                    'soghe' => $soghe,
                    'gia' => $chuyendi->giave,
                    'trangthai' => 'Booked',
                    'pickup_status' => 0,
                ]);
                
                // Dispatch event for real-time seat update
                event(new \App\Events\SeatBooked($data['machuyendi'], $soghe, 'Booked'));

                // 5. Create CTHD (chi tiet hoa don)
                CTHD::create([
                    'mahd' => $hoadon->mahd,
                    'mave' => $ve->mave,
                ]);
            }

            return $hoadon->fresh(['khach', 'nhanvien']);
        });
    }

    /**
     * Find existing customer or create new one.
     *
     * @param array $data
     * @return Khach
     */
    private function findOrCreateKhach(array $data): Khach
    {
        $khach = Khach::where('sdt', $data['sdt'])->first();

        if (!$khach) {
            $khach = Khach::create([
                'makh' => $this->generateMaKhach(),
                'ten' => $data['ten'],
                'sdt' => $data['sdt'],
                'email' => $data['email'],
            ]);
        } else {
            // Update existing customer info
            $khach->update([
                'ten' => $data['ten'],
                'email' => $data['email'] ?? $khach->email,
            ]);
        }

        return $khach;
    }

    /**
     * Get available seats for a trip with optimized query using scopes.
     *
     * @param string $machuyendi
     * @return array
     */
    public function getAvailableSeats(string $machuyendi): array
    {
        return DB::transaction(function () use ($machuyendi) {
            $chuyendi = Chuyendi::with('xe.loaixe')->lockForUpdate()->findOrFail($machuyendi);
            $sogheToiDa = $chuyendi->xe->loaixe->soghe ?? 40;

            // Get unavailable seats using scope (Pending, Booked, approved, pending)
            $bookedSeats = Ve::where('machuyendi', $machuyendi)
                ->unavailable()
                ->pluck('soghe')
                ->toArray();

            // Generate seat map
            $seats = [];
            for ($i = 1; $i <= $sogheToiDa; $i++) {
                $seatNumber = str_pad($i, 2, '0', STR_PAD_LEFT);
                $seats[] = [
                    'number' => $seatNumber,
                    'available' => !in_array($seatNumber, $bookedSeats),
                ];
            }

            return $seats;
        });
    }

    /**
     * Generate unique ma hoa don.
     *
     * @return string
     */
    private function generateMaHoaDon(): string
    {
        do {
            $ma = 'HD' . date('Ymd') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (Hoadon::where('mahd', $ma)->exists());

        return $ma;
    }

    /**
     * Generate unique ma ve.
     *
     * @return string
     */
    private function generateMaVe(): string
    {
        // Ensure length <= 10 (schema). Format: VE + yymmdd + 2 base36 chars = 2+6+2=10
        do {
            $datePart = date('ymd');
            $randPart = strtoupper(substr(base_convert(rand(0, 1295), 10, 36), 0, 2));
            $ma = 'VE' . $datePart . $randPart;
        } while (Ve::where('mave', $ma)->exists());

        return $ma;
    }

    /**
     * Generate unique ma khach.
     *
     * @return string
     */
    private function generateMaKhach(): string
    {
        do {
            $ma = 'KH' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
        } while (Khach::where('makh', $ma)->exists());

        return $ma;
    }
}
