{{--
    Product Design Gallery Component
    Modern, responsive image gallery with filtering, lazy loading, and lightbox

    Usage:
    @component('components.product-gallery', [
        'images' => $Ikm->first()->produkDesign,
        'title' => 'Desain Produk',
        'galleryId' => 'produkDesign',
        'showFilter' => true,
        'showDownload' => true,
        'columns' => 4,
    ])
    @endcomponent
--}}

@php
    // Get unique categories from images if available
    $categories = $images->pluck('kategori')->unique()->filter()->values();

    // Default categories if none exist
    if ($categories->isEmpty()) {
        $categories = collect(['Semua', 'Kemasan', 'Label', 'Produk', 'Lainnya']);
    }

    $galleryId = $galleryId ?? 'productGallery';
    $columns = $columns ?? 4;
    $showFilter = $showFilter ?? true;
    $showDownload = $showDownload ?? true;
    $showDelete = $showDelete ?? true;
@endphp

<div class="product-gallery" id="{{ $galleryId }}">
    {{-- Gallery Header --}}
    <div class="gallery-header mb-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h5 class="gallery-title mb-1">{{ $title ?? 'Galeri Produk' }}</h5>
                <p class="gallery-subtitle text-muted mb-0 small">
                    <span class="image-count">{{ $images->count() }}</span> gambar
                </p>
            </div>

            @if($showFilter && $categories->count() > 1)
                {{-- Filter Buttons --}}
                <div class="gallery-filters">
                    <div class="btn-group btn-group-sm" role="group" aria-label="Filter galeri">
                        @foreach($categories as $index => $category)
                            <button type="button"
                                    class="btn btn-outline-secondary filter-btn {{ $index === 0 ? 'active' : '' }}"
                                    data-filter="{{ strtolower($category) }}"
                                    data-gallery="{{ $galleryId }}">
                                {{ $category }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Gallery Grid --}}
    <div class="gallery-grid row g-3" data-columns="{{ $columns }}">
        @forelse($images as $index => $image)
            @php
                $isValid = \App\Helpers\ThumbnailHelper::isValidImage($image->gambar ?? null);
                $thumbnailUrl = $isValid ? \App\Helpers\ThumbnailHelper::thumbnailUrl($image->gambar, 'large', true) : null;
                $originalUrl = $isValid ? \App\Helpers\ThumbnailHelper::originalUrl($image->gambar) : null;
                $category = strtolower($image->kategori ?? 'lainnya');
                $fileSize = $isValid ? \App\Helpers\ThumbnailHelper::getFileSize($image->gambar) : null;
                $dimensions = \App\Helpers\ThumbnailHelper::getImageDimensions($image->gambar ?? null);
            @endphp

            <div class="gallery-item col-6 col-md-4 col-lg-{{ 12 / $columns }}"
                 data-category="{{ $category }}"
                 data-index="{{ $index }}"
                 tabindex="0">

                <div class="gallery-card h-100">
                    {{-- Image Container --}}
                    <div class="gallery-image-wrapper position-relative overflow-hidden rounded">
                        {{-- Placeholder/Loading --}}
                        <div class="gallery-placeholder position-absolute inset-0 d-flex align-items-center justify-content-center bg-light">
                            <div class="spinner-border text-secondary" style="width: 2rem; height: 2rem;">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>

                        {{-- Main Image --}}
                        @if($isValid && $thumbnailUrl)
                            <img src="{{ $thumbnailUrl }}"
                                 data-src="{{ $thumbnailUrl }}"
                                 data-full="{{ $originalUrl }}"
                                 alt="{{ $image->nama ?? 'Produk' }}"
                                 class="gallery-image w-100"
                                 loading="lazy"
                                 data-category="{{ $category }}"
                                 data-file-size="{{ $fileSize }}"
                                 data-dimensions="{{ $dimensions['width'] ?? 0 }}x{{ $dimensions['height'] ?? 0 }}"
                                 onload="this.parentElement.querySelector('.gallery-placeholder').style.display = 'none';"
                                 onerror="this.parentElement.querySelector('.gallery-placeholder').innerHTML = '<i class=\'ti ti-photo-off text-muted\' style=\'font-size: 2rem;\'></i>';">
                        @else
                            <div class="gallery-fallback position-absolute inset-0 d-flex align-items-center justify-content-center bg-light">
                                <div class="text-center text-muted">
                                    <i class="ti ti-photo-off" style="font-size: 2rem;"></i>
                                    <p class="mb-0 small mt-1">Gambar tidak tersedia</p>
                                </div>
                            </div>
                        @endif

                        {{-- Hover Overlay with Actions --}}
                        <div class="gallery-overlay position-absolute inset-0 d-flex flex-column justify-content-end p-3">
                            {{-- Product Details (shown on hover) --}}
                            <div class="gallery-details mb-auto">
                                @if($image->nama)
                                    <h6 class="gallery-product-name text-white mb-1">{{ $image->nama }}</h6>
                                @endif
                                @if($image->kategori)
                                    <span class="badge bg-primary bg-opacity-75">{{ $image->kategori }}</span>
                                @endif
                            </div>

                            {{-- Action Buttons --}}
                            <div class="gallery-actions d-flex gap-2 mt-2">
                                @if($isValid && $originalUrl)
                                    {{-- Fullscreen Button --}}
                                    <a href="{{ $originalUrl }}"
                                       class="gallery-btn gallery-btn-fullscreen btn btn-light btn-sm"
                                       data-fslightbox
                                       title="Lihat ukuran penuh"
                                       target="_blank">
                                        <i class="ti ti-maximize"></i>
                                    </a>

                                    {{-- Download Button --}}
                                    @if($showDownload)
                                        <a href="{{ $originalUrl }}"
                                           class="gallery-btn gallery-btn-download btn btn-light btn-sm"
                                           title="Unduh gambar asli"
                                           download="{{ basename($image->gambar) }}">
                                            <i class="ti ti-download"></i>
                                        </a>
                                    @endif
                                @endif

                                {{-- Delete Button --}}
                                @if($showDelete && isset($deleteRoute))
                                    <form action="{{ $deleteRoute }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="id" value="{{ $image->id }}">
                                        <button type="submit"
                                                class="gallery-btn gallery-btn-delete btn btn-danger btn-sm"
                                                title="Hapus"
                                                onclick="return confirm('Yakin hapus gambar ini?')">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>

                        {{-- Loading Progress --}}
                        <div class="gallery-loading position-absolute top-50 start-50 translate-middle">
                            <div class="spinner-border text-primary" role="status" style="width: 2rem; height: 2rem;">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>

                    {{-- Image Info (always visible) --}}
                    @if($showInfo ?? false)
                        <div class="gallery-info mt-2">
                            @if($image->nama)
                                <p class="gallery-info-name mb-0 small fw-medium text-truncate">{{ $image->nama }}</p>
                            @endif
                            @if($fileSize || ($dimensions && ($dimensions['width'] ?? 0) > 0))
                                <p class="gallery-info-meta mb-0 text-muted" style="font-size: 0.75rem;">
                                    @if($dimensions)
                                        {{ $dimensions['width'] }}x{{ $dimensions['height'] }}
                                    @endif
                                    @if($fileSize && $dimensions)
                                        |
                                    @endif
                                    @if($fileSize)
                                        {{ $fileSize }}
                                    @endif
                                </p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        @empty
            {{-- Empty State --}}
            <div class="col-12">
                <div class="gallery-empty text-center py-5">
                    <div class="empty-icon-wrapper mb-3">
                        <i class="ti ti-photo-off text-muted" style="font-size: 4rem;"></i>
                    </div>
                    <h6 class="text-muted mb-2">Belum ada gambar</h6>
                    <p class="text-muted small mb-3">Tambahkan gambar pertama untuk galeri produk Anda</p>
                    @if(isset($uploadButton) && $uploadButton)
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#{{ $uploadModalId ?? 'uploadModal' }}">
                            <i class="ti ti-upload me-2"></i>Upload Gambar
                        </button>
                    @endif
                </div>
            </div>
        @endforelse
    </div>

    {{-- Lightbox Container --}}
    <div class="gallery-lightbox" id="{{ $galleryId }}-lightbox">
        <div class="lightbox-backdrop"></div>
        <div class="lightbox-container">
            <button class="lightbox-close btn btn-light btn-sm position-absolute top-0 end-0 m-3" aria-label="Tutup">
                <i class="ti ti-x"></i>
            </button>

            {{-- Navigation --}}
            <button class="lightbox-prev btn btn-light btn-sm position-absolute start-0 top-50 translate-middle-y" aria-label="Sebelumnya">
                <i class="ti ti-chevron-left"></i>
            </button>
            <button class="lightbox-next btn btn-light btn-sm position-absolute end-0 top-50 translate-middle-y" aria-label="Selanjutnya">
                <i class="ti ti-chevron-right"></i>
            </button>

            {{-- Image Display --}}
            <div class="lightbox-content">
                <img src="" alt="" class="lightbox-image" id="{{ $galleryId }}-lightbox-image">
            </div>

            {{-- Image Info --}}
            <div class="lightbox-info position-absolute bottom-0 start-0 end-0 p-3">
                <div class="lightbox-info-content d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="lightbox-title text-white mb-1"></h6>
                        <p class="lightbox-meta text-white-50 small mb-0"></p>
                    </div>
                    <div class="lightbox-actions d-flex gap-2">
                        <a href="#" class="lightbox-download btn btn-light btn-sm" download>
                            <i class="ti ti-download me-1"></i>Unduh
                        </a>
                    </div>
                </div>
            </div>

            {{-- Counter --}}
            <div class="lightbox-counter position-absolute top-0 start-0 m-3">
                <span class="lightbox-current text-white"></span> / <span class="lightbox-total"></span>
            </div>
        </div>
    </div>
