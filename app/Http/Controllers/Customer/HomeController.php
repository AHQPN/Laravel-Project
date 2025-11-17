<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class HomeController extends Controller
{
    /**
     * Hiển thị trang chủ.
     * [Nguồn: HomeController.cs - Index()]
     */
    public function index()
    {
        // 'cities' sẽ được tự động cung cấp cho layout bởi CityComposer.
        // [Nguồn: HomeController.cs - return View()]
        return view('home.index');
    }
}
