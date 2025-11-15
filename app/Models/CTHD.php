<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CTHD extends Model
{
    protected $table = 'CTHD';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['mahd', 'mave', 'dongia'];

    // ==================== RELATIONSHIPS ====================

    public function hoadon()
    {
        return $this->belongsTo(Hoadon::class, 'mahd', 'mahd');
    }

    public function ve()
    {
        return $this->belongsTo(Ve::class, 'mave', 'mave');
    }

    // ==================== SCOPES ====================

    /**
     * Scope lọc theo hóa đơn.
     */
    public function scopeByHoaDon($query, $mahd)
    {
        return $query->where('mahd', $mahd);
    }

    /**
     * Scope lọc theo vé.
     */
    public function scopeByVe($query, $mave)
    {
        return $query->where('mave', $mave);
    }
}
