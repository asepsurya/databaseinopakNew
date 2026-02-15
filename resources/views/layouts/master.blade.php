<!DOCTYPE html>
<html lang="en" data-skin="neo" data-bs-theme="light" data-menu-color="gray" data-topbar-color="light" data-layout-width="fluid" dir="ltr" data-sidenav-size="condensed" data-layout-position="fixed" data-theme="light">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="{{ $metaDescription ?? 'Database INOPAK - Sistem Pengelolaan Informasi' }}" />
    <meta name="keywords" content="{{ $metaKeywords ?? 'inopak, database, Ikm, admin dashboard' }}" />
    <meta name="author" content="{{ $companyName ?? 'INOPAK' }}" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') | {{ $appName ?? 'Database INOPAK' }}</title>

    <!-- App favicon - Dynamic from settings -->
    @php $faviconLogo = $logos['favicon'] ?? null; @endphp
    @if($faviconLogo && $faviconLogo->is_active && $faviconLogo->image_url)
        <link rel="shortcut icon" href="{{ asset($faviconLogo->image_url) }}" />
    @else
        <link rel="shortcut icon" href="{{ asset('assets/images/inopak/Fav.png') }}" />
    @endif

    <!-- Theme Config Js -->
    <script src="{{ asset('assets/js/config.js') }}"></script>

    <!-- Vendor css -->
    <link href="{{ asset('assets/css/vendors.min.css') }}" rel="stylesheet" type="text/css" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/7.3.0/tinymce.min.js" referrerpolicy="origin"></script>

    <!-- App css -->
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Product Gallery CSS -->
    <link href="{{ asset('assets/css/product-gallery.css') }}" rel="stylesheet" type="text/css" />

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <!-- Toastr Custom Styles -->
    <style>
        .mobile-menu {
            background-color: rgba(255, 255, 255, 0.95);
        }
        [data-theme="dark"] .mobile-menu {
            background-color: #1e1f27;
        }

        .card-header {
            background-color: transparent !important;
        }
        .dataTable  .thead-sm th {
            background-color: transparent !important;
        }
        @media (max-width: 576px) {
            .Ikm-counter {
               display: none !important;
            }
        }
        /* Toastr notification styles */
        .toast-simple {
            font-family: inherit;
            font-size: 13px;
            padding: 12px 16px;
            border-radius: 0.375rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        .toast-success {
            background-color: #d1e7dd;
            border-left: 4px solid #198754;
            color: #0f5132;
        }
        .toast-error {
            background-color: #f8d7da;
            border-left: 4px solid #dc3545;
            color: #842029;
        }
        .toast-info {
            background-color: #cff4fc;
            border-left: 4px solid #0dcaf0;
            color: #055160;
        }
        .toast-warning {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            color: #664d03;
        }
        .toast-top-right {
            top: 20px;
            right: 20px;
        }

        /* Dark mode toastr support */
        [data-theme="dark"] .toast-simple,
        .dark .toast-simple {
            background-color: #344050;
            color: #f8f9fa;
        }
        [data-theme="dark"] .toast-success,
        .dark .toast-success {
            background-color: #1e3a2f;
            color: #75b798;
        }
        [data-theme="dark"] .toast-error,
        .dark .toast-error {
            background-color: #3d1e21;
            color: #ea868f;
        }

        /* Small toast notifications - Light/Dark mode support */
        .swal2-toast-small {
            font-size: 13px !important;
            max-width: 320px !important;
            padding: 10px 14px !important;
        }
        .swal2-toast-small .swal2-title {
            font-size: 13px !important;
            margin-bottom: 2px !important;
        }
        .swal2-toast-small .swal2-content {
            font-size: 12px !important;
        }
        .swal2-toast-small .swal2-icon {
            width: 22px !important;
            height: 22px !important;
            font-size: 14px !important;
        }

        /* Light mode toast */
        :root .swal2-popup {
            background: #fff !important;
            color: #333 !important;
        }
        :root .swal2-title {
            color: #495057 !important;
        }
        :root .swal2-content {
            color: #6c757d !important;
        }

        /* Dark mode toast - matching Paces template */
        [data-theme="dark"] .swal2-popup,
        .dark .swal2-popup,
        body.dark .swal2-popup {
            background: #344050 !important;
            color: #f8f9fa !important;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3) !important;
        }
        [data-theme="dark"] .swal2-title,
        .dark .swal2-title,
        body.dark .swal2-title {
            color: #f8f9fa !important;
        }
        [data-theme="dark"] .swal2-content,
        .dark .swal2-content,
        body.dark .swal2-content {
            color: #c8cdd4 !important;
        }
        [data-theme="dark"] .swal2-icon.swal2-success,
        .dark .swal2-icon.swal2-success,
        body.dark .swal2-icon.swal2-success {
            border-color: #3fc3ee !important;
            color: #3fc3ee !important;
        }
        [data-theme="dark"] .swal2-icon.swal2-error,
        .dark .swal2-icon.swal2-error,
        body.dark .swal2-icon.swal2-error {
            border-color: #f46a6a !important;
            color: #f46a6a !important;
        }

        /* Neo Theme Sidebar Add Button Styles */
        .btn-neo-add {
            background: linear-gradient(135deg, #5b5b5b 0%, #3a3a3a 100%) !important;
            border: 2px solid #2d2d2d !important;
            border-radius: 8px !important;
            color: #ffffff !important;
            font-weight: 600 !important;
            transition: all 0.3s ease !important;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1) !important;
            margin: 8px 12px !important;
            padding: 12px 16px !important;
        }

        .btn-neo-add:hover {
            background: linear-gradient(135deg, #4a4a4a 0%, #2a2a2a 100%) !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15) !important;
            border-color: #1a1a1a !important;
        }

        /* Footer spacing fix - reduce excessive space */
        .footer {
            margin-top: auto !important;
            padding: 1rem 0 !important;
        }

        /* Make wrapper use flexbox to push footer down */
        .wrapper {
            min-height: 100vh !important;
            display: flex !important;
            flex-direction: column !important;
        }

        /* Main content area should grow to fill space */
        .page-content {
            flex: 1 0 auto !important;
        }

        /* Reduce page container padding */
        .page-content .container-fluid {
            padding-top: 0.5rem !important;
            padding-bottom: 0.5rem !important;
        }

        /* Neo theme accent color variant */
        .btn-neo-add.btn-neo-primary {
            background: linear-gradient(135deg, #435ebe 0%, #2c4a9e 100%) !important;
            border-color: #1e3a8a !important;
        }

        .btn-neo-add.btn-neo-primary:hover {
            background: linear-gradient(135deg, #3a52d0 0%, #1e3a8a 100%) !important;
            border-color: #1e3a8a !important;
        }

        /* Sidebar Guide Modal Styles */
        .sidebar-guide-modal .modal-content {
            border-radius: 12px;
            border: 2px solid #2d2d2d;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
        }

        .sidebar-guide-modal .modal-header {
            border-bottom: 2px solid #e9ecef;
            border-radius: 10px 10px 0 0;
        }

        .sidebar-guide-modal .modal-footer {
            border-top: 2px solid #e9ecef;
            border-radius: 0 0 10px 10px;
        }

        /* Guide list styles */
        .guide-item {
            padding: 12px 16px;
            margin: 8px 0;
            border-radius: 8px;
            border: 1px solid #e9ecef;
            transition: all 0.2s ease;
        }

        .guide-item:hover {
            background: #f8f9fa;
            border-color: #435ebe;
        }

        .guide-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
        }

        /* Search Autocomplete Dropdown Styles */
        .search-autocomplete-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: #fff;
            border-radius: 0 0 8px 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            border: 1px solid #e9ecef;
            border-top: none;
            z-index: 1050;
            max-height: 300px;
            overflow-y: auto;
            display: none;
        }

        .search-autocomplete-dropdown.show {
            display: block;
        }

        .search-autocomplete-item {
            padding: 12px 16px;
            border-bottom: 1px solid #f1f3f5;
            cursor: pointer;
            transition: all 0.15s ease;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .search-autocomplete-item:last-child {
            border-bottom: none;
        }

        .search-autocomplete-item:hover {
            background: #f8f9fa;
        }

        .search-autocomplete-item.highlighted {
            background: #e7f1ff;
        }

        .search-autocomplete-item .item-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .search-autocomplete-item .item-icon.project {
            background: #e3f2fd;
            color: #1976d2;
        }

        .search-autocomplete-item .item-icon.Ikm {
            background: #fff3e0;
            color: #f57c00;
        }

        .search-autocomplete-item .item-content {
            flex: 1;
            min-width: 0;
        }

        .search-autocomplete-item .item-title {
            font-weight: 600;
            color: #343a40;
            font-size: 14px;
            margin-bottom: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .search-autocomplete-item .item-subtitle {
            font-size: 12px;
            color: #6c757d;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .search-autocomplete-item .item-type {
            font-size: 11px;
            padding: 2px 8px;
            border-radius: 12px;
            background: #f1f3f5;
            color: #495057;
            flex-shrink: 0;
        }

        .search-autocomplete-item .item-type.project {
            background: #e3f2fd;
            color: #1976d2;
        }

        .search-autocomplete-item .item-type.Ikm {
            background: #fff3e0;
            color: #f57c00;
        }

        .search-autocomplete-dropdown .no-results {
            padding: 20px 16px;
            text-align: center;
            color: #6c757d;
        }

        .search-autocomplete-dropdown .loading {
            padding: 20px 16px;
            text-align: center;
            color: #6c757d;
        }

        .search-autocomplete-dropdown .loading .spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 2px solid #f1f3f5;
            border-top-color: #435ebe;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin-right: 8px;
            vertical-align: middle;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .search-autocomplete-dropdown .error-message {
            padding: 16px;
            text-align: center;
            color: #dc3545;
            font-size: 13px;
        }

        /* Dark mode support */
        [data-theme="dark"] .search-autocomplete-dropdown,
        .dark .search-autocomplete-dropdown {
            background: #2c3036;
            border-color: #40484f;
        }

        [data-theme="dark"] .search-autocomplete-item,
        .dark .search-autocomplete-item {
            border-color: #40484f;
        }

        [data-theme="dark"] .search-autocomplete-item:hover,
        .dark .search-autocomplete-item:hover {
            background: #343a40;
        }

        [data-theme="dark"] .search-autocomplete-item.highlighted,
        .dark .search-autocomplete-item.highlighted {
            background: #1e3a5f;
        }

        [data-theme="dark"] .search-autocomplete-item .item-title,
        .dark .search-autocomplete-item .item-title {
            color: #f8f9fa;
        }

        [data-theme="dark"] .search-autocomplete-item .item-subtitle,
        .dark .search-autocomplete-item .item-subtitle {
            color: #adb5bd;
        }

        [data-theme="dark"] .search-autocomplete-dropdown .no-results,
        .dark .search-autocomplete-dropdown .no-results {
            color: #adb5bd;
        }
         @media (max-width: 768px) {
            #sidebarToggleBtn{
                display: none !important;
            }
        }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Begin page -->
    <div class="wrapper">
        <!-- Topbar Start -->
        <header class="app-topbar">
            <div class="container-fluid topbar-menu">
                <div class="d-flex align-items-center gap-2">
                    <!-- Topbar Brand Logo -->
                    <div class="logo-topbar">
                        @php
                        $headerLogo = $logos['header'] ?? null;
                        $faviconLogo = $logos['favicon'] ?? null;
                        @endphp

                        <!-- Single logo element that handles light/dark mode via CSS/data attributes -->
                        <a href="/dashboard" class="logo">
                            <span class="logo-lg">
                                @if($headerLogo && $headerLogo->is_active && $headerLogo->image_url)
                                    <img src="{{ asset($headerLogo->image_url) }}"
                                         alt="{{ $headerLogo->name ?? 'Logo' }}"
                                         data-light="{{ asset($headerLogo->image_url) }}"
                                         data-dark="{{ asset($headerLogo->image_url) }}"
                                         style="{{ $headerLogo->width ? 'width:'.$headerLogo->width.'px;' : '' }}{{ $headerLogo->height ? 'height:'.$headerLogo->height.'px;' : '' }}" />
                                @else
                                    <img src="{{ asset('assets/images/inopak/logo_light.png') }}"
                                         alt="Logo"
                                         data-light="{{ asset('assets/images/inopak/logo_light.png') }}"
                                         data-dark="{{ asset('assets/images/inopak/logo_dark.png') }}"
                                         style="height: 40px;" />
                                @endif
                            </span>
                            <span class="logo-sm">
                                @if($faviconLogo && $faviconLogo->is_active && $faviconLogo->image_url)
                                    <img src="{{ asset($faviconLogo->image_url) }}"
                                         alt="{{ $faviconLogo->name ?? 'Favicon' }}"
                                         style="width: 30px; height: 30px;" />
                                @else
                                    <img src="{{ asset('assets/images/inopak/fav.png') }}"
                                         alt="Logo"
                                         style="width: 30px; height: 30px;" />
                                @endif
                            </span>
                        </a>
                    </div>

                    <!-- Sidebar Toggle Button -->
                    <button class="sidenav-toggle-button btn btn-primary btn-icon" id="sidebarToggleBtn">
                        <i class="ti ti-menu-4"></i>
                    </button>

                    <!-- Search Box -->
                    <div id="search-box" class="app-search d-none d-xl-flex position-relative">
                        <input type="search" class="form-control rounded-pill topbar-search" name="search" placeholder="Search..." id="topSearch" autocomplete="off" />
                        <i class="ti ti-search app-search-icon text-muted"></i>
                        <!-- Autocomplete Dropdown -->
                        <div id="search-autocomplete-dropdown" class="search-autocomplete-dropdown"></div>
                    </div>


                </div>

                <div class="d-flex align-items-center gap-2">
                    <!-- Theme Toggle -->
                    <div id="theme-dropdown" class="topbar-item d-none d-sm-flex">
                        <div class="dropdown">
                            <button class="topbar-link" data-bs-toggle="dropdown" type="button" aria-haspopup="false" aria-expanded="false">
                                <i class="ti ti-sun topbar-link-icon" id="theme-icon-light"></i>
                                <i class="ti ti-moon topbar-link-icon d-none" id="theme-icon-dark"></i>
                                <i class="ti ti-sun-moon topbar-link-icon d-none" id="theme-icon-system"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" data-thememode="dropdown">
                                <label class="dropdown-item cursor-pointer">
                                    <input class="form-check-input" type="radio" name="data-bs-theme" value="light" style="display: none" checked />
                                    <i class="ti ti-sun align-middle me-1 fs-16"></i>
                                    <span class="align-middle">Light</span>
                                </label>
                                <label class="dropdown-item cursor-pointer">
                                    <input class="form-check-input" type="radio" name="data-bs-theme" value="dark" style="display: none" />
                                    <i class="ti ti-moon align-middle me-1 fs-16"></i>
                                    <span class="align-middle">Dark</span>
                                </label>
                                <label class="dropdown-item cursor-pointer">
                                    <input class="form-check-input" type="radio" name="data-bs-theme" value="system" style="display: none" />
                                    <i class="ti ti-sun-moon align-middle me-1 fs-16"></i>
                                    <span class="align-middle">System</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar Show/Hide Toggle -->
                    {{-- <div class="topbar-item d-none d-sm-flex">
                        <button class="topbar-link" type="button" id="sidebarShowHideBtn" title="Show/Hide Sidebar">
                            <i class="ti ti-layout-sidebar-left-collapse topbar-link-icon"></i>
                        </button>
                    </div> --}}

                    <!-- UMKM Counter Badge -->
                    <div class="topbar-item Ikm-counter">
                        <div class="d-flex align-items-center gap-2 px-2 py-1 rounded bg-primary bg-opacity-10 border border-primary border-opacity-25">
                            <i class="ti ti-building-skyscraper text-primary fs-lg"></i>
                            <span class="fw-semibold text-primary">{{ $totalUmkm ?? 0 }}</span>
                            <span class="text-muted fs-xs d-none d-lg-inline">Ikm</span>
                        </div>
                    </div>

                    <!-- Notification -->
                    @auth
                        @include('layouts.partials.notifications')
                    @else
                    <div class="topbar-item">
                        <div class="dropdown">
                            <button class="topbar-link dropdown-toggle drop-arrow-none" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="false" aria-expanded="false">
                                <i class="ti ti-bell topbar-link-icon"></i>
                            </button>
                            <div class="dropdown-menu p-0 dropdown-menu-end dropdown-menu-lg">
                                <div class="px-3 py-2 border-bottom">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h6 class="m-0 fs-md fw-semibold">Notifications</h6>
                                        </div>
                                    </div>
                                </div>
                                <div style="max-height: 300px" data-simplebar>
                                    <div class="dropdown-item notification-item">
                                        <span class="d-flex align-items-center gap-3">
                                            <span class="flex-shrink-0 position-relative">
                                                <span class="avatar-md rounded-circle bg-primary d-flex align-items-center justify-content-center">
                                                    <i class="ti ti-info text-white"></i>
                                                </span>
                                            </span>
                                            <span class="flex-grow-1 text-muted">
                                                <span class="fw-medium text-body">Info</span>
                                                <br />
                                                <span class="fs-xs">Silakan login untuk melihat notifikasi</span>
                                            </span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endauth

                    <!-- Fullscreen Toggle -->
                    <div class="topbar-item d-none d-md-flex">
                        <button class="topbar-link" type="button" data-toggle="fullscreen">
                            <i class="ti ti-maximize topbar-link-icon"></i>
                        </button>
                    </div>

                    <!-- Settings Toggle -->
                    {{-- <div class="topbar-item d-none d-sm-flex">
                        <button class="topbar-link btn-theme-setting" data-bs-toggle="offcanvas" data-bs-target="#theme-settings-offcanvas" type="button">
                            <i class="ti ti-settings topbar-link-icon"></i>
                        </button>
                    </div> --}}
                     @if(Auth::check() && Auth::user()->isAdmin())
                        <div class="topbar-item d-none d-sm-flex">
                            <a class="topbar-link " type="button" href="/settings">
                                <i class="ti ti-settings topbar-link-icon"></i>
                            </a>
                        </div>
                    @endif

                    <!-- User Profile -->
                    <div class="topbar-item nav-user">
                        <div class="dropdown">
                            <a class="topbar-link dropdown-toggle drop-arrow-none px-2" data-bs-toggle="dropdown" href="#!" aria-haspopup="false" aria-expanded="false">
                               @if(auth()->user()->profile_photo && Storage::disk('public')->exists(auth()->user()->profile_photo))
                                    <img src="/storage/{{ auth()->user()->profile_photo }}" width="32"
                                        class="rounded-circle me-lg-2 d-flex" alt="user-image" />
                                @else
                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-lg-2"
                                        style="width:32px;height:32px;">
                                        <i class="ti ti-user fs-5 text-muted"></i>
                                    </div>
                                @endif

                                <div class="d-lg-flex align-items-center gap-1 d-none">
                                    <span>
                                        <h5 class="my-0 lh-1 pro-username">{{ Auth::user()->name ?? 'User' }}</h5>
                                        <span class="fs-xs lh-1">{{ Auth::user()->phone ?? 'Administration' }}</span>
                                    </span>
                                    <i class="ti ti-chevron-down align-middle"></i>
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <!-- Header -->
                                <div class="dropdown-header noti-title">
                                    <h6 class="text-overflow m-0">Welcome back {{ Auth::user()->nama ?? 'User' }}!</h6>
                                </div>

                                <!-- My Profile -->
                                <a href="/profile" class="dropdown-item">
                                    <i class="ti ti-user-circle me-1 fs-lg align-middle"></i>
                                    <span class="align-middle">Profile</span>
                                </a>

                                <!-- Settings -->
                                <a href="/profile" class="dropdown-item">
                                    <i class="ti ti-settings-2 me-1 fs-lg align-middle"></i>
                                    <span class="align-middle">Account Settings</span>
                                </a>

                                <!-- Divider -->
                                <div class="dropdown-divider"></div>

                                <!-- Logout -->
                                <form action="/logout" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger fw-semibold w-100">
                                        <i class="ti ti-logout me-1 fs-lg align-middle"></i>
                                        <span class="align-middle">Log Out</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- Topbar End -->

        <!-- Theme Settings Offcanvas -->
        <div class="offcanvas offcanvas-end overflow-hidden" tabindex="-1" id="theme-settings-offcanvas">
            <div class="d-flex justify-content-between text-bg-primary gap-2 p-3" style="background-image: url({{ asset('assets/images/settings-bg.png') }})">
                <div>
                    <h5 class="mb-1 fw-bold text-white text-uppercase">Admin Customizer</h5>
                    <p class="text-white text-opacity-75 fst-italic fw-medium mb-0">Easily configure layout, styles, and preferences for your admin interface.</p>
                </div>
                <div class="flex-grow-0">
                    <button type="button" class="d-block btn btn-sm bg-white bg-opacity-25 text-white rounded-circle btn-icon" data-bs-dismiss="offcanvas">
                        <i class="ti ti-x fs-lg"></i>
                    </button>
                </div>
            </div>
            <div class="offcanvas-body theme-customizer-bar p-0 h-100" data-simplebar>
                <!-- Skin Selection -->
                <div id="skin" class="p-3 border-bottom border-dashed">
                    <h5 class="mb-3 fw-bold">Select Skin</h5>
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="form-check card-radio">
                                <input class="form-check-input" type="radio" name="data-skin" id="demo-skin-default" value="default" />
                                <label class="form-check-label p-0 w-100" for="demo-skin-default">
                                    <img src="{{ asset('assets/images/layouts/skin-default.png') }}" alt="skin-default" class="img-fluid" />
                                </label>
                            </div>
                            <h5 class="text-center text-muted mt-2 mb-0 fs-12">Default</h5>
                        </div>
                        <div class="col-6">
                            <div class="form-check card-radio">
                                <input class="form-check-input" type="radio" name="data-skin" id="demo-skin-neo" value="neo" checked />
                                <label class="form-check-label p-0 w-100" for="demo-skin-neo">
                                    <img src="{{ asset('assets/images/layouts/skin-neo.png') }}" alt="skin-neo" class="img-fluid" />
                                </label>
                            </div>
                            <h5 class="text-center text-muted mt-2 mb-0 fs-12">Neo</h5>
                        </div>
                        <div class="col-6">
                            <div class="form-check card-radio">
                                <input class="form-check-input" type="radio" name="data-skin" id="demo-skin-minimal" value="minimal" />
                                <label class="form-check-label p-0 w-100" for="demo-skin-minimal">
                                    <img src="{{ asset('assets/images/layouts/skin-minimal.png') }}" alt="skin-minimal" class="img-fluid" />
                                </label>
                            </div>
                            <h5 class="text-center text-muted mt-2 mb-0 fs-12">Minimal</h5>
                        </div>
                        <div class="col-6">
                            <div class="form-check card-radio">
                                <input class="form-check-input" type="radio" name="data-skin" id="demo-skin-modern" value="modern" />
                                <label class="form-check-label p-0 w-100" for="demo-skin-modern">
                                    <img src="{{ asset('assets/images/layouts/skin-modern.png') }}" alt="skin-modern" class="img-fluid" />
                                </label>
                            </div>
                            <h5 class="text-center text-muted mt-2 mb-0 fs-12">Modern</h5>
                        </div>
                        <div class="col-6">
                            <div class="form-check card-radio">
                                <input class="form-check-input" type="radio" name="data-skin" id="demo-skin-material" value="material" />
                                <label class="form-check-label p-0 w-100" for="demo-skin-material">
                                    <img src="{{ asset('assets/images/layouts/skin-material.png') }}" alt="skin-material" class="img-fluid" />
                                </label>
                            </div>
                            <h5 class="text-center text-muted mt-2 mb-0 fs-12">Material</h5>
                        </div>
                        <div class="col-6">
                            <div class="form-check card-radio">
                                <input class="form-check-input" type="radio" name="data-skin" id="demo-skin-flat" value="flat" />
                                <label class="form-check-label p-0 w-100" for="demo-skin-flat">
                                    <img src="{{ asset('assets/images/layouts/skin-flat.png') }}" alt="skin-flat" class="img-fluid" />
                                </label>
                            </div>
                            <h5 class="text-center text-muted mt-2 mb-0 fs-12">Flat</h5>
                        </div>
                    </div>
                </div>

                <!-- Theme Color -->
                <div id="theme-color" class="p-3 border-bottom border-dashed">
                    <h5 class="mb-3 fw-bold">Theme Color</h5>
                    <div class="row g-3">
                        <div class="col-4">
                            <div class="form-check card-radio">
                                <input class="form-check-input" type="radio" name="data-bs-theme" id="layout-color-light" value="light" checked />
                                <label class="form-check-label p-0 w-100" for="layout-color-light">
                                    <img src="{{ asset('assets/images/layouts/theme-light.png') }}" alt="theme-light" class="img-fluid" />
                                </label>
                            </div>
                            <h5 class="text-center text-muted mt-2 mb-0 fs-12">Light</h5>
                        </div>
                        <div class="col-4">
                            <div class="form-check card-radio">
                                <input class="form-check-input" type="radio" name="data-bs-theme" id="layout-color-dark" value="dark" />
                                <label class="form-check-label p-0 w-100" for="layout-color-dark">
                                    <img src="{{ asset('assets/images/layouts/theme-dark.png') }}" alt="theme-dark" class="img-fluid" />
                                </label>
                            </div>
                            <h5 class="text-center text-muted mt-2 mb-0 fs-12">Dark</h5>
                        </div>
                        <div class="col-4">
                            <div class="form-check card-radio">
                                <input class="form-check-input" type="radio" name="data-bs-theme" id="layout-color-system" value="system" />
                                <label class="form-check-label p-0 w-100" for="layout-color-system">
                                    <img src="{{ asset('assets/images/layouts/theme-system.png') }}" alt="theme-system" class="img-fluid" />
                                </label>
                            </div>
                            <h5 class="text-center text-muted mt-2 mb-0 fs-12">System</h5>
                        </div>
                    </div>
                </div>

                <!-- Sidenav Color -->
                <div id="sidenav-color" class="p-3 border-bottom border-dashed">
                    <h5 class="mb-3 fw-bold">Sidenav Color</h5>
                    <div class="row g-2">
                        <div class="col-3">
                            <div class="form-check sidebar-setting card-radio">
                                <input class="form-check-input" type="radio" name="data-menu-color" id="layout-sidenav-color-light" value="light" />
                                <label class="form-check-label p-0 w-100" for="layout-sidenav-color-light">
                                    <div class="bg-light rounded p-2" style="height: 40px;"></div>
                                </label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-check sidebar-setting card-radio">
                                <input class="form-check-input" type="radio" name="data-menu-color" id="layout-sidenav-color-dark" value="dark" />
                                <label class="form-check-label p-0 w-100" for="layout-sidenav-color-dark">
                                    <div class="bg-dark rounded p-2" style="height: 40px;"></div>
                                </label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-check sidebar-setting card-radio">
                                <input class="form-check-input" type="radio" name="data-menu-color" id="layout-sidenav-color-gray" value="gray" checked />
                                <label class="form-check-label p-0 w-100" for="layout-sidenav-color-gray">
                                    <div class="bg-secondary rounded p-2" style="height: 40px;"></div>
                                </label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-check sidebar-setting card-radio">
                                <input class="form-check-input" type="radio" name="data-menu-color" id="layout-sidenav-color-gradient" value="gradient" />
                                <label class="form-check-label p-0 w-100" for="layout-sidenav-color-gradient">
                                    <div class="bg-gradient rounded p-2" style="height: 40px;"></div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Topbar Color -->
                <div id="topbar-color" class="p-3 border-bottom border-dashed">
                    <h5 class="mb-3 fw-bold">Topbar Color</h5>
                    <div class="row g-2">
                        <div class="col-3">
                            <div class="form-check card-radio">
                                <input class="form-check-input" type="radio" name="data-topbar-color" id="layout-topbar-color-light" value="light" checked />
                                <label class="form-check-label p-0 w-100" for="layout-topbar-color-light">
                                    <div class="bg-light rounded p-2" style="height: 40px;"></div>
                                </label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-check card-radio">
                                <input class="form-check-input" type="radio" name="data-topbar-color" id="layout-topbar-color-dark" value="dark" />
                                <label class="form-check-label p-0 w-100" for="layout-topbar-color-dark">
                                    <div class="bg-dark rounded p-2" style="height: 40px;"></div>
                                </label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-check card-radio">
                                <input class="form-check-input" type="radio" name="data-topbar-color" id="layout-topbar-color-gray" value="gray" />
                                <label class="form-check-label p-0 w-100" for="layout-topbar-color-gray">
                                    <div class="bg-secondary rounded p-2" style="height: 40px;"></div>
                                </label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-check card-radio">
                                <input class="form-check-input" type="radio" name="data-topbar-color" id="layout-topbar-color-gradient" value="gradient" />
                                <label class="form-check-label p-0 w-100" for="layout-topbar-color-gradient">
                                    <div class="bg-gradient rounded p-2" style="height: 40px;"></div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidenav Size -->
                <div id="sidenav-size" class="p-3 border-bottom border-dashed">
                    <h5 class="mb-3 fw-bold">Sidenav Size</h5>
                    <div class="row g-2">
                        <div class="col-4">
                            <div class="form-check sidebar-setting card-radio">
                                <input class="form-check-input" type="radio" name="data-sidenav-size" id="layout-sidenav-size-default" value="default" />
                                <label class="form-check-label p-0 w-100" for="layout-sidenav-size-default">
                                    <span class="d-flex align-items-center gap-2">
                                        <span class="bg-primary rounded" style="width: 30px; height: 30px;"></span>
                                        <span class="bg-primary rounded" style="width: 80px; height: 20px;"></span>
                                    </span>
                                </label>
                            </div>
                            <h5 class="text-center text-muted mt-2 mb-0 fs-12">Default</h5>
                        </div>
                        <div class="col-4">
                            <div class="form-check sidebar-setting card-radio">
                                <input class="form-check-input" type="radio" name="data-sidenav-size" id="layout-sidenav-size-compact" value="compact" />
                                <label class="form-check-label p-0 w-100" for="layout-sidenav-size-compact">
                                    <span class="d-flex align-items-center gap-2">
                                        <span class="bg-primary rounded" style="width: 20px; height: 20px;"></span>
                                        <span class="bg-primary rounded" style="width: 60px; height: 15px;"></span>
                                    </span>
                                </label>
                            </div>
                            <h5 class="text-center text-muted mt-2 mb-0 fs-12">Compact</h5>
                        </div>
                        <div class="col-4">
                            <div class="form-check sidebar-setting card-radio">
                                <input class="form-check-input" type="radio" name="data-sidenav-size" id="layout-sidenav-size-condensed" value="condensed" checked />
                                <label class="form-check-label p-0 w-100" for="layout-sidenav-size-condensed">
                                    <span class="d-flex align-items-center gap-2">
                                        <span class="bg-primary rounded" style="width: 15px; height: 15px;"></span>
                                        <span class="bg-primary rounded" style="width: 50px; height: 12px;"></span>
                                    </span>
                                </label>
                            </div>
                            <h5 class="text-center text-muted mt-2 mb-0 fs-12">Condensed</h5>
                        </div>
                        <div class="col-4">
                            <div class="form-check sidebar-setting card-radio">
                                <input class="form-check-input" type="radio" name="data-sidenav-size" id="layout-sidenav-size-on-hover" value="on-hover" />
                                <label class="form-check-label p-0 w-100" for="layout-sidenav-size-on-hover">
                                    <span class="d-flex align-items-center gap-2">
                                        <span class="bg-primary rounded" style="width: 15px; height: 15px;"></span>
                                        <span class="bg-primary rounded" style="width: 40px; height: 10px;"></span>
                                    </span>
                                </label>
                            </div>
                            <h5 class="text-center text-muted mt-2 mb-0 fs-12">On Hover</h5>
                        </div>
                        <div class="col-4">
                            <div class="form-check sidebar-setting card-radio">
                                <input class="form-check-input" type="radio" name="data-sidenav-size" id="layout-sidenav-size-offcanvas" value="offcanvas" />
                                <label class="form-check-label p-0 w-100" for="layout-sidenav-size-offcanvas">
                                    <span class="d-flex align-items-center gap-2">
                                        <span class="bg-primary rounded" style="width: 15px; height: 15px;"></span>
                                        <span class="border rounded" style="width: 50px; height: 12px;"></span>
                                    </span>
                                </label>
                            </div>
                            <h5 class="text-center text-muted mt-2 mb-0 fs-12">Offcanvas</h5>
                        </div>
                    </div>
                </div>

                <!-- Layout Width -->
                <div id="layout-width" class="p-3 border-bottom border-dashed">
                    <h5 class="mb-3 fw-bold">Layout Width</h5>
                    <div class="row g-2">
                        <div class="col-4">
                            <div class="form-check sidebar-setting card-radio">
                                <input class="form-check-input" type="radio" name="data-layout-width" id="layout-width-fluid" value="fluid" checked />
                                <label class="form-check-label p-0 w-100" for="layout-width-fluid">
                                    <span class="d-flex align-items-center justify-content-center bg-light border rounded p-2" style="width: 100%;">
                                        <span class="bg-primary rounded" style="width: 80%; height: 20px;"></span>
                                    </span>
                                </label>
                            </div>
                            <h5 class="text-center text-muted mt-2 mb-0 fs-12">Fluid</h5>
                        </div>
                        <div class="col-4">
                            <div class="form-check sidebar-setting card-radio">
                                <input class="form-check-input" type="radio" name="data-layout-width" id="layout-width-boxed" value="boxed" />
                                <label class="form-check-label p-0 w-100" for="layout-width-boxed">
                                    <span class="d-flex align-items-center justify-content-center bg-light border rounded p-2" style="width: 100%;">
                                        <span class="bg-primary rounded" style="width: 60%; height: 20px;"></span>
                                    </span>
                                </label>
                            </div>
                            <h5 class="text-center text-muted mt-2 mb-0 fs-12">Boxed</h5>
                        </div>
                    </div>
                </div>

                <!-- Layout Position -->
                <div id="layout-position" class="p-3 border-bottom border-dashed">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">Layout Position</h5>
                        <div class="d-flex gap-1">
                            <input type="radio" class="btn-check" name="data-layout-position" id="layout-position-fixed" value="fixed" checked />
                            <label class="btn btn-sm btn-soft-warning w-sm" for="layout-position-fixed">Fixed</label>
                            <input type="radio" class="btn-check" name="data-layout-position" id="layout-position-scrollable" value="scrollable" />
                            <label class="btn btn-sm btn-soft-warning w-sm ms-0" for="layout-position-scrollable">Scrollable</label>
                        </div>
                    </div>
                </div>

                <!-- Sidebar User -->
                <div id="sidenav-user" class="p-3 border-bottom border-dashed">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <label class="fw-bold m-0" for="sidebaruser-check">Sidebar User Info</label>
                        </h5>
                        <div class="form-check form-switch fs-lg">
                            <input type="checkbox" class="form-check-input" name="sidebar-user" id="sidebaruser-check" checked />
                        </div>
                    </div>
                </div>

                <!-- Sidebar Show/Hide -->
                <div id="sidebar-showhide" class="p-3 border-bottom border-dashed">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <label class="fw-bold m-0">Show Sidebar</label>
                        </h5>
                        <div class="form-check form-switch fs-lg">
                            <input type="checkbox" class="form-check-input" id="sidebarShowHide" checked />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reset Button -->
            <div class="offcanvas-footer border-top p-3 text-center">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <button type="button" class="btn btn-danger fw-semibold py-2 w-100" id="reset-layout">
                            <i class="ti ti-refresh me-2 fs-md"></i> Reset to Default
                        </button>
                    </div>
                </div>
            </div>
        </div>



        <!-- Sidenav Menu -->
        <div class="sidenav-menu" id="sidenavMenu">
            <!-- Brand Logo -->
            @php $sidebarLogo = $logos['sidebar'] ?? null; @endphp
            <a href="/dashboard" class="logo">
                @php $sidebarLogo = $logos['sidebar'] ?? null; @endphp

                <!-- Sidebar logo using favicon/logo_dark.png as primary -->
                @if($sidebarLogo && $sidebarLogo->is_active && $sidebarLogo->image_url)
                    <span class="logo-lg">
                        <img src="{{ asset($sidebarLogo->image_url) }}"
                             alt="{{ $sidebarLogo->name ?? 'Logo' }}"
                            style="height: 50px;"/>

                    </span>
                    <span class="logo-sm">
                         @if($faviconLogo && $faviconLogo->is_active && $faviconLogo->image_url)
                                    <img src="{{ asset($faviconLogo->image_url) }}"
                                         alt="{{ $faviconLogo->name ?? 'Favicon' }}"
                                         style="width: 30px; height: 30px;" />
                                @else
                                    <img src="{{ asset('assets/images/inopak/fav.png') }}"
                                         alt="Logo"
                                         style="width: 30px; height: 30px;" />
                                @endif
                    </span>
                @else
                    <span class="logo-lg">
                        <img src="{{ asset('assets/images/inopak/logo_dark.png') }}"
                             alt="Logo"
                             style="height: 40px;" />
                    </span>
                    <span class="logo-sm">
                        <img src="{{ asset('assets/images/inopak/fav.png') }}"
                             alt="Logo"
                             style="width: 30px; height: 30px;" />
                    </span>
                @endif
            </a>

            <!-- Sidebar User -->
            <div id="user-profile-settings" class="sidenav-user" style="background: url({{ asset('assets/images/user-bg-pattern.svg') }})">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <a href="#!" class="link-reset">
                            <img src="{{ asset('assets/images/inopak/Fav.png') }}" alt="user-image" class="rounded-circle mb-2 avatar-md" />
                            <span class="sidenav-user-name fw-bold">{{ Auth::user()->nama ?? 'User' }}</span>
                            <span class="fs-12 fw-semibold">Admin INOPAK</span>
                        </a>
                    </div>
                </div>
            </div>

            <!--- Sidenav Menu -->
            <div id="sidenav-menu">
                <ul class="side-nav">
                    <li class="side-nav-title mt-2">Main</li>

                    <li class="side-nav-item">
                        <a href="/dashboard" class="side-nav-link">
                            <span class="menu-icon"><i class="ti ti-dashboard"></i></span>
                            <span class="menu-text">Dashboard</span>
                        </a>
                    </li>

                    <li class="side-nav-title mt-2">Menu</li>

                    <li class="side-nav-item">
                        <a href="/project" class="side-nav-link">
                            <span class="menu-icon"><i class="ti ti-folder"></i></span>
                            <span class="menu-text">Project</span>
                        </a>
                    </li>

                    @if(Auth::check() && Auth::user()->isAdmin())
                    <li class="side-nav-item">
                        <a href="/backup" class="side-nav-link">
                            <span class="menu-icon"><i class="ti ti-database"></i></span>
                            <span class="menu-text">Backup Database</span>
                        </a>
                    </li>
                    @endif


                    <li class="side-nav-item">
                        <a href="https://tidessa.inopakinstitute.or.id/login" class="side-nav-link" target="_Blank">
                            <span class="menu-icon"><i class="ti ti-folders"></i></span>
                            <span class="menu-text">TIDESSA</span>
                        </a>
                    </li>

                    {{-- <li class="side-nav-item">
                        <a href="/project/dataIkm/1" class="side-nav-link">
                            <span class="menu-icon"><i class="ti ti-users"></i></span>
                            <span class="menu-text">Data Ikm</span>
                        </a>
                    </li> --}}


                    {{-- @if(Auth::user())
                    <li class="side-nav-item">
                        <a href="/profile" class="side-nav-link">
                            <span class="menu-icon"><i class="ti ti-user"></i></span>
                            <span class="menu-text">Profile</span>
                        </a>
                    </li>
                    @endif --}}

                    <li class="side-nav-title mt-2">Akun</li>

                    <li class="side-nav-item">
                        <form action="/logout" method="POST">
                            @csrf
                            <button type="submit" class="side-nav-link w-100 text-start" style="background: none; border: none; cursor: pointer;">
                                <span class="menu-icon"><i class="ti ti-logout"></i></span>
                                <span class="menu-text">Logout</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </div>

        </div>
        <!-- Sidenav Menu End -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('sidebarToggleBtn')?.addEventListener('click', function () {
        document.body.classList.toggle('sidebar-collapsed');
    });
});
</script>
        <!-- ============================================================== -->
        <!-- Start Main Content -->
        <!-- ============================================================== -->
        <div class="content-page">
            <div class="content">
                <div class="container-fluid py-3">
                    <!-- Main Content -->
                    @yield('content')

                </div> <!-- container -->
            </div> <!-- content -->

            <!-- Footer Start -->
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6 text-center text-md-start">
                            {{ date('Y') }} &copy; Database INOPAK
                        </div>
                        <div class="col-md-6">
                            <div class="d-none d-md-flex justify-content-end gap-3">
                                <a href="#!" class="link-reset">About</a>
                                <a href="#!" class="link-reset">Support</a>
                                <a href="#!" class="link-reset">Contact Us</a>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
            <!-- end Footer -->
            <div class="d-lg-none fixed-bottom  border-top shadow-sm mobile-menu">
                <div class="container">
                    <div class="row text-center">

                        <div class="col py-2">
                            <a href="/dashboard" class="text-decoration-none small {{ Request::is('dashboard') ? 'text-primary' : 'text-muted' }}">
                                <div><i class="ti ti-dashboard fs-5"></i></div>
                                Dashboard
                            </a>
                        </div>

                        <div class="col py-2">
                            <a href="/project" class="text-decoration-none small {{ Request::is('project*') ? 'text-primary' : 'text-muted' }}">
                                <div><i class="ti ti-folder fs-5"></i></div>
                                Project
                            </a>
                        </div>

                        <div class="col py-2">
                            <a href="https://tidessa.inopakinstitute.or.id/login"
                            target="_blank"
                            class="text-decoration-none small text-muted">
                                <div><i class="ti ti-folders fs-5"></i></div>
                                TIDESSA
                            </a>
                        </div>

                        <div class="col py-2">
                            <a href="#"
                            onclick="event.preventDefault(); document.getElementById('logoutForm').submit();"
                            class="text-decoration-none small text-muted">
                                <div><i class="ti ti-logout fs-5"></i></div>
                                Logout
                            </a>
                        </div>

                    </div>
                </div>
            </div>

            <form id="logoutForm" action="/logout" method="POST" class="d-none">
                @csrf
            </form>
        </div>
        <!-- ============================================================== -->
        <!-- End Main Content -->
        <!-- ============================================================== -->
    </div>
    <!-- END wrapper -->

    <!-- Vendor js -->
    <script src="{{ asset('assets/js/vendors.min.js') }}"></script>

    <!-- App js -->
    <script src="{{ asset('assets/js/app.js') }}"></script>

    <!-- Customizer Settings -->

<script>
document.addEventListener('DOMContentLoaded', function () {

    const sidenavMenu = document.getElementById('sidenavMenu');
    const sidebarShowHideBtn = document.getElementById('sidebarShowHideBtn');
    const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
    const sidebarShowHideCheckbox = document.getElementById('sidebarShowHide');

    if (!sidenavMenu) return;

    /* ===============================
       Helper Functions
    =============================== */
    function hideSidebar() {

        localStorage.setItem('sidebarHidden', 'true');
        if (sidebarShowHideCheckbox) sidebarShowHideCheckbox.checked = false;
    }

    function showSidebar() {
        sidenavMenu.classList.remove('d-none');
        localStorage.setItem('sidebarHidden', 'false');
        if (sidebarShowHideCheckbox) sidebarShowHideCheckbox.checked = true;
    }

    function toggleSidebar() {
        const isHidden = sidenavMenu.classList.contains('d-none');
        isHidden ? showSidebar() : hideSidebar();
    }

    /* ===============================
       Load Saved State (IMPORTANT)
    =============================== */
    const sidebarHidden = localStorage.getItem('sidebarHidden') === 'true';
    sidebarHidden ? hideSidebar() : showSidebar();

    /* ===============================
       Event Bindings
    =============================== */
    sidebarShowHideBtn?.addEventListener('click', toggleSidebar);
    sidebarToggleBtn?.addEventListener('click', toggleSidebar);

    sidebarShowHideCheckbox?.addEventListener('change', function () {
        this.checked ? showSidebar() : hideSidebar();
    });

});
</script>
<script>
    // Theme toggle functionality
    document.querySelectorAll('[data-thememode="dropdown"] label').forEach(function(label) {
        label.addEventListener('click', function() {
            const theme = this.querySelector('input').value;
            document.documentElement.setAttribute('data-bs-theme', theme);
            document.documentElement.setAttribute('data-theme', theme);

            // Update icons
            document.getElementById('theme-icon-light').classList.add('d-none');
            document.getElementById('theme-icon-dark').classList.add('d-none');
            document.getElementById('theme-icon-system').classList.add('d-none');

            if (theme === 'light') {
                document.getElementById('theme-icon-light').classList.remove('d-none');
            } else if (theme === 'dark') {
                document.getElementById('theme-icon-dark').classList.remove('d-none');
            } else {
                document.getElementById('theme-icon-system').classList.remove('d-none');
            }

            // Save preference
            localStorage.setItem('theme', theme);
        });
    });

    // Load saved theme
    const savedTheme = localStorage.getItem('theme') || 'light';
    document.documentElement.setAttribute('data-bs-theme', savedTheme);
    document.documentElement.setAttribute('data-theme', savedTheme);

    if (savedTheme === 'dark') {
        document.getElementById('theme-icon-light').classList.add('d-none');
        document.getElementById('theme-icon-dark').classList.remove('d-none');
    }

    // Skin selector
    document.querySelectorAll('input[name="data-skin"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            document.documentElement.setAttribute('data-skin', this.value);
            localStorage.setItem('skin', this.value);
        });
    });

    // Load saved skin
    const savedSkin = localStorage.getItem('skin') || 'neo';
    document.documentElement.setAttribute('data-skin', savedSkin);
    const skinRadio = document.getElementById('demo-skin-' + savedSkin);
    if (skinRadio) skinRadio.checked = true;

    // Menu color
    document.querySelectorAll('input[name="data-menu-color"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            document.documentElement.setAttribute('data-menu-color', this.value);
            localStorage.setItem('menuColor', this.value);
        });
    });

    // Topbar color
    document.querySelectorAll('input[name="data-topbar-color"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            document.documentElement.setAttribute('data-topbar-color', this.value);
            localStorage.setItem('topbarColor', this.value);
        });
    });

    // Reset button
    document.getElementById('reset-layout').addEventListener('click', function() {
        localStorage.clear();
        location.reload();
    });

    // Search functionality with autocomplete
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('topSearch');
        const dropdown = document.getElementById('search-autocomplete-dropdown');
        let debounceTimer;
        let currentHighlightIndex = -1;
        let currentResults = [];

        // Debounce function
        function debounce(func, wait) {
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(debounceTimer);
                    func(...args);
                };
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(later, wait);
            };
        }

        // Get CSRF token
        function getCsrfToken() {
            return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        }

        // Show loading state
        function showLoading() {
            dropdown.innerHTML = '<div class="loading"><span class="spinner"></span>Mencari...</div>';
            dropdown.classList.add('show');
        }

        // Show no results
        function showNoResults(query) {
            dropdown.innerHTML = '<div class="no-results">Tidak ditemukan hasil untuk "' + escapeHtml(query) + '"</div>';
            dropdown.classList.add('show');
        }

        // Show error
        function showError(message) {
            dropdown.innerHTML = '<div class="error-message">' + escapeHtml(message) + '</div>';
            dropdown.classList.add('show');
        }

        // Escape HTML to prevent XSS
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Render results
        function renderResults(results, query) {
            if (!results || results.length === 0) {
                showNoResults(query);
                return;
            }

            let html = '';
            results.forEach((item, index) => {
                // Debug: Log the actual data structure
                console.log('Item data:', JSON.stringify(item, null, 2));

                // Normalize type to lowercase for consistent comparison
                const normalizedType = (item.type || '').toLowerCase();
                const iconClass = normalizedType === 'project' ? 'ti ti-folder' : 'ti ti-building-skyscraper';
                const iconType = normalizedType || 'unknown';
                const typeLabel = normalizedType === 'project' ? 'Project' : (normalizedType === 'ikm' ? 'IKM' : 'Unknown');

                let title = '';
                let subtitle = '';

                if (normalizedType === 'ikm') {
                    title = item.nama_ikm || item.nama_Ikm || item.nama || 'N/A';
                    subtitle = item.nama_project || item.project_name || '';
                } else if (normalizedType === 'project') {
                    title = item.nama_project || item.project_name || 'N/A';
                } else {
                    title = item.name || item.title || item.nama || 'N/A';
                }

                html += `
                    <div class="search-autocomplete-item" data-index="${index}" data-route="${escapeHtml(item.route || '')}">
                        <div class="item-icon ${iconType}">
                            <i class="${iconClass}"></i>
                        </div>
                        <div class="item-content">
                            <div class="item-title">${escapeHtml(title)}</div>
                            ${subtitle ? '<div class="item-subtitle">' + escapeHtml(subtitle) + '</div>' : ''}
                        </div>
                        <span class="item-type ${iconType}">${typeLabel}</span>
                    </div>
                `;
            });

            dropdown.innerHTML = html;
            dropdown.classList.add('show');
            currentResults = results;
            currentHighlightIndex = -1;

            // Add click event listeners
            dropdown.querySelectorAll('.search-autocomplete-item').forEach(item => {
                item.addEventListener('click', function() {
                    const route = this.getAttribute('data-route');
                    if (route) {
                        window.location.href = route;
                    }
                });
            });
        }

        // Close dropdown
        function closeDropdown() {
            dropdown.classList.remove('show');
            currentHighlightIndex = -1;
            currentResults = [];
        }

        // Perform search
        async function performSearch(query) {
            if (!query || query.length < 1) {
                closeDropdown();
                return;
            }

            showLoading();

            try {
                const response = await fetch(`/api/project/search?q=${encodeURIComponent(query)}`, {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': getCsrfToken(),
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                const data = await response.json();

                if (data.error) {
                    showError(data.error);
                } else {
                    renderResults(data, query);
                }
            } catch (error) {
                console.error('Search error:', error);
                showError('Terjadi kesalahan saat melakukan pencarian');
            }
        }

        // Debounced search function
        const debouncedSearch = debounce(performSearch, 300);

        // Input event handler
        searchInput.addEventListener('input', function(e) {
            const query = this.value.trim();
            debouncedSearch(query);
        });

        // Keyboard navigation
        searchInput.addEventListener('keydown', function(e) {
            const items = dropdown.querySelectorAll('.search-autocomplete-item');

            if (e.key === 'ArrowDown') {
                e.preventDefault();
                if (currentHighlightIndex < items.length - 1) {
                    if (currentHighlightIndex >= 0) {
                        items[currentHighlightIndex].classList.remove('highlighted');
                    }
                    currentHighlightIndex++;
                    items[currentHighlightIndex].classList.add('highlighted');
                }
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                if (currentHighlightIndex > 0) {
                    items[currentHighlightIndex].classList.remove('highlighted');
                    currentHighlightIndex--;
                    items[currentHighlightIndex].classList.add('highlighted');
                }
            } else if (e.key === 'Enter') {
                e.preventDefault();
                if (currentHighlightIndex >= 0 && items[currentHighlightIndex]) {
                    const route = items[currentHighlightIndex].getAttribute('data-route');
                    if (route) {
                        window.location.href = route;
                    }
                }
            } else if (e.key === 'Escape') {
                closeDropdown();
                searchInput.blur();
            }
        });

        // Focus handler
        searchInput.addEventListener('focus', function() {
            const query = this.value.trim();
            if (query && currentResults.length > 0) {
                dropdown.classList.add('show');
            }
        });

        // Click outside to close
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) {
                closeDropdown();
            }
        });
    });

    // Search functionality with autocomplete - standalone version
    (function() {
        'use strict';

        function initSearch() {
            const searchInput = document.getElementById('topSearch');
            const dropdown = document.getElementById('search-autocomplete-dropdown');

            if (!searchInput || !dropdown) {
                console.warn('Search elements not found');
                return;
            }

            let debounceTimer;
            let currentHighlightIndex = -1;
            let currentResults = [];

            function debounce(func, wait) {
                return function(...args) {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(() => func.apply(this, args), wait);
                };
            }

            function escapeHtml(text) {
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }

            function showLoading() {
                dropdown.innerHTML = '<div class="loading"><span class="spinner"></span>Mencari...</div>';
                dropdown.classList.add('show');
            }

            function showNoResults(query) {
                dropdown.innerHTML = '<div class="no-results">Tidak ditemukan hasil untuk "' + escapeHtml(query) + '"</div>';
                dropdown.classList.add('show');
            }

            function showError(message) {
                dropdown.innerHTML = '<div class="error-message">' + escapeHtml(message) + '</div>';
                dropdown.classList.add('show');
            }

            function closeDropdown() {
                dropdown.classList.remove('show');
                currentHighlightIndex = -1;
                currentResults = [];
            }

            function renderResults(results, query) {
                if (!results || results.length === 0) {
                    showNoResults(query);
                    return;
                }

                let html = '';
                results.forEach((item, index) => {
                    // Debug: Log the actual data structure
                    console.log('Item data:', JSON.stringify(item, null, 2));

                    // Normalize type to lowercase for consistent comparison
                    const normalizedType = (item.type || '').toLowerCase();
                    const iconClass = normalizedType === 'project' ? 'ti ti-folder' : 'ti ti-building-skyscraper';
                    const iconType = normalizedType || 'unknown';
                    const typeLabel = normalizedType === 'project' ? 'Project' : (normalizedType === 'ikm' ? 'IKM' : 'Unknown');

                    let title = '';
                    let subtitle = '';

                    if (normalizedType === 'ikm') {
                        title = item.nama_ikm || item.nama_Ikm || item.nama || 'N/A';
                        subtitle = item.nama_project || item.project_name || '';
                    } else if (normalizedType === 'project') {
                        title = item.nama_project || item.project_name || 'N/A';
                    } else {
                        title = item.name || item.title || item.nama || 'N/A';
                    }

                    html += `
                        <div class="search-autocomplete-item" data-index="${index}" data-route="${escapeHtml(item.route || '')}">
                            <div class="item-icon ${iconType}">
                                <i class="${iconClass}"></i>
                            </div>
                            <div class="item-content">
                                <div class="item-title">${escapeHtml(title)}</div>
                                ${subtitle ? '<div class="item-subtitle">' + escapeHtml(subtitle) + '</div>' : ''}
                            </div>
                            <span class="item-type ${iconType}">${typeLabel}</span>
                        </div>
                    `;
                });

                dropdown.innerHTML = html;
                dropdown.classList.add('show');
                currentResults = results;
                currentHighlightIndex = -1;

                dropdown.querySelectorAll('.search-autocomplete-item').forEach(item => {
                    item.addEventListener('click', function(e) {
                        e.preventDefault();
                        const route = this.getAttribute('data-route');
                        if (route) {
                            window.location.href = route;
                        }
                    });
                });
            }

            async function performSearch(query) {
                if (!query || query.length < 1) {
                    closeDropdown();
                    return;
                }

                showLoading();

                try {
                    console.log('Fetching search results for:', query);
                    const response = await fetch('/api/project/search?q=' + encodeURIComponent(query));

                    if (!response.ok) {
                        if (response.status === 401) {
                            showError('Silakan login untuk melakukan pencarian');
                            return;
                        }
                        throw new Error('Network response was not ok: ' + response.status);
                    }

                    const data = await response.json();
                    console.log('Search results:', data);

                    if (data.error) {
                        showError(data.error);
                    } else {
                        renderResults(data, query);
                    }
                } catch (error) {
                    console.error('Search error:', error);
                    showError('Terjadi kesalahan saat melakukan pencarian');
                }
            }

            const debouncedSearch = debounce(performSearch, 300);

            searchInput.addEventListener('input', function() {
                debouncedSearch(this.value.trim());
            });

            searchInput.addEventListener('keydown', function(e) {
                const items = dropdown.querySelectorAll('.search-autocomplete-item');

                if (e.key === 'ArrowDown' && items.length > 0) {
                    e.preventDefault();
                    if (currentHighlightIndex < items.length - 1) {
                        if (currentHighlightIndex >= 0) items[currentHighlightIndex].classList.remove('highlighted');
                        currentHighlightIndex++;
                        items[currentHighlightIndex].classList.add('highlighted');
                    }
                } else if (e.key === 'ArrowUp' && items.length > 0) {
                    e.preventDefault();
                    if (currentHighlightIndex > 0) {
                        items[currentHighlightIndex].classList.remove('highlighted');
                        currentHighlightIndex--;
                        items[currentHighlightIndex].classList.add('highlighted');
                    }
                } else if (e.key === 'Enter' && currentHighlightIndex >= 0 && items[currentHighlightIndex]) {
                    e.preventDefault();
                    const route = items[currentHighlightIndex].getAttribute('data-route');
                    if (route) window.location.href = route;
                } else if (e.key === 'Escape') {
                    closeDropdown();
                    searchInput.blur();
                }
            });

            searchInput.addEventListener('focus', function() {
                if (this.value.trim() && currentResults.length > 0) {
                    dropdown.classList.add('show');
                }
            });

            document.addEventListener('click', function(e) {
                if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) {
                    closeDropdown();
                }
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initSearch);
        } else {
            initSearch();
        }
    })();

    // Encrypted ID click handler - decrypts IDs before navigation
    (function() {
        'use strict';

        function initEncryptedLinks() {
            // Find all links with encrypted ID data attributes
            const encryptedLinks = document.querySelectorAll('[data-encrypted-Ikm]');

            if (encryptedLinks.length === 0) {
                return;
            }

            console.log('Encrypted link handler initialized for ' + encryptedLinks.length + ' links');

            encryptedLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();

                    const encryptedIkm = this.getAttribute('data-encrypted-Ikm');
                    const encryptedProject = this.getAttribute('data-encrypted-project');

                    console.log('Click on encrypted link:', { encryptedIkm, encryptedProject });

                    // Create form for POST request to decrypt endpoint
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '/decrypt-ids';
                    form.style.display = 'none';

                    // Add CSRF token
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                    if (csrfToken) {
                        const csrfInput = document.createElement('input');
                        csrfInput.type = 'hidden';
                        csrfInput.name = '_token';
                        csrfInput.value = csrfToken;
                        form.appendChild(csrfInput);
                    }

                    // Add encrypted IDs
                    if (encryptedIkm) {
                        const IkmInput = document.createElement('input');
                        IkmInput.type = 'hidden';
                        IkmInput.name = 'encrypted_Ikm';
                        IkmInput.value = encryptedIkm;
                        form.appendChild(IkmInput);
                    }

                    if (encryptedProject) {
                        const projectInput = document.createElement('input');
                        projectInput.type = 'hidden';
                        projectInput.name = 'encrypted_project';
                        projectInput.value = encryptedProject;
                        form.appendChild(projectInput);
                    }

                    document.body.appendChild(form);
                    form.submit();
                });
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initEncryptedLinks);
        } else {
            initEncryptedLinks();
        }
    })();

    // SweetAlert2 - Global notification handler for all pages
    @php
    $alertType = '';
    $alertMessage = '';
    $alertIcon = '';

    if(session()->has('Berhasil')) {
        $alertType = 'success';
        $alertMessage = 'Data berhasil disimpan';
    } elseif(session()->has('HapusBerhasil')) {
        $alertType = 'success';
        $alertMessage = 'Data berhasil dihapus';
    } elseif(session()->has('UpdateBerhasil')) {
        $alertType = 'success';
        $alertMessage = 'Data berhasil diperbarui';
    } elseif(session()->has('gagalSimpan')) {
        $alertType = 'error';
        $alertMessage = 'Gagal menyimpan data';
    }
    @endphp

    @if(!empty($alertType))
    document.addEventListener('DOMContentLoaded', function() {
        toastr['{{ $alertType }}']('{{ $alertMessage }}', '{{ $alertType === "success" ? "Berhasil" : "Gagal" }}', {
            timeOut: 3000,
            closeButton: true,
            progressBar: true,
            positionClass: 'toast-top-right',
            toastClass: 'toast-simple toast-' + '{{ $alertType }}'
        });
    });
    @endif
    </script>

    <!-- Sidebar Guide and Add Button JavaScript -->
    <script>
        // Handle Add Button Action
        function handleAddAction(event) {
            event.preventDefault();

            // Show SweetAlert2 for add action
            Swal.fire({
                title: 'Tambah Data Baru',
                text: 'Pilih kategori data yang ingin ditambahkan',
                icon: 'question',
                showCancelButton: true,
                showCloseButton: true,
                focusConfirm: false,
                confirmButtonText: '<i class="ti ti-plus"></i> Project',
                confirmButtonAriaLabel: 'Tambah Project',
                cancelButtonText: '<i class="ti ti-close"></i> Batal',
                cancelButtonAriaLabel: 'Batal',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-primary me-2',
                    cancelButton: 'btn btn-secondary'
                },
                didOpen: () => {
                    // Add additional buttons dynamically
                    const actions = [
                        { text: 'Ikm', icon: 'ti ti-users', action: '/project/dataIkm/create' },
                        { text: 'COTS', icon: 'ti ti-file-text', action: '/cots/create' }
                    ];

                    const container = Swal.getFooter();
                    actions.forEach((item) => {
                        const btn = document.createElement('button');
                        btn.className = 'btn btn-outline-primary ms-2';
                        btn.innerHTML = '<i class="' + item.icon + '"></i> ' + item.text;
                        btn.onclick = () => {
                            window.location.href = item.action;
                        };
                        container.appendChild(btn);
                    });
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '/project/create';
                }
            });
        }

        // Initialize guide modal on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Check if we should show the guide modal (first visit)
            const guideShown = localStorage.getItem('sidebarGuideShown');
            if (!guideShown) {
                // Show guide modal after 3 seconds on first visit
                setTimeout(() => {
                    const guideModal = document.getElementById('sidebarGuideModal');
                    if (guideModal) {
                        const modal = new bootstrap.Modal(guideModal);
                        modal.show();
                    }
                    localStorage.setItem('sidebarGuideShown', 'true');
                }, 3000);
            }
        });
    </script>
    <script>
