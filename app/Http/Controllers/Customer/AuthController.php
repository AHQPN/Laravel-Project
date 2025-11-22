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

        // Kiểm tra password với Hash::check (hỗ trợ cả password đã hash và plain text)
        if ($customer) {
            // Nếu password trong DB đã hash (bắt đầu bằng $2y$)
            if (str_starts_with($customer->password, '$2y$') || str_starts_with($customer->password, '$2a$')) {
                $passwordMatch = Hash::check($request->pw, $customer->password);
            } else {
                // Nếu password plain text (để tương thích với dữ liệu cũ)
                $passwordMatch = ($request->pw == $customer->password);
            }

            if ($passwordMatch) {
                Session::put('UserID', $customer->makh);
                Session::put('UserName', $customer->ten);
                return redirect()->route('home.index')->with('success', 'Đăng nhập thành công!');
            }
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
        ], [
            'ten.required' => 'Vui lòng nhập họ tên.',
            'sdt.required' => 'Vui lòng nhập số điện thoại.',
            'sdt.unique' => 'Số điện thoại này đã được đăng ký.',
            'pw.required' => 'Vui lòng nhập mật khẩu.',
            'pw.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'confrimed-pw.required' => 'Vui lòng xác nhận mật khẩu.',
            'confrimed-pw.same' => 'Mật khẩu xác nhận không khớp.',
        ]);

        try {
            // Tạo mã khách hàng unique
            do {
                $makh = 'KH' . Str::upper(Str::random(8));
            } while (Khach::where('makh', $makh)->exists());

            // Model Khach đã có mutator tự động hash password
            $customer = Khach::create([
                'makh' => $makh,
                'ten' => $request->ten,
                'sdt' => $request->sdt,
                'diachi' => $request->diachi,
                'password' => $request->pw, // Sẽ tự động hash qua mutator
            ]);

            Session::put('UserID', $customer->makh);
            Session::put('UserName', $customer->ten);

            return redirect()->route('home.index')->with('success', 'Đăng ký tài khoản thành công!');

        } catch (\Exception $e) {
            return back()
                ->withInput($request->except('pw', 'confrimed-pw'))
                ->with('error_register', 'Đã xảy ra lỗi: ' . $e->getMessage())
                ->with('ShowRegister', true);
        }
    }

    public function logout()
    {
        Session::forget('UserID');
        Session::forget('UserName');
        return redirect()->route('home.index');
    }
}