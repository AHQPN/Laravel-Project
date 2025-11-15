<?php

namespace App\Http\Controllers\PhuXe;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show()
    {
        $phuxe = session('phuxe');
        return view('PhuXe.HoSo', compact('phuxe'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $phuxe = \App\Models\Nhanvien::find(session('phuxe')->id);

        if ($request->current_password !== $phuxe->password) {
            return back()->with('error', 'Mật khẩu hiện tại không đúng.');
        }

        $phuxe->password = $request->new_password;
        $phuxe->save();

        return back()->with('success', 'Đổi mật khẩu thành công!');
    }
}
