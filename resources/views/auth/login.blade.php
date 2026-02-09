<!doctype html>
<html lang="en">
<head>
        <meta charset="utf-8" />
        <title>Sign In | Responsive Bootstrap 5 Admin Dashboard Template</title>
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="description" content="Paces is a modern, responsive admin dashboard available on ThemeForest. Ideal for building CRM, CMS, project management tools, and custom web applications with a clean UI, flexible layouts, and rich features." />
        <meta name="keywords" content="Paces, admin dashboard, ThemeForest, Bootstrap 5 admin, responsive admin, CRM dashboard, CMS admin, web app UI, admin theme, premium admin template" />
        <meta name="author" content="Coderthemes" />

        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}" />
        <!-- Theme Config Js -->
        <script src="{{ asset('assets/js/config.js') }}"></script>

        <!-- Vendor css -->
        <link href="{{ asset('assets/css/vendors.min.css') }}" rel="stylesheet" type="text/css" />

        <!-- App css -->
        <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />

            </head>

    <body>
        <div class="position-absolute top-0 end-0">
            <img src="{{ asset('assets/images/auth-card-bg.svg') }}" class="auth-card-bg-img" alt="auth-card-bg" />
        </div>
        <div class="position-absolute bottom-0 start-0" style="transform: rotate(180deg)">
            <img src="{{ asset('assets/images/auth-card-bg.svg') }}" class="auth-card-bg-img" alt="auth-card-bg" />
        </div>
        <div class="auth-box overflow-hidden align-items-center d-flex">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xxl-5 col-md-6 col-sm-8">
                        <div class="card p-4">
                            <div class="auth-brand text-center mb-2">
                                <a href="index.html" class="logo-dark">
                                    <img src="{{ asset('assets/images/inopak/fav.png') }}" alt="dark logo" style="width: 60px; height: auto;" />
                                </a>
                                <a href="index.html" class="logo-light">
                                    <img src="{{ asset('assets/images/inopak/fav.png') }}" alt="logo" style="width: 60px; height: auto;" />
                                </a>
                                <h4 class="fw-bold text-dark mt-3">Database INOPAK</h4>
                                <p class="text-muted w-lg-75 mx-auto">Sistem Pengelolaan Informasi Database INOPAK</p>
                            </div>

                            <form action="https://coderthemes.com/paces/bootstrap/index.html">
                                <div class="mb-3">
                                    <label for="userEmail" class="form-label">
                                        Email address
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="email" class="form-control" id="userEmail" placeholder="you@example.com" required />
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="userPassword" class="form-label">
                                        Password
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="userPassword" placeholder="••••••••" required />
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input form-check-input-light fs-14" type="checkbox" id="showPassword" onchange="togglePassword()" />
                                        <label class="form-check-label" for="showPassword">Show password</label>
                                    </div>
                                    <a href="auth-reset-pass.html" class="text-decoration-underline link-offset-3 text-muted">Forgot Password?</a>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary fw-semibold py-2">Sign In</button>
                                </div>
                            </form>

                        </div>
                        <script>
                            function togglePassword() {
                                const passwordInput = document.getElementById('userPassword');
                                const showPasswordCheckbox = document.getElementById('showPassword');

                                if (showPasswordCheckbox.checked) {
                                    passwordInput.type = 'text';
                                } else {
                                    passwordInput.type = 'password';
                                }
                            }
                        </script>
                    </div>
                </div>
            </div>
        </div>

        <!-- end auth-fluid-->
        <!-- Vendor js -->
        <script src="{{ asset('assets/js/vendors.min.js') }}"></script>

        <!-- App js -->
        <script src="{{ asset('assets/js/app.js') }}"></script>

    </body>
</html>
