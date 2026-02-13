{{--
    Thumbnail Component - Custom thumbnail display for images

    Usage:
    @component('components.thumbnail', [
        'imagePath' => $image->gambar,
        'alt' => 'Image description',
        'size' => 'medium', // small, medium, large, xlarge
        'showDownload' => true,
        'showFullscreen' => true,
        'lazy' => true,
        'class' => '',
        'style' => ''
    ])
    @endcomponent

    Features:
    - Responsive thumbnail generation
    - Download button for original images
    - Fullscreen preview with lightbox
    - Lazy loading support
    - File size display
    - Dimension display
--}}

@php
    // Get image information using ThumbnailHelper
    $isValid = \ThumbnailHelper::isValidImage($imagePath);
    $dimensions = \ThumbnailHelper::getImageDimensions($imagePath);
    $fileSize = \ThumbnailHelper::getFileSize($imagePath);

    // Get URLs
    $thumbnailUrl = \ThumbnailHelper::thumbnailUrl($imagePath, $size ?? 'medium', true);
    $originalUrl = \ThumbnailHelper::originalUrl($imagePath);
    $srcset = \ThumbnailHelper::srcset($imagePath);

    // Default attributes
    $defaultAlt = $alt ?? 'Image';
    $defaultClass = 'thumbnail-image';
    $imgClass = $class ?? $defaultClass;
    $imgStyle = $style ?? '';
    $imgId = $id ?? '';
    $loading = $lazy ?? true ? 'lazy' : 'eager';

    // Container dimensions
    $containerWidth = $containerWidth ?? 200;
    $containerHeight = $containerHeight ?? 200;

    // Aspect ratio for placeholder
    $aspectRatio = 'aspect-ratio: 1 / 1';
    if ($dimensions) {
        $aspectRatio = "aspect-ratio: {$dimensions['width']} / {$dimensions['height']}";
    }
@endphp

<div class="thumbnail-wrapper position-relative d-inline-block {{ $wrapperClass ?? '' }}"
     style="{{ $wrapperStyle ?? '' }}"
     data-image-path="{{ $imagePath }}"
     data-original-url="{{ $originalUrl }}"
     data-file-size="{{ $fileSize }}"
     data-dimensions="{{ $dimensions ? $dimensions['width'] . 'x' . $dimensions['height'] : '' }}">

    {{-- Image Container --}}
    <div class="thumbnail-container position-relative overflow-hidden rounded"
         style="{{ $aspectRatio }}; width: 100%; max-width: {{ $containerWidth }}px; max-height: {{ $containerHeight }}px; background-color: #f8f9fa;">

        {{-- Placeholder/Loading state --}}
        <div class="thumbnail-placeholder position-absolute inset-0 d-flex align-items-center justify-content-center bg-light"
             style="z-index: 1;">
            <div class="spinner-border text-secondary" role="status" style="width: 1.5rem; height: 1.5rem;">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>

        {{-- Main Image --}}
        @if($isValid && $thumbnailUrl)
            <img src="{{ $thumbnailUrl }}"
                 srcset="{{ $srcset }}"
                 sizes="{{ $sizes ?? '(max-width: 576px) 100vw, (max-width: 768px) 50vw, 33vw' }}"
                 alt="{{ $defaultAlt }}"
                 class="{{ $imgClass }} position-absolute inset-0 w-100 h-100"
                 style="object-fit: cover; z-index: 2; {{ $imgStyle }}"
                 id="{{ $imgId }}"
                 loading="{{ $loading }}"
                 onload="this.parentElement.querySelector('.thumbnail-placeholder').style.display = 'none';"
                 onerror="this.parentElement.querySelector('.thumbnail-placeholder').innerHTML = '<i class=\'ti ti-photo-off text-muted\'></i>';">

            {{-- Fullscreen trigger (if enabled) --}}
            @if(($showFullscreen ?? true) && $originalUrl)
                <a href="{{ $originalUrl }}"
                   class="thumbnail-fullscreen position-absolute bottom-0 end-0 m-2 p-1 rounded bg-dark bg-opacity-50 text-white text-decoration-none"
                   style="z-index: 3; opacity: 0; transition: opacity 0.2s;"
                   data-fslightbox
                   title="Lihat ukuran penuh"
                   target="_blank">
                    <i class="ti ti-maximize" style="font-size: 14px;"></i>
                </a>
            @endif
        @else
            {{-- Fallback when image is invalid --}}
            <div class="thumbnail-fallback position-absolute inset-0 d-flex align-items-center justify-content-center text-muted"
                 style="z-index: 2;">
                <div class="text-center">
                    <i class="ti {{ $fallbackIcon ?? 'ti-photo-off' }}" style="font-size: 32px;"></i>
                    <p class="mb-0 small mt-1">{{ $fallbackText ?? 'Gambar tidak tersedia' }}</p>
                </div>
            </div>
        @endif
    </div>

    {{-- Download Button --}}
    @if(($showDownload ?? true) && $isValid && $originalUrl)
        <a href="{{ $originalUrl }}"
           class="thumbnail-download position-absolute top-0 end-0 m-1 p-1 rounded bg-white shadow-sm text-dark text-decoration-none"
           style="z-index: 4; opacity: 0; transition: opacity 0.2s;"
           title="Unduh gambar asli"
           download="{{ basename($imagePath) }}"
           onclick="event.stopPropagation();">
            <i class="ti ti-download" style="font-size: 14px;"></i>
        </a>
    @endif

    {{-- Hover effects container --}}
    <div class="thumbnail-hover-overlay position-absolute inset-0 rounded pointer-events-none"
         style="z-index: 5; opacity: 0; transition: opacity 0.2s; background: rgba(0,0,0,0.1);">
    </div>
