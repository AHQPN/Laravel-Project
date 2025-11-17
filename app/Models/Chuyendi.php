<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Chuyendi extends Model
{
    protected $table = 'Chuyendi';
    protected $primaryKey = 'machuyendi';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'machuyendi',
        'tenchuyen',
        'maxe',
        'SLgheconlai',
        'thoigiandi',
        'thoigiandichuyen',
        'gia',
        'trangthai',
        'batdau_luc',
        'ketthuc_luc',
    ];

    protected $casts = [
        'thoigiandi' => 'datetime',
        'batdau_luc' => 'datetime',
        'ketthuc_luc' => 'datetime',
        'gia' => 'integer',
        'SLgheconlai' => 'integer',
    ];

    protected $appends = ['trang_thai_badge', 'ngay_gio_di'];

    // Quan hệ với bảng Xe
    public function xe()
    {
        return $this->belongsTo(Xe::class, 'maxe', 'maxe');
    }

    // Một chuyến đi có nhiều điểm dừng trong lộ trình
    public function lotrinhs()
    {
        return $this->hasMany(Lotrinh::class, 'machuyendi', 'machuyendi');
    }

    // Một chuyến đi có nhiều vé
    // Một chuyến đi có nhiều vé
    public function ves()
    {
        return $this->hasMany(Ve::class, 'machuyendi', 'machuyendi');
    }

    // Một chuyến đi có thể có nhiều báo cáo sự cố
    public function baocaosucos()
    {
        return $this->hasMany(BaocaoSuco::class, 'machuyendi', 'machuyendi');
    }

    // Lọc chuyến đi theo trạng thái
    public function scopeByStatus($query, $trangthai)
    {
        return $query->where('trangthai', $trangthai);
    }

    // Lọc chuyến đi chưa khởi hành
    public function scopePending($query)
    {
        return $query->where('trangthai', 'Chưa khởi hành');
    }

    // Lọc chuyến đi đang di chuyển
    public function scopeOngoing($query)
    {
        return $query->where('trangthai', 'Đang di chuyển');
    }

    // Lọc chuyến đi đã hoàn thành
    public function scopeCompleted($query)
    {
        return $query->where('trangthai', 'Đã hoàn thành');
    }

    // Lọc chuyến đi theo ngày
    public function scopeByDate($query, $date)
    {
        return $query->whereDate('thoigiandi', $date);
    }

    // Lọc chuyến đi hôm nay
    public function scopeToday($query)
    {
        return $query->whereDate('thoigiandi', Carbon::today());
    }

    // Lọc chuyến đi sắp tới (từ hôm nay trở đi)
    public function scopeUpcoming($query)
    {
        return $query->where('thoigiandi', '>=', Carbon::today());
    }

    // Lấy badge màu theo trạng thái chuyến đi
    public function getTrangThaiBadgeAttribute()
    {
        return match($this->trangthai) {
            'Chưa khởi hành' => 'warning',
            'Đang di chuyển' => 'info',
            'Đã hoàn thành' => 'success',
            'Đã hủy' => 'danger',
            default => 'secondary',
        };
    }

    // Lấy ngày giờ đi định dạng đẹp
    public function getNgayGioDiAttribute()
    {
        return $this->thoigiandi ? $this->thoigiandi->format('d/m/Y H:i') : null;
    }

    // Kiểm tra chuyến đi có thể hủy không
    public function canCancel(): bool
    {
        return $this->trangthai === 'Chưa khởi hành';
    }

    // Kiểm tra chuyến đi có thể bắt đầu không
    public function canStart(): bool
    {
        return $this->trangthai === 'Chưa khởi hành' && 
               Carbon::parse($this->thoigiandi)->isToday();
    }
}
