<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Ikm;
use App\Models\BencmarkProduk;
use App\Models\ProdukDesign;
use App\Models\DokumentasiCots;
use App\Helpers\ThumbnailHelper;

class GenerateThumbnails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'thumbnails:generate {--all : Generate thumbnails for all image models}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate thumbnails for uploaded images';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $all = $this->option('all');

        $this->info('Starting thumbnail generation...');
        $this->newLine();

        $stats = [
            'ikm' => ['total' => 0, 'success' => 0, 'failed' => 0],
            'bencmark' => ['total' => 0, 'success' => 0, 'failed' => 0],
            'produk_design' => ['total' => 0, 'success' => 0, 'failed' => 0],
            'dokumentasi' => ['total' => 0, 'success' => 0, 'failed' => 0],
        ];

        // Process IKM images
        if ($all || $this->confirm('Generate thumbnails for IKM profile images?')) {
            $this->info('Processing IKM profile images...');
            $ikmImages = Ikm::whereNotNull('gambar')->get();
            $stats['ikm']['total'] = $ikmImages->count();

            foreach ($ikmImages as $ikm) {
                try {
                    if (ThumbnailHelper::isValidImage($ikm->gambar)) {
                        foreach (ThumbnailHelper::THUMBNAIL_SIZES as $size => $dimension) {
                            ThumbnailHelper::generateThumbnail($ikm->gambar, $size);
                        }
                        $stats['ikm']['success']++;
                    } else {
                        $stats['ikm']['failed']++;
                        $this->warn("Invalid image: {$ikm->gambar}");
                    }
                } catch (\Exception $e) {
                    $stats['ikm']['failed']++;
                    $this->error("Error processing IKM {$ikm->id}: {$e->getMessage()}");
                }
            }
            $this->info("IKM: {$stats['ikm']['success']}/{$stats['ikm']['total']} processed successfully");
        }

        // Process Benchmark images
        if ($all || $this->confirm('Generate thumbnails for Bencmark Produk images?')) {
            $this->info('Processing Bencmark Produk images...');
            $bencmarkImages = BencmarkProduk::whereNotNull('gambar')->get();
            $stats['bencmark']['total'] = $bencmarkImages->count();

            foreach ($bencmarkImages as $image) {
                try {
                    if (ThumbnailHelper::isValidImage($image->gambar)) {
                        foreach (ThumbnailHelper::THUMBNAIL_SIZES as $size => $dimension) {
                            ThumbnailHelper::generateThumbnail($image->gambar, $size);
                        }
                        $stats['bencmark']['success']++;
                    } else {
                        $stats['bencmark']['failed']++;
                        $this->warn("Invalid image: {$image->gambar}");
                    }
                } catch (\Exception $e) {
                    $stats['bencmark']['failed']++;
                    $this->error("Error processing Bencmark {$image->id}: {$e->getMessage()}");
                }
            }
            $this->info("Bencmark: {$stats['bencmark']['success']}/{$stats['bencmark']['total']} processed successfully");
        }

        // Process Produk Design images
        if ($all || $this->confirm('Generate thumbnails for Produk Design images?')) {
            $this->info('Processing Produk Design images...');
            $designImages = ProdukDesign::whereNotNull('gambar')->get();
            $stats['produk_design']['total'] = $designImages->count();

            foreach ($designImages as $image) {
                try {
                    if (ThumbnailHelper::isValidImage($image->gambar)) {
                        foreach (ThumbnailHelper::THUMBNAIL_SIZES as $size => $dimension) {
                            ThumbnailHelper::generateThumbnail($image->gambar, $size);
                        }
                        $stats['produk_design']['success']++;
                    } else {
                        $stats['produk_design']['failed']++;
                        $this->warn("Invalid image: {$image->gambar}");
                    }
                } catch (\Exception $e) {
                    $stats['produk_design']['failed']++;
                    $this->error("Error processing Design {$image->id}: {$e->getMessage()}");
                }
            }
            $this->info("Produk Design: {$stats['produk_design']['success']}/{$stats['produk_design']['total']} processed successfully");
        }

        // Process Dokumentasi images
        if ($all || $this->confirm('Generate thumbnails for Dokumentasi images?')) {
            $this->info('Processing Dokumentasi images...');
            $dokumentasiImages = DokumentasiCots::whereNotNull('gambar')->get();
            $stats['dokumentasi']['total'] = $dokumentasiImages->count();

            foreach ($dokumentasiImages as $image) {
                try {
                    if (ThumbnailHelper::isValidImage($image->gambar)) {
                        foreach (ThumbnailHelper::THUMBNAIL_SIZES as $size => $dimension) {
                            ThumbnailHelper::generateThumbnail($image->gambar, $size);
                        }
                        $stats['dokumentasi']['success']++;
                    } else {
                        $stats['dokumentasi']['failed']++;
                        $this->warn("Invalid image: {$image->gambar}");
                    }
                } catch (\Exception $e) {
                    $stats['dokumentasi']['failed']++;
                    $this->error("Error processing Dokumentasi {$image->id}: {$e->getMessage()}");
                }
            }
            $this->info("Dokumentasi: {$stats['dokumentasi']['success']}/{$stats['dokumentasi']['total']} processed successfully");
        }

        // Summary
        $this->newLine();
        $this->info('========================================');
        $this->info('Thumbnail Generation Summary:');
        $this->info('========================================');

        $total = $stats['ikm']['total'] + $stats['bencmark']['total'] + $stats['produk_design']['total'] + $stats['dokumentasi']['total'];
        $success = $stats['ikm']['success'] + $stats['bencmark']['success'] + $stats['produk_design']['success'] + $stats['dokumentasi']['success'];
        $failed = $stats['ikm']['failed'] + $stats['bencmark']['failed'] + $stats['produk_design']['failed'] + $stats['dokumentasi']['failed'];

        $this->info("IKM Profile: {$stats['ikm']['success']}/{$stats['ikm']['total']}");
        $this->info("Bencmark Produk: {$stats['bencmark']['success']}/{$stats['bencmark']['total']}");
        $this->info("Produk Design: {$stats['produk_design']['success']}/{$stats['produk_design']['total']}");
        $this->info("Dokumentasi: {$stats['dokumentasi']['success']}/{$stats['dokumentasi']['total']}");
        $this->newLine();
        $this->info("Total: {$success}/{$total} images processed successfully");

        if ($failed > 0) {
            $this->warn("{$failed} images failed to process");
        }

        return $failed > 0 ? Command::FAILURE : Command::SUCCESS;
    }
}
