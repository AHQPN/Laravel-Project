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

    /**
     * SỬA LỖI: Thêm 'trangthai' và 'pending_expires_at' vào đây
     * để cho phép Controller có quyền ghi vào 2 cột này.
     */
    protected $fillable = [
        'mave',
        'machuyendi',
        'maghe',
        'trangthai',
        'pending_expires_at'
    ];

    public function chuyendi()
    {
        return $this->belongsTo(Chuyendi::class, 'machuyendi', 'machuyendi');
    }

    public function cthds()
    {
        return $this->hasMany(CTHD::class, 'mave', 'mave');
    }
}
