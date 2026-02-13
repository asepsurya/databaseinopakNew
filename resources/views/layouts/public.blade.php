<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="Database INOPAK - Sistem Pengelolaan Informasi" />
    <meta name="keywords" content="inopak, database, Ikm, Cots" />
    <meta name="author" content="INOPAK" />
    <title>@yield('title', 'Page') | Database INOPAK</title>

    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}" />
    <script src="{{ asset('assets/js/config.js') }}"></script>
    <link href="{{ asset('assets/css/vendors.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />

    <style>
    .public-wrapper {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        padding: 20px 0;
    }
    .public-content {
        background: white;
        border-radius: 10px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        padding: 30px;
    }
    </style>

    @stack('styles')
</head>

<body>
    <div class="public-wrapper">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="text-center mb-4">
                        <a href="/">
                            <img src="{{ asset('assets/images/inopak/fav.png') }}" alt="logo" style="width: 60px;">
                        </a>
                        <h3 class="text-white mt-2">Database INOPAK</h3>
                    </div>

                    <!-- Flash Messages -->
                    @if (session()->has('Berhasil'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('Berhasil') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session()->has('loginError'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('loginError') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Main Content -->
                    <div class="public-content">
                        @yield('content')
                    </div>

                    <div class="text-center mt-4">
                        <p class="text-white">{{ date('Y') }} &copy; Database INOPAK</p>
                        <a href="/login" class="btn btn-light btn-sm">
                            <i class="mdi mdi-login"></i> Login Admin
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/vendors.min.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>

    @stack('scripts')
</body>
</html>
