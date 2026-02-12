<?php

namespace App\Providers;

use App\Models\ikm;
use App\Models\LogoSetting;
use App\Models\AppSetting;
use App\Services\BrandingService;
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

        // Share branding settings with all views
        view()->composer(['layouts.master', 'layouts.auth', 'layouts.public'], function ($view) {
            // Get logo settings
            $logoTypes = ['header', 'footer', 'sidebar', 'login', 'favicon'];
            $logos = [];

            foreach ($logoTypes as $type) {
                $logos[$type] = LogoSetting::getByType($type);
            }

            // Get app settings
            $appName = AppSetting::get('app_name', 'Database INOPAK');
            $appTagline = AppSetting::get('app_tagline', 'Sistem Pengelolaan Informasi');
            $companyName = AppSetting::get('company_name', 'INOPAK');
            $copyrightText = AppSetting::get('copyright_text', '© 2024 INOPAK. All rights reserved.');
            $registrationEnabled = AppSetting::get('registration_enabled', true);

            // Meta settings
            $metaDescription = AppSetting::get('meta_description', 'Database INOPAK - Sistem Pengelolaan Informasi');
            $metaKeywords = AppSetting::get('meta_keywords', 'inopak, database, ikm, admin dashboard');

            // Get full branding config using BrandingService
            $brandingService = app(BrandingService::class);
            $branding = $brandingService->getAllBranding();

            $view->with(compact(
                'logos',
                'appName',
                'appTagline',
                'companyName',
                'copyrightText',
                'registrationEnabled',
                'metaDescription',
                'metaKeywords',
                'branding'
            ));
        });
    }
}
