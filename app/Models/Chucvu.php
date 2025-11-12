<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chucvu extends Model
{
    protected $table = 'chucvu';
    protected $primaryKey = 'macv';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['macv', 'chucvu'];

    public function nhanviens()
    {
        return $this->hasMany(Nhanvien::class, 'macv', 'macv');
    }
}
