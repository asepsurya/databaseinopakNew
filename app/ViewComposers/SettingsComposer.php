<?php

namespace App\ViewComposers;

use App\Models\LogoSetting;
use App\Models\AppSetting;
use Illuminate\View\View;

class SettingsComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view)
    {
        // Get all logo settings
        $logoTypes = ['header', 'footer', 'sidebar', 'login'];
        $logos = [];

        foreach ($logoTypes as $type) {
            $logos[$type] = LogoSetting::getByType($type);
        }

        // Get app settings
        $appName = AppSetting::get('app_name', 'Database INOPAK');
        $registrationEnabled = AppSetting::get('registration_enabled', true);

        $view->with(compact('logos', 'appName', 'registrationEnabled'));
    }
}
