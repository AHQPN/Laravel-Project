<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
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
use App\Http\Controllers\Customer\HomeController;
use App\Http\Controllers\Customer\TicketController;
use App\Http\Controllers\Customer\TripController;
use App\Http\Controllers\Customer\BillController;
use App\Http\Controllers\Customer\AuthController;
use App\Http\Controllers\Customer\ProfileController as CustomerProfileController;
use App\Http\Controllers\VnpayController;

Route::get('/payment/vnpay/return', [VnpayController::class, 'vnpayReturn'])->name('vnpay.return');

Route::get('/', function () {
    return view('landing');
})->name('landing');

// CUSTOMER ROUTES (TRANG NGƯỜI DÙNG)
Route::get('/home', [HomeController::class, 'index'])->name('home.index');

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

    // Ticket Lookup Routes
    Route::get('lookup', [TicketController::class, 'lookupForm'])->name('lookupForm');
    Route::post('lookup', [TicketController::class, 'lookupTicket'])->name('lookup');
});

// Bill Controller
Route::prefix('bill')->name('bill.')->group(function () {
    Route::get('search', [BillController::class, 'index'])->name('index');
    Route::post('search', [BillController::class, 'search'])->name('search');
    Route::get('detail/{id}', [BillController::class, 'chiTietHoaDon'])->name('detail');
    Route::get('download-pdf/{id}', [BillController::class, 'downloadPDF'])->name('downloadPDF');
});

// Customer Profile Routes
Route::prefix('profile')->name('customer.')->group(function () {
    Route::get('/', [CustomerProfileController::class, 'show'])->name('profile');
    Route::get('edit', [CustomerProfileController::class, 'edit'])->name('profile.edit');
    Route::post('update', [CustomerProfileController::class, 'update'])->name('profile.update');
    Route::post('password', [CustomerProfileController::class, 'updatePassword'])->name('profile.password');
});

// ADMIN ROUTES (TRANG QUẢN TRỊ)
Route::prefix('quan-ly')->name('quan-ly.')->group(function () {
    Route::get('dang-nhap', [AdminAuthController::class, 'showLogin'])->name('dang-nhap');
    Route::post('dang-nhap', [AdminAuthController::class, 'login'])
        ->middleware('throttle:5,1') // Giới hạn 5 lần login/phút
        ->name('dang-nhap.post');
    Route::post('dang-xuat', [AdminAuthController::class, 'logout'])->name('dang-xuat');

    // Protected Admin Routes
    Route::middleware(['admin.auth'])->group(function () {
        Route::get('tong-quan', [DashboardController::class, 'index'])->name('tong-quan');
        Route::resource('tinhthanh', TinhThanhController::class);
        Route::resource('loaixe', LoaixeController::class);
        Route::resource('xe', XeController::class);
        Route::resource('chuyendi', ChuyendiController::class);
        Route::resource('hoadon', HoadonController::class)->except(['create', 'store', 'edit', 'update']);

        // Invoice approval/cancellation routes (unified)
        Route::post('hoadon/{id}/duyet', [HoadonController::class, 'approve'])->name('hoadon.duyet');
        Route::post('hoadon/{id}/huy', [HoadonController::class, 'cancel'])->name('hoadon.huy');

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

// NhanVienBanVe Routes
Route::prefix('nhan-vien-ban-ve')->name('nhan-vien-ban-ve.')->group(function () {
    Route::get('dang-nhap', [AdminAuthController::class, 'showNhanVienLogin'])->name('dang-nhap');
    Route::post('dang-nhap', [AdminAuthController::class, 'nhanvienLogin'])
        ->middleware('throttle:5,1') // Giới hạn 5 lần login/phút
        ->name('dang-nhap.post');
    Route::post('dang-xuat', [AdminAuthController::class, 'nhanvienLogout'])->name('dang-xuat');

    Route::middleware(['nhanvien.auth'])->group(function () {
        Route::get('tong-quan', [NhanVienBanVeController::class, 'dashboard'])->name('tong-quan');

        Route::get('profile', [NhanVienBanVeController::class, 'profile'])->name('ho-so');
        Route::get('profile/edit', [NhanVienBanVeController::class, 'editProfile'])->name('ho-so.edit');
        Route::post('profile/update', [NhanVienBanVeController::class, 'updateProfile'])->name('ho-so.update');
        Route::post('profile/avatar', [NhanVienBanVeController::class, 'uploadAvatar'])->name('ho-so.avatar');
        Route::post('password/update', [NhanVienBanVeController::class, 'updatePassword'])->name('mat-khau.update');

        Route::get('dat-ve', [NhanVienBanVeController::class, 'createDatVe'])->name('dat-ve.create');
        Route::post('dat-ve', [NhanVienBanVeController::class, 'storeDatVe'])->name('dat-ve.store');

        // VNPay payment route (requires auth)
        Route::post('/payment/vnpay', [VnpayController::class, 'createPayment'])->name('vnpay.create');

        Route::get('ve', [NhanVienBanVeController::class, 'indexVe'])->name('ve.index');
        Route::get('ve/{id}', [NhanVienBanVeController::class, 'showVe'])->name('ve.show');
        Route::delete('ve/{id}', [NhanVienBanVeController::class, 'destroyVe'])->name('ve.destroy');

        Route::get('chuyen-di', [NhanVienBanVeController::class, 'indexChuyenDi'])->name('chuyen-di.index');
        Route::get('chuyen-di/{machuyendi}', [NhanVienBanVeController::class, 'getChuyenDiDetails'])->name('chuyen-di.details');

        Route::get('hoa-don', [NhanVienBanVeController::class, 'indexHoadon'])->name('hoa-don.index');

        Route::get('api/chuyen-di', [NhanVienBanVeController::class, 'getChuyenDiApi'])->name('api.chuyen-di');
        Route::get('api/chuyen-di/{machuyendi}/ghe', [NhanVienBanVeController::class, 'getSeatMapApi'])->name('api.seat-map');
        Route::get('api/xe-by-chuyendi', [NhanVienBanVeController::class, 'getXeApi'])->name('api.xe');
        Route::get('api/gio-khoi-hanh', [NhanVienBanVeController::class, 'getGioKhoiHanhApi'])->name('api.gio-khoi-hanh');
        Route::get('api/vehicles', [NhanVienBanVeController::class, 'getVehiclesApi'])->name('api.vehicles');
        Route::get('api/so-do-ghe', [NhanVienBanVeController::class, 'getSoDoGheApi'])->name('api.so-do-ghe');
    });
});

// Tài xế Routes
Route::prefix('tai-xe')->name('tai-xe.')->group(function () {
    Route::get('dang-nhap', [AuthTaiXeController::class, 'showLogin'])->name('dang-nhap');
    Route::post('dang-nhap', [AuthTaiXeController::class, 'login'])
        ->middleware('throttle:5,1') // Giới hạn 5 lần login/phút
        ->name('dang-nhap.post');
    Route::post('dang-xuat', [AuthTaiXeController::class, 'logout'])->name('dang-xuat');

    Route::middleware(['taixe.auth'])->group(function () {
        Route::get('/', fn() => redirect()->route('tai-xe.chuyen-hom-nay'));

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
