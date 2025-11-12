<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TinhThanh extends Model
{
    protected $table = 'tinhthanh';
    protected $primaryKey = 'matinh';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['matinh', 'ten'];

    public function lotrinhs()
    {
        return $this->hasMany(Lotrinh::class, 'matinh', 'matinh');
    }
}
