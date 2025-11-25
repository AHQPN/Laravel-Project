<?php

namespace App\Services\QuanLy;

use App\Models\Nhanvien;
use App\Models\Chucvu;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class NhanVienService
{
    /**
     * Get paginated list of nhanvien with search.
     *
     * @param string|null $search
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getPaginatedNhanvien(?string $search = null, int $perPage = 10)
    {
        return Nhanvien::with('chucvu')
            ->when($search, function($query, $search) {
                return $query->where('manv', 'like', "%{$search}%")
                            ->orWhere('ten', 'like', "%{$search}%")
                            ->orWhere('sdt', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
            })
            ->paginate($perPage);
    }

    /**
     * Create a new nhanvien with transaction.
     *
     * @param array $data
     * @return Nhanvien
     * @throws \Exception
     */
    public function createNhanvien(array $data): Nhanvien
    {
        return DB::transaction(function () use ($data) {
            // Password sẽ tự động hash qua mutator trong Model
            // Không cần hash ở đây nữa
            
            // Set default trangthai if not provided
            $data['trangthai'] = $data['trangthai'] ?? 1;

            return Nhanvien::create($data);
        });
    }

    /**
     * Update an existing nhanvien with transaction.
     *
     * @param string $manv
     * @param array $data
     * @return Nhanvien
     * @throws \Exception
     */
    public function updateNhanvien(string $manv, array $data): Nhanvien
    {
        return DB::transaction(function () use ($manv, $data) {
            $nhanvien = Nhanvien::findOrFail($manv);

            // Password sẽ tự động hash qua mutator trong Model
            // Nếu không có password mới, remove khỏi data
            if (empty($data['password'])) {
                unset($data['password']);
            }

            $nhanvien->update($data);
            return $nhanvien->fresh();
        });
    }

    /**
     * Delete a nhanvien.
     *
     * @param string $manv
     * @return bool
     * @throws \Exception
     */
    public function deleteNhanvien(string $manv): bool
    {
        $nhanvien = Nhanvien::findOrFail($manv);
        return $nhanvien->delete();
    }

    /**
     * Get all chuc vu for dropdown.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllChucVu()
    {
        return Chucvu::all();
    }
}
