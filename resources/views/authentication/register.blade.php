@extends('layouts.auth')

@section('title', 'Register - Database INOPAK')

@section('content')
    <style>

    /* tinggi sama seperti input */

    /* Tinggi konsisten */
    .select2-container .select2-selection--single {
        height: 48px;
        display: flex;
        align-items: center;
    }

    /* Text & placeholder */
    .select2-container--default .select2-selection--single
    .select2-selection__rendered {
        line-height: normal !important;
        padding-left: 12px;
        padding-right: 20px;
    }

    /* Placeholder */
    .select2-container--default
    .select2-selection__placeholder {
        color: #adb5bd;
        display: flex;
        align-items: center;
        height: 100%;
    }
    @media (max-width: 576px) {
    .auth-box {
        padding: 0 !important;
        margin-top:30px;
    }
}
    .stepper-wrapper {
        display: flex;
        justify-content: space-between;
        margin-bottom: 2rem;
        position: relative;
    }

    .stepper-wrapper::before {
        content: '';
        position: absolute;
        top: 20px;
        left: 10%;
        right: 10%;
        height: 2px;
        background: #e4e6ef;
        z-index: 0;
    }

    [data-bs-theme="dark"] .stepper-wrapper::before {
        background: #33354a;
    }

    .stepper-item {
        position: relative;
        z-index: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        flex: 1;
    }

    .stepper-counter {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #fff;
        border: 2px solid #e4e6ef;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        color: #6c757d;
        transition: all 0.3s;
    }

    [data-bs-theme="dark"] .stepper-counter {
        background: #1f1f2e;
        border-color: #33354a;
        color: #a1a4c0;
    }

    .stepper-item.active .stepper-counter,
    .stepper-item.completed .stepper-counter {
        background: #0d6efd;
        border-color: #0d6efd;
        color: #fff;
    }

    .stepper-label {
        margin-top: 8px;
        font-size: 0.75rem;
        color: #6c757d;
        text-align: center;
    }

    .stepper-item.active .stepper-label,
    .stepper-item.completed .stepper-label {
        color: #0d6efd;
        font-weight: 500;
    }

    .step-content {
        display: none;
    }

    .step-content.active {
        display: block;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .btn-step {
        min-width: 120px;
    }

    .register-form .form-label {
        font-weight: 500;
        font-size: 0.875rem;
    }

    .register-form .form-control,
    .register-form .form-select {
        padding: 0.625rem 0.875rem;
    }

    .register-form textarea.form-control {
        min-height: 80px;
        resize: vertical;
    }

    [data-bs-theme="dark"] .stepper-counter {
        background: #2a2c40;
    }

    /* Custom Select2 Styling to Match App Theme */
    .select2-container .select2-selection--single {
        height: 42px;
        border: 1px solid #e4e6ef;
        border-radius: 0.375rem;
        background-color: #fff;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 28px;
        padding-left: 12px;
        padding-right: 30px;
        color: #495057;
        display: flex;
        align-items: center;
    }

    .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: #6c757d;
        display: flex;
        align-items: center;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 40px;
        width: 40px;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow b {
        border-color: #6c757d transparent transparent transparent;
        border-width: 6px 6px 0 6px;
        margin-left: -12px;
        margin-top: -3px;
    }

    .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
        border-color: transparent transparent #6c757d transparent;
        border-width: 0 6px 6px 6px;
    }

    .select2-dropdown {
        border: 1px solid #e4e6ef;
        border-radius: 0.375rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .select2-container--default .select2-results__option[aria-selected=true] {
        background-color: #f3f4f6;
    }

    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #0d6efd;
        color: white;
    }

    .select2-search--dropdown {
        padding: 8px;
    }

    .select2-container--default .select2-search--dropdown .select2-search__field {
        border: 1px solid #e4e6ef;
        border-radius: 0.25rem;
        padding: 8px 12px;
    }

    /* Dark mode support for Select2 */
    [data-bs-theme="dark"] .select2-container .select2-selection--single {
        background-color: #1f1f2e;
        border-color: #33354a;
        color: #e6e7f2;
    }

    [data-bs-theme="dark"] .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #e6e7f2;
        display: flex;
        align-items: center;
    }

    [data-bs-theme="dark"] .select2-dropdown {
        background-color: #1f1f2e;
        border-color: #33354a;
    }

    [data-bs-theme="dark"] .select2-container--default .select2-results__option {
        color: #e6e7f2;
    }

    [data-bs-theme="dark"] .select2-container--default .select2-search--dropdown .select2-search__field {
        background-color: #2a2c40;
        border-color: #33354a;
        color: #e6e7f2;
    }

    [data-bs-theme="dark"] .select2-container--default .select2-results__option[aria-selected=true] {
        background-color: #2a2c40;
    }
</style>

