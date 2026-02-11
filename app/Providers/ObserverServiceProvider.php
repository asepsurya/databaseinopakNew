<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Observers\NotificationObserver;

class ObserverServiceProvider extends ServiceProvider
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
        // Register observers
        $this->registerObservers();
    }

    /**
     * Register all model observers.
     */
    protected function registerObservers(): void
    {
        // Only register observers if the observer classes exist
        if (class_exists(NotificationObserver::class)) {
            // Project observer
            if (class_exists(\App\Models\Project::class)) {
                \App\Models\Project::observe(NotificationObserver::class);
            }

            // IKM observer
            if (class_exists(\App\Models\Ikm::class)) {
                \App\Models\Ikm::observe(NotificationObserver::class);
            }

            // COTS observer
            if (class_exists(\App\Models\Cots::class)) {
                \App\Models\Cots::observe(NotificationObserver::class);
            }

            // Benchmark Produk observer
            if (class_exists(\App\Models\BencmarkProduk::class)) {
                \App\Models\BencmarkProduk::observe(NotificationObserver::class);
            }

            // Dokumentasi COTS observer
            if (class_exists(\App\Models\DokumentasiCots::class)) {
                \App\Models\DokumentasiCots::observe(NotificationObserver::class);
            }

            // Produk Design observer
            if (class_exists(\App\Models\ProdukDesign::class)) {
                \App\Models\ProdukDesign::observe(NotificationObserver::class);
            }
        }
    }
}
