<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Xe extends Model
{
    protected $table = 'xe';
    protected $primaryKey = 'maxe';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['maxe', 'maloai', 'soxe', 'manv'];

    public function loaixe()
    {
        return $this->belongsTo(Loaixe::class, 'maloai', 'maloai');
    }

    public function taixe()
    {
        return $this->belongsTo(Nhanvien::class, 'manv', 'manv');
    }

    public function chuyendis()
    {
        return $this->hasMany(Chuyendi::class, 'maxe', 'maxe');
    }
}
