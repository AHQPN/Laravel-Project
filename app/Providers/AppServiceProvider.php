<?php

namespace App\Providers;

use App\Http\View\Composers\CityComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Cung cấp $cities cho view 'layouts.khach'
        View::composer('layouts.khach', CityComposer::class);
    }
}
