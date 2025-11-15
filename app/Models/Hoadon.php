<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Hoadon extends Model
{
    protected $table = 'Hoadon';
    protected $primaryKey = 'mahd';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'mahd', 'makh', 'manv', 'thoigian', 'matt', 
        'soluong', 'thanhtien', 'trangthai'
    ];

    protected $casts = [
        'thoigian' => 'datetime',
        'thanhtien' => 'integer',
    ];

    protected $appends = ['trang_thai_badge', 'thoi_gian_formatted'];

    // Quan hệ với khách hàng
    public function khach()
    {
        return $this->belongsTo(Khach::class, 'makh', 'makh');
    }

    // Quan hệ với nhân viên xử lý
    public function nhanvien()
    {
        return $this->belongsTo(Nhanvien::class, 'manv', 'manv');
    }

    // Quan hệ với phương thức thanh toán
    public function thanhtoan()
    {
        return $this->belongsTo(Thanhtoan::class, 'matt', 'matt');
    }

    // Một hóa đơn có nhiều chi tiết hóa đơn
    public function cthds()
    {
        return $this->hasMany(CTHD::class, 'mahd', 'mahd');
    }

    // Lấy danh sách vé qua chi tiết hóa đơn
    public function ves()
    {
        return $this->hasManyThrough(
            Ve::class,
            CTHD::class,
            'mahd',      // Foreign key on CTHD table
            'mave',      // Foreign key on Ve table
            'mahd',      // Local key on Hoadon table
            'mave'       // Local key on CTHD table
        );
    }

    // ==================== SCOPES ====================

    /**
     * Scope lọc hóa đơn theo trạng thái.
     */
    public function scopeByStatus($query, $trangthai)
    {
        return $query->where('trangthai', $trangthai);
    }

    /**
     * Scope lọc hóa đơn chờ xử lý.
     */
    public function scopePending($query)
    {
        return $query->where('trangthai', 'Chờ xử lý');
    }

    /**
     * Scope lọc hóa đơn đã thanh toán.
     */
    public function scopePaid($query)
    {
        return $query->where('trangthai', 'Đã thanh toán');
    }

    /**
     * Scope lọc hóa đơn đã hủy.
     */
    public function scopeCancelled($query)
    {
        return $query->where('trangthai', 'Đã hủy');
    }

    /**
     * Scope lọc theo ngày lập.
     */
    public function scopeByDate($query, $date)
    {
        return $query->whereDate('thoigian', $date);
    }

    /**
     * Scope lọc theo khoảng ngày.
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('thoigian', [$startDate, $endDate]);
    }

    /**
     * Scope lọc theo tháng.
     */
    public function scopeByMonth($query, $month, $year = null)
    {
        $year = $year ?? Carbon::now()->year;
        return $query->whereMonth('thoigian', $month)
                     ->whereYear('thoigian', $year);
    }

    // ==================== ACCESSORS ====================

    /**
     * Accessor lấy badge trạng thái.
     */
    public function getTrangThaiBadgeAttribute()
    {
        return match($this->trangthai) {
            'Chờ xử lý' => 'warning',
            'Đã thanh toán' => 'success',
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

    /**
     * Accessor lấy tổng tiền đã format.
     */
    public function getThanhTienFormattedAttribute()
    {
        return number_format($this->thanhtien, 0, ',', '.') . ' VNĐ';
    }

    // ==================== HELPER METHODS ====================

    /**
     * Kiểm tra hóa đơn có thể hủy không.
     */
    public function canCancel(): bool
    {
        return $this->trangthai === 'Chờ xử lý';
    }

    /**
     * Kiểm tra hóa đơn có thể duyệt không.
     */
    public function canApprove(): bool
    {
        return $this->trangthai === 'Chờ xử lý';
    }
}
