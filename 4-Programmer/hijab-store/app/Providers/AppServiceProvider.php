<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;

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
        // 1. Set default timezone Carbon ke Asia/Jakarta (WIB)
        config(['app.timezone' => 'Asia/Jakarta']);
        date_default_timezone_set('Asia/Jakarta');
        Carbon::setLocale('id');

        // 2. Mencegah masalah panjang string kustom pada beberapa versi MySQL
        Schema::defaultStringLength(191);

        // 3. Paksa HTTPS jika berjalan di Production ATAU diakses melalui Ngrok
        if (
            config('app.env') === 'production' || 
            request()->hasHeader('x-forwarded-proto') || 
            str_contains(config('app.url'), 'ngrok-free.dev') ||
            str_contains(request()->header('host'), 'ngrok-free.dev')
        ) {
            URL::forceScheme('https');
        }
    }
}