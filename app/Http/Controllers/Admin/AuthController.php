<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\QuanLy\LoginRequest;
use App\Models\Nhanvien;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Hiển thị trang đăng nhập cho quản lý
    public function showLogin()
    {
        if (session()->has('admin')) {
            return redirect()->route('quan-ly.tong-quan');
        }
        return view('admin.DangNhap');
    }

    // Xử lý đăng nhập (chỉ cho nhân viên có chức vụ Quản lý)
    public function login(LoginRequest $request)
    {
        $nhanvien = Nhanvien::where('manv', $request->manv)
            ->where('macv', 'QL')
            ->where('trangthai', 1)
            ->first();

        if ($nhanvien && Hash::check($request->password, $nhanvien->password)) {
            Session::put('admin', $nhanvien);
            return redirect()->route('quan-ly.tong-quan')->with('success', 'Đăng nhập thành công!');
        }

        return back()->withInput()->withErrors(['login' => 'Mã nhân viên hoặc mật khẩu không đúng, hoặc bạn không có quyền quản lý!']);
    }

    // Đăng xuất
    public function logout()
    {
        session()->forget('admin');
        return redirect()->route('quan-ly.dang-nhap')->with('success', 'Đăng xuất thành công!');
    }

    // Hiển thị trang đăng nhập cho nhân viên bán vé
    public function showNhanVienLogin()
    {
        if (Session::has('nhanvien')) {
            return redirect()->route('nhan-vien-ban-ve.tong-quan');
        }
        return view('NhanVienBanVe.DangNhap');
    }

    // Xử lý đăng nhập nhân viên bán vé (chỉ cho chức vụ NVBV)
    public function nhanvienLogin(LoginRequest $request)
    {
        $nhanvien = Nhanvien::with('chucvu')
            ->where('manv', $request->manv)
            ->where('macv', 'NVBV')
            ->where('trangthai', 1)
            ->first();

        if ($nhanvien && Hash::check($request->password, $nhanvien->password)) {
            Session::put('nhanvien', $nhanvien);
            return redirect()->route('nhan-vien-ban-ve.tong-quan')->with('success', 'Đăng nhập thành công!');
        }

        return back()->withInput()->with('error', 'Mã nhân viên hoặc mật khẩu không đúng, hoặc tài khoản của bạn đã bị khóa!');
    }

    // Đăng xuất nhân viên bán vé
    public function nhanvienLogout()
    {
        session()->forget('nhanvien');
        return redirect()->route('nhan-vien-ban-ve.dang-nhap')->with('success', 'Đăng xuất thành công!');
    }
}
