<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nhanvien extends Model
{
    protected $table = 'nhanvien';
    protected $primaryKey = 'manv';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'manv', 'macv', 'password', 'ten', 'sdt', 'diachi', 
        'cccd', 'email', 'ngaysinh', 'gioitinh', 'hinhanh', 'trangthai'
    ];

    protected $hidden = ['password'];

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
}
