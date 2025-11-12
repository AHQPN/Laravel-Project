<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loaixe extends Model
{
    protected $table = 'loaixe';
    protected $primaryKey = 'maloai';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['maloai', 'tenloai', 'soghe'];

    public function xes()
    {
        return $this->hasMany(Xe::class, 'maloai', 'maloai');
    }
}
