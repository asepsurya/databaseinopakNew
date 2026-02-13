@extends('layouts.auth')

@section('title', 'Sign In - Database INOPAK')

@section('content')
<style>
    /* Mobile */
@media (max-width: 576px) {
    .auth-box {
        padding: 0 !important;
    }
}
</style>
<form action="{{ route('login.process') }}" method="POST" id="loginForm">
    @csrf
    <div class="mb-3">
        <label for="userEmail" class="form-label">
            Email address
            <span class="text-danger">*</span>
        </label>
        <div class="input-group">
            <input type="email" class="form-control @error('email') is-invalid @enderror"
                   id="userEmail" placeholder="you@example.com" required
                   name="email" value="{{ old('email') }}"/>
            @error('email')
                <span class="invalid-feedback d-block">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="mb-3">
        <label for="userPassword" class="form-label">
            Password
            <span class="text-danger">*</span>
        </label>
        <div class="input-group">
            <input type="password" class="form-control @error('password') is-invalid @enderror"
                   id="userPassword" placeholder="Masukkan password" required name="password" />
            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()" tabindex="-1">
                <i class="ti ti-eye" id="eyeIcon"></i>
            </button>
            @error('password')
                <span class="invalid-feedback d-block">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="form-check">
            <input class="form-check-input form-check-input-light fs-14" type="checkbox" id="remember" />
            <label class="form-check-label" for="remember">Remember me</label>
        </div>
        <a href="#" class="text-decoration-underline link-offset-3 text-muted">Forgot Password?</a>
    </div>

    <div class="d-grid">
        <button type="submit" class="btn btn-primary fw-semibold py-2" id="submitBtn">
            <span class="mdi mdi-login"></span> Sign In
        </button>
    </div>

    <div class="text-center mt-4">
        <p class="text-muted mb-0">Belum punya akun?
            <a href="/register" class="text-decoration-underline fw-bold">Daftar Sekarang</a>
        </p>
    </div>
</form>

<script>
function togglePassword() {
    const passwordInput = document.getElementById('userPassword');
    const eyeIcon = document.getElementById('eyeIcon');

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.classList.remove('ti-eye');
        eyeIcon.classList.add('ti-eye-off');
    } else {
        passwordInput.type = 'password';
        eyeIcon.classList.remove('ti-eye-off');
        eyeIcon.classList.add('ti-eye');
    }
}

document.getElementById('loginForm').addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Loading...';

    // Enable button after 30 seconds as fallback
    setTimeout(function() {
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<span class="mdi mdi-login"></span> Sign In';
    }, 30000);
});
</script>
@endsection
