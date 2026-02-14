# Image Gallery Component Documentation

## Overview

A full-featured image gallery component with interactive zoom and swipe functionality for the detail page. The gallery supports pinch-to-zoom on touch devices, scroll wheel zoom on desktop, and touch swipe gestures for navigating between images.

## Files

- **JavaScript**: `public/assets/js/image-gallery.js`
- **CSS**: `public/assets/css/product-gallery.css` (includes gallery modal styles)
- **Integration**: `resources/views/pages/ikm/detile.blade.php`

## Features

### 1. Interactive Zoom
- **Pinch-to-zoom** on touch devices (pinch with two fingers)
- **Scroll wheel zoom** on desktop
- **Double-tap zoom** to toggle between zoom levels
- **Single-tap** to show/hide controls
- Configurable zoom levels (min: 0.5x, max: 5x, default step: 0.25x)
- Smooth zoom-in and zoom-out transitions

### 2. Swipe Navigation
- **Touch swipe** left/right to navigate between images
- Works in both **portrait** and **landscape** orientations
- Visual swipe indicators during navigation
- Swipe threshold detection (50px)

### 3. Responsive Design
- Adapts to different screen sizes
- Mobile-first approach
- Touch-friendly controls
- Thumbnail strip for quick navigation

### 4. Visual Feedback
- Loading spinner during image loading
- Zoom level indicator
- Image counter (e.g., "1 / 5")
- Swipe indicators
- Smooth animations for all interactions

### 5. Loading States & Preloading
- Loading spinner while image loads
- Adjacent images preloaded (2 by default)
- Lazy loading for thumbnails

### 6. Controls
- Zoom in/out buttons
- Reset zoom button
- Fullscreen toggle
- Thumbnail navigation strip
- Keyboard navigation

## Usage

### Basic HTML Structure

```html
<!-- Gallery Container -->
<div class="design-gallery" data-gallery="gallery-name">
    @foreach($images as $image)
    <div class="gallery-item">
        <div class="gallery-thumb">
            <a href="{{ $image->url }}"
               data-full-image="{{ $image->fullUrl }}"
               data-title="{{ $image->title }}">
                <img src="{{ $image->thumbnailUrl }}" loading="lazy">
            </a>
            <div class="gallery-actions">
                <a href="{{ $image->fullUrl }}"
                   data-full-image="{{ $image->fullUrl }}"
                   class="action-btn"
                   title="Preview">
                    <i class="ti ti-eye"></i>
                </a>
            </div>
        </div>
    </div>
    @endforeach
</div>
```

### Attributes

| Attribute | Description | Required |
|-----------|-------------|----------|
| `data-gallery` | Unique gallery identifier | Yes |
| `data-full-image` | URL of full-resolution image | Yes |
| `data-title` | Optional title for the image | No |

### Keyboard Shortcuts

| Key | Action |
|-----|--------|
| `Escape` | Close gallery |
| `Arrow Left` | Previous image |
| `Arrow Right` | Next image |
| `+` / `=` | Zoom in |
| `-` | Zoom out |
| `0` | Reset zoom |
| `f` | Toggle fullscreen |

### Configuration Options

```javascript
const gallery = new ImageGallery({
    zoomMin: 0.5,        // Minimum zoom level
    zoomMax: 5,          // Maximum zoom level
    zoomStep: 0.25,      // Zoom increment
    zoomDuration: 300,   // Animation duration in ms
    swipeThreshold: 50,  // Pixels to trigger swipe
    doubleTapDelay: 300, // ms between taps
    preloadCount: 2      // Images to preload
});
```

## Gallery Sections (Benchmark, Desain, Dokumentasi)

The detail page includes three gallery sections:

### 1. Benchmark Produk Gallery
- Identifier: `benchmark-gallery`
- Purpose: Upload referensi produk pesaing sebagai bahan perbandingan
- Location: Sidebar section

### 2. Desain Produk Gallery
- Identifier: `design-gallery`
- Purpose: Desain kemasan atau produk
- Location: Sidebar section

### 3. Dokumentasi Cots Gallery
- Identifier: `dokumentasi-gallery`
- Purpose: Foto dokumentasi kegiatan
- Location: Sidebar section

## Browser Support

- Chrome 60+
- Firefox 55+
- Safari 12+
- Edge 79+
- iOS Safari 12+
- Android Chrome 80+

## Accessibility

- Keyboard navigation support
- ARIA labels on all controls
- Focus management
- Reduced motion support via `prefers-reduced-motion`
- Screen reader compatible

## Dark Mode Support

The gallery automatically adapts to dark mode with:
- Dark background overlay
- Light-colored controls
- Contrast-optimized indicators

## Performance Considerations

- Images are preloaded for adjacent images
- Thumbnail strip uses lazy loading
- CSS animations use GPU acceleration
- Minimal reflows during zoom operations

## Troubleshooting

### Gallery not opening
- Ensure `data-gallery` attribute is set on container
- Verify `data-full-image` attributes are present on links
- Check browser console for JavaScript errors

### Zoom not working
- Verify touch events are not blocked
- Check that images are fully loaded
- Ensure container has proper dimensions

### Swipe not working
- Verify touch-action CSS is not overridden
- Check that element is not in an iframe
- Ensure swipe threshold is not too high
