<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lotrinh extends Model
{
    protected $table = 'lotrinh';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'machuyendi',
        'matinh',
        'trinhtu',
    ];

    protected $casts = [
        'trinhtu' => 'integer',
    ];

    // ==================== RELATIONSHIPS ====================

    public function chuyendi()
    {
        return $this->belongsTo(Chuyendi::class, 'machuyendi', 'machuyendi');
    }

    public function tinhthanh()
    {
        return $this->belongsTo(TinhThanh::class, 'matinh', 'matinh');
    }
}
