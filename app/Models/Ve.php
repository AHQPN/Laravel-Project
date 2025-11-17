<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ve extends Model
{
    protected $table = 'Ve';
    protected $primaryKey = 'mave';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    /**
     * SỬA LỖI: Thêm 'trangthai' và 'pending_expires_at' vào đây
     * để cho phép Controller có quyền ghi vào 2 cột này.
     * Thêm 'trangthai_don' và 'thoidiem_don' cho tài xế đánh dấu đón khách.
     */
    protected $fillable = [
        'mave',
        'machuyendi',
        'maghe',
        'trangthai',
        'pending_expires_at',
        'trangthai_don',
        'thoidiem_don'
    ];

    protected $casts = [
        'pending_expires_at' => 'datetime',
        'thoidiem_don' => 'datetime',
    ];

    public function chuyendi()
    {
        return $this->belongsTo(Chuyendi::class, 'machuyendi', 'machuyendi');
    }

    public function cthds()
    {
        return $this->hasMany(CTHD::class, 'mave', 'mave');
    }

    public function hoadon()
    {
        return $this->hasOneThrough(
            Hoadon::class,
            CTHD::class,
            'mave',      // Foreign key on CTHD table
            'mahd',      // Foreign key on Hoadon table
            'mave',      // Local key on Ve table
            'mahd'       // Local key on CTHD table
        );
    }

    // ==================== SCOPES ====================

    /**
     * Scope lọc ghế available (chưa đặt).
     * Ghế available là ghế có trạng thái 'Available'.
     */
    public function scopeAvailable($query)
    {
        return $query->where('trangthai', 'Available');
    }

    /**
     * Scope lọc ghế unavailable (đã đặt hoặc đang pending).
     * Bao gồm: 'Pending', 'Booked', 'approved', 'pending'.
     */
    public function scopeUnavailable($query)
    {
        return $query->whereIn('trangthai', [
            'Pending', 'Booked', 'approved', 'pending'
        ]);
    }

    /**
     * Scope lọc vé theo trạng thái.
     */
    public function scopeByStatus($query, $trangthai)
    {
        return $query->where('trangthai', $trangthai);
    }

    /**
     * Scope lọc vé đã đặt (Booked).
     */
    public function scopeBooked($query)
    {
        return $query->where('trangthai', 'Booked');
    }

    /**
     * Scope lọc vé đã thanh toán (approved).
     */
    public function scopePaid($query)
    {
        return $query->where('trangthai', 'approved');
    }

    /**
     * Scope lọc vé theo chuyến đi.
     */
    public function scopeByChuyenDi($query, $machuyendi)
    {
        return $query->where('machuyendi', $machuyendi);
    }

    /**
     * Scope lọc vé đã đón khách.
     * Sử dụng cột trangthai_don với giá trị 'da_don'.
     */
    public function scopePickedUp($query)
    {
        return $query->where('trangthai_don', 'da_don');
    }

    /**
     * Scope lọc vé chưa đón khách.
     * Bao gồm 'chua_don' và NULL.
     */
    public function scopeNotPickedUp($query)
    {
        return $query->where(function($q) {
            $q->where('trangthai_don', 'chua_don')
              ->orWhereNull('trangthai_don');
        });
    }

    /**
     * Scope lọc vé với pending đã hết hạn.
     */
    public function scopeExpiredPending($query)
    {
        return $query->where('trangthai', 'Pending')
            ->whereNotNull('pending_expires_at')
            ->where('pending_expires_at', '<', now());
    }

    // ==================== ACCESSORS ====================

    /**
     * Accessor lấy badge trạng thái.
     */
    public function getTrangThaiBadgeAttribute()
    {
        return match($this->trangthai) {
            'Pending' => 'info',
            'Booked' => 'warning',
            'approved' => 'success',
            'pending' => 'info',
            'Available' => 'secondary',
            'Đã hủy' => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Accessor lấy text trạng thái đón khách.
     */
    public function getPickupStatusTextAttribute()
    {
        return $this->pickup_status ? 'Đã đón' : 'Chưa đón';
    }

    // ==================== HELPER METHODS ====================

    /**
     * Kiểm tra vé có thể hủy không.
     */
    public function canCancel(): bool
    {
        return in_array($this->trangthai, ['Pending', 'Booked', 'approved', 'pending']);
    }

    /**
     * Toggle trạng thái đón khách.
     */
    public function togglePickupStatus(): bool
    {
        $this->pickup_status = !$this->pickup_status;
        return $this->save();
    }
}
