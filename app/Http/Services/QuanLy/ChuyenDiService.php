<?php

namespace App\Http\Services\QuanLy;

use App\Models\Chuyendi;
use App\Models\Lotrinh;
use App\Models\Xe;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ChuyenDiService
{
    /**
     * Get paginated list of chuyen di with relations and search.
     *
     * @param string|null $search
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getPaginatedChuyenDi(?string $search = null, int $perPage = 10)
    {
        return Chuyendi::with(['lotrinh', 'xe'])
            ->when($search, function($query, $search) {
                return $query->where('machuyendi', 'like', "%{$search}%")
                            ->orWhereHas('lotrinh', function($q) use ($search) {
                                $q->where('tenchuyen', 'like', "%{$search}%");
                            });
            })
            ->paginate($perPage);
    }

    /**
     * Create a new chuyen di with transaction.
     *
     * @param array $data
     * @return Chuyendi
     * @throws \Exception
     */
    public function createChuyenDi(array $data): Chuyendi
    {
        return DB::transaction(function () use ($data) {
            // Generate machuyendi if not provided
            if (empty($data['machuyendi'])) {
                $data['machuyendi'] = $this->generateMaChuyenDi();
            }

            // Set default trangthai
            $data['trangthai'] = $data['trangthai'] ?? 'Chưa khởi hành';

            return Chuyendi::create($data);
        });
    }

    /**
     * Update an existing chuyen di with transaction.
     *
     * @param string $machuyendi
     * @param array $data
     * @return Chuyendi
     * @throws \Exception
     */
    public function updateChuyenDi(string $machuyendi, array $data): Chuyendi
    {
        return DB::transaction(function () use ($machuyendi, $data) {
            $chuyendi = Chuyendi::findOrFail($machuyendi);
            $chuyendi->update($data);
            return $chuyendi->fresh();
        });
    }

    /**
     * Delete a chuyen di.
     *
     * @param string $machuyendi
     * @return bool
     * @throws \Exception
     */
    public function deleteChuyenDi(string $machuyendi): bool
    {
        $chuyendi = Chuyendi::findOrFail($machuyendi);
        return $chuyendi->delete();
    }

    /**
     * Generate unique ma chuyen di.
     *
     * @return string
     */
    private function generateMaChuyenDi(): string
    {
        do {
            $ma = 'CD' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
        } while (Chuyendi::where('machuyendi', $ma)->exists());

        return $ma;
    }

    /**
     * Get all lo trinh for dropdown.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllLoTrinh()
    {
        return Lotrinh::all();
    }

    /**
     * Get all xe for dropdown.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllXe()
    {
        return Xe::with('loaixe')->where('trangthai', 1)->get();
    }
}
