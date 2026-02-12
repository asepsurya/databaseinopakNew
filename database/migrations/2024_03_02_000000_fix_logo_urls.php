<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\LogoSetting;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Fix existing absolute URLs to relative paths
        LogoSetting::chunk(100, function ($logos) {
            foreach ($logos as $logo) {
                if (!empty($logo->image_url) && str_contains($logo->image_url, 'http://')) {
                    // Extract the path portion after the domain
                    $parsedUrl = parse_url($logo->image_url);
                    if (isset($parsedUrl['path'])) {
                        $logo->image_url = ltrim($parsedUrl['path'], '/');
                        $logo->save();
                    }
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration is not reversible in a meaningful way
        // as we're simplifying URLs, not making them more complex
    }
};
