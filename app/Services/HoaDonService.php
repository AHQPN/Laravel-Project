<?php

namespace App\Services;

use App\Models\Hoadon;
use App\Models\CTHD;
use App\Models\Ve;
use App\Models\Thanhtoan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class HoaDonService
{
    /**
     * Create invoice from tickets with transaction
     *
     * @param array $data
     * @return Hoadon
     * @throws \Exception
     */
    public function createInvoiceFromTickets(array $data): Hoadon
    {
        return DB::transaction(function () use ($data) {
            // Step 1: Validate tickets
            $tickets = Ve::whereIn('mave', $data['ticket_codes'])
                ->with('chuyendi')
                ->get();

            if ($tickets->isEmpty()) {
                throw new \Exception('Không tìm thấy vé nào.');
            }

            // Check if all tickets belong to same customer
            $customerId = $tickets->first()->makh;
            if ($tickets->pluck('makh')->unique()->count() > 1) {
                throw new \Exception('Các vé phải thuộc cùng một khách hàng.');
            }

            // Step 2: Calculate total amount
            $tongTien = $tickets->sum('gia');

            // Step 3: Create invoice
            $hoadon = Hoadon::create([
                'mahd' => $this->generateInvoiceCode(),
                'makh' => $customerId,
                'manv' => $data['manv'] ?? null,
                'tongtien' => $tongTien,
                'trangthai' => 'pending',
                'ngaylap' => now(),
            ]);

            // Step 4: Create invoice details
            foreach ($tickets as $ticket) {
                CTHD::create([
                    'mahd' => $hoadon->mahd,
                    'mave' => $ticket->mave,
                    'soluong' => 1,
                    'gia' => $ticket->gia,
                ]);

                // Link ticket to invoice
                $ticket->update(['mahd' => $hoadon->mahd]);
            }

            // Step 5: Create payment record if payment info provided
            if (!empty($data['phuongthuc_thanhtoan'])) {
                $this->createPayment($hoadon, $data);
            }

            return $hoadon;
        });
    }

    /**
     * Create payment record
     *
     * @param Hoadon $hoadon
     * @param array $data
     * @return Thanhtoan
     */
    protected function createPayment(Hoadon $hoadon, array $data): Thanhtoan
    {
        return Thanhtoan::create([
            'matt' => $this->generatePaymentCode(),
            'mahd' => $hoadon->mahd,
            'phuongthuc' => $data['phuongthuc_thanhtoan'],
            'sotien' => $data['sotien'] ?? $hoadon->tongtien,
            'ngaythanhtoan' => now(),
            'trangthai' => 'completed',
        ]);
    }

    /**
     * Approve invoice and process payment
     *
     * @param string $mahd
     * @param array $paymentData
     * @return Hoadon
     * @throws \Exception
     */
    public function approveInvoice(string $mahd, array $paymentData = []): Hoadon
    {
        return DB::transaction(function () use ($mahd, $paymentData) {
            $hoadon = Hoadon::where('mahd', $mahd)->firstOrFail();

            if ($hoadon->trangthai === 'approved') {
                throw new \Exception('Hóa đơn đã được duyệt trước đó.');
            }

            if ($hoadon->trangthai === 'cancelled') {
                throw new \Exception('Không thể duyệt hóa đơn đã hủy.');
            }

            // Update invoice status
            $hoadon->update([
                'trangthai' => 'approved',
                'ngayduyet' => now(),
            ]);

            // Update all tickets in invoice
            Ve::where('mahd', $mahd)->update(['trangthai' => 'approved']);

            // Create payment if not exists
            if (!$hoadon->thanhtoan && !empty($paymentData)) {
                $this->createPayment($hoadon, $paymentData);
            }

            return $hoadon->fresh();
        });
    }

    /**
     * Cancel invoice and restore seats
     *
     * @param string $mahd
     * @param string|null $reason
     * @return Hoadon
     * @throws \Exception
     */
    public function cancelInvoice(string $mahd, ?string $reason = null): Hoadon
    {
        return DB::transaction(function () use ($mahd, $reason) {
            $hoadon = Hoadon::where('mahd', $mahd)
                ->with('cthds.ve.chuyendi')
                ->firstOrFail();

            if ($hoadon->trangthai === 'cancelled') {
                throw new \Exception('Hóa đơn đã bị hủy trước đó.');
            }

            // Check if trip has departed
            foreach ($hoadon->cthds as $cthd) {
                $chuyendi = $cthd->ve->chuyendi;
                if ($chuyendi && Carbon::parse($chuyendi->thoigiandi)->isPast()) {
                    throw new \Exception('Không thể hủy hóa đơn cho chuyến đã khởi hành.');
                }
            }

            // Update invoice
            $hoadon->update([
                'trangthai' => 'cancelled',
                'ghichu' => $reason,
            ]);

            // Cancel all tickets and restore seats
            foreach ($hoadon->cthds as $cthd) {
                $ve = $cthd->ve;
                $ve->update(['trangthai' => 'cancelled']);

                // Restore seat
                if ($ve->chuyendi) {
                    $ve->chuyendi->increment('SLgheconlai');
                }
            }

            return $hoadon->fresh();
        });
    }

    /**
     * Get invoices with filters and sorting
     *
     * @param array $filters
     * @param array $sort
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getInvoicesWithFilters(array $filters = [], array $sort = [], int $perPage = 10)
    {
        $query = Hoadon::with(['khach', 'nhanvien', 'cthds.ve.chuyendi']);

        // Apply filters
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('mahd', 'LIKE', "%{$search}%")
                    ->orWhereHas('khach', function ($qr) use ($search) {
                        $qr->where('ten', 'LIKE', "%{$search}%")
                           ->orWhere('sdt', 'LIKE', "%{$search}%");
                    });
            });
        }

        if (!empty($filters['status'])) {
            $query->where('trangthai', $filters['status']);
        }

        if (!empty($filters['from_date'])) {
            $query->whereDate('ngaylap', '>=', $filters['from_date']);
        }

        if (!empty($filters['to_date'])) {
            $query->whereDate('ngaylap', '<=', $filters['to_date']);
        }

        // Apply sorting
        $sortColumn = $sort['column'] ?? 'ngaylap';
        $sortDirection = $sort['direction'] ?? 'desc';
        $query->orderBy($sortColumn, $sortDirection);

        return $query->paginate($perPage);
    }

    /**
     * Generate unique invoice code
     *
     * @return string
     */
    protected function generateInvoiceCode(): string
    {
        do {
            $code = 'HD' . date('Ymd') . strtoupper(Str::random(6));
        } while (Hoadon::where('mahd', $code)->exists());

        return $code;
    }

    /**
     * Generate unique payment code
     *
     * @return string
     */
    protected function generatePaymentCode(): string
    {
        do {
            $code = 'TT' . date('Ymd') . strtoupper(Str::random(6));
        } while (Thanhtoan::where('matt', $code)->exists());

        return $code;
    }

    /**
     * Get invoice statistics
     *
     * @param array $filters
     * @return array
     */
    public function getStatistics(array $filters = []): array
    {
        $query = Hoadon::query();

        if (!empty($filters['from_date'])) {
            $query->whereDate('ngaylap', '>=', $filters['from_date']);
        }

        if (!empty($filters['to_date'])) {
            $query->whereDate('ngaylap', '<=', $filters['to_date']);
        }

        return [
            'total_invoices' => $query->count(),
            'pending' => (clone $query)->where('trangthai', 'pending')->count(),
            'approved' => (clone $query)->where('trangthai', 'approved')->count(),
            'cancelled' => (clone $query)->where('trangthai', 'cancelled')->count(),
            'total_revenue' => (clone $query)->where('trangthai', 'approved')->sum('tongtien'),
        ];
    }
}
