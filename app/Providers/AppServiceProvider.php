<?php

namespace App\Providers;

use App\Http\View\Composers\CityComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Nhanvien;
use App\Policies\NhanvienPolicy;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        Nhanvien::class => NhanvienPolicy::class,
    ];

    public function register(): void
    {
        //
    }

    // Khởi tạo các Gates để kiểm tra quyền truy cập theo vai trò
    public function boot(): void
    {
        Gate::policy(Nhanvien::class, NhanvienPolicy::class);

        // Gate cho Quản lý
        Gate::define('access-quanly', function (?Nhanvien $nhanvien) {
            return $nhanvien && $nhanvien->macv === 'QL' && $nhanvien->isActive();
        });

        // Gate cho Nhân viên bán vé
        Gate::define('access-nhanvien-banve', function (?Nhanvien $nhanvien) {
            return $nhanvien && $nhanvien->macv === 'NVBV' && $nhanvien->isActive();
        });

        // Gate cho Tài xế
        Gate::define('access-taixe', function (?Nhanvien $nhanvien) {
            return $nhanvien && $nhanvien->macv === 'TX' && $nhanvien->isActive();
        });

        // Gate cho Nhân viên kiểm soát
        Gate::define('access-kiemsoat', function (?Nhanvien $nhanvien) {
            return $nhanvien && in_array($nhanvien->macv, ['NVKS', 'KS']) && $nhanvien->isActive();
        });
        // Cung cấp $cities cho view 'layouts.khach'
        View::composer('layouts.khach', CityComposer::class);
    }
}
