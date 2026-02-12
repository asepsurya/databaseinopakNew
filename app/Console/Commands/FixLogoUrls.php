<?php

namespace App\Console\Commands;

use App\Models\LogoSetting;
use Illuminate\Console\Command;

class FixLogoUrls extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logos:fix-urls {--dry-run : Show what would be changed without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix logo URLs stored as absolute URLs to use relative paths instead';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $dryRun = $this->option('dry-run');

        $logos = LogoSetting::whereNotNull('image_url')->get();
        $fixedCount = 0;

        foreach ($logos as $logo) {
            if (empty($logo->image_url)) {
                continue;
            }

            // Check if the URL contains http:// or https:// (absolute URL)
            if (str_contains($logo->image_url, 'http://') || str_contains($logo->image_url, 'https://')) {
                // Extract the path portion after the domain
                $parsedUrl = parse_url($logo->image_url);
                $newUrl = isset($parsedUrl['path']) ? ltrim($parsedUrl['path'], '/') : null;

                if ($newUrl && $newUrl !== $logo->image_url) {
                    if ($dryRun) {
                        $this->info("DRY RUN: Would fix {$logo->logo_type}: {$logo->image_url} -> {$newUrl}");
                    } else {
                        $logo->image_url = $newUrl;
                        $logo->save();
                        $this->info("Fixed {$logo->logo_type}: {$newUrl}");
                    }
                    $fixedCount++;
                }
            }
        }

        if ($fixedCount === 0) {
            $this->info('No absolute URLs found in logo settings. All URLs are already relative.');
        } else {
            $this->info("Fixed {$fixedCount} logo URL(s).");
        }

        if ($dryRun) {
            $this->info('DRY RUN - No changes were made.');
        }

        return Command::SUCCESS;
    }
}
