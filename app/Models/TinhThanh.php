<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TinhThanh extends Model
{
    protected $table = 'TinhThanh';
    protected $primaryKey = 'matinh';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['matinh', 'ten', 'mien'];

    // ==================== RELATIONSHIPS ====================

    public function lotrinhsDi()
    {
        return $this->hasMany(Lotrinh::class, 'diemdi', 'matinh');
    }

    public function lotrinhsDen()
    {
        return $this->hasMany(Lotrinh::class, 'diemden', 'matinh');
    }

    // ==================== SCOPES ====================

    /**
     * Scope lọc tỉnh theo miền.
     */
    public function scopeByRegion($query, $mien)
    {
        return $query->where('mien', $mien);
    }

    /**
     * Scope lọc tỉnh miền Bắc.
     */
    public function scopeNorth($query)
    {
        return $query->where('mien', 'Bắc');
    }

    /**
     * Scope lọc tỉnh miền Trung.
     */
    public function scopeCentral($query)
    {
        return $query->where('mien', 'Trung');
    }

    /**
     * Scope lọc tỉnh miền Nam.
     */
    public function scopeSouth($query)
    {
        return $query->where('mien', 'Nam');
    }

    /**
     * Scope tìm kiếm theo tên.
     */
    public function scopeSearchByName($query, $ten)
    {
        return $query->where('ten', 'like', "%{$ten}%");
    }

    // ==================== HELPER METHODS ====================

    /**
     * Lấy tổng số tuyến xuất phát từ tỉnh này.
     */
    public function getTotalDepartureRoutes(): int
    {
        return $this->lotrinhsDi()->count();
    }

    /**
     * Lấy tổng số tuyến đến tỉnh này.
     */
    public function getTotalArrivalRoutes(): int
    {
        return $this->lotrinhsDen()->count();
    }
}
