@extends('layouts.master')

@section('title', 'Branding & Identitas | Database INOPAK')

@section('styles')
<style>
    .branding-preview {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        min-height: 120px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .branding-preview img {
        max-width: 100%;
        max-height: 80px;
        object-fit: contain;
    }
    .setting-group {
        margin-bottom: 2rem;
        padding-bottom: 2rem;
        border-bottom: 1px solid #e9ecef;
    }
    .setting-group:last-child {
        border-bottom: none;
    }
    .nav-tabs .nav-link.active {
        font-weight: 600;
    }
    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 26px;
    }
    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 26px;
    }
    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 20px;
        width: 20px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }
    input:checked + .toggle-slider {
        background-color: #435ebe;
    }
    input:checked + .toggle-slider:before {
        transform: translateX(24px);
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">
                    <i class="ti ti-palette me-2"></i>
                    Branding & Identitas Aplikasi
                </h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="/settings">Pengaturan</a></li>
                        <li class="breadcrumb-item active">Branding</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#logos" role="tab">
                                <i class="ti ti-photo me-1"></i> Logo & Gambar
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#texts" role="tab">
                                <i class="ti ti-text-size me-1"></i> Teks & Metadata
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#toggles" role="tab">
                                <i class="ti ti-switch me-1"></i> Pengaturan Umum
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#preview" role="tab">
                                <i class="ti ti-eye me-1"></i> Preview
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <!-- Logos Tab -->
                        <div class="tab-pane fade show active" id="logos" role="tabpanel">
                            <form action="{{ route('settings.branding.image', 'logo') }}" method="POST" enctype="multipart/form-data" class="setting-group">
                                @csrf @method('PUT')
                                <div class="row">
                                    <div class="col-md-3">
                                        <h6>Logo Utama</h6>
                                        <p class="text-muted small">Logo yang ditampilkan di header aplikasi</p>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="branding-preview">
                                            @php $logo = $logos['header'] ?? null; @endphp
                                            <img src="{{ $logo ? $logo->getUrl() : asset('assets/images/logo.png') }}" alt="Logo Utama">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Upload Logo</label>
                                            <input type="file" class="form-control" name="image" accept=".png,.jpg,.jpeg,.svg,.ico,.gif,.webp">
                                            <small class="text-muted">Format: PNG, JPG, SVG. Max: 2MB</small>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <label class="form-label">Lebar (px)</label>
                                                <input type="number" class="form-control" name="width" value="{{ $logo->width ?? '' }}" min="32" max="500">
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label">Tinggi (px)</label>
                                                <input type="number" class="form-control" name="height" value="{{ $logo->height ?? '' }}" min="32" max="500">
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary mt-3">
                                            <i class="ti ti-check me-1"></i> Simpan
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <form action="{{ route('settings.branding.image', 'favicon') }}" method="POST" enctype="multipart/form-data" class="setting-group">
                                @csrf @method('PUT')
                                <div class="row">
                                    <div class="col-md-3">
                                        <h6>Favicon</h6>
                                        <p class="text-muted small">Ikon yang tampil di tab browser</p>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="branding-preview">
                                            @php $favicon = $logos['favicon'] ?? null; @endphp
                                            <img src="{{ $favicon ? $favicon->getUrl() : asset('assets/images/favicon.ico') }}" alt="Favicon">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Upload Favicon</label>
                                            <input type="file" class="form-control" name="image" accept=".png,.jpg,.jpeg,.ico,.svg,.webp">
                                            <small class="text-muted">Format: PNG, JPG, ICO, SVG. Max: 2MB</small>
                                        </div>
                                        <button type="submit" class="btn btn-primary mt-3">
                                            <i class="ti ti-check me-1"></i> Simpan
                                        </button>
                                        @if($favicon && !empty($favicon->image_url))
                                            <a href="{{ route('settings.logo.reset', 'favicon') }}" class="btn btn-outline-warning mt-3 ms-2">
                                                <i class="ti ti-refresh me-1"></i> Reset ke Default
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </form>

                            <form action="{{ route('settings.branding.image', 'login') }}" method="POST" enctype="multipart/form-data" class="setting-group">
                                @csrf @method('PUT')
                                <div class="row">
                                    <div class="col-md-3">
                                        <h6>Logo Login</h6>
                                        <p class="text-muted small">Logo yang tampil di halaman login</p>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="branding-preview">
                                            @php $loginLogo = $logos['login'] ?? null; @endphp
                                            <img src="{{ $loginLogo ? $loginLogo->getUrl() : asset('assets/images/inopak/fav.png') }}" alt="Logo Login">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Upload Logo Login</label>
                                            <input type="file" class="form-control" name="image" accept=".png,.jpg,.jpeg,.svg,.gif,.webp">
                                        </div>
                                        <button type="submit" class="btn btn-primary mt-3">
                                            <i class="ti ti-check me-1"></i> Simpan
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <form action="{{ route('settings.branding.image', 'company_logo') }}" method="POST" enctype="multipart/form-data" class="setting-group">
                                @csrf @method('PUT')
                                <div class="row">
                                    <div class="col-md-3">
                                        <h6>Logo Perusahaan</h6>
                                        <p class="text-muted small">Logo untuk footer dan laporan</p>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="branding-preview">
                                            @php $companyLogo = $logos['company_logo'] ?? null; @endphp
                                            <img src="{{ $companyLogo ? $companyLogo->getUrl() : asset('assets/images/logo.png') }}" alt="Logo Perusahaan">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Upload Logo Perusahaan</label>
                                            <input type="file" class="form-control" name="image" accept=".png,.jpg,.jpeg,.svg,.gif,.webp">
                                        </div>
                                        <button type="submit" class="btn btn-primary mt-3">
                                            <i class="ti ti-check me-1"></i> Simpan
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Texts Tab -->
                        <div class="tab-pane fade" id="texts" role="tabpanel">
                            <form action="{{ route('settings.branding.text', 'app_name') }}" method="POST" class="setting-group">
                                @csrf @method('PUT')
                                <div class="row align-items-center">
                                    <div class="col-md-4">
                                        <h6 class="mb-0">Nama Aplikasi</h6>
                                        <p class="text-muted small mb-0">Nama yang tampil di browser dan header</p>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="value" value="{{ $appName ?? 'Database INOPAK' }}">
                                        <button type="submit" class="btn btn-primary mt-3">
                                            <i class="ti ti-check me-1"></i> Simpan
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <form action="{{ route('settings.branding.text', 'app_tagline') }}" method="POST" class="setting-group">
                                @csrf @method('PUT')
                                <div class="row align-items-center">
                                    <div class="col-md-4">
                                        <h6 class="mb-0">Tagline</h6>
                                        <p class="text-muted small mb-0">Deskripsi singkat aplikasi</p>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="value" value="{{ $appTagline ?? 'Sistem Pengelolaan Informasi' }}">
                                        <button type="submit" class="btn btn-primary mt-3">
                                            <i class="ti ti-check me-1"></i> Simpan
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <form action="{{ route('settings.branding.text', 'company_name') }}" method="POST" class="setting-group">
                                @csrf @method('PUT')
                                <div class="row align-items-center">
                                    <div class="col-md-4">
                                        <h6 class="mb-0">Nama Perusahaan</h6>
                                        <p class="text-muted small mb-0">Nama perusahaan/organisasi</p>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="value" value="{{ $companyName ?? 'INOPAK' }}">
                                        <button type="submit" class="btn btn-primary mt-3">
                                            <i class="ti ti-check me-1"></i> Simpan
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <form action="{{ route('settings.branding.text', 'copyright_text') }}" method="POST" class="setting-group">
                                @csrf @method('PUT')
                                <div class="row align-items-center">
                                    <div class="col-md-4">
                                        <h6 class="mb-0">Teks Copyright</h6>
                                        <p class="text-muted small mb-0">Teks hak cipta di footer</p>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="value" value="{{ $copyrightText ?? '© 2024 INOPAK. All rights reserved.' }}">
                                        <button type="submit" class="btn btn-primary mt-3">
                                            <i class="ti ti-check me-1"></i> Simpan
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <form action="{{ route('settings.branding.text', 'meta_description') }}" method="POST" class="setting-group">
                                @csrf @method('PUT')
                                <div class="row align-items-start">
                                    <div class="col-md-4">
                                        <h6 class="mb-0">Meta Description</h6>
                                        <p class="text-muted small mb-0">Deskripsi untuk SEO dan social media</p>
                                    </div>
                                    <div class="col-md-8">
                                        <textarea class="form-control" name="value" rows="3">{{ $metaDescription ?? 'Database INOPAK - Sistem Pengelolaan Informasi' }}</textarea>
                                        <button type="submit" class="btn btn-primary mt-3">
                                            <i class="ti ti-check me-1"></i> Simpan
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <form action="{{ route('settings.branding.text', 'meta_keywords') }}" method="POST" class="setting-group">
                                @csrf @method('PUT')
                                <div class="row align-items-center">
                                    <div class="col-md-4">
                                        <h6 class="mb-0">Meta Keywords</h6>
                                        <p class="text-muted small mb-0">Kata kunci untuk SEO (pisahkan dengan koma)</p>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="value" value="{{ $metaKeywords ?? 'inopak, database, Ikm, admin dashboard' }}">
                                        <button type="submit" class="btn btn-primary mt-3">
                                            <i class="ti ti-check me-1"></i> Simpan
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Toggles Tab -->
                        <div class="tab-pane fade" id="toggles" role="tabpanel">
                            <form action="{{ route('settings.branding.toggle', 'registration_enabled') }}" method="POST" class="setting-group">
                                @csrf @method('PUT')
                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <h6 class="mb-0">Aktifkan Pendaftaran</h6>
                                        <p class="text-muted small mb-0">Izinkan pengguna baru mendaftar sendiri</p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="toggle-switch">
                                            <input type="checkbox" name="value" value="1" {{ ($registrationEnabled ?? true) ? 'checked' : '' }} onchange="this.form.submit()">
                                            <span class="toggle-slider"></span>
                                        </label>
                                        <span class="ms-3">
                                            {{ ($registrationEnabled ?? true) ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </div>
                                </div>
                            </form>

                            <form action="{{ route('settings.branding.toggle', 'show_branding') }}" method="POST" class="setting-group">
                                @csrf @method('PUT')
                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <h6 class="mb-0">Tampilkan Branding</h6>
                                        <p class="text-muted small mb-0">Tampilkan elemen branding di seluruh aplikasi</p>
                                    </div>
                                    <div class="col-md-6">
                                        @php $showBranding = AppSetting::get('show_branding', true); @endphp
                                        <label class="toggle-switch">
                                            <input type="checkbox" name="value" value="1" {{ $showBranding ? 'checked' : '' }} onchange="this.form.submit()">
                                            <span class="toggle-slider"></span>
                                        </label>
                                        <span class="ms-3">{{ $showBranding ? 'Ya' : 'Tidak' }}</span>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Preview Tab -->
                        <div class="tab-pane fade" id="preview" role="tabpanel">
                            <h5 class="mb-4">Preview Tampilan</h5>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0">Browser Tab</h6>
                                        </div>
                                        <div class="card-body text-center">
                                            <div class="branding-preview" style="background: #f0f0f0; border-radius: 8px;">
                                                <div>
                                                    @php $favicon = $logos['favicon'] ?? null; @endphp
                                                    <img src="{{ $favicon ? $favicon->getUrl() : asset('assets/images/favicon.ico') }}" alt="Favicon" style="width: 24px; height: 24px; margin-right: 8px;">
                                                    <span>{{ $appName ?? 'Database INOPAK' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0">Halaman Login</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="text-center mb-3">
                                                @php $loginLogo = $logos['login'] ?? null; @endphp
                                                <img src="{{ $loginLogo ? $loginLogo->getUrl() : asset('assets/images/inopak/fav.png') }}" alt="Logo" style="max-width: 80px;">
                                                <h5 class="mt-2">{{ $appName ?? 'Database INOPAK' }}</h5>
                                                <p class="text-muted">{{ $appTagline ?? 'Sistem Pengelolaan Informasi' }}</p>
                                            </div>
                                            <div class="text-center text-muted small">
                                                {{ $copyrightText ?? '© 2024 INOPAK. All rights reserved.' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
