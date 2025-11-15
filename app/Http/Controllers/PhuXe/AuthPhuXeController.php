<?php

namespace App\Http\Controllers\PhuXe;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AuthPhuXeController extends Controller
{
    public function showLogin()
    {
        if (Session::has('phuxe')) {
            return redirect()->route('phu-xe.tong-quan');
        }

        return view('PhuXe.DangNhap');
    }

    public function login(Request $request)
    {
        $request->validate([
            'manv' => 'required|string',
            'password' => 'required|string',
        ]);

        $nhanvien = \App\Models\Nhanvien::where('manv', $request->manv)
            ->where('macv', 'PX') // Phụ xe
            ->where('trangthai', 1)
            ->first();

        if (! $nhanvien || ! \Illuminate\Support\Facades\Hash::check($request->password, $nhanvien->password)) {
            return back()->withInput()->with('error', 'Mã nhân viên hoặc mật khẩu không đúng.');
        }

        Session::put('phuxe', $nhanvien);

        return redirect()->route('phu-xe.tong-quan')->with('success', 'Đăng nhập thành công!');
    }

    public function logout()
    {
        Session::forget('phuxe');
        return redirect()->route('phu-xe.dang-nhap');
    }
}
