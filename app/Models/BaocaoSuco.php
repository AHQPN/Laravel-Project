<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BaocaoSuco extends Model
{
    protected $table = 'BaocaoSuco';
    protected $primaryKey = 'mabaocao';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'mabaocao',
        'machuyendi',
        'manv',
        'loai_suco',
        'loaisuco',
        'mota',
        'vitri',
        'thoigian',
        'trangthai',
        'duongdan_anh',
        'tao_luc',
    ];

    protected $casts = [
        'thoigian' => 'datetime',
    ];

    protected $appends = ['trang_thai_badge'];

    // ==================== RELATIONSHIPS ====================

    public function chuyendi(): BelongsTo
    {
        return $this->belongsTo(Chuyendi::class, 'machuyendi', 'machuyendi');
    }

    public function taixe(): BelongsTo
    {
        return $this->belongsTo(Nhanvien::class, 'manv', 'manv');
    }

    // ==================== SCOPES ====================

    /**
     * Scope lọc báo cáo theo trạng thái.
     */
    public function scopeByStatus($query, $trangthai)
    {
        return $query->where('trangthai', $trangthai);
    }

    /**
     * Scope lọc báo cáo đang xử lý.
     */
    public function scopePending($query)
    {
        return $query->where('trangthai', 'Đang xử lý');
    }

    /**
     * Scope lọc báo cáo đã hoàn thành.
     */
    public function scopeCompleted($query)
    {
        return $query->where('trangthai', 'Đã hoàn thành');
    }

    /**
     * Scope lọc báo cáo theo loại sự cố.
     */
    public function scopeByType($query, $loaisuco)
    {
        return $query->where('loaisuco', $loaisuco);
    }

    /**
     * Scope lọc báo cáo theo chuyến đi.
     */
    public function scopeByTrip($query, $machuyendi)
    {
        return $query->where('machuyendi', $machuyendi);
    }

    /**
     * Scope lọc báo cáo theo tài xế.
     */
    public function scopeByDriver($query, $manv)
    {
        return $query->where('manv', $manv);
    }

    // ==================== ACCESSORS ====================

    /**
     * Accessor lấy badge trạng thái.
     */
    public function getTrangThaiBadgeAttribute()
    {
        return match($this->trangthai) {
            'Đang xử lý' => 'warning',
            'Đã hoàn thành' => 'success',
            'Đã hủy' => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Accessor lấy thời gian đã format.
     */
    public function getThoiGianFormattedAttribute()
    {
        return $this->thoigian ? $this->thoigian->format('d/m/Y H:i') : null;
    }
}