<form action="{{ route('register.store') }}" method="POST" class="register-form" id="registerForm">
    @csrf

    <!-- Stepper -->
    <div class="stepper-wrapper">
        <div class="stepper-item active" data-step="1">
            <div class="stepper-counter">1</div>
            <div class="stepper-label">Data Diri</div>
        </div>
        <div class="stepper-item" data-step="2">
            <div class="stepper-counter">2</div>
            <div class="stepper-label">Alamat</div>
        </div>
        <div class="stepper-item" data-step="3">
            <div class="stepper-counter">3</div>
            <div class="stepper-label">Akun</div>
        </div>
    </div>

    <!-- Step 1: Personal Information -->
    <div class="step-content active" data-step="1">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="mb-1">
                    <label class="form-label">NIK <span class="text-danger">*</span></label>
                    <input type="text" name="nik" class="form-control" required minlength="16" value="{{ old('nik') }}" placeholder="Masukkan 16 digit NIK">
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-1">
                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control" required value="{{ old('nama') }}" placeholder="Masukkan nama lengkap">
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-1">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control" required value="{{ old('email') }}" placeholder="name@example.com">
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-1">
                    <label class="form-label">No. Telepon <span class="text-danger">*</span></label>
                    <input type="text" name="telp" class="form-control" required value="{{ old('telp') }}" placeholder="08xxxxxxxxxx">
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-1">
                    <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                    <select name="gender" class="form-select select2" required>
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="L" {{ old('gender') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('gender') == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-1">
                    <label class="form-label">Komunitas</label>
                    <input type="text" name="komunitas" class="form-control" value="{{ old('komunitas') }}" placeholder="Nama komunitas (opsional)">
                </div>
            </div>

            <div class="col-12">
                <div class="mb-1">
                    <label class="form-label">Alamat <span class="text-danger">*</span></label>
                    <textarea name="alamat" class="form-control" required placeholder="Masukkan alamat lengkap">{{ old('alamat') }}</textarea>
                </div>
            </div>
        </div>

        <div class="d-flex mt-4">
            <button type="button"
                    class="btn btn-primary btn-step w-100"
                    onclick="nextStep(1)">
                Next <i class="ti ti-arrow-right ms-1"></i>
            </button>
        </div>
    </div>

    <!-- Step 2: Address Information -->
    <div class="step-content" data-step="2">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="mb-1">
                    <label class="form-label">RT <span class="text-danger">*</span></label>
                    <input type="text" name="rt" class="form-control" required value="{{ old('rt') }}" placeholder="001">
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-1">
                    <label class="form-label">RW <span class="text-danger">*</span></label>
                    <input type="text" name="rw" class="form-control" required value="{{ old('rw') }}" placeholder="001">
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-1">
                    <label class="form-label">Provinsi <span class="text-danger">*</span></label>
                    <select name="id_provinsi" class="form-select select2" id="provinsi" required>
                        <option value="">Pilih Provinsi</option>
                        @foreach($provinsi as $p)
                            <option value="{{ $p->id }}" {{ old('id_provinsi') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-1">
                    <label class="form-label">Kota/Kabupaten <span class="text-danger">*</span></label>
                    <select name="id_kota" class="form-select select2" id="kabupaten" required>
                        <option value="">Pilih Kota/Kabupaten</option>
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-1">
                    <label class="form-label">Kecamatan <span class="text-danger">*</span></label>
                    <select name="id_kecamatan" class="form-select select2" id="kecamatan" required>
                        <option value="">Pilih Kecamatan</option>
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-1">
                    <label class="form-label">Desa/Kelurahan <span class="text-danger">*</span></label>
                    <select name="id_desa" class="form-select select2" id="desa" required>
                        <option value="">Pilih Desa/Kelurahan</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <button type="button" class="btn btn-outline-secondary btn-step" onclick="prevStep(2)">
                <i class="ti ti-arrow-left me-1"></i> Back
            </button>
            <button type="button" class="btn btn-primary btn-step" onclick="nextStep(2)">
                Next <i class="ti ti-arrow-right ms-1"></i>
            </button>
        </div>
    </div>

    <!-- Step 3: Account Information -->
    <div class="step-content" data-step="3">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="mb-1">
                    <label class="form-label">Password <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="password" name="password" class="form-control" required minlength="6" id="passwordInput" placeholder="Min. 6 karakter">
                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('passwordInput', 'passwordEye')" tabindex="-1">
                            <i class="ti ti-eye" id="passwordEye"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-1">
                    <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="password" name="confirmPassword" class="form-control" required id="confirmPasswordInput" placeholder="Masukkan password lagi">
                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('confirmPasswordInput', 'confirmPasswordEye')" tabindex="-1">
                            <i class="ti ti-eye" id="confirmPasswordEye"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <button type="button" class="btn btn-outline-secondary btn-step" onclick="prevStep(3)">
                <i class="ti ti-arrow-left me-1"></i> Back
            </button>
            <button type="submit" class="btn btn-success btn-step">
                <i class="ti ti-check me-1"></i> Daftar
            </button>
        </div>
    </div>
</form>

<div class="text-center mt-4">
    <p class="text-muted mb-0">Sudah punya akun?
        <a href="/login" class="text-decoration-underline fw-bold">Login</a>
    </p>
</div>

<script>
let currentStep = 1;
const totalSteps = 3;

function updateStepper() {
    document.querySelectorAll('.stepper-item').forEach(item => {
        const step = parseInt(item.dataset.step);
        item.classList.remove('active', 'completed');

        if (step < currentStep) {
            item.classList.add('completed');
        } else if (step === currentStep) {
            item.classList.add('active');
        }
    });

    document.querySelectorAll('.step-content').forEach(content => {
        content.classList.remove('active');
        if (parseInt(content.dataset.step) === currentStep) {
            content.classList.add('active');
        }
    });

    // Reinitialize Select2 for newly visible selects
    $('.select2').select2({
        placeholder: 'Pilih opsi',
        allowClear: false,
        width: '100%',
        minimumResultsForSearch: 5,
        dropdownCssClass: '',
        escapeMarkup: function(markup) {
            return markup;
        }
    });
}

function nextStep(step) {
    // Validate current step fields
    const currentContent = document.querySelector(`.step-content[data-step="${step}"]`);
    const inputs = currentContent.querySelectorAll('input[required], select[required], textarea[required]');
    let isValid = true;

    inputs.forEach(input => {
        if (!input.value.trim()) {
            input.classList.add('is-invalid');
            isValid = false;
        } else {
            input.classList.remove('is-invalid');
        }
    });

    if (!isValid) {
        return;
    }

    if (currentStep < totalSteps) {
        currentStep++;
        updateStepper();
    }
}

function prevStep(step) {
    if (currentStep > 1) {
        currentStep--;
        updateStepper();
    }
}

// Toggle password visibility
function togglePassword(inputId, eyeIconId) {
    const passwordInput = document.getElementById(inputId);
    const eyeIcon = document.getElementById(eyeIconId);

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

$(document).ready(function() {
    // Initialize Select2
    $('.select2').select2({
        placeholder: 'Pilih opsi',
        allowClear: false,
        width: '100%',
        minimumResultsForSearch: 5,
        dropdownCssClass: '',
        escapeMarkup: function(markup) {
            return markup;
        }
    });

    // Setup CSRF token for AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Province change handler - load kabupatens
    $('#provinsi').on('change', function() {
        var id_provinsi = $(this).val();

        if (id_provinsi) {
            $.ajax({
                type: 'POST',
                url: "{{ route('getkabupaten') }}",
                data: { id_provinsi: id_provinsi },
                cache: false,
                success: function(msg) {
                    $('#kabupaten').html(msg).trigger('change');
                    $('#kecamatan').html('<option value="">Pilih Kecamatan</option>').trigger('change');
                    $('#desa').html('<option value="">Pilih Desa/Kelurahan</option>').trigger('change');
                },
                error: function(xhr, status, error) {
                    console.log('Error loading kabupatens:', error);
                }
            });
        } else {
            $('#kabupaten').html('<option value="">Pilih Kota/Kabupaten</option>').trigger('change');
            $('#kecamatan').html('<option value="">Pilih Kecamatan</option>').trigger('change');
            $('#desa').html('<option value="">Pilih Desa/Kelurahan</option>').trigger('change');
        }
    });

    // Kabupaten change handler - load kecamatans
    $('#kabupaten').on('change', function() {
        var id_kabupaten = $(this).val();

        if (id_kabupaten) {
            $.ajax({
                type: 'POST',
                url: "{{ route('getkecamatan') }}",
                data: { id_kabupaten: id_kabupaten },
                cache: false,
                success: function(msg) {
                    $('#kecamatan').html(msg).trigger('change');
                    $('#desa').html('<option value="">Pilih Desa/Kelurahan</option>').trigger('change');
                },
                error: function(xhr, status, error) {
                    console.log('Error loading kecamatans:', error);
                }
            });
        } else {
            $('#kecamatan').html('<option value="">Pilih Kecamatan</option>').trigger('change');
            $('#desa').html('<option value="">Pilih Desa/Kelurahan</option>').trigger('change');
        }
    });

    // Kecamatan change handler - load desas
    $('#kecamatan').on('change', function() {
        var id_kecamatan = $(this).val();

        if (id_kecamatan) {
            $.ajax({
                type: 'POST',
                url: "{{ route('getdesa') }}",
                data: { id_kecamatan: id_kecamatan },
                cache: false,
                success: function(msg) {
                    $('#desa').html(msg).trigger('change');
                },
                error: function(xhr, status, error) {
                    console.log('Error loading desas:', error);
                }
            });
        } else {
            $('#desa').html('<option value="">Pilih Desa/Kelurahan</option>').trigger('change');
        }
    });

    // Remove validation class on input
    $('input, select, textarea').on('input change', function() {
        $(this).removeClass('is-invalid');
    });
});
</script>
@endsection
