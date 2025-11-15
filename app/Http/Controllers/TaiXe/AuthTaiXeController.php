<?php

namespace App\Http\Controllers\TaiXe;

use App\Http\Controllers\Controller;
use App\Models\Nhanvien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

class AuthTaiXeController extends Controller
{
    public function showLogin()
    {
        if (Session::has('taixe')) {
            return redirect()->route('tai-xe.chuyen-hom-nay');
        }

        return view('TaiXe.DangNhap');
    }

    public function login(Request $request)
    {
        $request->validate(
            [
                'manv' => 'required|string',
                'password' => 'required|string',
            ],
            [
                'manv.required' => 'Vui lòng nhập mã nhân viên.',
                'password.required' => 'Vui lòng nhập mật khẩu.',
            ]
        );

        $taixe = Nhanvien::with('chucvu')
            ->where('manv', $request->manv)
            ->where('macv', 'TX')
            ->where('trangthai', 1)
            ->first();

        if (!$taixe || !Hash::check($request->password, $taixe->password)) {
            return back()->withInput()->with('error', 'Thông tin đăng nhập không hợp lệ hoặc bạn không có quyền tài xế.');
        }

        Session::put('taixe', $taixe);

        return redirect()->route('tai-xe.chuyen-hom-nay')->with('success', 'Đăng nhập thành công!');
    }

    public function logout()
    {
        Session::forget('taixe');

        return redirect()->route('tai-xe.dang-nhap')->with('success', 'Đăng xuất thành công.');
    }
}

