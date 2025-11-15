<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class TaiXeAuth
{
    /**
     * Xác thực tài xế trước khi truy cập các route được bảo vệ.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!session()->has('taixe')) {
            return redirect()->route('tai-xe.dang-nhap')->with('error', 'Vui lòng đăng nhập tài xế để tiếp tục.');
        }

        $taixe = session()->get('taixe');

        // Use Gate to check authorization
        if (!Gate::forUser($taixe)->allows('access-taixe')) {
            session()->forget('taixe');
            return redirect()->route('tai-xe.dang-nhap')->with('error', 'Bạn không có quyền truy cập!');
        }

        return $next($request);
    }
}

