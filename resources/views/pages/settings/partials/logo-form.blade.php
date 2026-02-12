@php
    $logoType = $type;
    $typeName = $typeName ?? ucfirst($type);
    $logo = $logo ?? null;
    $hasCustom = $logo && !empty($logo->image_url);
    $currentUrl = $hasCustom ? $logo->getUrl() : ($logo ? $logo->getDefaultUrl() : asset('assets/images/logo.png'));
@endphp

<form action="{{ route('settings.logo.update', $logoType) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row">
        <!-- Logo Preview -->
        <div class="col-md-4 mb-3">
            <label class="form-label">Preview Logo</label>
            <div class="logo-preview-card card border">
                <div class="card-body p-3">
                    <div class="logo-preview mb-2" id="preview-{{ $logoType }}">
                        <img src="{{ $currentUrl }}" alt="{{ $typeName }} Logo"
                             style="max-width: 100%; max-height: 70px; object-fit: contain;">
                    </div>
                    @if($hasCustom)
                        <span class="badge bg-success mb-2">
                            <i class="ti ti-check me-1"></i>Logo Kustom
                        </span>
                    @else
                        <span class="badge bg-secondary mb-2">
                            <i class="ti ti-photo me-1"></i>Logo Default
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Upload Settings -->
        <div class="col-md-8">
            <div class="mb-3">
                <label class="form-label">Upload Logo</label>
                <input type="file"
                       class="form-control @error('logo') is-invalid @enderror"
                       name="logo"
                       accept=".png,.jpg,.jpeg,.svg,.ico,.gif,.webp"
                       onchange="previewLogo(this, 'preview-img-{{ $logoType }}')">
                <small class="text-muted">
                    Format: PNG, JPG, SVG, ICO, GIF, WEBP. Maksimal: 2MB
                </small>
                @error('logo')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Nama Logo</label>
                <input type="text" class="form-control" name="name"
                       value="{{ old('name', $logo->name ?? LogoSetting::DEFAULT_LOGOS[$logoType]['name'] ?? ucfirst($logoType) . ' Logo') }}">
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Lebar (px)</label>
                    <input type="number" class="form-control" name="width"
                           value="{{ old('width', $logo->width ?? '') }}"
                           min="16" max="500" placeholder="Auto">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Tinggi (px)</label>
                    <input type="number" class="form-control" name="height"
                           value="{{ old('height', $logo->height ?? '') }}"
                           min="16" max="500" placeholder="Auto">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Alignment</label>
                    <select class="form-select" name="alignment">
                        <option value="left" {{ (old('alignment', $logo->alignment ?? '') == 'left') ? 'selected' : '' }}>
                            Kiri
                        </option>
                        <option value="center" {{ (old('alignment', $logo->alignment ?? '') == 'center') ? 'selected' : '' }}>
                            Tengah
                        </option>
                        <option value="right" {{ (old('alignment', $logo->alignment ?? '') == 'right') ? 'selected' : '' }}>
                            Kanan
                        </option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Custom CSS (Opsional)</label>
                <textarea class="form-control" name="custom_css" rows="2"
                          placeholder="Contoh: padding: 10px; background: transparent;">{{ old('custom_css', $logo->custom_css ?? '') }}</textarea>
            </div>

            <div class="form-check form-switch mb-3">
                <input class="form-check-input" type="checkbox" name="is_active" value="1"
                       id="active-{{ $logoType }}"
                       {{ old('is_active', $logo->is_active ?? true) ? 'checked' : '' }}>
                <label class="form-check-label" for="active-{{ $logoType }}">
                    Aktifkan logo ini
                </label>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="ti ti-check me-1"></i>Simpan
                </button>

                @if($hasCustom)
                    <a href="{{ route('settings.logo.reset', $logoType) }}"
                       class="btn btn-outline-warning"
                       onclick="return confirm('Apakah Anda yakin ingin mengembalikan ke logo default?')">
                        <i class="ti ti-refresh me-1"></i>Reset ke Default
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Hidden preview image element -->
    <img id="preview-img-{{ $logoType }}" src="" alt="Preview"
         style="display: none; max-width: 100%; max-height: 70px; object-fit: contain; margin-top: 10px;">
</form>

<style>
    .logo-preview-card {
        transition: all 0.3s ease;
    }
    .logo-preview {
        min-height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8f9fa;
        border-radius: 6px;
    }
</style>
