<?php

namespace App\Policies;

use App\Models\Nhanvien;
use App\Models\Hoadon;

class HoadonPolicy
{
    /**
     * Determine if the user can view any invoices.
     */
    public function viewAny(?Nhanvien $nhanvien): bool
    {
        // Quản lý và Nhân viên bán vé có thể xem tất cả hóa đơn
        return $nhanvien && 
               ($nhanvien->macv === 'QL' || $nhanvien->macv === 'BV') && 
               $nhanvien->isActive();
    }

    /**
     * Determine if the user can view the invoice.
     */
    public function view(?Nhanvien $nhanvien, Hoadon $hoadon): bool
    {
        if (!$nhanvien || !$nhanvien->isActive()) {
            return false;
        }

        // Quản lý và Nhân viên bán vé xem được tất cả
        if ($nhanvien->macv === 'QL' || $nhanvien->macv === 'BV') {
            return true;
        }

        // Nhân viên khác chỉ xem được hóa đơn do mình tạo
        return $hoadon->manv === $nhanvien->manv;
    }

    /**
     * Determine if the user can create invoices.
     */
    public function create(?Nhanvien $nhanvien): bool
    {
        // Chỉ Nhân viên bán vé mới tạo được hóa đơn
        return $nhanvien && 
               $nhanvien->macv === 'BV' && 
               $nhanvien->isActive();
    }

    /**
     * Determine if the user can update the invoice.
     */
    public function update(?Nhanvien $nhanvien, Hoadon $hoadon): bool
    {
        if (!$nhanvien || !$nhanvien->isActive()) {
            return false;
        }

        // Quản lý có thể sửa tất cả
        if ($nhanvien->macv === 'QL') {
            return true;
        }

        // Nhân viên bán vé chỉ sửa được hóa đơn chưa duyệt do mình tạo
        if ($nhanvien->macv === 'BV') {
            return $hoadon->manv === $nhanvien->manv && 
                   $hoadon->tinhtrang === 'Chờ duyệt';
        }

        return false;
    }

    /**
     * Determine if the user can approve the invoice.
     */
    public function approve(?Nhanvien $nhanvien, Hoadon $hoadon): bool
    {
        if (!$nhanvien || !$nhanvien->isActive()) {
            return false;
        }

        // Chỉ Quản lý mới duyệt được hóa đơn
        if ($nhanvien->macv !== 'QL') {
            return false;
        }

        // Chỉ duyệt được hóa đơn đang chờ duyệt
        return $hoadon->tinhtrang === 'Chờ duyệt';
    }

    /**
     * Determine if the user can cancel the invoice.
     */
    public function cancel(?Nhanvien $nhanvien, Hoadon $hoadon): bool
    {
        if (!$nhanvien || !$nhanvien->isActive()) {
            return false;
        }

        // Quản lý có thể hủy bất kỳ hóa đơn nào chưa hoàn thành
        if ($nhanvien->macv === 'QL') {
            return $hoadon->tinhtrang !== 'Hoàn thành';
        }

        // Nhân viên bán vé chỉ hủy được hóa đơn chờ duyệt do mình tạo
        if ($nhanvien->macv === 'BV') {
            return $hoadon->manv === $nhanvien->manv && 
                   $hoadon->tinhtrang === 'Chờ duyệt';
        }

        return false;
    }

    /**
     * Determine if the user can delete the invoice.
     */
    public function delete(?Nhanvien $nhanvien, Hoadon $hoadon): bool
    {
        // Chỉ Quản lý mới xóa được và chỉ xóa hóa đơn đã hủy
        return $nhanvien && 
               $nhanvien->macv === 'QL' && 
               $nhanvien->isActive() && 
               $hoadon->tinhtrang === 'Đã hủy';
    }

    /**
     * Determine if the user can export invoice reports.
     */
    public function export(?Nhanvien $nhanvien): bool
    {
        return $nhanvien && 
               ($nhanvien->macv === 'QL' || $nhanvien->macv === 'BV') && 
               $nhanvien->isActive();
    }
}
