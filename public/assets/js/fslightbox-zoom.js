/**
 * FsLightbox Custom Zoom Buttons Extension
 * Adds zoom in/out buttons to fsLightbox gallery
 *
 * Usage: Include this script after fslightbox
 */

(function() {
    'use strict';

    // Create custom CSS for zoom buttons
    const style = document.createElement('style');
    style.textContent = `
        /* Custom Zoom Controls for FsLightbox */
        .fslightbox-booted .custom-zoom-controls {
            position: absolute;
            bottom: 80px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 8px;
            z-index: 100;
            background: rgba(0, 0, 0, 0.7);
            padding: 8px 12px;
            border-radius: 25px;
        }

        .fslightbox-booted .custom-zoom-controls button {
            background: transparent;
            border: none;
            color: white;
            cursor: pointer;
            padding: 6px 10px;
            border-radius: 4px;
            font-size: 16px;
            transition: background 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .fslightbox-booted .custom-zoom-controls button:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .fslightbox-booted .custom-zoom-controls button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .fslightbox-booted .zoom-level-indicator {
            color: white;
            font-size: 12px;
            padding: 6px 8px;
            font-family: system-ui, sans-serif;
        }

        /* Zoomed image container */
        .fslightbox-slide-wrapper-inner img {
            transition: transform 0.2s ease-out;
            transform-origin: center center;
        }
    `;
    document.head.appendChild(style);

    // Zoom state
    let currentZoom = 1;
    const ZOOM_MIN = 0.5;
    const ZOOM_MAX = 5;
    const ZOOM_STEP = 0.25;

    /**
     * Create zoom control buttons
     */
    function createZoomControls() {
        const controls = document.createElement('div');
        controls.className = 'custom-zoom-controls';
        controls.innerHTML = `
            <button class="zoom-out" title="Zoom Out (-)">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    <line x1="8" y1="11" x2="14" y2="11"></line>
                </svg>
            </button>
            <span class="zoom-level-indicator">100%</span>
            <button class="zoom-in" title="Zoom In (+)">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    <line x1="11" y1="8" x2="11" y2="14"></line>
                    <line x1="8" y1="11" x2="14" y2="11"></line>
                </svg>
            </button>
            <button class="zoom-reset" title="Reset (0)">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="1 4 1 10 7 10"></polyline>
                    <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path>
                </svg>
            </button>
        `;
        return controls;
    }

    /**
     * Apply zoom to current slide image
     */
    function applyZoom(container, zoomLevel) {
        const img = container.querySelector('.fslightbox-slide-wrapper-inner img');
        if (img) {
            img.style.transform = `scale(${zoomLevel})`;
            currentZoom = zoomLevel;
            updateZoomIndicator(container);
        }
    }

    /**
     * Update zoom level indicator
     */
    function updateZoomIndicator(container) {
        const indicator = container.querySelector('.zoom-level-indicator');
        if (indicator) {
            indicator.textContent = Math.round(currentZoom * 100) + '%';
        }

        // Update button states
        const zoomInBtn = container.querySelector('.zoom-in');
        const zoomOutBtn = container.querySelector('.zoom-out');

        if (zoomInBtn) zoomInBtn.disabled = currentZoom >= ZOOM_MAX;
        if (zoomOutBtn) zoomOutBtn.disabled = currentZoom <= ZOOM_MIN;
    }

    /**
     * Inject zoom controls into fslightbox
     */
    function injectZoomControls() {
        // Wait for fslightbox to be ready
        if (typeof FsLightbox === 'undefined') {
            setTimeout(injectZoomControls, 100);
            return;
        }

        // Store original fslightbox
        const OriginalFsLightbox = FsLightbox;

        // Override constructor
        window.FsLightbox = function(props) {
            const instance = new OriginalFsLightbox(props);

            // Add custom properties for zoom
            instance.customZoom = 1;
            instance.zoomControlsInjected = false;

            // Hook into aftermount
            const originalOnMount = instance.onMount;
            instance.onMount = function() {
                if (originalOnMount) originalOnMount.call(this);

                // Inject zoom controls
                this.injectZoomControlsOnce();
            };

            return instance;
        };

        // Also handle existing instances
        document.addEventListener('DOMContentLoaded', function() {
            // For any already initialized fslightbox
            observeFsLightboxOpen();
        });
    }

    /**
     * Inject zoom controls once
     */
    function injectZoomControlsOnce(container) {
        if (this.zoomControlsInjected) return;

        // Find the slide container
        const slideWrapper = document.querySelector('.fslightbox-slide-wrapper');
        if (!slideWrapper) {
            setTimeout(() => this.injectZoomControlsOnce(), 100);
            return;
        }

        // Check if controls already exist
        if (slideWrapper.querySelector('.custom-zoom-controls')) return;

        // Create and inject controls
        const controls = createZoomControls();
        slideWrapper.appendChild(controls);
        this.zoomControlsInjected = true;

        // Add event listeners
        const zoomInBtn = controls.querySelector('.zoom-in');
        const zoomOutBtn = controls.querySelector('.zoom-out');
        const zoomResetBtn = controls.querySelector('.zoom-reset');

        const handleZoom = (direction) => {
            const newZoom = direction === 'in'
                ? Math.min(currentZoom + ZOOM_STEP, ZOOM_MAX)
                : Math.max(currentZoom - ZOOM_STEP, ZOOM_MIN);
            applyZoom(slideWrapper, newZoom);
        };

        zoomInBtn.addEventListener('click', () => handleZoom('in'));
        zoomOutBtn.addEventListener('click', () => handleZoom('out'));
        zoomResetBtn.addEventListener('click', () => applyZoom(slideWrapper, 1));

        // Reset zoom when slide changes
        slideWrapper.addEventListener('click', function(e) {
            if (e.target === slideWrapper) {
                applyZoom(slideWrapper, 1);
            }
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            if (!document.querySelector('.fslightbox-container')) return;

            if (e.key === '+' || e.key === '=') {
                handleZoom('in');
            } else if (e.key === '-') {
                handleZoom('out');
            } else if (e.key === '0') {
                applyZoom(slideWrapper, 1);
            }
        });

        // Add mouse wheel zoom
        slideWrapper.addEventListener('wheel', function(e) {
            e.preventDefault();
            const direction = e.deltaY > 0 ? 'out' : 'in';
            handleZoom(direction);
        }, { passive: false });
    }

    /**
     * Observe when fslightbox opens
     */
    function observeFsLightboxOpen() {
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                mutation.addedNodes.forEach(function(node) {
                    if (node.nodeType === 1 && node.classList &&
                        node.classList.contains('fslightbox-container')) {

                        // Add zoom controls to new instance
                        setTimeout(() => {
                            const slideWrapper = node.querySelector('.fslightbox-slide-wrapper');
                            if (slideWrapper && !slideWrapper.querySelector('.custom-zoom-controls')) {
                                const controls = createZoomControls();
                                slideWrapper.appendChild(controls);

                                // Add event listeners
                                const zoomInBtn = controls.querySelector('.zoom-in');
                                const zoomOutBtn = controls.querySelector('.zoom-out');
                                const zoomResetBtn = controls.querySelector('.zoom-reset');

                                zoomInBtn.addEventListener('click', () => {
                                    const newZoom = Math.min(currentZoom + ZOOM_STEP, ZOOM_MAX);
                                    applyZoom(slideWrapper, newZoom);
                                });

                                zoomOutBtn.addEventListener('click', () => {
                                    const newZoom = Math.max(currentZoom - ZOOM_STEP, ZOOM_MIN);
                                    applyZoom(slideWrapper, newZoom);
                                });

                                zoomResetBtn.addEventListener('click', () => applyZoom(slideWrapper, 1));

                                // Mouse wheel zoom
                                slideWrapper.addEventListener('wheel', function(e) {
                                    e.preventDefault();
                                    const direction = e.deltaY > 0 ? 'out' : 'in';
                                    const newZoom = direction === 'in'
                                        ? Math.min(currentZoom + ZOOM_STEP, ZOOM_MAX)
                                        : Math.max(currentZoom - ZOOM_STEP, ZOOM_MIN);
                                    applyZoom(slideWrapper, newZoom);
                                }, { passive: false });
                            }
                        }, 500);
                    }
                });
            });
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    }

    // Initialize
    injectZoomControls();
    observeFsLightboxOpen();

    // Auto-initialize for any existing data-fslightbox elements
    document.addEventListener('click', function(e) {
        const fsLightboxLink = e.target.closest('[data-fslightbox]');
        if (fsLightboxLink) {
            setTimeout(observeFsLightboxOpen, 1000);
        }
    });

})();
