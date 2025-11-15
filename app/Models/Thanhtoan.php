<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Thanhtoan extends Model
{
    protected $table = 'Thanhtoan';
    protected $primaryKey = 'matt';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['matt', 'ptthanhtoan', 'mota'];

    // ==================== RELATIONSHIPS ====================

    public function hoadons()
    {
        return $this->hasMany(Hoadon::class, 'matt', 'matt');
    }

    // ==================== SCOPES ====================

    /**
     * Scope lọc phương thức thanh toán tiền mặt.
     */
    public function scopeCash($query)
    {
        return $query->where('matt', 'TM');
    }

    /**
     * Scope lọc phương thức chuyển khoản.
     */
    public function scopeBankTransfer($query)
    {
        return $query->where('matt', 'CK');
    }

    /**
     * Scope lọc phương thức ví điện tử.
     */
    public function scopeEWallet($query)
    {
        return $query->where('matt', 'VDT');
    }

    // ==================== HELPER METHODS ====================

    /**
     * Lấy tổng số giao dịch dùng phương thức này.
     */
    public function getTotalTransactions(): int
    {
        return $this->hoadons()->count();
    }

    /**
     * Lấy tổng doanh thu qua phương thức này.
     */
    public function getTotalRevenue(): float
    {
        return $this->hoadons()->where('trangthai', 'Đã thanh toán')->sum('tongtien') ?? 0;
    }
}
