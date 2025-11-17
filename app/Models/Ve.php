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
     */
    protected $fillable = [
        'mave',
        'machuyendi',
        'maghe',
        'trangthai',
        'pending_expires_at'
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
     * Scope lọc vé theo trạng thái.
     */
    public function scopeByStatus($query, $trangthai)
    {
        return $query->where('trangthai', $trangthai);
    }

    /**
     * Scope lọc vé đã đặt.
     */
    public function scopeBooked($query)
    {
        return $query->where('trangthai', 'Đã đặt');
    }

    /**
     * Scope lọc vé đã thanh toán.
     */
    public function scopePaid($query)
    {
        return $query->where('trangthai', 'Đã thanh toán');
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
     */
    public function scopePickedUp($query)
    {
        return $query->where('pickup_status', 1);
    }

    /**
     * Scope lọc vé chưa đón khách.
     */
    public function scopeNotPickedUp($query)
    {
        return $query->where('pickup_status', 0);
    }

    // ==================== ACCESSORS ====================

    /**
     * Accessor lấy badge trạng thái.
     */
    public function getTrangThaiBadgeAttribute()
    {
        return match($this->trangthai) {
            'Đã đặt' => 'warning',
            'Đã thanh toán' => 'success',
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
        return in_array($this->trangthai, ['Đã đặt', 'Đã thanh toán']);
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
