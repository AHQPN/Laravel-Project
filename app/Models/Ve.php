<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ve extends Model
{
    protected $table = 've';
    protected $primaryKey = 'mave';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['mave', 'machuyendi', 'maghe'];

    public function chuyendi()
    {
        return $this->belongsTo(Chuyendi::class, 'machuyendi', 'machuyendi');
    }

    public function cthds()
    {
        return $this->hasMany(CTHD::class, 'mave', 'mave');
    }
}
