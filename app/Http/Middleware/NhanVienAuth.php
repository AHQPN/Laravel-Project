<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class NhanVienAuth
{
    // Kiểm tra quyền truy cập cho nhân viên bán vé
    public function handle(Request $request, Closure $next): Response
    {
        if (!session()->has('nhanvien')) {
            return redirect()->route('nhan-vien-ban-ve.dang-nhap')->with('error', 'Vui lòng đăng nhập để tiếp tục.');
        }

        $nhanvien = session()->get('nhanvien');

        if (!Gate::forUser($nhanvien)->allows('access-nhanvien-banve')) {
            session()->forget('nhanvien');
            return redirect()->route('nhan-vien-ban-ve.dang-nhap')->with('error', 'Bạn không có quyền truy cập!');
        }
        
        return $next($request);
    }
}