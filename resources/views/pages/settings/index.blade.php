@extends('layouts.master')

@section('title', 'Pengaturan Aplikasi | Database INOPAK')

@section('styles')
<style>
    .logo-preview-card {
        transition: all 0.3s ease;
    }
    .logo-preview-card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    .logo-preview {
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8f9fa;
        border-radius: 8px;
        overflow: hidden;
    }
    .logo-preview img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }
    .settings-section {
        margin-bottom: 2rem;
    }
    .activity-log-item {
        transition: background-color 0.2s;
    }
    .activity-log-item:hover {
        background-color: #f8f9fa;
    }
    .form-label {
        font-weight: 500;
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
                <h4 class="mb-0">Pengaturan Aplikasi</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item active">Pengaturan</li>
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

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- Left Sidebar - Navigation -->
        <div class="col-lg-3">
            <div class="card">
                <div class="card-body">
                    <nav class="nav flex-column nav-pills">
                        <a class="nav-link active" href="#logo-settings" data-bs-toggle="tab">
                            <i class="ti ti-photo me-2"></i>Logo Aplikasi
                        </a>
                        <a class="nav-link" href="#registration-settings" data-bs-toggle="tab">
                            <i class="ti ti-user-plus me-2"></i>Pendaftaran Pengguna
                        </a>
                        <a class="nav-link" href="#general-settings" data-bs-toggle="tab">
                            <i class="ti ti-settings me-2"></i>Pengaturan Umum
                        </a>
                        <a class="nav-link" href="#activity-logs" data-bs-toggle="tab">
                            <i class="ti ti-history me-2"></i>Log Aktivitas
                        </a>
                        <a class="nav-link" style="cursor: pointer;" data-bs-toggle="offcanvas" data-bs-target="#theme-settings-offcanvas">
                            <i class="ti ti-history me-2"></i>Tema &amp; Tampilan
                        </a>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Right Content -->
        <div class="col-lg-9">
            <div class="tab-content">
                <!-- Logo Settings Tab -->
                <div class="tab-pane fade show active" id="logo-settings">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ti ti-photo me-2"></i>Konfigurasi Logo Aplikasi
                            </h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted mb-4">
                                Upload dan konfigurasi logo untuk berbagai bagian aplikasi.
                                Format yang didukung: PNG, JPG, SVG, ICO, GIF, WEBP.
                                Maksimal ukuran file: 2MB.
                            </p>

                            <ul class="nav nav-tabs mb-4" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#header-logo" role="tab">
                                        <i class="ti ti-layout-navbar me-1"></i>Header
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#sidebar-logo" role="tab">
                                        <i class="ti ti-layout-sidebar me-1"></i>Sidebar
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#footer-logo" role="tab">
                                        <i class="ti ti-layout-footer me-1"></i>Footer
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#login-logo" role="tab">
                                        <i class="ti ti-login me-1"></i>Login
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#favicon-logo" role="tab">
                                        <i class="ti ti-star me-1"></i>Favicon
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <!-- Header Logo -->
                                <div class="tab-pane fade show active" id="header-logo" role="tabpanel">
                                    @include('pages.settings.partials.logo-form', [
                                        'logo' => $logos['header'] ?? null,
                                        'type' => 'header',
                                        'typeName' => 'Header'
                                    ])
                                </div>

                                <!-- Sidebar Logo -->
                                <div class="tab-pane fade" id="sidebar-logo" role="tabpanel">
                                    @include('pages.settings.partials.logo-form', [
                                        'logo' => $logos['sidebar'] ?? null,
                                        'type' => 'sidebar',
                                        'typeName' => 'Sidebar'
                                    ])
                                </div>

                                <!-- Footer Logo -->
                                <div class="tab-pane fade" id="footer-logo" role="tabpanel">
                                    @include('pages.settings.partials.logo-form', [
                                        'logo' => $logos['footer'] ?? null,
                                        'type' => 'footer',
                                        'typeName' => 'Footer'
                                    ])
                                </div>

                                <!-- Login Logo -->
                                <div class="tab-pane fade" id="login-logo" role="tabpanel">
                                    @include('pages.settings.partials.logo-form', [
                                        'logo' => $logos['login'] ?? null,
                                        'type' => 'login',
                                        'typeName' => 'Login Page'
                                    ])
                                </div>

                                <!-- Favicon -->
                                <div class="tab-pane fade" id="favicon-logo" role="tabpanel">
                                    @include('pages.settings.partials.logo-form', [
                                        'logo' => $logos['favicon'] ?? null,
                                        'type' => 'favicon',
                                        'typeName' => 'Favicon'
                                    ])
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Registration Settings Tab -->
                <div class="tab-pane fade" id="registration-settings">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ti ti-user-plus me-2"></i>Pengaturan Pendaftaran Pengguna
                            </h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted mb-4">
                                Kontrol akses ke halaman pendaftaran pengguna. Nonaktifkan untuk
                                mencegah pendaftaran baru dan gunakan metode alternatif seperti
                                undangan admin.
                            </p>

                            <form action="{{ route('settings.registration.update') }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="card bg-light border">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div>
                                                        <h6 class="mb-1">Halaman Pendaftaran</h6>
                                                        <p class="text-muted mb-0 small">
                                                            Aktifkan untuk memungkinkan pengguna baru mendaftar sendiri
                                                        </p>
                                                    </div>
                                                  <label class="toggle-switch">

                                                    <!-- fallback false -->
                                                    <input type="hidden" name="registration_enabled" value="0">

                                                    <!-- checkbox true -->
                                                    <input type="checkbox"
                                                        name="registration_enabled"
                                                        value="1"
                                                        {{ $registrationEnabled ? 'checked' : '' }}>

                                                    <span class="toggle-slider"></span>
                                                </label>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="card bg-light border">
                                            <div class="card-body">
                                                <h6 class="mb-2">Status Saat Ini:</h6>
                                                @if($registrationEnabled)
                                                    <span class="badge bg-success">
                                                        <i class="ti ti-check me-1"></i>Aktif
                                                    </span>
                                                    <p class="text-muted mb-0 small mt-2">
                                                        Pengguna baru dapat mengakses halaman register
                                                    </p>
                                                @else
                                                    <span class="badge bg-danger">
                                                        <i class="ti ti-x me-1"></i>Nonaktif
                                                    </span>
                                                    <p class="text-muted mb-0 small mt-2">
                                                        Pendaftaran dinonaktifkan. Admin harus membuat akun manually
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-check me-2"></i>Simpan Perubahan
                                    </button>
                                </div>
                            </form>

                            <hr class="my-4">

                            <h6 class="mb-3">Alternatif Pembuatan Akun</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h6><i class="ti ti-user-plus me-2"></i>Buat Akun Manual</h6>
                                            <p class="text-muted small">
                                                Administrator dapat membuat akun pengguna baru melalui dashboard admin.
                                            </p>
                                            <a href="#" class="btn btn-outline-primary btn-sm">
                                                <i class="ti ti-users me-1"></i>Kelola Pengguna
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h6><i class="ti ti-mail me-2"></i>Undangan Email</h6>
                                            <p class="text-muted small">
                                                Kirim undangan ke email tertentu untuk membuat akun baru.
                                            </p>
                                            <button type="button" class="btn btn-outline-primary btn-sm" disabled>
                                                <i class="ti ti-send me-1"></i>Kirim Undangan
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- General Settings Tab -->
                <div class="tab-pane fade" id="general-settings">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ti ti-settings me-2"></i>Pengaturan Umum
                            </h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('settings.general.update') }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label class="form-label">Nama Aplikasi</label>
                                    <input type="text" class="form-control" name="app_name"
                                           value="{{ App\Models\AppSetting::get('app_name', 'Database INOPAK') }}">
                                    <small class="text-muted">Nama yang ditampilkan di judul browser dan header</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Versi Aplikasi</label>
                                    <input type="text" class="form-control" name="app_version"
                                           value="{{ App\Models\AppSetting::get('app_version', '1.0.0') }}">
                                    <small class="text-muted">Versi aplikasi saat ini</small>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-check me-2"></i>Simpan Perubahan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Activity Logs Tab -->
                <div class="tab-pane fade" id="activity-logs">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="card-title mb-0">
                                <i class="ti ti-history me-2"></i>Log Aktivitas Admin
                            </h5>
                            <span class="badge bg-secondary">{{ $activityLogs->total() }} aktivitas</span>
                        </div>
                        <div class="card-body">
                            <p class="text-muted mb-4">
                                Catatan aktivitas untuk keperluan audit dan keamanan sistem.
                                Setiap perubahan konfigurasi dicatat di sini.
                            </p>

                            @if($activityLogs->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Tanggal</th>
                                                <th>Admin</th>
                                                <th>Aksi</th>
                                                <th>Detail</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($activityLogs as $log)
                                                <tr class="activity-log-item">
                                                    <td>
                                                        <small>{{ $log->created_at->format('d/m/Y H:i:s') }}</small>
                                                    </td>
                                                    <td>
                                                        {{ $log->user?->name ?? 'System' }}
                                                    </td>
                                                    <td>
                                                        @switch($log->action)
                                                            @case('logo_updated')
                                                                <span class="badge bg-info">
                                                                    <i class="ti ti-photo me-1"></i>Logo Diubah
                                                                </span>
                                                                @break
                                                            @case('logo_reset')
                                                                <span class="badge bg-warning">
                                                                    <i class="ti ti-refresh me-1"></i>Logo Direset
                                                                </span>
                                                                @break
                                                            @case('setting_updated')
                                                                <span class="badge bg-primary">
                                                                    <i class="ti ti-settings me-1"></i>Pengaturan Diubah
                                                                </span>
                                                                @break
                                                            @case('registration_toggled')
                                                                <span class="badge bg-secondary">
                                                                    <i class="ti ti-user-plus me-1"></i>Pendaftaran Diubah
                                                                </span>
                                                                @break
                                                            @default
                                                                <span class="badge bg-secondary">{{ $log->action }}</span>
                                                        @endswitch
                                                    </td>
                                                    <td>
                                                        <small class="text-muted">
                                                            @if(isset($log->properties['logo_type']))
                                                                Logo: {{ $log->properties['logo_type'] }}
                                                            @elseif(isset($log->properties['key']))
                                                                {{ $log->properties['key'] }}
                                                            @else
                                                                Perubahan konfigurasi
                                                            @endif
                                                        </small>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="mt-3">
                                    {{ $activityLogs->links() }}
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="ti ti-history fs-48 text-muted mb-3"></i>
                                    <p class="text-muted">Belum ada aktivitas yang tercatat</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Preview uploaded logo before submit
    function previewLogo(input, previewId) {
        const file = input.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById(previewId).src = e.target.result;
                document.getElementById(previewId).style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    }

    // Auto-submit form on checkbox change for registration
    document.addEventListener('DOMContentLoaded', function() {
        const registrationToggle = document.querySelector('input[name="registration_enabled"]');
        if (registrationToggle) {
            registrationToggle.addEventListener('change', function() {
                this.form.submit();
            });
        }
    });
</script>
@endsection
