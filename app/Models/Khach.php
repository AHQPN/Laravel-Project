<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Khach extends Model
{
    protected $table = 'khach';
    protected $primaryKey = 'makh';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['makh', 'password', 'ten', 'sdt', 'diachi', 'ngaysinh', 'gioitinh'];
    protected $hidden = ['password'];

    public function hoadons()
    {
        return $this->hasMany(Hoadon::class, 'makh', 'makh');
    }
}
