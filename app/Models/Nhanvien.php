<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Nhanvien extends Model
{
    protected $table = 'nhanvien';
    protected $primaryKey = 'manv';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'manv',
        'macv',
        'password',
        'ten',
        'sdt',
        'diachi',
        'cccd',
        'email',
        'ngaysinh',
        'gioitinh',
        'hinhanh',
        'trangthai'
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'ngaysinh' => 'datetime',
        'trangthai' => 'boolean',
    ];

    protected $appends = ['ten_chuc_vu', 'trang_thai_text'];

    // ==================== RELATIONSHIPS ====================

    public function chucvu()
    {
        return $this->belongsTo(Chucvu::class, 'macv', 'macv');
    }

    public function xes()
    {
        return $this->hasMany(Xe::class, 'manv', 'manv');
    }

    public function hoadons()
    {
        return $this->hasMany(Hoadon::class, 'manv', 'manv');
    }

    public function baocaosucos()
    {
        return $this->hasMany(BaocaoSuco::class, 'manv', 'manv');
    }

    // ==================== SCOPES ====================

    /**
     * Scope để lọc nhân viên đang hoạt động.
     */
    public function scopeActive($query)
    {
        return $query->where('trangthai', 1);
    }

    /**
     * Scope để lọc theo chức vụ.
     */
    public function scopeByRole($query, $macv)
    {
        return $query->where('macv', $macv);
    }

    /**
     * Scope để lọc theo trạng thái.
     */
    public function scopeByStatus($query, $trangthai)
    {
        return $query->where('trangthai', $trangthai);
    }

    // ==================== ACCESSORS ====================

    /**
     * Accessor để lấy tên chức vụ.
     */
    public function getTenChucVuAttribute()
    {
        return $this->chucvu->tencv ?? 'N/A';
    }

    /**
     * Accessor để lấy text trạng thái.
     */
    public function getTrangThaiTextAttribute()
    {
        return $this->trangthai ? 'Hoạt động' : 'Khóa';
    }

    /**
     * Accessor để format ngày sinh.
     */
    public function getNgaySinhFormattedAttribute()
    {
        return $this->ngaysinh ? $this->ngaysinh->format('d/m/Y') : null;
    }

    // ==================== MUTATORS ====================

    /**
     * Mutator để tự động hash password khi set.
     * Chỉ hash nếu password chưa được hash.
     */
    public function setPasswordAttribute($value)
    {
        // Nếu value rỗng, không làm gì
        if (empty($value)) {
            return;
        }

        // Chỉ hash nếu password chưa được hash (không bắt đầu bằng $2y$)
        if (!str_starts_with($value, '$2y$') && !str_starts_with($value, '$2a$')) {
            $this->attributes['password'] = Hash::make($value);
        } else {
            $this->attributes['password'] = $value;
        }
    }

    // ==================== HELPER METHODS ====================

    /**
     * Kiểm tra nhân viên có đang hoạt động không.
     */
    public function isActive(): bool
    {
        return (bool) $this->trangthai;
    }

    /**
     * Kiểm tra nhân viên có phải Quản lý không.
     */
    public function isQuanLy(): bool
    {
        return $this->macv === 'QL';
    }

    /**
     * Kiểm tra nhân viên có phải Nhân viên bán vé không.
     */
    public function isNhanVienBanVe(): bool
    {
        return $this->macv === 'BV';
    }

    /**
     * Kiểm tra nhân viên có phải Tài xế không.
     */
    public function isTaiXe(): bool
    {
        return $this->macv === 'TX';
    }
}
