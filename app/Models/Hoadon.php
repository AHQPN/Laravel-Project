<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hoadon extends Model
{
    protected $table = 'hoadon';
    protected $primaryKey = 'mahd';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'mahd', 'thoigian', 'makh', 'manv', 'matt', 
        'soluong', 'thanhtien', 'trangthai'
    ];

    protected $casts = [
        'thoigian' => 'datetime',
    ];

    public function khach()
    {
        return $this->belongsTo(Khach::class, 'makh', 'makh');
    }

    public function nhanvien()
    {
        return $this->belongsTo(Nhanvien::class, 'manv', 'manv');
    }

    public function thanhtoan()
    {
        return $this->belongsTo(Thanhtoan::class, 'matt', 'matt');
    }

    public function cthds()
    {
        return $this->hasMany(CTHD::class, 'mahd', 'mahd');
    }
}
