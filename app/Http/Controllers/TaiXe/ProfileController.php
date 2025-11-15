<?php

namespace App\Http\Controllers\TaiXe;

use App\Http\Controllers\Controller;
use App\Models\Nhanvien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ProfileController extends Controller
{
    public function show(): \Illuminate\View\View
    {
        $taixe = Nhanvien::with('chucvu')->findOrFail(session('taixe')->manv);

        return view('TaiXe.HoSo', [
            'taixe' => $taixe,
        ]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate(
            [
                'mat_khau_hien_tai' => 'required|string',
                'mat_khau_moi' => 'required|string|min:6|confirmed',
            ],
            [
                'mat_khau_hien_tai.required' => 'Vui lòng nhập mật khẩu hiện tại.',
                'mat_khau_moi.required' => 'Vui lòng nhập mật khẩu mới.',
                'mat_khau_moi.min' => 'Mật khẩu mới tối thiểu 6 ký tự.',
                'mat_khau_moi.confirmed' => 'Mật khẩu xác nhận không khớp.',
            ]
        );

        $taixe = Nhanvien::findOrFail(session('taixe')->manv);

        if (!\Illuminate\Support\Facades\Hash::check($request->mat_khau_hien_tai, $taixe->password)) {
            return back()->with('error', 'Mật khẩu hiện tại không chính xác.');
        }

        $taixe->password = \Illuminate\Support\Facades\Hash::make($request->mat_khau_moi);
        $taixe->save();

        Session::put('taixe', $taixe->fresh('chucvu'));

        return redirect()->route('tai-xe.ho-so')->with('success', 'Đổi mật khẩu thành công.');
    }
}

