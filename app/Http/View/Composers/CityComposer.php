<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Models\TinhThanh; // Đảm bảo bạn đã tạo Model TinhThanh (MySQL)

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
            return TinhThanh::orderBy('ten')->pluck('ten')->toArray();
        });

        $view->with('cities', $cities);
    }
}
