<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TinhThanhController;
use App\Http\Controllers\Admin\LoaixeController;
use App\Http\Controllers\Admin\XeController;
use App\Http\Controllers\Admin\ChuyendiController;
use App\Http\Controllers\Admin\HoadonController;
use App\Http\Controllers\Admin\NguoiDungController;
use App\Http\Controllers\Admin\ThongKeController;

Route::get('/', function () {
    return redirect()->route('admin.login');
});

// Admin Authentication Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // Protected Admin Routes
    Route::middleware(['admin.auth'])->group(function () {
        // Dashboard
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // Tỉnh Thành (Tuyến đường) - UC-12
        Route::resource('tinhthanh', TinhThanhController::class);
        
        // Loại xe - UC-13
        Route::resource('loaixe', LoaixeController::class);
        
        // Xe - UC-14
        Route::resource('xe', XeController::class);
        
        // Chuyến đi - UC-15
        Route::resource('chuyendi', ChuyendiController::class);
        
        // Đơn đặt vé - UC-16
        Route::resource('hoadon', HoadonController::class)->except(['create', 'store', 'edit', 'update']);
        Route::post('hoadon/{id}/approve', [HoadonController::class, 'approve'])->name('hoadon.approve');
        Route::post('hoadon/{id}/cancel', [HoadonController::class, 'cancel'])->name('hoadon.cancel');
        
        // Người dùng - UC-17
        Route::prefix('nguoidung')->name('nguoidung.')->group(function () {
            // Khách hàng
            Route::get('khach', [NguoiDungController::class, 'khach'])->name('khach');
            Route::get('khach/{id}/edit', [NguoiDungController::class, 'khachEdit'])->name('khach.edit');
            Route::put('khach/{id}', [NguoiDungController::class, 'khachUpdate'])->name('khach.update');
            Route::delete('khach/{id}', [NguoiDungController::class, 'khachDestroy'])->name('khach.destroy');
            
            // Nhân viên
            Route::get('nhanvien', [NguoiDungController::class, 'nhanvien'])->name('nhanvien');
            Route::get('nhanvien/create', [NguoiDungController::class, 'nhanvienCreate'])->name('nhanvien.create');
            Route::post('nhanvien', [NguoiDungController::class, 'nhanvienStore'])->name('nhanvien.store');
            Route::get('nhanvien/{id}/edit', [NguoiDungController::class, 'nhanvienEdit'])->name('nhanvien.edit');
            Route::put('nhanvien/{id}', [NguoiDungController::class, 'nhanvienUpdate'])->name('nhanvien.update');
            Route::delete('nhanvien/{id}', [NguoiDungController::class, 'nhanvienDestroy'])->name('nhanvien.destroy');
        });
        
        // Thống kê - UC-18
        Route::get('thongke', [ThongKeController::class, 'index'])->name('thongke.index');
    });
});
