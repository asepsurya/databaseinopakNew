<!DOCTYPE html>
<html lang="en" data-skin="neo" data-bs-theme="light" data-menu-color="gray" data-topbar-color="light" data-layout-width="fluid" dir="ltr" data-sidenav-size="condensed" data-layout-position="fixed" data-theme="light">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="Database INOPAK - Sistem Pengelolaan Informasi" />
    <meta name="keywords" content="inopak, database, ikm, admin dashboard" />
    <meta name="author" content="INOPAK" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') | Database INOPAK</title>

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}" />

    <!-- Theme Config Js -->
    <script src="{{ asset('assets/js/config.js') }}"></script>

    <!-- Vendor css -->
    <link href="{{ asset('assets/css/vendors.min.css') }}" rel="stylesheet" type="text/css" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/7.3.0/tinymce.min.js" referrerpolicy="origin"></script>

    <!-- App css -->
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <!-- Toastr Custom Styles -->
    <style>
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
                        <!-- Logo light -->
                        <a href="/dashboard" class="logo-light">
                            <span class="logo-lg">
                                <img src="{{ asset('assets/images/logo.png') }}" alt="logo" />
                            </span>
                            <span class="logo-sm">
                                <img src="{{ asset('assets/images/logo-sm.png') }}" alt="small logo" />
                            </span>
                        </a>

                        <!-- Logo Dark -->
                        <a href="/dashboard" class="logo-dark">
                            <span class="logo-lg">
                                <img src="{{ asset('assets/images/logo-black.png') }}" alt="dark logo" />
                            </span>
                            <span class="logo-sm">
                                <img src="{{ asset('assets/images/logo-sm.png') }}" alt="small logo" />
                            </span>
                        </a>
                    </div>

                    <!-- Sidebar Toggle Button -->
                    <button class="sidenav-toggle-button btn btn-primary btn-icon" id="sidebarToggleBtn">
                        <i class="ti ti-menu-4"></i>
                    </button>

                    <!-- Search Box -->
                    <div id="search-box" class="app-search d-none d-xl-flex">
                        <input type="search" class="form-control rounded-pill topbar-search" name="search" placeholder="Search..." id="topSearch" />
                        <i class="ti ti-search app-search-icon text-muted"></i>
                    </div>

                    <div id="megamenu-columns" class="topbar-item d-none d-md-flex">
                        <div class="dropdown">
                            <button class="topbar-link btn fw-medium btn-link dropdown-toggle drop-arrow-none px-2" data-bs-toggle="dropdown" type="button" aria-haspopup="false" aria-expanded="false">
                                Mega Menu
                                <i class="ti ti-chevron-down ms-1"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-xxl p-0">
                                <div class="h-100" style="max-height: 380px" data-simplebar>
                                    <div class="row g-0">
                                        <div class="col-md-4">
                                            <div class="p-2">
                                                <h5 class="mb-1 fw-semibold fs-sm dropdown-header">Quick Links</h5>
                                                <ul class="list-unstyled megamenu-list">
                                                    <li>
                                                        <a href="/dashboard" class="dropdown-item">
                                                            <i class="ti ti-chevron-right align-middle me-1 text-muted"></i>
                                                            Dashboard
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="/project" class="dropdown-item">
                                                            <i class="ti ti-chevron-right align-middle me-1 text-muted"></i>
                                                            Project
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="/project/dataikm/1" class="dropdown-item">
                                                            <i class="ti ti-chevron-right align-middle me-1 text-muted"></i>
                                                            Data IKM
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="/cots" class="dropdown-item">
                                                            <i class="ti ti-chevron-right align-middle me-1 text-muted"></i>
                                                            COTS
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="/report" class="dropdown-item">
                                                            <i class="ti ti-chevron-right align-middle me-1 text-muted"></i>
                                                            Report
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                    <div class="topbar-item d-none d-sm-flex">
                        <button class="topbar-link" type="button" id="sidebarShowHideBtn" title="Show/Hide Sidebar">
                            <i class="ti ti-layout-sidebar-left-collapse topbar-link-icon"></i>
                        </button>
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
                    <div class="topbar-item d-none d-sm-flex">
                        <button class="topbar-link btn-theme-setting" data-bs-toggle="offcanvas" data-bs-target="#theme-settings-offcanvas" type="button">
                            <i class="ti ti-settings topbar-link-icon"></i>
                        </button>
                    </div>

                    <!-- User Profile -->
                    <div class="topbar-item nav-user">
                        <div class="dropdown">
                            <a class="topbar-link dropdown-toggle drop-arrow-none px-2" data-bs-toggle="dropdown" href="#!" aria-haspopup="false" aria-expanded="false">
                                @if(auth()->user()->profile_photo && Storage::disk('public')->exists(auth()->user()->profile_photo))
                                    <img src="/storage/{{ auth()->user()->profile_photo }}" width="32" class="rounded-circle me-lg-2 d-flex" alt="user-image" />
                                @else
                                    <img src="{{ asset('assets/images/users/avatar-1.jpg') }}" width="32" class="rounded-circle me-lg-2 d-flex" alt="user-image" />
                                @endif
                                <div class="d-lg-flex align-items-center gap-1 d-none">
                                    <span>
                                        <h5 class="my-0 lh-1 pro-username">{{ Auth::user()->nama ?? 'User' }}</h5>
                                        <span class="fs-xs lh-1">Admin</span>
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

        <!-- Sidebar Guide Modal -->
        <div class="modal fade sidebar-guide-modal" id="sidebarGuideModal" tabindex="-1" aria-labelledby="sidebarGuideModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="sidebarGuideModalLabel">
                            <i class="ti ti-help me-2"></i>Panduan Penggunaan Sidebar
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12 mb-4">
                                <h6 class="fw-bold text-primary mb-3">
                                    <i class="ti ti-layout-sidebar me-2"></i>Navigasi Sidebar
                                </h6>
                                <div class="guide-item d-flex align-items-center">
                                    <div class="guide-icon bg-primary-subtle text-primary">
                                        <i class="ti ti-dashboard fs-5"></i>
                                    </div>
                                    <div>
                                        <strong>Dashboard</strong>
                                        <p class="text-muted mb-0 small">Halaman utama untuk melihat ringkasan sistem</p>
                                    </div>
                                </div>
                                <div class="guide-item d-flex align-items-center">
                                    <div class="guide-icon bg-success-subtle text-success">
                                        <i class="ti ti-folder fs-5"></i>
                                    </div>
                                    <div>
                                        <strong>Project</strong>
                                        <p class="text-muted mb-0 small">Kelola dan pantau project yang sedang berjalan</p>
                                    </div>
                                </div>
                                <div class="guide-item d-flex align-items-center">
                                    <div class="guide-icon bg-info-subtle text-info">
                                        <i class="ti ti-users fs-5"></i>
                                    </div>
                                    <div>
                                        <strong>Data IKM</strong>
                                        <p class="text-muted mb-0 small">Kelola data Industri Kecil Menengah (IKM)</p>
                                    </div>
                                </div>
                                <div class="guide-item d-flex align-items-center">
                                    <div class="guide-icon bg-warning-subtle text-warning">
                                        <i class="ti ti-bulb fs-5"></i>
                                    </div>
                                    <div>
                                        <strong>Brainstorming</strong>
                                        <p class="text-muted mb-0 small">Fitur untuk ide dan brainstorming project</p>
                                    </div>
                                </div>
                                <div class="guide-item d-flex align-items-center">
                                    <div class="guide-icon bg-secondary-subtle text-secondary">
                                        <i class="ti ti-palette fs-5"></i>
                                    </div>
                                    <div>
                                        <strong>Kurasi</strong>
                                        <p class="text-muted mb-0 small">Kelola kurasi produk dan design</p>
                                    </div>
                                </div>
                                <div class="guide-item d-flex align-items-center">
                                    <div class="guide-icon bg-danger-subtle text-danger">
                                        <i class="ti ti-file-text fs-5"></i>
                                    </div>
                                    <div>
                                        <strong>COTS</strong>
                                        <p class="text-muted mb-0 small">Kelola Commercial Off-The-Shelf</p>
                                    </div>
                                </div>
                                <div class="guide-item d-flex align-items-center">
                                    <div class="guide-icon bg-dark-subtle text-dark">
                                        <i class="ti ti-chart-bar fs-5"></i>
                                    </div>
                                    <div>
                                        <strong>Report</strong>
                                        <p class="text-muted mb-0 small">Lihat laporan dan statistik system</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <h6 class="fw-bold text-primary mb-3">
                                    <i class="ti ti-plus-circle me-2"></i>Tombol Tambah
                                </h6>
                                <div class="guide-item d-flex align-items-center">
                                    <div class="guide-icon" style="background: linear-gradient(135deg, #435ebe 0%, #2c4a9e 100%);">
                                        <i class="ti ti-plus text-white fs-5"></i>
                                    </div>
                                    <div>
                                        <strong>Tambah Data Baru</strong>
                                        <p class="text-muted mb-0 small">Klik tombol "Tambah" untuk menambahkan data baru ke sistem</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="ti ti-x me-1"></i>Tutup
                        </button>
                        <a href="/dashboard" class="btn btn-primary">
                            <i class="ti ti-home me-1"></i>Ke Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidenav Menu -->
        <div class="sidenav-menu" id="sidenavMenu">
            <!-- Brand Logo -->
            <a href="/dashboard" class="logo">
                <span class="logo logo-light">
                    <span class="logo-lg"><img src="{{ asset('assets/images/logo.png') }}" alt="logo" /></span>
                    <span class="logo-sm"><img src="{{ asset('assets/images/logo-sm.png') }}" alt="small logo" /></span>
                </span>
                <span class="logo logo-dark">
                    <span class="logo-lg"><img src="{{ asset('assets/images/logo-black.png') }}" alt="dark logo" /></span>
                    <span class="logo-sm"><img src="{{ asset('assets/images/logo-sm.png') }}" alt="small logo" /></span>
                </span>
            </a>

            <!-- Sidebar User -->
            <div id="user-profile-settings" class="sidenav-user" style="background: url({{ asset('assets/images/user-bg-pattern.svg') }})">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <a href="#!" class="link-reset">
                            <img src="{{ asset('assets/images/users/avatar-1.jpg') }}" alt="user-image" class="rounded-circle mb-2 avatar-md" />
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

                    <li class="side-nav-item">
                        <a href="/project/dataikm/1" class="side-nav-link">
                            <span class="menu-icon"><i class="ti ti-users"></i></span>
                            <span class="menu-text">Data IKM</span>
                        </a>
                    </li>

                    <li class="side-nav-item">
                        <a href="/brainstorming" class="side-nav-link">
                            <span class="menu-icon"><i class="ti ti-bulb"></i></span>
                            <span class="menu-text">Brainstorming</span>
                        </a>
                    </li>

                    <li class="side-nav-item">
                        <a href="/kurasi" class="side-nav-link">
                            <span class="menu-icon"><i class="ti ti-palette"></i></span>
                            <span class="menu-text">Kurasi</span>
                        </a>
                    </li>

                    <li class="side-nav-item">
                        <a href="/cots" class="side-nav-link">
                            <span class="menu-icon"><i class="ti ti-file-text"></i></span>
                            <span class="menu-text">COTS</span>
                        </a>
                    </li>

                    <li class="side-nav-item">
                        <a href="/report" class="side-nav-link">
                            <span class="menu-icon"><i class="ti ti-chart-bar"></i></span>
                            <span class="menu-text">Report</span>
                        </a>
                    </li>

                    <!-- Sidebar Guide -->
                    <li class="side-nav-title mt-2">Panduan</li>
                    <li class="side-nav-item">
                        <a href="#" class="side-nav-link" data-bs-toggle="modal" data-bs-target="#sidebarGuideModal">
                            <span class="menu-icon"><i class="ti ti-help"></i></span>
                            <span class="menu-text">Panduan</span>
                        </a>
                    </li>



                    @if(Auth::user())
                    <li class="side-nav-item">
                        <a href="/profile" class="side-nav-link">
                            <span class="menu-icon"><i class="ti ti-user"></i></span>
                            <span class="menu-text">Profile</span>
                        </a>
                    </li>
                    @endif

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
    // Sidebar Show/Hide Toggle
    const sidebarShowHideBtn = document.getElementById('sidebarShowHideBtn');
    const sidebarShowHide = document.getElementById('sidebar-showhide');
    const sidenavMenu = document.getElementById('sidenavMenu');
    const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');

    if (sidebarShowHideBtn && sidebarShowHide && sidenavMenu) {
        // Toggle from button
        sidebarShowHideBtn.addEventListener('click', function() {
            sidenavMenu.classList.toggle('d-none');
            localStorage.setItem('sidebarHidden', sidenavMenu.classList.contains('d-none'));
        });

        // Toggle from settings
        const sidebarShowHideCheckbox = document.getElementById('sidebarShowHide');
        if (sidebarShowHideCheckbox) {
            sidebarShowHideCheckbox.addEventListener('change', function() {
                sidenavMenu.classList.toggle('d-none', !this.checked);
                localStorage.setItem('sidebarHidden', sidenavMenu.classList.contains('d-none'));
            });
        }

        // Load saved state
        const sidebarHidden = localStorage.getItem('sidebarHidden') === 'true';
        if (sidebarHidden) {
            sidenavMenu.classList.add('d-none');
            if (sidebarShowHideCheckbox) sidebarShowHideCheckbox.checked = false;
        }
    }

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

    // Search functionality
    document.getElementById('topSearch').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            const query = this.value.trim();
            if (query) {
                // Implement search logic here
                alert('Search: ' + query);
            }
        }
    });

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
                        { text: 'IKM', icon: 'ti ti-users', action: '/project/dataikm/create' },
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

    <!-- Toastr CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- FsLightbox JS -->
    <script src="https://cdn.jsdelivr.net/npm/fslightbox@3.4.2/index.min.js"></script>

    @stack('scripts')
</body>
</html>
