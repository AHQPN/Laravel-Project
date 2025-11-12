<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CTHD extends Model
{
    protected $table = 'cthd';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['mahd', 'mave', 'dongia'];

    public function hoadon()
    {
        return $this->belongsTo(Hoadon::class, 'mahd', 'mahd');
    }

    public function ve()
    {
        return $this->belongsTo(Ve::class, 'mave', 'mave');
    }
}
