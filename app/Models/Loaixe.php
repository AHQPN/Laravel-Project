<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loaixe extends Model
{
    protected $table = 'loaixe';
    protected $primaryKey = 'maloai';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['maloai', 'tenloai', 'soghe'];

    protected $casts = [
        'soghe' => 'integer',
    ];

    // ==================== RELATIONSHIPS ====================

    public function xes()
    {
        return $this->hasMany(Xe::class, 'maloai', 'maloai');
    }

    // ==================== SCOPES ====================

    /**
     * Scope lọc loại xe theo số ghế.
     */
    public function scopeBySeats($query, $soghe)
    {
        return $query->where('soghe', $soghe);
    }

    /**
     * Scope lọc loại xe có số ghế từ.
     */
    public function scopeMinSeats($query, $minSeats)
    {
        return $query->where('soghe', '>=', $minSeats);
    }

    // ==================== HELPER METHODS ====================

    // Alias cho các đoạn code dùng tong_so_ghe
    public function getTongSoGheAttribute(): int
    {
        return (int) $this->soghe;
    }

    /**
     * Lấy tổng số xe thuộc loại này.
     */
    public function getTotalVehicles(): int
    {
        return $this->xes()->count();
    }
}
