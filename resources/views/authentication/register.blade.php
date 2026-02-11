@extends('layouts.auth')

@section('title', 'Register - Database INOPAK')

@section('content')
<form action="{{ route('register.store') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">NIK <span class="text-danger">*</span></label>
                <input type="text" name="nik" class="form-control" required minlength="16" value="{{ old('nik') }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" name="nama" class="form-control" required value="{{ old('nama') }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Email <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Telp <span class="text-danger">*</span></label>
                <input type="text" name="telp" class="form-control" required value="{{ old('telp') }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                <select name="gender" class="form-select select2" required>
                    <option value="">Pilih</option>
                    <option value="L" {{ old('gender') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ old('gender') == 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Alamat <span class="text-danger">*</span></label>
                <textarea name="alamat" class="form-control" required>{{ old('alamat') }}</textarea>
            </div>
        </div>

        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">RT <span class="text-danger">*</span></label>
                <input type="text" name="rt" class="form-control" required value="{{ old('rt') }}">
            </div>
            <div class="mb-3">
                <label class="form-label">RW <span class="text-danger">*</span></label>
                <input type="text" name="rw" class="form-control" required value="{{ old('rw') }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Provinsi <span class="text-danger">*</span></label>
                <select name="id_provinsi" class="form-select select2" id="provinsi" required>
                    <option value="">Pilih Provinsi</option>
                    @foreach($provinsi as $p)
                        <option value="{{ $p->id }}" {{ old('id_provinsi') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Kota/Kabupaten <span class="text-danger">*</span></label>
                <select name="id_kota" class="form-select select2" id="kabupaten" required>
                    <option value="">Pilih Kota/Kabupaten</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Kecamatan <span class="text-danger">*</span></label>
                <select name="id_kecamatan" class="form-select select2" id="kecamatan" required>
                    <option value="">Pilih Kecamatan</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Desa/Kelurahan <span class="text-danger">*</span></label>
                <select name="id_desa" class="form-select select2" id="desa" required>
                    <option value="">Pilih Desa/Kelurahan</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Komunitas</label>
                <input type="text" name="komunitas" class="form-control" value="{{ old('komunitas') }}">
            </div>
        </div>

        <div class="col-12">
            <hr>
            <h6 class="mb-3">Informasi Akun</h6>
        </div>

        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Password <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input type="password" name="password" class="form-control" required minlength="6" id="passwordInput">
                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('passwordInput', 'passwordEye')" tabindex="-1">
                        <i class="ti ti-eye" id="passwordEye"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input type="password" name="confirmPassword" class="form-control" required id="confirmPasswordInput">
                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('confirmPasswordInput', 'confirmPasswordEye')" tabindex="-1">
                        <i class="ti ti-eye" id="confirmPasswordEye"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="d-grid">
        <button type="submit" class="btn btn-primary fw-semibold py-2">
            <span class="mdi mdi-account-plus"></span> Daftar
        </button>
    </div>

    <div class="text-center mt-4">
        <p class="text-muted mb-0">Sudah punya akun?
            <a href="/login" class="text-decoration-underline fw-bold">Login</a>
        </p>
    </div>
</form>

<script>
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
    // Initialize Select2 with Tabler theme configuration
    $('.select2').select2({
        placeholder: 'Pilih opsi',
        allowClear: false,
        width: '100%',
        minimumResultsForSearch: 5,
        dropdownCssClass: 'select2--tabler', // Custom class for Tabler styling
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
});
</script>
@endsection
