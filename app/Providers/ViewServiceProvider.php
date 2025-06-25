<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\SettingSistem;
use Illuminate\Support\Facades\View;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Menggunakan View Composer untuk membagikan data ke semua view
        // View::composer('*', function ($view) {
        //     $sistemData = SettingSistem::first();
        //     $view->with('sistemData', $sistemData);
        // });
    }
}
