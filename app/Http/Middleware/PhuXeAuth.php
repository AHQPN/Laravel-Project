<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PhuXeAuth
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!session()->has('phuxe')) {
            return redirect()->route('phu-xe.dang-nhap')
                ->with('error', 'Vui lòng đăng nhập để tiếp tục.');
        }

        return $next($request);
    }
}
