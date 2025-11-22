<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'manv',
        'action',
        'model',
        'model_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'description',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationship: Activity log belongs to a Nhanvien.
     */
    public function nhanvien()
    {
        return $this->belongsTo(Nhanvien::class, 'manv', 'manv');
    }

    /**
     * Scope: Filter by action type.
     */
    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope: Filter by model.
     */
    public function scopeByModel($query, $model)
    {
        return $query->where('model', $model);
    }

    /**
     * Scope: Filter by user.
     */
    public function scopeByUser($query, $manv)
    {
        return $query->where('manv', $manv);
    }

    /**
     * Scope: Recent logs.
     */
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Get formatted action name.
     */
    public function getActionNameAttribute()
    {
        $actions = [
            'created' => 'Tạo mới',
            'updated' => 'Cập nhật',
            'deleted' => 'Xóa',
            'approved' => 'Duyệt',
            'cancelled' => 'Hủy',
            'login' => 'Đăng nhập',
            'logout' => 'Đăng xuất',
        ];

        return $actions[$this->action] ?? $this->action;
    }

    /**
     * Static method to log activity.
     */
    public static function log($action, $model, $modelId = null, $oldValues = null, $newValues = null, $description = null)
    {
        // Lấy thông tin user từ session
        $user = session('admin') ?? session('nhanvien') ?? session('taixe');

        if (!$user) {
            return null;
        }

        return self::create([
            'manv' => $user->manv,
            'action' => $action,
            'model' => is_object($model) ? get_class($model) : $model,
            'model_id' => $modelId,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'description' => $description,
        ]);
    }
}
