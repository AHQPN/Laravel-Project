<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chucvu extends Model
{
    protected $table = 'chucvu';
    protected $primaryKey = 'macv';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['macv', 'chucvu'];

    // ==================== RELATIONSHIPS ====================

    public function nhanviens()
    {
        return $this->hasMany(Nhanvien::class, 'macv', 'macv');
    }

    // ==================== SCOPES ====================

    /**
     * Scope lọc chức vụ quản lý.
     */
    public function scopeManagement($query)
    {
        return $query->where('macv', 'QL');
    }

    /**
     * Scope lọc chức vụ nhân viên bán vé.
     */
    public function scopeTicketSeller($query)
    {
        return $query->where('macv', 'BV');
    }

    /**
     * Scope lọc chức vụ tài xế.
     */
    public function scopeDriver($query)
    {
        return $query->where('macv', 'TX');
    }

    // ==================== ACCESSORS ====================

    /**
     * Accessor cho ten_chucvu (alias cho chucvu)
     */
    public function getTenChucvuAttribute()
    {
        return $this->chucvu;
    }

    // ==================== HELPER METHODS ====================

    /**
     * Lấy tổng số nhân viên thuộc chức vụ này.
     */
    public function getTotalEmployees(): int
    {
        return $this->nhanviens()->count();
    }

    /**
     * Lấy số nhân viên đang hoạt động.
     */
    public function getActiveEmployees(): int
    {
        return $this->nhanviens()->where('trangthai', 1)->count();
    }
}