const texts = [
    "Cari Nama Ikm...",
    "Cari Nama Project...",
    "Ayo mulai dari sekarang!",
    "Jangan tunda kesuksesanmu!",
    "Langkah kecil, hasil besar!",
    "Ide hebat dimulai di sini!",
    "Bersama menuju sukses!",
    "Yuk wujudkan mimpimu!",
    "Saatnya berkembang!",
    "Gas terus pantang mundur!"
];

let index = 0;
let charIndex = 0;
let isDeleting = false;

const input = document.getElementById("topSearch");

function typeEffect() {
    const currentText = texts[index];

    if (isDeleting) {
        charIndex--;
    } else {
        charIndex++;
    }

    input.placeholder = currentText.substring(0, charIndex);

    if (!isDeleting && charIndex === currentText.length) {
        isDeleting = true;
        setTimeout(typeEffect, 1500); // pause setelah selesai ngetik
        return;
    }

    if (isDeleting && charIndex === 0) {
        isDeleting = false;
        index = (index + 1) % texts.length;
    }

    setTimeout(typeEffect, isDeleting ? 50 : 80);
}

typeEffect();
</script>

    <!-- Toastr CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- FsLightbox JS -->
    <script src="{{ asset('assets/js/image-gallery.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/fslightbox@3.4.2/index.min.js"></script>
    <script src="{{ asset('assets/js/fslightbox-zoom.js') }}"></script>

    @stack('scripts')
</body>
</html>
