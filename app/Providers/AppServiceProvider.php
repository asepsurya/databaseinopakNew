<?php

namespace App\Providers;

use App\Models\ikm;
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
        // Share total UMKM count with all views
        view()->composer('*', function ($view) {
            $totalUmkm = ikm::count();
            $view->with('totalUmkm', $totalUmkm);
        });
    }
}
