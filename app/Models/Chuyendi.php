<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chuyendi extends Model
{
    protected $table = 'chuyendi';
    protected $primaryKey = 'machuyendi';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'machuyendi', 'tenchuyen', 'maxe', 'SLgheconlai', 
        'thoigiandi', 'thoigiandichuyen', 'gia', 'trangthai',
        'batdau_luc', 'ketthuc_luc'
    ];

    protected $casts = [
        'thoigiandi' => 'datetime',
        'batdau_luc' => 'datetime',
        'ketthuc_luc' => 'datetime',
    ];

    public function xe()
    {
        return $this->belongsTo(Xe::class, 'maxe', 'maxe');
    }

    public function lotrinhs()
    {
        return $this->hasMany(Lotrinh::class, 'machuyendi', 'machuyendi')->orderBy('trinhtu');
    }

    public function ves()
    {
        return $this->hasMany(Ve::class, 'machuyendi', 'machuyendi');
    }

    public function baocaosucos()
    {
        return $this->hasMany(Baocaosuco::class, 'machuyendi', 'machuyendi');
    }
}
