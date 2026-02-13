<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use App\Models\LogoSetting;
use Illuminate\Http\Request;
use App\Services\BrandingService;
use App\Services\SettingsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    protected SettingsService $settingsService;
    protected BrandingService $brandingService;

    public function __construct(SettingsService $settingsService, BrandingService $brandingService)
    {
        $this->settingsService = $settingsService;
        $this->brandingService = $brandingService;
    }

    /**
     * Display the settings page.
     */
    public function index()
    {
        $logos = $this->settingsService->getAllLogos();
        $registrationEnabled = $this->settingsService->isRegistrationEnabled();
        $activityLogs = $this->settingsService->getActivityLogs(10);

        return view('pages.settings.index', compact('logos', 'registrationEnabled', 'activityLogs'));
    }

    /**
     * Display the branding settings page.
     */
    public function branding()
    {
        $logos = $this->settingsService->getAllLogos();
        $appName = $this->brandingService->getText('app_name', 'Database INOPAK');
        $appTagline = $this->brandingService->getText('app_tagline', 'Sistem Pengelolaan Informasi');
        $companyName = $this->brandingService->getText('company_name', 'INOPAK');
        $copyrightText = $this->brandingService->getText('copyright_text', '© 2024 INOPAK. All rights reserved.');
        $metaDescription = $this->brandingService->getText('meta_description', 'Database INOPAK - Sistem Pengelolaan Informasi');
        $metaKeywords = $this->brandingService->getText('meta_keywords', 'inopak, database, ikm, admin dashboard');
        $registrationEnabled = $this->settingsService->isRegistrationEnabled();

        return view('pages.settings.branding', compact(
            'logos',
            'appName',
            'appTagline',
            'companyName',
            'copyrightText',
            'metaDescription',
            'metaKeywords',
            'registrationEnabled'
        ));
    }

    /**
     * Update branding image.
     */
    public function updateBrandingImage(Request $request, string $type)
    {
        $logo = $this->settingsService->updateLogo($request, $type);

        return redirect()->back()->with('success', ucfirst($type) . ' updated successfully.');
    }

    /**
     * Update branding text.
     */
    public function updateBrandingText(Request $request, string $key)
    {
        $this->brandingService->updateText($request, $key);

        return redirect()->back()->with('success', ucfirst(str_replace('_', ' ', $key)) . ' updated successfully.');
    }

    /**
     * Update branding toggle.
     */
    public function updateBrandingToggle(Request $request, string $key)
    {
        $this->brandingService->updateToggle($request, $key);

        $value = $request->boolean('value');
        $status = $value ? 'enabled' : 'disabled';

        return redirect()->back()->with('success', ucfirst(str_replace('_', ' ', $key)) . ' has been ' . $status . '.');
    }

    /**
     * Update logo settings.
     */
    public function updateLogo(Request $request, string $logoType)
    {
        $validated = $request->validate([
            'logo' => [
                'nullable',
                'file',
                'image',
                'mimes:png,jpg,jpeg,svg,ico,gif,webp',
                'max:2048', // 2MB
            ],
            'name' => ['nullable', 'string', 'max:255'],
            'width' => ['nullable', 'integer', 'min:16', 'max:500'],
            'height' => ['nullable', 'integer', 'min:16', 'max:500'],
            'alignment' => ['nullable', 'in:left,center,right'],
            'position' => ['nullable', 'string', 'max:50'],
            'is_active' => ['nullable', 'boolean'],
            'custom_css' => ['nullable', 'string'],
        ]);

        $logo = $this->settingsService->updateLogo($request, $logoType);

        return redirect()->back()->with('success', ucfirst($logoType) . ' logo updated successfully.');
    }

    /**
     * Reset logo to default.
     */
    public function resetLogo(string $logoType)
    {
        $result = $this->settingsService->resetLogo($logoType);

        if ($result) {
            return redirect()->back()->with('success', ucfirst($logoType) . ' logo has been reset to default.');
        }

        return redirect()->back()->with('error', 'Failed to reset logo.');
    }

    /**
     * Update registration settings.
     */
    public function updateRegistration(Request $request)
    {
        $validated = $request->validate([
            'registration_enabled' => ['required', 'boolean'],
        ]);

        $this->settingsService->setRegistrationEnabled($validated['registration_enabled']);

        $status = $validated['registration_enabled'] ? 'enabled' : 'disabled';

        return redirect()->back()->with('success', "User registration has been {$status}.");
    }

    /**
     * Update general settings.
     */
    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'app_name' => ['nullable', 'string', 'max:255'],
            'app_version' => ['nullable', 'string', 'max:50'],
        ]);

        foreach ($validated as $key => $value) {
            if (!empty($value)) {
                $this->settingsService->updateSetting($request, $key);
            }
        }

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }

    /**
     * Get logo preview (for AJAX).
     */
    public function logoPreview(string $logoType): JsonResponse
    {
        $logo = $this->settingsService->getLogo($logoType);

        return response()->json([
            'type' => $logo->logo_type,
            'name' => $logo->name,
            'url' => $logo->getUrl(),
            'default_url' => $logo->getDefaultUrl(),
            'is_custom' => !empty($logo->image_url),
            'styles' => $logo->getStyles(),
            'alignment' => $logo->alignment,
            'is_active' => $logo->is_active,
        ]);
    }

    /**
     * Delete uploaded logo (keep settings).
     */
    public function deleteLogo(string $logoType)
    {
        $logo = LogoSetting::getByType($logoType);

        if ($logo->image_path && Storage::exists($logo->image_path)) {
            Storage::delete($logo->image_path);
        }

        $logo->image_path = null;
        $logo->image_url = null;
        $logo->save();

        return redirect()->back()->with('success', 'Logo deleted. Default logo will be shown.');
    }

    /**
     * Display activity logs.
     */
    public function activityLogs(Request $request)
    {
        $perPage = $request->input('per_page', 20);
        $logs = $this->settingsService->getActivityLogs($perPage);

        if ($request->ajax()) {
            return response()->json([
                'logs' => $logs->items(),
                'pagination' => [
                    'current_page' => $logs->currentPage(),
                    'last_page' => $logs->lastPage(),
                    'total' => $logs->total(),
                ],
            ]);
        }

        return view('pages.settings.activity-logs', compact('logs'));
    }

    /**
     * Seed default settings.
     */
    public function seedDefaults()
    {
        $this->settingsService->seedDefaultLogos();

        return redirect()->back()->with('success', 'Default settings have been seeded.');
    }
    public function searchImage(Request $request)
    {
        $query = $request->q;

        $response = Http::get('https://www.googleapis.com/customsearch/v1', [
            'key' => env('GOOGLE_SEARCH_API_KEY'),
            'cx' => env('GOOGLE_SEARCH_ENGINE_ID'),
            'q' => $query,
            'searchType' => 'image',
            'num' => 10
        ]);

        return response()->json($response->json());
    }
}
