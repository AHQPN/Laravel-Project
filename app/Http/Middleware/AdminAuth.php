<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    // Kiểm tra quyền truy cập cho quản lý
    public function handle(Request $request, Closure $next): Response
    {
        // Kiểm tra đã đăng nhập chưa
        if (!$request->session()->has('admin')) {
            return redirect()->route('quan-ly.dang-nhap')->with('error', 'Vui lòng đăng nhập!');
        }

        $admin = $request->session()->get('admin');
        
        // Kiểm tra quyền truy cập bằng Gate
        if (!Gate::forUser($admin)->allows('access-quanly')) {
            $request->session()->forget('admin');
            return redirect()->route('quan-ly.dang-nhap')->with('error', 'Bạn không có quyền truy cập!');
        }

        return $next($request);
    }
}
