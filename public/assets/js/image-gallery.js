/**
 * Image Gallery Component - Ultra Lightweight Version
 * Full-featured gallery with interactive zoom and swipe functionality
 */

(function() {
    'use strict';

    // State management - minimal state
    const state = {
        galleries: new Map(),
        currentGallery: null,
        currentIndex: 0,
        zoom: 1,
        panX: 0,
        panY: 0,
        isOpen: false,
        isDragging: false,
        startX: 0,
        startY: 0,
        initialized: false
    };

    // DOM cache
    let elements = {};

    // Configuration
    const config = {
        zoomMin: 0.5,
        zoomMax: 5,
        zoomStep: 0.25,
        swipeThreshold: 50,
        preloadCount: 1  // Reduced for performance
    };

    // Debounce utility
    function debounce(fn, delay) {
        let timer;
        return function(...args) {
            clearTimeout(timer);
            timer = setTimeout(() => fn.apply(this, args), delay);
        };
    }

    /**
     * Initialize gallery - lazy initialization
     */
    function init() {
        if (state.initialized) return;
        state.initialized = true;

        // Create modal only when needed
        requestAnimationFrame(() => {
            createModal();
            bindEvents();
            scanGalleries();
        });
    }

    /**
     * Create modal HTML
     */
    function createModal() {
        if (document.getElementById('gallery-modal')) return;

        const modal = document.createElement('div');
        modal.id = 'gallery-modal';
        modal.className = 'gallery-modal';
        modal.innerHTML = `
            <div class="gallery-backdrop"></div>
            <div class="gallery-container">
                <div class="gallery-loading"><div class="gallery-spinner"></div></div>
                <button class="gallery-close" aria-label="Close">
                    <i class="ti ti-x"></i>
                </button>
                <button class="gallery-nav gallery-prev" aria-label="Previous">
                    <i class="ti ti-chevron-left"></i>
                </button>
                <button class="gallery-nav gallery-next" aria-label="Next">
                    <i class="ti ti-chevron-right"></i>
                </button>
                <div class="gallery-image-wrapper">
                    <div class="gallery-image-container">
                        <img id="gallery-current-image" src="" alt="" draggable="false">
                    </div>
                </div>
                <div class="gallery-controls">
                    <button class="gallery-ctrl-btn" data-action="zoom-out" title="Zoom Out (-)">
                        <i class="ti ti-minus"></i>
                    </button>
                    <span class="gallery-zoom-level">100%</span>
                    <button class="gallery-ctrl-btn" data-action="zoom-in" title="Zoom In (+)">
                        <i class="ti ti-plus"></i>
                    </button>
                    <button class="gallery-ctrl-btn" data-action="fit" title="Fit to Screen (Home)">
                        <i class="ti ti-arrows-maximize"></i>
                    </button>
                    <button class="gallery-ctrl-btn" data-action="reset" title="Reset View (0)">
                        <i class="ti ti-refresh"></i>
                    </button>
                    <button class="gallery-ctrl-btn" data-action="fullscreen" title="Fullscreen (F)">
                        <i class="ti ti-maximize"></i>
                    </button>
                </div>
                <div class="gallery-info">
                    <span class="gallery-counter">
                        <span id="gallery-current-index">1</span> / <span id="gallery-total">1</span>
                    </span>
                </div>
                <div class="gallery-thumbnails" id="gallery-thumbnails"></div>
                <div class="gallery-hand-cursor"></div>
            </div>
        `;

        document.body.appendChild(modal);
        cacheElements();
    }

    /**
     * Cache DOM elements
     */
    function cacheElements() {
        const modal = document.getElementById('gallery-modal');
        elements = {
            modal: modal,
            image: modal.querySelector('#gallery-current-image'),
            container: modal.querySelector('.gallery-container'),
            wrapper: modal.querySelector('.gallery-image-wrapper'),
            imageContainer: modal.querySelector('.gallery-image-container'),
            close: modal.querySelector('.gallery-close'),
            prev: modal.querySelector('.gallery-prev'),
            next: modal.querySelector('.gallery-next'),
            controls: modal.querySelector('.gallery-controls'),
            zoomLevel: modal.querySelector('.gallery-zoom-level'),
            counter: modal.querySelector('#gallery-current-index'),
            total: modal.querySelector('#gallery-total'),
            thumbnails: modal.querySelector('#gallery-thumbnails'),
            loading: modal.querySelector('.gallery-loading'),
            backdrop: modal.querySelector('.gallery-backdrop')
        };
    }

    /**
     * Bind events
     */
    function bindEvents() {
        // Close
        elements.close.addEventListener('click', close);
        elements.backdrop.addEventListener('click', close);

        // Navigation
        elements.prev.addEventListener('click', prevImage);
        elements.next.addEventListener('click', nextImage);

        // Controls
        elements.controls.addEventListener('click', function(e) {
            const btn = e.target.closest('.gallery-ctrl-btn');
            if (!btn) return;
            const action = btn.dataset.action;
            if (action === 'zoom-in') zoomIn();
            else if (action === 'zoom-out') zoomOut();
            else if (action === 'fit') fitToContainer();
            else if (action === 'reset') resetZoom();
            else if (action === 'fullscreen') toggleFullscreen();
        });

        // Mouse wheel zoom
        elements.wrapper.addEventListener('wheel', handleWheel, { passive: false });

        // Touch events
        elements.wrapper.addEventListener('touchstart', handleTouchStart, { passive: true });
        elements.wrapper.addEventListener('touchmove', handleTouchMove, { passive: false });
        elements.wrapper.addEventListener('touchend', handleTouchEnd, { passive: true });

        // Mouse drag for panning
        elements.wrapper.addEventListener('mousedown', handleMouseDown);
        document.addEventListener('mousemove', handleMouseMove);
        document.addEventListener('mouseup', handleMouseUp);

        // Click/double-click - zoom at pointer position
        let lastClick = 0;
        let clickStartX = 0;
        let clickStartY = 0;

        elements.wrapper.addEventListener('mousedown', function(e) {
            clickStartX = e.clientX;
            clickStartY = e.clientY;
        });

        elements.wrapper.addEventListener('click', function(e) {
            // Don't trigger zoom if user was dragging
            const moveDist = Math.abs(e.clientX - clickStartX) + Math.abs(e.clientY - clickStartY);
            if (moveDist > 10) return;

            const now = Date.now();
            if (now - lastClick < 300) {
                // Double-click: zoom at pointer position
                toggleZoom(e);
            }
            lastClick = now;
        });

        // Keyboard
        document.addEventListener('keydown', handleKeydown);
    }

    /**
     * Scan for galleries - optimized with intersection observer
     */
    function scanGalleries() {
        // Use MutationObserver to detect new galleries dynamically
        const observer = new MutationObserver(debounce(() => {
            document.querySelectorAll('[data-gallery]:not([data-scanned])').forEach(container => {
                container.setAttribute('data-scanned', 'true');
                registerGallery(container);
            });
        }, 100));

        // Observe document body for changes
        observer.observe(document.body, {
            childList: true,
            subtree: true,
            attributes: true,
            attributeFilter: ['data-gallery']
        });

        // Initial scan
        document.querySelectorAll('[data-gallery]').forEach(container => {
            if (!container.hasAttribute('data-scanned')) {
                container.setAttribute('data-scanned', 'true');
                registerGallery(container);
            }
        });
    }

    /**
     * Register a single gallery container
     */
    function registerGallery(container) {
        if (state.galleries.has(container)) return;

        const id = container.dataset.gallery;
        const links = container.querySelectorAll('a[data-full-image]');
        const images = [];

        links.forEach((link, index) => {
            images.push({
                thumb: link.querySelector('img')?.src || link.href,
                full: link.dataset.fullImage || link.href,
                title: link.dataset.title || ''
            });

            // Use event delegation - don't add listener to each link
            link.addEventListener('click', function(e) {
                e.preventDefault();
                open(id, index);
            });
        });

        if (images.length) {
            state.galleries.set(id, { element: container, images });
        }
    }

    /**
     * Open gallery
     */
    function open(galleryId, index) {
        const gallery = state.galleries.get(galleryId);
        if (!gallery) return;

        state.currentGallery = gallery;
        state.currentIndex = Math.max(0, Math.min(index, gallery.images.length - 1));
        state.isOpen = true;
        state.zoom = 1;
        state.panX = 0;
        state.panY = 0;

        // Show modal first for perceived performance
        elements.modal.classList.add('active');
        elements.modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';

        // Load UI and preload in background
        updateUI();
        // Defer preloading
        setTimeout(() => preloadImages(), 100);
    }

    /**
     * Close gallery
     */
    function close() {
        if (!state.isOpen) return;
        state.isOpen = false;

        elements.modal.classList.remove('active');
        elements.modal.setAttribute('aria-hidden', 'false'); // Keep accessible
        document.body.style.overflow = '';

        // Clear image to free memory
        elements.image.src = '';
        state.currentGallery = null;
    }

    /**
     * Update UI - optimized
     */
    function updateUI() {
        if (!state.currentGallery) return;

        const image = state.currentGallery.images[state.currentIndex];
        if (!image) return;

        // Show loading briefly
        showLoading(true);

        // Use decode() for better performance
        const img = elements.image;
        img.src = image.full;

        img.onload = function() {
            showLoading(false);
            requestAnimationFrame(() => fitToContainer());
        };

        img.onerror = function() {
            showLoading(false);
        };

        // Update counter
        elements.counter.textContent = state.currentIndex + 1;
        elements.total.textContent = state.currentGallery.images.length;

        // Update zoom display
        elements.zoomLevel.textContent = '100%';

        // Nav buttons - minimal update
        elements.prev.style.opacity = state.currentIndex === 0 ? '0.3' : '1';
        elements.next.style.opacity = state.currentIndex === state.currentGallery.images.length - 1 ? '0.3' : '1';

        // Generate thumbnails on demand
        generateThumbnails();
    }

    /**
     * Fit image to container - optimized
     */
    function fitToContainer() {
        const container = elements.wrapper;
        const img = elements.image;

        if (!container || !img || !img.naturalWidth) return;

        // Use client dimensions directly for speed
        const containerRect = container.getBoundingClientRect();
        const containerW = containerRect.width;
        const containerH = containerRect.height;

        if (!containerW || !containerH) return;

        const imgRatio = img.naturalWidth / img.naturalHeight;
        const containerRatio = containerW / containerH;

        if (imgRatio > containerRatio) {
            state.zoom = containerW / img.naturalWidth;
        } else {
            state.zoom = containerH / img.naturalHeight;
        }

        state.zoom = Math.min(state.zoom, config.zoomMax);
        applyTransform();
    }

    /**
     * Generate thumbnails - optimized with caching
     */
    let thumbnailsCache = {};

    function generateThumbnails() {
        if (!state.currentGallery) return;

        const galleryId = state.currentGallery.element.dataset.gallery;
        const cacheKey = `${galleryId}-${state.currentIndex}`;

        // Skip if already rendered for current gallery
        if (thumbnailsCache[cacheKey]) return;

        elements.thumbnails.innerHTML = '';
        state.currentGallery.images.forEach((image, index) => {
            const btn = document.createElement('button');
            btn.className = 'gallery-thumbnail' + (index === state.currentIndex ? ' active' : '');
            btn.innerHTML = `<img src="${image.thumb}" alt="" loading="lazy">`;
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                state.currentIndex = index;
                // Clear cache to force regeneration
                thumbnailsCache = {};
                updateUI();
                preloadImages();
            });
            elements.thumbnails.appendChild(btn);
        });

        // Mark as cached
        thumbnailsCache[cacheKey] = true;
    }

    /**
     * Preload adjacent images - optimized
     */
    function preloadImages() {
        if (!state.currentGallery) return;

        // Only preload immediate neighbors (reduced from 2 to 1)
        const toPreload = [];
        if (state.currentIndex > 0) {
            toPreload.push(state.currentGallery.images[state.currentIndex - 1].full);
        }
        if (state.currentIndex < state.currentGallery.images.length - 1) {
            toPreload.push(state.currentGallery.images[state.currentIndex + 1].full);
        }

        // Use link preload for browser optimization
        toPreload.forEach(src => {
            const link = document.createElement('link');
            link.rel = 'prefetch';
            link.as = 'image';
            link.href = src;
            document.head.appendChild(link);
        });
    }

    /**
     * Navigation
     */
    function prevImage() {
        if (state.currentIndex > 0) {
            state.currentIndex--;
            updateUI();
            preloadImages();
        }
    }

    function nextImage() {
        if (state.currentGallery && state.currentIndex < state.currentGallery.images.length - 1) {
            state.currentIndex++;
            updateUI();
            preloadImages();
        }
    }

    /**
     * Zoom functions - optimized with RAF
     */
    let rafId = null;

    function zoomIn() {
        state.zoom = Math.min(config.zoomMax, state.zoom + config.zoomStep);
        scheduleTransform();
    }

    function zoomOut() {
        state.zoom = Math.max(config.zoomMin, state.zoom - config.zoomStep);
        scheduleTransform();
    }

    function zoomBy(delta) {
        state.zoom = Math.max(config.zoomMin, Math.min(config.zoomMax, state.zoom + delta));
        scheduleTransform();
    }

    function scheduleTransform() {
        if (rafId) return;
        rafId = requestAnimationFrame(() => {
            applyTransform();
            rafId = null;
        });
    }

    function applyTransform() {
        state.zoom = Math.round(state.zoom * 100) / 100;
        elements.image.style.transform = `translate(${state.panX}px, ${state.panY}px) scale(${state.zoom})`;
        elements.zoomLevel.textContent = Math.round(state.zoom * 100) + '%';

        // Update cursor state - only when crossing threshold
        const isZoomed = state.zoom > 1;
        if (isZoomed !== (elements.wrapper.getAttribute('data-zoom') === 'true')) {
            elements.wrapper.style.cursor = isZoomed ? 'grab' : 'zoom-in';
            elements.wrapper.setAttribute('data-zoom', isZoomed ? 'true' : 'false');
        }
    }

    function resetZoom() {
        state.zoom = 1;
        state.panX = 0;
        state.panY = 0;
        applyTransform();
    }

    /**
     * Toggle zoom at mouse pointer position (point zoom)
     */
    function toggleZoom(e) {
        if (!e) {
            // Fallback if no event
            if (state.zoom > 1) {
                resetZoom();
            } else {
                state.zoom = Math.min(config.zoomMax, state.zoom * 2);
                applyTransform();
            }
            return;
        }

        const img = elements.image;
        const rect = img.getBoundingClientRect();

        // Get click position relative to image
        const clickX = e.clientX - rect.left;
        const clickY = e.clientY - rect.top;

        // Get image center relative position (0 to 1)
        const relX = clickX / rect.width;
        const relY = clickY / rect.height;

        if (state.zoom > 1) {
            // Zoom out - reset
            resetZoom();
        } else {
            // Zoom in at click point
            const oldZoom = state.zoom;
            state.zoom = Math.min(config.zoomMax, state.zoom * 2);

            // Calculate pan to keep click point in same screen position
            const zoomDelta = state.zoom - oldZoom;

            // Center the zoom on the click point
            const imgCenterX = rect.width / 2;
            const imgCenterY = rect.height / 2;

            // Pan offset to keep click point centered
            state.panX = (imgCenterX - clickX) * (state.zoom - 1);
            state.panY = (imgCenterY - clickY) * (state.zoom - 1);

            applyTransform();
        }
    }

    function updateZoomDisplay() {
        elements.zoomLevel.textContent = Math.round(state.zoom * 100) + '%';
    }

    /**
     * Mouse drag handlers
     */
    function handleMouseDown(e) {
        if (state.zoom > 1) {
            state.isDragging = true;
            state.startX = e.clientX - state.panX;
            state.startY = e.clientY - state.panY;
            elements.wrapper.style.cursor = 'grabbing';
        }
    }

    function handleMouseMove(e) {
        if (state.isDragging) {
            state.panX = e.clientX - state.startX;
            state.panY = e.clientY - state.startY;
            applyTransform();
        }
    }

    function handleMouseUp() {
        state.isDragging = false;
        if (state.zoom > 1) {
            elements.wrapper.style.cursor = 'grab';
        }
    }

    /**
     * Touch handlers
     */
    function handleTouchStart(e) {
        if (e.touches.length === 1) {
            state.startX = e.touches[0].clientX;
            state.touchStartTime = Date.now();
        }
    }

    function handleTouchMove(e) {
        if (e.touches.length === 1 && state.zoom > 1) {
            e.preventDefault();
            const diff = e.touches[0].clientX - state.startX;
            state.panX = diff;
            applyTransform();
        }
    }

    function handleTouchEnd(e) {
        const duration = Date.now() - state.touchStartTime;
        const diff = state.startX - e.changedTouches[0].clientX;

        if (duration < 500 && Math.abs(diff) > config.swipeThreshold) {
            if (diff > 0) nextImage();
            else prevImage();
        }
    }

    /**
     * Wheel handler
     */
    function handleWheel(e) {
        e.preventDefault();
        const delta = e.deltaY > 0 ? -config.zoomStep : config.zoomStep;
        zoomBy(delta);
    }

    /**
     * Keyboard handler
     */
    function handleKeydown(e) {
        if (!state.isOpen) return;

        switch(e.key) {
            case 'Escape': close(); break;
            case 'ArrowLeft': prevImage(); break;
            case 'ArrowRight': nextImage(); break;
            case '+': case '=': zoomIn(); break;
            case '-': zoomOut(); break;
            case '0': case 'Home': fitToContainer(); break;
            case 'r': resetZoom(); break;
            case 'f': toggleFullscreen(); break;
        }
    }

    /**
     * Fullscreen toggle
     */
    function toggleFullscreen() {
        if (document.fullscreenElement) {
            document.exitFullscreen();
            elements.container.classList.remove('fullscreen');
        } else {
            elements.container.requestFullscreen().catch(function() {});
            elements.container.classList.add('fullscreen');
        }
    }

    /**
     * Loading state
     */
    function showLoading(show) {
        elements.loading.classList.toggle('active', show);
    }

    // Initialize
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    // Expose globally
    window.ImageGallery = {
        open: function(id, index) { open(id, index); },
        close: close
    };

})();
