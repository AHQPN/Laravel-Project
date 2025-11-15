<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Xe extends Model
{
    protected $table = 'Xe';
    protected $primaryKey = 'maxe';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['maxe', 'maloai', 'soxe', 'manv', 'manvpx', 'trangthai'];

    protected $casts = [
        'trangthai' => 'boolean',
    ];

    protected $appends = ['trang_thai_text'];

    // ==================== RELATIONSHIPS ====================

    public function loaixe()
    {
        return $this->belongsTo(Loaixe::class, 'maloai', 'maloai');
    }

    public function taixe()
    {
        return $this->belongsTo(Nhanvien::class, 'manv', 'manv');
    }

    public function phuxe()
    {
        return $this->belongsTo(Nhanvien::class, 'manvpx', 'manv');
    }

    public function chuyendis()
    {
        return $this->hasMany(Chuyendi::class, 'maxe', 'maxe');
    }

    // ==================== SCOPES ====================

    /**
     * Scope lọc xe đang hoạt động.
     */
    public function scopeActive($query)
    {
        return $query->where('trangthai', 1);
    }

    /**
     * Scope lọc xe theo loại.
     */
    public function scopeByType($query, $maloai)
    {
        return $query->where('maloai', $maloai);
    }

    /**
     * Scope lọc xe theo tài xế.
     */
    public function scopeByDriver($query, $manv)
    {
        return $query->where('manv', $manv);
    }

    /**
     * Scope lọc xe không có tài xế.
     */
    public function scopeWithoutDriver($query)
    {
        return $query->whereNull('manv');
    }

    // ==================== ACCESSORS ====================

    /**
     * Accessor lấy text trạng thái.
     */
    public function getTrangThaiTextAttribute()
    {
        return $this->trangthai ? 'Hoạt động' : 'Khóa';
    }

    /**
     * Accessor lấy thông tin đầy đủ.
     */
    public function getFullInfoAttribute()
    {
        return $this->soxe . ' - ' . ($this->loaixe->tenloai ?? 'N/A');
    }

    // ==================== HELPER METHODS ====================

    /**
     * Kiểm tra xe có đang hoạt động không.
     */
    public function isActive(): bool
    {
        return (bool) $this->trangthai;
    }

    /**
     * Kiểm tra xe có tài xế chưa.
     */
    public function hasDriver(): bool
    {
        return !is_null($this->manv);
    }
}
