<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Khach extends Model
{
    protected $table = 'khach';
    protected $primaryKey = 'makh';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'makh',
        'password',
        'ten',
        'sdt',
        'diachi',
        'ngaysinh',
        'gioitinh',
        'email'
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'ngaysinh' => 'datetime',
    ];

    protected $appends = ['ngay_sinh_formatted'];

    // ==================== RELATIONSHIPS ====================

    public function hoadons()
    {
        return $this->hasMany(Hoadon::class, 'makh', 'makh');
    }

    // ==================== SCOPES ====================

    /**
     * Scope tìm kiếm khách theo sđt.
     */
    public function scopeByPhone($query, $sdt)
    {
        return $query->where('sdt', $sdt);
    }

    /**
     * Scope tìm kiếm khách theo email.
     */
    public function scopeByEmail($query, $email)
    {
        return $query->where('email', $email);
    }

    /**
     * Scope tìm kiếm khách theo tên.
     */
    public function scopeSearchByName($query, $ten)
    {
        return $query->where('ten', 'like', "%{$ten}%");
    }

    // ==================== ACCESSORS ====================

    /**
     * Accessor lấy ngày sinh đã format.
     */
    public function getNgaySinhFormattedAttribute()
    {
        return $this->ngaysinh ? $this->ngaysinh->format('d/m/Y') : null;
    }

    // ==================== MUTATORS ====================

    /**
     * Mutator tự động hash password khi set.
     * Chỉ hash nếu password chưa được hash.
     */
    public function setPasswordAttribute($value)
    {
        // Nếu value rỗng, không làm gì
        if (empty($value)) {
            return;
        }

        // Chỉ hash nếu password chưa được hash
        if (!str_starts_with($value, '$2y$') && !str_starts_with($value, '$2a$')) {
            $this->attributes['password'] = Hash::make($value);
        } else {
            $this->attributes['password'] = $value;
        }
    }

    // ==================== HELPER METHODS ====================

    /**
     * Lấy tổng số vé đã đặt.
     */
    public function getTotalBookings(): int
    {
        return $this->hoadons()->count();
    }

    /**
     * Lấy tổng tiền đã chi tiêu.
     */
    public function getTotalSpent(): float
    {
        return $this->hoadons()->where('trangthai', 'Đã thanh toán')->sum('tongtien') ?? 0;
    }
}
