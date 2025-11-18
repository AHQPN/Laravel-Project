<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Baocaosuco extends Model
{
    protected $table = 'baocaosuco';
    protected $primaryKey = 'id_baocao';
    public $timestamps = false;

    const CREATED_AT = 'tao_luc';
    const UPDATED_AT = 'capnhat_luc';

    protected $fillable = [
        'machuyendi',
        'manv',
        'loai_suco',
        'mota',
        'duongdan_anh',
        'trangthai',
        'tao_luc',
        'capnhat_luc'
    ];

    protected $casts = [
        'tao_luc' => 'datetime',
        'capnhat_luc' => 'datetime',
    ];

    /**
     * Get the chuyendi that owns the incident report.
     */
    public function chuyendi()
    {
        return $this->belongsTo(Chuyendi::class, 'machuyendi', 'machuyendi');
    }

    /**
     * Get the nhanvien that reported the incident.
     */
    public function nhanvien()
    {
        return $this->belongsTo(Nhanvien::class, 'manv', 'manv');
    }
}
