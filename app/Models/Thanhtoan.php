<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Thanhtoan extends Model
{
    protected $table = 'thanhtoan';
    protected $primaryKey = 'matt';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['matt', 'ptthanhtoan'];

    public function hoadons()
    {
        return $this->hasMany(Hoadon::class, 'matt', 'matt');
    }
}
