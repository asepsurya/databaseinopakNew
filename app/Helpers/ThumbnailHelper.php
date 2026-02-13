<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

/**
 * Thumbnail Helper - Provides thumbnail generation and display functionality
 * for all image uploads in the application.
 */
class ThumbnailHelper
{
    /**
     * Thumbnail sizes configuration
     */
    const THUMBNAIL_SIZES = [
        'small' => 150,
        'medium' => 300,
        'large' => 600,
        'xlarge' => 1200,
    ];

    /**
     * Supported image formats
     */
    const SUPPORTED_FORMATS = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    /**
     * Quality for JPEG/WebP thumbnails (0-100)
     */
    const THUMBNAIL_QUALITY = 85;

    /**
     * Generate thumbnail URL for a given image path
     *
     * @param string|null $imagePath Path to the image in storage
     * @param string $size Thumbnail size (small, medium, large, xlarge)
     * @param bool $generate If true, generates thumbnail if it doesn't exist
     * @return string|null Thumbnail URL or null if image doesn't exist
     */
    public static function thumbnailUrl(?string $imagePath, string $size = 'medium', bool $generate = true): ?string
    {
        if (empty($imagePath)) {
            return null;
        }

        $fullPath = storage_path('app/public/' . $imagePath);

        if (!File::exists($fullPath)) {
            // Fallback to original if thumbnail doesn't exist and generation is disabled
            return asset('storage/' . $imagePath);
        }

        $thumbnailPath = self::getThumbnailPath($imagePath, $size);

        if ($generate && !File::exists(storage_path('app/public/' . $thumbnailPath))) {
            self::generateThumbnail($imagePath, $size);
        }

        // Return the thumbnail URL
        return asset('storage/' . $thumbnailPath);
    }

    /**
     * Generate thumbnail for a given image using native GD functions
     *
     * @param string $imagePath Path to the original image
     * @param string $size Thumbnail size
     * @return bool True if successful, false otherwise
     */
    public static function generateThumbnail(string $imagePath, string $size = 'medium'): bool
    {
        try {
            $fullPath = storage_path('app/public/' . $imagePath);

            if (!File::exists($fullPath)) {
                return false;
            }

            $thumbnailPath = self::getThumbnailPath($imagePath, $size);
            $thumbnailDir = dirname(storage_path('app/public/' . $thumbnailPath));

            // Create directory if it doesn't exist
            if (!File::exists($thumbnailDir)) {
                File::makeDirectory($thumbnailDir, 0755, true);
            }

            // Get image dimensions using native PHP
            $imageInfo = self::getImageInfo($fullPath);

            if (!$imageInfo) {
                return false;
            }

            $width = $imageInfo['width'];
            $height = $imageInfo['height'];
            $mimeType = $imageInfo['mime'];

            // Get target dimension
            $targetWidth = self::THUMBNAIL_SIZES[$size] ?? self::THUMBNAIL_SIZES['medium'];

            // If image is smaller than target, don't upscale - just copy
            if ($width <= $targetWidth && $height <= $targetWidth) {
                // Copy original to thumbnail path
                File::copy($fullPath, storage_path('app/public/' . $thumbnailPath));
                return true;
            }

            // Calculate new dimensions maintaining aspect ratio
            $ratio = $width / $height;

            if ($width > $height) {
                $newWidth = $targetWidth;
                $newHeight = (int) round($targetWidth / $ratio);
            } else {
                $newHeight = $targetWidth;
                $newWidth = (int) round($targetWidth * $ratio);
            }

            // Create source image based on mime type
            $sourceImage = self::createImageFromFile($fullPath, $mimeType);

            if (!$sourceImage) {
                return false;
            }

            // Create new image for thumbnail
            $thumbnailImage = imagecreatetruecolor($newWidth, $newHeight);

            // Handle transparency for PNG and GIF
            if ($mimeType === 'image/png' || $mimeType === 'image/gif') {
                imagealphablending($thumbnailImage, false);
                imagesavealpha($thumbnailImage, true);
                $transparent = imagecolorallocatealpha($thumbnailImage, 0, 0, 0, 127);
                imagefill($thumbnailImage, 0, 0, $transparent);
            }

            // Resize the image
            imagecopyresampled(
                $thumbnailImage,
                $sourceImage,
                0, 0, 0, 0,
                $newWidth, $newHeight,
                $width, $height
            );

            // Save the thumbnail
            $saved = false;
            switch ($mimeType) {
                case 'image/jpeg':
                    $saved = imagejpeg($thumbnailImage, storage_path('app/public/' . $thumbnailPath), self::THUMBNAIL_QUALITY);
                    break;
                case 'image/png':
                    $saved = imagepng($thumbnailImage, storage_path('app/public/' . $thumbnailPath), 9);
                    break;
                case 'image/gif':
                    $saved = imagegif($thumbnailImage, storage_path('app/public/' . $thumbnailPath));
                    break;
                case 'image/webp':
                    $saved = imagewebp($thumbnailImage, storage_path('app/public/' . $thumbnailPath), self::THUMBNAIL_QUALITY);
                    break;
            }

            // Clean up memory
            imagedestroy($sourceImage);
            imagedestroy($thumbnailImage);

            return $saved;
        } catch (\Exception $e) {
            \Log::error('Thumbnail generation failed: ' . $e->getMessage(), [
                'image_path' => $imagePath,
                'size' => $size,
                'exception' => $e
            ]);
            return false;
        }
    }

