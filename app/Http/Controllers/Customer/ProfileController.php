<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use App\Models\Khach;

class ProfileController extends Controller
{
    /**
     * Display customer profile
     */
    public function show()
    {
        if (!Session::has('UserID')) {
            Session::flash('ShowLogin', true);
            return redirect()->route('home.index');
        }

        $userId = Session::get('UserID');
        $customer = Khach::findOrFail($userId);

        // Get statistics
        $totalBookings = $customer->hoadons()->count();
        $totalSpent = $customer->hoadons()
            ->whereIn('trangthai', ['Đã duyệt', 'Paid'])
            ->sum('thanhtien') ?? 0;

        return view('customer.profile', compact('customer', 'totalBookings', 'totalSpent'));
    }

    /**
     * Show edit profile form
     */
    public function edit()
    {
        if (!Session::has('UserID')) {
            Session::flash('ShowLogin', true);
            return redirect()->route('home.index');
        }

        $userId = Session::get('UserID');
        $customer = Khach::findOrFail($userId);

        return view('customer.profile-edit', compact('customer'));
    }

    /**
     * Update customer profile
     */
    public function update(Request $request)
    {
        if (!Session::has('UserID')) {
            Session::flash('ShowLogin', true);
            return redirect()->route('home.index');
        }

        $userId = Session::get('UserID');
        $customer = Khach::findOrFail($userId);

        $request->validate([
            'ten' => 'required|string|max:255',
            'sdt' => 'required|string|max:15',
            'email' => 'required|email|unique:khach,email,' . $userId . ',makh',
            'diachi' => 'nullable|string|max:500',
            'ngaysinh' => 'nullable|date',
            'gioitinh' => 'nullable|in:Nam,Nữ,Khác',
        ], [
            'ten.required' => 'Vui lòng nhập họ tên',
            'sdt.required' => 'Vui lòng nhập số điện thoại',
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không hợp lệ',
            'email.unique' => 'Email đã được sử dụng',
            'ngaysinh.date' => 'Ngày sinh không hợp lệ',
            'gioitinh.in' => 'Giới tính không hợp lệ',
        ]);

        try {
            $customer->update([
                'ten' => $request->ten,
                'sdt' => $request->sdt,
                'email' => $request->email,
                'diachi' => $request->diachi,
                'ngaysinh' => $request->ngaysinh,
                'gioitinh' => $request->gioitinh,
            ]);

            // Update session name
            Session::put('UserName', $customer->ten);

            return redirect()->route('customer.profile')
                ->with('success', 'Cập nhật thông tin thành công!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Update customer password
     */
    public function updatePassword(Request $request)
    {
        if (!Session::has('UserID')) {
            Session::flash('ShowLogin', true);
            return redirect()->route('home.index');
        }

        $userId = Session::get('UserID');
        $customer = Khach::findOrFail($userId);

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại',
            'new_password.required' => 'Vui lòng nhập mật khẩu mới',
            'new_password.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự',
            'new_password.confirmed' => 'Xác nhận mật khẩu không khớp',
        ]);

        // Verify current password
        if (!Hash::check($request->current_password, $customer->password)) {
            return redirect()->back()
                ->with('error', 'Mật khẩu hiện tại không đúng');
        }

        try {
            $customer->update([
                'password' => $request->new_password,
            ]);

            return redirect()->route('customer.profile')
                ->with('success', 'Đổi mật khẩu thành công!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}
