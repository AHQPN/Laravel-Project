<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\Khach;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'sdt' => 'required|string',
            'pw' => 'required',
        ]);

        $customer = Khach::where('sdt', $request->sdt)->first();
        
        if ($customer && $request->pw == $customer->password) { // Tạm thời check text
        // if ($customer && Hash::check($request->pw, $customer->password)) { // Dùng khi bạn đã hash pass
             Session::put('UserID', $customer->makh);
             Session::put('UserName', $customer->ten);
             return redirect()->route('home.index');
        }

        return back()->with('error', 'SĐT hoặc mật khẩu không đúng.')->with('ShowLogin', true);
    }

    public function signup(Request $request)
    {
        $request->validate([
            'ten' => 'required|string|max:255',
            'sdt' => 'required|string|max:20|unique:khach,sdt',
            'pw' => 'required|string|min:6',
            'confrimed-pw' => 'required|same:pw',
            'diachi' => 'nullable|string|max:255',
        ]);

        try {
            $customer = Khach::create([
                'makh' => 'KH'.Str::upper(Str::random(8)),
                'ten' => $request->ten,
                'sdt' => $request->sdt,
                'diachi' => $request->diachi,
                'password' => $request->pw, // Tạm thời
                // 'password' => Hash::make($request->pw), // Dùng khi production
            ]);

            Session::put('UserID', $customer->makh);
            Session::put('UserName', $customer->ten);

            return redirect()->route('home.index')->with('success', 'Đăng ký tài khoản thành công!');
        
        } catch (\Exception $e) {
             return back()->with('error_register', 'Đã xảy ra lỗi. Vui lòng thử lại.')->with('ShowRegister', true);
        }
    }

    public function logout()
    {
        Session::forget('UserID');
        Session::forget('UserName');
        return redirect()->route('home.index');
    }
}