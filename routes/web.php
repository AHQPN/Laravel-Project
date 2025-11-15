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
use App\Http\Controllers\NhanVienBanVe\NhanVienBanVeController;
use App\Http\Controllers\TaiXe\AuthTaiXeController;
use App\Http\Controllers\TaiXe\BaoCaoController;
use App\Http\Controllers\TaiXe\ChuyenController;
use App\Http\Controllers\TaiXe\HanhKhachController;
use App\Http\Controllers\TaiXe\ProfileController;
use App\Http\Controllers\PhuXe\AuthPhuXeController;
use App\Http\Controllers\PhuXe\DashboardController as PhuXeDashboardController;
use App\Http\Controllers\PhuXe\HanhKhachController as PhuXeHanhKhachController;
use App\Http\Controllers\PhuXe\ProfileController as PhuXeProfileController;

Route::get('/', function () {
    return view('landing');
})->name('landing');

// Test Suite Route (Development only)
Route::get('/test-suite', function () {
    return view('test-suite');
})->name('test.suite');

// Admin Authentication Routes (Quản lý)
Route::prefix('quan-ly')->name('quan-ly.')->group(function () {
    Route::get('dang-nhap', [AuthController::class, 'showLogin'])->name('dang-nhap');
    Route::post('dang-nhap', [AuthController::class, 'login'])->name('dang-nhap.post');
    Route::post('dang-xuat', [AuthController::class, 'logout'])->name('dang-xuat');

    // Protected Admin Routes
    Route::middleware(['admin.auth'])->group(function () {
        // Dashboard
        Route::get('tong-quan', [DashboardController::class, 'index'])->name('tong-quan');
        
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
        Route::post('hoadon/{id}/duyet', [HoadonController::class, 'approve'])->name('hoadon.duyet');
        Route::post('hoadon/{id}/huy', [HoadonController::class, 'cancel'])->name('hoadon.huy');
        
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

// NhanVienBanVe Routes
Route::prefix('nhan-vien-ban-ve')->name('nhan-vien-ban-ve.')->group(function () {
    // Authentication
    Route::get('dang-nhap', [AuthController::class, 'showNhanVienLogin'])->name('dang-nhap');
    Route::post('dang-nhap', [AuthController::class, 'nhanvienLogin'])->name('dang-nhap.post');
    Route::post('dang-xuat', [AuthController::class, 'nhanvienLogout'])->name('dang-xuat');

    // Protected Routes for NhanVienBanVe
    Route::middleware(['nhanvien.auth'])->group(function () {
        Route::get('tong-quan', [NhanVienBanVeController::class, 'dashboard'])->name('tong-quan');
        
        // (A) Trang "Thông tin cá nhân"
        Route::get('profile', [NhanVienBanVeController::class, 'profile'])->name('ho-so');
        Route::get('profile/edit', [NhanVienBanVeController::class, 'editProfile'])->name('ho-so.edit');
        Route::post('profile/update', [NhanVienBanVeController::class, 'updateProfile'])->name('ho-so.update');
        Route::post('profile/avatar', [NhanVienBanVeController::class, 'uploadAvatar'])->name('ho-so.avatar');
        Route::post('password/update', [NhanVienBanVeController::class, 'updatePassword'])->name('mat-khau.update');

        // (B) Trang "Đặt vé offline"
        Route::get('dat-ve', [NhanVienBanVeController::class, 'createDatVe'])->name('dat-ve.create');
        Route::post('dat-ve', [NhanVienBanVeController::class, 'storeDatVe'])->name('dat-ve.store');

        // (C) Trang "Quản lý vé offline"
        Route::get('ve', [NhanVienBanVeController::class, 'indexVe'])->name('ve.index');
        Route::get('ve/{id}', [NhanVienBanVeController::class, 'showVe'])->name('ve.show');
        Route::delete('ve/{id}', [NhanVienBanVeController::class, 'destroyVe'])->name('ve.destroy');
        
        // (D) Trang "Theo dõi chuyến đi"
        Route::get('chuyen-di', [NhanVienBanVeController::class, 'indexChuyenDi'])->name('chuyen-di.index');
        Route::get('chuyen-di/{machuyendi}', [NhanVienBanVeController::class, 'getChuyenDiDetails'])->name('chuyen-di.details');

        // (E) Trang "Hóa đơn" cho NV bán vé
        Route::get('hoa-don', [NhanVienBanVeController::class, 'indexHoadon'])->name('hoa-don.index');

        // API endpoints for dynamic data
        Route::get('api/chuyen-di', [NhanVienBanVeController::class, 'getChuyenDiApi'])->name('api.chuyen-di');
        Route::get('api/xe-by-chuyendi', [NhanVienBanVeController::class, 'getXeApi'])->name('api.xe');
        Route::get('api/gio-khoi-hanh', [NhanVienBanVeController::class, 'getGioKhoiHanhApi'])->name('api.gio-khoi-hanh');
        Route::get('api/vehicles', [NhanVienBanVeController::class, 'getVehiclesApi'])->name('api.vehicles');
        Route::get('api/so-do-ghe', [NhanVienBanVeController::class, 'getSoDoGheApi'])->name('api.so-do-ghe');
    });
});

// Tài xế Routes
Route::prefix('tai-xe')->name('tai-xe.')->group(function () {
    Route::get('dang-nhap', [AuthTaiXeController::class, 'showLogin'])->name('dang-nhap');
    Route::post('dang-nhap', [AuthTaiXeController::class, 'login'])->name('dang-nhap.post');
    Route::post('dang-xuat', [AuthTaiXeController::class, 'logout'])->name('dang-xuat');

    Route::middleware(['taixe.auth'])->group(function () {
        Route::get('/', fn () => redirect()->route('tai-xe.chuyen-hom-nay'));

        Route::get('chuyen-hom-nay', [ChuyenController::class, 'today'])->name('chuyen-hom-nay');
        Route::post('chuyen-di/{machuyendi}/bat-dau', [ChuyenController::class, 'start'])->name('chuyen-di.bat-dau');
        Route::post('chuyen-di/{machuyendi}/ket-thuc', [ChuyenController::class, 'end'])->name('chuyen-di.ket-thuc');

        Route::get('hanh-khach', [ChuyenController::class, 'passengerIndex'])->name('hanh-khach');
        Route::get('hanh-khach/{machuyendi}', [HanhKhachController::class, 'show'])->name('hanh-khach.show');
        Route::post('hanh-khach/{mave}/trang-thai', [HanhKhachController::class, 'togglePickup'])->name('hanh-khach.toggle');

        Route::get('su-co/bao-cao', [BaoCaoController::class, 'create'])->name('su-co.create');
        Route::post('su-co', [BaoCaoController::class, 'store'])->name('su-co.store');

        Route::get('ho-so', [ProfileController::class, 'show'])->name('ho-so');
        Route::post('ho-so/doi-mat-khau', [ProfileController::class, 'updatePassword'])->name('ho-so.password');
    });
});

// Phụ xe Routes
Route::prefix('phu-xe')->name('phu-xe.')->group(function () {
    Route::get('dang-nhap', [AuthPhuXeController::class, 'showLogin'])->name('dang-nhap');
    Route::post('dang-nhap', [AuthPhuXeController::class, 'login'])->name('dang-nhap.post');
    Route::post('dang-xuat', [AuthPhuXeController::class, 'logout'])->name('dang-xuat');

    Route::middleware(['phuxe.auth'])->group(function () {
        Route::get('/', fn () => redirect()->route('phu-xe.tong-quan'));
        Route::get('dashboard', [PhuXeDashboardController::class, 'index'])->name('tong-quan');

        Route::get('hanh-khach', [PhuXeHanhKhachController::class, 'index'])->name('hanh-khach');
        Route::get('hanh-khach/{machuyendi}', [PhuXeHanhKhachController::class, 'show'])->name('hanh-khach.show');
        Route::post('hanh-khach/{mave}/trang-thai', [PhuXeHanhKhachController::class, 'togglePickup'])->name('hanh-khach.toggle');

        Route::get('ho-so', [PhuXeProfileController::class, 'show'])->name('ho-so');
        Route::post('ho-so/doi-mat-khau', [PhuXeProfileController::class, 'updatePassword'])->name('ho-so.password');
    });
});
