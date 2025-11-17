<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Controllers
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TinhThanhController;
use App\Http\Controllers\Admin\LoaixeController;
use App\Http\Controllers\Admin\XeController;
use App\Http\Controllers\Admin\ChuyendiController;
use App\Http\Controllers\Admin\HoadonController;
use App\Http\Controllers\Admin\NguoiDungController;
use App\Http\Controllers\Admin\ThongKeController;

/*
|--------------------------------------------------------------------------
| Customer Controllers (KHÁCH HÀNG)
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Customer\HomeController;
use App\Http\Controllers\Customer\TicketController;
use App\Http\Controllers\Customer\TripController;
use App\Http\Controllers\Customer\BillController;
use App\Http\Controllers\Customer\AuthController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

/*
|-------------------------
| CUSTOMER ROUTES (TRANG NGƯỜI DÙNG)
|-------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home.index');

// Customer Auth (ĐĂNG NHẬP / ĐĂNG KÝ)
Route::prefix('auth')->name('auth.')->group(function () {
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('signup', [AuthController::class, 'signup'])->name('signup');
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
});

// Trip Controller
Route::prefix('trip')->name('trip.')->group(function () {
    Route::get('find', [TripController::class, 'gFindTrip'])->name('gfind');
    Route::post('find', [TripController::class, 'findTrip'])->name('find');
});

// Ticket Controller
Route::prefix('ticket')->name('ticket.')->group(function () {
    Route::get('find', [TicketController::class, 'findTicket'])->name('find');
    Route::get('book/{tripID}', [TicketController::class, 'bookTicket'])->name('book');
    Route::post('handle-booking', [TicketController::class, 'handleBookTicket'])->name('handleBooking');

    Route::get('payment', [TicketController::class, 'thanhToan'])->name('thanhToan');
    Route::post('payment-confirm', [TicketController::class, 'paymentConfirm'])->name('paymentConfirm');
    Route::get('payment-success', [TicketController::class, 'paymentSuccess'])->name('paymentSuccess');
    Route::post('rollback', [TicketController::class, 'rollbackBooking'])->name('rollback');
});

// Bill Controller
Route::prefix('bill')->name('bill.')->group(function () {
    Route::get('search', [BillController::class, 'index'])->name('index');
    Route::post('search', [BillController::class, 'search'])->name('search');
    Route::get('detail/{id}', [BillController::class, 'chiTietHoaDon'])->name('detail');
});


/*
|-------------------------
| ADMIN ROUTES (TRANG QUẢN TRỊ)
|-------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    // Đổi tên AuthController của Admin để tránh trùng lặp
    Route::get('login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AdminAuthController::class, 'login'])->name('login.post');
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');

    // Protected Admin Routes
    Route::middleware(['admin.auth'])->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('tinhthanh', TinhThanhController::class);
        Route::resource('loaixe', LoaixeController::class);
        Route::resource('xe', XeController::class);
        Route::resource('chuyendi', ChuyendiController::class);
        Route::resource('hoadon', HoadonController::class)->except(['create', 'store', 'edit', 'update']);
        Route::post('hoadon/{id}/approve', [HoadonController::class, 'approve'])->name('hoadon.approve');
        Route::post('hoadon/{id}/cancel', [HoadonController::class, 'cancel'])->name('hoadon.cancel');
        Route::prefix('nguoidung')->name('nguoidung.')->group(function () {
            Route::get('khach', [NguoiDungController::class, 'khach'])->name('khach');
            Route::get('khach/{id}/edit', [NguoiDungController::class, 'khachEdit'])->name('khach.edit');
            Route::put('khach/{id}', [NguoiDungController::class, 'khachUpdate'])->name('khach.update');
            Route::delete('khach/{id}', [NguoiDungController::class, 'khachDestroy'])->name('khach.destroy');
            Route::get('nhanvien', [NguoiDungController::class, 'nhanvien'])->name('nhanvien');
            Route::get('nhanvien/create', [NguoiDungController::class, 'nhanvienCreate'])->name('nhanvien.create');
            Route::post('nhanvien', [NguoiDungController::class, 'nhanvienStore'])->name('nhanvien.store');
            Route::get('nhanvien/{id}/edit', [NguoiDungController::class, 'nhanvienEdit'])->name('nhanvien.edit');
            Route::put('nhanvien/{id}', [NguoiDungController::class, 'nhanvienUpdate'])->name('nhanvien.update');
            Route::delete('nhanvien/{id}', [NguoiDungController::class, 'nhanvienDestroy'])->name('nhanvien.destroy');
        });
        Route::get('thongke', [ThongKeController::class, 'index'])->name('thongke.index');
    });
});
