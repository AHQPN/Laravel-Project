<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->session()->has('admin')) {
            return redirect()->route('admin.login')->with('error', 'Vui lòng đăng nhập!');
        }

        $admin = $request->session()->get('admin');
        if ($admin->macv !== 'QL' || $admin->trangthai != 1) {
            $request->session()->forget('admin');
            return redirect()->route('admin.login')->with('error', 'Bạn không có quyền truy cập!');
        }

        return $next($request);
    }
}