    /**
     * Get image information using native PHP functions
     *
     * @param string $filePath Path to the image file
     * @return array|null Array with image info or null if invalid
     */
    private static function getImageInfo(string $filePath): ?array
    {
        if (!File::exists($filePath)) {
            return null;
        }

        $info = @getimagesize($filePath);

        if ($info === false || !isset($info['mime'])) {
            return null;
        }

        $mimeMap = [
            IMAGETYPE_JPEG => 'image/jpeg',
            IMAGETYPE_PNG => 'image/png',
            IMAGETYPE_GIF => 'image/gif',
            IMAGETYPE_WEBP => 'image/webp',
        ];

        $mimeType = $mimeMap[$info[2]] ?? null;

        if (!$mimeType) {
            return null;
        }

        return [
            'width' => $info[0],
            'height' => $info[1],
            'mime' => $mimeType
        ];
    }

    /**
     * Create GD image resource from file
     *
     * @param string $filePath Path to the image file
     * @param string $mimeType MIME type of the image
     * @return resource|false GD image resource or false on failure
     */
    private static function createImageFromFile(string $filePath, string $mimeType)
    {
        switch ($mimeType) {
            case 'image/jpeg':
                return @imagecreatefromjpeg($filePath);
            case 'image/png':
                return @imagecreatefrompng($filePath);
            case 'image/gif':
                return @imagecreatefromgif($filePath);
            case 'image/webp':
                return @imagecreatefromwebp($filePath);
            default:
                return false;
        }
    }

    /**
     * Generate all thumbnail sizes for an image
     *
     * @param string $imagePath Path to the original image
     * @return array Array of generated thumbnail URLs
     */
    public static function generateAllThumbnails(string $imagePath): array
    {
        $thumbnails = [];

        foreach (self::THUMBNAIL_SIZES as $size => $dimension) {
            if (self::generateThumbnail($imagePath, $size)) {
                $thumbnails[$size] = self::getThumbnailPath($imagePath, $size);
            }
        }

        return $thumbnails;
    }

    /**
     * Get thumbnail path for a given image and size
     *
     * @param string $imagePath Original image path
     * @param string $size Thumbnail size
     * @return string Thumbnail path
     */
    public static function getThumbnailPath(string $imagePath, string $size = 'medium'): string
    {
        $extension = pathinfo($imagePath, PATHINFO_EXTENSION);
        $dirname = dirname($imagePath);
        $basename = pathinfo($imagePath, PATHINFO_FILENAME);

        // Ensure extension is lowercase for consistency
        $extension = strtolower($extension);

        return $dirname . '/thumbnails/' . $basename . '_' . $size . '.' . $extension;
    }

    /**
     * Get original image URL
     *
     * @param string|null $imagePath Path to the image
     * @return string|null Original image URL or null
     */
    public static function originalUrl(?string $imagePath): ?string
    {
        if (empty($imagePath)) {
            return null;
        }

        return asset('storage/' . $imagePath);
    }

    /**
     * Check if an image is valid and exists
     *
     * @param string|null $imagePath Path to the image
     * @return bool True if image exists and is valid
     */
    public static function isValidImage(?string $imagePath): bool
    {
        if (empty($imagePath)) {
            return false;
        }

        $fullPath = storage_path('app/public/' . $imagePath);
        return File::exists($fullPath) && @getimagesize($fullPath) !== false;
    }

    /**
     * Get image dimensions
     *
     * @param string|null $imagePath Path to the image
     * @return array|null Array with 'width' and 'height' or null if invalid
     */
    public static function getImageDimensions(?string $imagePath): ?array
    {
        if (!self::isValidImage($imagePath)) {
            return null;
        }

        return self::getImageInfo(storage_path('app/public/' . $imagePath));
    }

    /**
     * Get file size in human readable format
     *
     * @param string|null $imagePath Path to the image
     * @return string|null Formatted file size or null
     */
    public static function getFileSize(?string $imagePath): ?string
    {
        if (!self::isValidImage($imagePath)) {
            return null;
        }

        $fullPath = storage_path('app/public/' . $imagePath);
        $bytes = File::size($fullPath);

        if ($bytes === 0) {
            return '0 Bytes';
        }

        $k = 1024;
        $sizes = ['Bytes', 'KB', 'MB', 'GB'];
        $i = floor(log($bytes, $k));

        return round($bytes / pow($k, $i), 2) . ' ' . $sizes[$i];
    }

    /**
     * Delete thumbnail files for an image
     *
     * @param string $imagePath Path to the original image
     * @return bool True if successful
     */
    public static function deleteThumbnails(string $imagePath): bool
    {
        try {
            foreach (self::THUMBNAIL_SIZES as $size => $dimension) {
                $thumbnailPath = self::getThumbnailPath($imagePath, $size);
                if (File::exists(storage_path('app/public/' . $thumbnailPath))) {
                    File::delete(storage_path('app/public/' . $thumbnailPath));
                }
            }
            return true;
        } catch (\Exception $e) {
            \Log::error('Failed to delete thumbnails: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Generate responsive srcset for an image
     *
     * @param string|null $imagePath Path to the original image
     * @return string srcset attribute value
     */
    public static function srcset(?string $imagePath): string
    {
        if (empty($imagePath)) {
            return '';
        }

        $srcset = [];

        foreach (self::THUMBNAIL_SIZES as $size => $dimension) {
            $thumbnailUrl = self::thumbnailUrl($imagePath, $size, false);
            if ($thumbnailUrl) {
                $srcset[] = $thumbnailUrl . ' ' . $dimension . 'w';
            }
        }

        return implode(', ', $srcset);
    }

    /**
     * Get optimal thumbnail size based on container width
     *
     * @param int $containerWidth Container width in pixels
     * @return string Optimal thumbnail size
     */
    public static function getOptimalSize(int $containerWidth): string
    {
        if ($containerWidth <= 200) {
            return 'small';
        } elseif ($containerWidth <= 400) {
            return 'medium';
        } elseif ($containerWidth <= 800) {
            return 'large';
        } else {
            return 'xlarge';
        }
    }
}
