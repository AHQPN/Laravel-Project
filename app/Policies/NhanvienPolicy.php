<?php

namespace App\Policies;

use App\Models\Nhanvien;

class NhanvienPolicy
{
    /**
     * Determine if the user is a Quản lý.
     */
    public function isQuanLy(?Nhanvien $nhanvien): bool
    {
        return $nhanvien && $nhanvien->macv === 'QL' && $nhanvien->isActive();
    }

    /**
     * Determine if the user is a Nhân viên bán vé.
     */
    public function isNhanVienBanVe(?Nhanvien $nhanvien): bool
    {
        return $nhanvien && $nhanvien->macv === 'BV' && $nhanvien->isActive();
    }

    /**
     * Determine if the user is a Tài xế.
     */
    public function isTaiXe(?Nhanvien $nhanvien): bool
    {
        return $nhanvien && $nhanvien->macv === 'TX' && $nhanvien->isActive();
    }

    /**
     * Determine if the user can manage employees (Quản lý only).
     */
    public function manageEmployees(?Nhanvien $nhanvien): bool
    {
        return $this->isQuanLy($nhanvien);
    }

    /**
     * Determine if the user can manage trips (Quản lý only).
     */
    public function manageTrips(?Nhanvien $nhanvien): bool
    {
        return $this->isQuanLy($nhanvien);
    }

    /**
     * Determine if the user can sell tickets (Nhân viên bán vé only).
     */
    public function sellTickets(?Nhanvien $nhanvien): bool
    {
        return $this->isNhanVienBanVe($nhanvien);
    }

    /**
     * Determine if the user can drive trips (Tài xế only).
     */
    public function driveTrips(?Nhanvien $nhanvien): bool
    {
        return $this->isTaiXe($nhanvien);
    }

    /**
     * Determine if the user can update their own profile.
     */
    public function updateOwnProfile(?Nhanvien $user, Nhanvien $nhanvien): bool
    {
        return $user && $user->manv === $nhanvien->manv;
    }

    /**
     * Determine if the user can view any nhanvien (Quản lý only).
     */
    public function viewAny(?Nhanvien $nhanvien): bool
    {
        return $this->isQuanLy($nhanvien);
    }

    /**
     * Determine if the user can create nhanvien (Quản lý only).
     */
    public function create(?Nhanvien $nhanvien): bool
    {
        return $this->isQuanLy($nhanvien);
    }

    /**
     * Determine if the user can update nhanvien (Quản lý only).
     */
    public function update(?Nhanvien $user, Nhanvien $nhanvien): bool
    {
        return $this->isQuanLy($user);
    }

    /**
     * Determine if the user can delete nhanvien (Quản lý only).
     */
    public function delete(?Nhanvien $user, Nhanvien $nhanvien): bool
    {
        return $this->isQuanLy($user) && $user->manv !== $nhanvien->manv;
    }
}
