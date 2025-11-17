<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Models\Tinhthanh; // Đảm bảo bạn đã tạo Model Tinhthanh (MySQL)

class CityComposer
{
    /**
     * Gắn dữ liệu vào view.
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {
        // [Nguồn: HomeController.cs, lấy cities từ context]
        // Cache lại query này trong 1 giờ để tăng tốc độ
        $cities = cache()->remember('all_cities', now()->addHour(), function () {
            return Tinhthanh::orderBy('ten')->pluck('ten')->toArray();
        });

        $view->with('cities', $cities);
    }
}