</div>

<style>
    /* Thumbnail hover effects */
    .thumbnail-wrapper:hover .thumbnail-download,
    .thumbnail-wrapper:hover .thumbnail-fullscreen {
        opacity: 1 !important;
    }

    .thumbnail-wrapper:hover .thumbnail-hover-overlay {
        opacity: 1 !important;
    }

    .thumbnail-wrapper {
        cursor: pointer;
    }

    .thumbnail-wrapper img {
        transition: transform 0.3s ease;
    }

    .thumbnail-wrapper:hover img {
        transform: scale(1.05);
    }

    /* Dark mode support */
    [data-bs-theme="dark"] .thumbnail-container {
        background-color: #2e3344 !important;
    }

    [data-bs-theme="dark"] .thumbnail-placeholder {
        background-color: #2e3344 !important;
    }

    [data-bs-theme="dark"] .thumbnail-download {
        background-color: #3d4458 !important;
        color: #dee2e6 !important;
    }

    /* Responsive styles */
    @media (max-width: 576px) {
        .thumbnail-container {
            max-width: 100% !important;
            max-height: 150px !important;
        }

        .thumbnail-download,
        .thumbnail-fullscreen {
            opacity: 1 !important;
            transform: scale(0.9);
        }
    }
</style>

{{-- JavaScript for additional functionality --}}
@push('scripts')
<script>
    // Thumbnail component initialization
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize FsLightbox if available
        if (typeof FsLightbox !== 'undefined') {
            // FsLightbox will auto-initialize for elements with data-fslightbox
        }

        // Add click-to-download functionality
        document.querySelectorAll('.thumbnail-wrapper').forEach(function(wrapper) {
            wrapper.addEventListener('click', function(e) {
                // Don't trigger if clicking on download button
                if (e.target.closest('.thumbnail-download')) {
                    return;
                }

                // Optional: Open fullscreen on click
                const fullscreenLink = this.querySelector('.thumbnail-fullscreen');
                if (fullscreenLink && window.innerWidth < 768) {
                    fullscreenLink.click();
                }
            });
        });
    });
</script>
@endpush
