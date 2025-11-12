<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Nhanvien;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Session::has('admin')) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'manv' => 'required',
            'password' => 'required',
        ], [
            'manv.required' => 'Vui lòng nhập mã nhân viên',
            'password.required' => 'Vui lòng nhập mật khẩu',
        ]);

        $nhanvien = Nhanvien::where('manv', $request->manv)
            ->where('password', $request->password)
            ->where('macv', 'QL') // Chỉ cho phép Quản lý đăng nhập
            ->where('trangthai', 1)
            ->first();

        if ($nhanvien) {
            Session::put('admin', $nhanvien);
            return redirect()->route('admin.dashboard')->with('success', 'Đăng nhập thành công!');
        }

        return back()->withInput()->withErrors(['login' => 'Mã nhân viên hoặc mật khẩu không đúng, hoặc bạn không có quyền quản lý!']);
    }

    public function logout()
    {
        Session::forget('admin');
        return redirect()->route('admin.login')->with('success', 'Đăng xuất thành công!');
    }
}