</div>

<style>
    /* ============================================
       PRODUCT GALLERY STYLES
       ============================================ */

    .product-gallery {
        --gallery-gap: 1rem;
        --gallery-radius: 0.5rem;
        --gallery-transition: 0.3s ease;
    }

    /* Header Styles */
    .gallery-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 0.25rem;
    }

    .gallery-filters .btn-group {
        gap: 0.25rem;
    }

    .gallery-filters .btn {
        border-radius: 2rem;
        font-size: 0.75rem;
        padding: 0.375rem 0.75rem;
        transition: all var(--gallery-transition);
    }

    .gallery-filters .btn.active,
    .gallery-filters .btn:hover {
        background-color: var(--bs-primary);
        border-color: var(--bs-primary);
        color: #fff;
    }

    /* Grid Styles */
    .gallery-grid {
        margin-left: calc(var(--gallery-gap) * -0.5);
        margin-right: calc(var(--gallery-gap) * -0.5);
    }

    .gallery-grid > * {
        padding-left: calc(var(--gallery-gap) * 0.5);
        padding-right: calc(var(--gallery-gap) * 0.5);
    }

    /* Card Styles */
    .gallery-card {
        background: #fff;
        border-radius: var(--gallery-radius);
        overflow: hidden;
        transition: all var(--gallery-transition);
    }

    .gallery-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    }

    /* Image Wrapper */
    .gallery-image-wrapper {
        aspect-ratio: 1;
        background-color: #f8f9fa;
    }

    .gallery-image {
        object-fit: cover;
        transition: transform 0.4s ease;
        opacity: 0;
    }

    .gallery-image.loaded {
        opacity: 1;
    }

    .gallery-card:hover .gallery-image {
        transform: scale(1.08);
    }

    /* Placeholder & Fallback */
    .gallery-placeholder,
    .gallery-fallback {
        z-index: 1;
    }

    .gallery-loading {
        display: none;
        z-index: 2;
    }

    .gallery-image-wrapper.loading .gallery-loading {
        display: block;
    }

    /* Overlay */
    .gallery-overlay {
        background: linear-gradient(transparent 50%, rgba(0, 0, 0, 0.8) 100%);
        opacity: 0;
        transition: opacity var(--gallery-transition);
        z-index: 3;
        pointer-events: none;
    }

    .gallery-card:hover .gallery-overlay {
        opacity: 1;
        pointer-events: auto;
    }

    /* Details */
    .gallery-details {
        transform: translateY(10px);
        transition: transform var(--gallery-transition);
    }

    .gallery-card:hover .gallery-details {
        transform: translateY(0);
    }

    .gallery-product-name {
        font-size: 0.875rem;
        font-weight: 600;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
    }

    /* Action Buttons */
    .gallery-actions {
        opacity: 0;
        transform: translateY(10px);
        transition: all var(--gallery-transition);
    }

    .gallery-card:hover .gallery-actions {
        opacity: 1;
        transform: translateY(0);
    }

    .gallery-btn {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.375rem;
        transition: all 0.2s ease;
    }

    .gallery-btn:hover {
        transform: scale(1.1);
    }

    .gallery-btn-delete:hover {
        background-color: #dc3545;
        border-color: #dc3545;
    }

    /* Info Section */
    .gallery-info-name {
        color: #333;
    }

    /* Empty State */
    .gallery-empty .empty-icon-wrapper {
        opacity: 0.5;
    }

    /* ============================================
       LIGHTBOX STYLES
       ============================================ */

    .gallery-lightbox {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 9999;
        outline: none;
    }

    .gallery-lightbox.active {
        display: block;
    }

    .lightbox-backdrop {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.95);
        backdrop-filter: blur(4px);
    }

    .lightbox-container {
        position: relative;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .lightbox-content {
        max-width: 90%;
        max-height: 80vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .lightbox-image {
        max-width: 100%;
        max-height: 80vh;
        object-fit: contain;
        border-radius: 0.25rem;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .lightbox-image.visible {
        opacity: 1;
    }

    .lightbox-close {
        z-index: 10;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: #fff;
        transition: all 0.2s ease;
    }

    .lightbox-close:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: scale(1.1);
    }

    .lightbox-prev,
    .lightbox-next {
        z-index: 10;
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: #fff;
        font-size: 1.25rem;
        transition: all 0.2s ease;
    }

    .lightbox-prev:hover,
    .lightbox-next:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: scale(1.1);
    }

    .lightbox-info {
        background: linear-gradient(transparent, rgba(0, 0, 0, 0.8));
    }

    .lightbox-counter {
        background: rgba(0, 0, 0, 0.5);
        padding: 0.5rem 1rem;
        border-radius: 2rem;
        font-size: 0.875rem;
    }

    /* ============================================
       RESPONSIVE STYLES
       ============================================ */

    @media (max-width: 576px) {
        .gallery-header {
            flex-direction: column;
            align-items: flex-start !important;
        }

        .gallery-filters {
            width: 100%;
            overflow-x: auto;
            padding-bottom: 0.5rem;
        }

        .gallery-filters .btn-group {
            flex-wrap: nowrap;
        }

        .gallery-filters .btn {
            white-space: nowrap;
        }

        .gallery-overlay {
            opacity: 1;
            background: linear-gradient(transparent 30%, rgba(0, 0, 0, 0.7) 100%);
        }

        .gallery-actions {
            opacity: 1;
            transform: translateY(0);
        }

        .lightbox-prev,
        .lightbox-next {
            width: 40px;
            height: 40px;
            font-size: 1rem;
        }

        .lightbox-content {
            max-width: 95%;
        }

        .lightbox-info {
            padding: 1rem;
        }

        .lightbox-download {
            display: none;
        }
    }

    @media (min-width: 577px) and (max-width: 768px) {
        .gallery-overlay {
            opacity: 1;
            pointer-events: auto;
        }

        .gallery-actions {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* ============================================
       ANIMATIONS
       ============================================ */

    @keyframes galleryFadeIn {
        from {
            opacity: 0;
            transform: scale(0.95);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .gallery-item {
        animation: galleryFadeIn 0.4s ease forwards;
    }

    .gallery-item.hidden {
        display: none;
    }

    /* Loading skeleton animation */
    @keyframes galleryPulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.5;
        }
    }

    .gallery-item.loading .gallery-placeholder {
        animation: galleryPulse 1.5s ease-in-out infinite;
    }

    /* ============================================
       DARK MODE
       ============================================ */

    [data-bs-theme="dark"] .gallery-card {
        background-color: #2e3344;
    }

    [data-bs-theme="dark"] .gallery-title {
        color: #dee2e6;
    }

    [data-bs-theme="dark"] .gallery-image-wrapper {
        background-color: #1a1d24;
    }

    [data-bs-theme="dark"] .gallery-info-name {
        color: #dee2e6;
    }

    /* ============================================
       ACCESSIBILITY
       ============================================ */

    .gallery-item:focus {
        outline: 2px solid var(--bs-primary);
        outline-offset: 2px;
    }

    .gallery-item:focus .gallery-overlay {
        opacity: 1;
    }

    @media (prefers-reduced-motion: reduce) {
        .gallery-card,
        .gallery-image,
        .gallery-overlay,
        .gallery-actions,
        .gallery-details,
        .gallery-btn,
        .lightbox-image {
            transition: none;
        }

        @keyframes galleryFadeIn {
            from { opacity: 1; transform: none; }
            to { opacity: 1; transform: none; }
        }
    }
</style>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const gallery = document.getElementById('{{ $galleryId }}');
        if (!gallery) return;

        const lightbox = document.getElementById('{{ $galleryId }}-lightbox');
        const lightboxImage = document.getElementById('{{ $galleryId }}-lightbox-image');
        const lightboxTitle = lightbox.querySelector('.lightbox-title');
        const lightboxMeta = lightbox.querySelector('.lightbox-meta');
        const lightboxDownload = lightbox.querySelector('.lightbox-download');
        const lightboxCurrent = lightbox.querySelector('.lightbox-current');
        const lightboxTotal = lightbox.querySelector('.lightbox-total');

        // Get all gallery items
        const items = gallery.querySelectorAll('.gallery-item:not(.hidden)');
        let currentIndex = 0;
        const totalItems = items.length;

        // Set total count
        if (lightboxTotal) {
            lightboxTotal.textContent = totalItems;
        }

        // Filter functionality
        const filterBtns = gallery.querySelectorAll('.filter-btn');
        filterBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const filter = this.dataset.filter;

                // Update active state
                filterBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                // Filter items
                items.forEach((item, index) => {
                    const category = item.dataset.category;
                    if (filter === 'semua' || category === filter) {
                        item.classList.remove('hidden');
                        item.style.animationDelay = `${index * 0.05}s`;
                    } else {
                        item.classList.add('hidden');
                    }
                });

                // Update lightbox total
                const visibleItems = gallery.querySelectorAll('.gallery-item:not(.hidden)');
                lightboxTotal.textContent = visibleItems.length;
            });
        });

        // Lightbox functions
        function openLightbox(index) {
            const visibleItems = Array.from(items).filter(item => !item.classList.contains('hidden'));
            currentIndex = visibleItems.findIndex(item => item === items[index]);

            const item = visibleItems[currentIndex];
            if (!item) return;

            const img = item.querySelector('.gallery-image');
            const imageWrapper = item.querySelector('.gallery-image-wrapper');

            // Get image sources
            const fullSrc = img.dataset.full || img.src;
            const title = img.alt || '';
            const fileSize = img.dataset.fileSize || '';
            const dimensions = img.dataset.dimensions || '';

            // Update lightbox content
            lightboxImage.src = fullSrc;
            lightboxImage.alt = title;

            if (lightboxTitle) lightboxTitle.textContent = title;
            if (lightboxMeta) {
                let metaText = [];
                if (dimensions) metaText.push(dimensions);
                if (fileSize) metaText.push(fileSize);
                lightboxMeta.textContent = metaText.join(' | ');
            }

            if (lightboxDownload) {
                lightboxDownload.href = fullSrc;
                lightboxDownload.download = title.replace(/\s+/g, '-').toLowerCase() + '.jpg';
            }

            // Update counter
            if (lightboxCurrent) {
                lightboxCurrent.textContent = currentIndex + 1;
            }

            // Show lightbox
            lightbox.classList.add('active');
            document.body.style.overflow = 'hidden';

            // Animate image
            setTimeout(() => {
                lightboxImage.classList.add('visible');
            }, 50);
        }

        function closeLightbox() {
            lightbox.classList.remove('active');
            document.body.style.overflow = '';
            lightboxImage.classList.remove('visible');
        }

        function navigateLightbox(direction) {
            const visibleItems = Array.from(items).filter(item => !item.classList.contains('hidden'));

            // Find current position in visible items
            const currentVisibleIndex = visibleItems.findIndex(item => item === items[currentIndex]);
            let newVisibleIndex = currentVisibleIndex + direction;

            // Loop or stop
            if (newVisibleIndex < 0) newVisibleIndex = visibleItems.length - 1;
            if (newVisibleIndex >= visibleItems.length) newVisibleIndex = 0;

            // Get the actual item index
            const newIndex = Array.from(items).indexOf(visibleItems[newVisibleIndex]);

            // Update image
            lightboxImage.classList.remove('visible');

            setTimeout(() => {
                openLightbox(newIndex);
            }, 200);
        }

        // Event listeners for images
        items.forEach((item, index) => {
            item.addEventListener('click', function(e) {
                if (!e.target.closest('.gallery-btn')) {
                    openLightbox(index);
                }
            });

            // Keyboard navigation
            item.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    openLightbox(index);
                }
            });
        });

        // Lightbox navigation
        lightbox.querySelector('.lightbox-close').addEventListener('click', closeLightbox);
        lightbox.querySelector('.lightbox-prev').addEventListener('click', () => navigateLightbox(-1));
        lightbox.querySelector('.lightbox-next').addEventListener('click', () => navigateLightbox(1));

        // Keyboard navigation for lightbox
        document.addEventListener('keydown', function(e) {
            if (!lightbox.classList.contains('active')) return;

            switch(e.key) {
                case 'Escape':
                    closeLightbox();
                    break;
                case 'ArrowLeft':
                    navigateLightbox(-1);
                    break;
                case 'ArrowRight':
                    navigateLightbox(1);
                    break;
            }
        });

        // Close on backdrop click
        lightbox.querySelector('.lightbox-backdrop').addEventListener('click', closeLightbox);

        // Touch swipe support
        let touchStartX = 0;
        let touchEndX = 0;

        lightbox.querySelector('.lightbox-container').addEventListener('touchstart', function(e) {
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });

        lightbox.querySelector('.lightbox-container').addEventListener('touchend', function(e) {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        }, { passive: true });

        function handleSwipe() {
            const swipeThreshold = 50;
            const diff = touchStartX - touchEndX;

            if (Math.abs(diff) > swipeThreshold) {
                if (diff > 0) {
                    navigateLightbox(1); // Swipe left - next
                } else {
                    navigateLightbox(-1); // Swipe right - prev
                }
            }
        }
    });
</script>
@endpush
