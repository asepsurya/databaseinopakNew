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
                <select name="gender" class="form-select" required>
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
                <select name="id_provinsi" class="form-select" id="provinsi" required>
                    <option value="">Pilih Provinsi</option>
                    @foreach($provinsi as $p)
                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Kota/Kabupaten <span class="text-danger">*</span></label>
                <select name="id_kota" class="form-select" id="kota" required>
                    <option value="">Pilih Kota/Kabupaten</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Kecamatan <span class="text-danger">*</span></label>
                <select name="id_kecamatan" class="form-select" id="kecamatan" required>
                    <option value="">Pilih Kecamatan</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Desa/Kelurahan <span class="text-danger">*</span></label>
                <select name="id_desa" class="form-select" id="desa" required>
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
                <input type="password" name="password" class="form-control" required minlength="6">
            </div>
        </div>

        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                <input type="password" name="confirmPassword" class="form-control" required>
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
document.getElementById('provinsi').addEventListener('change', function() {
    var id_provinsi = this.value;
    if(id_provinsi) {
        fetch('/getkabupaten?id_provinsi=' + id_provinsi)
            .then(response => response.text())
            .then(data => {
                document.getElementById('kota').innerHTML = data;
                document.getElementById('kecamatan').innerHTML = '<option value="">Pilih Kecamatan</option>';
                document.getElementById('desa').innerHTML = '<option value="">Pilih Desa/Kelurahan</option>';
            });
    }
});

document.getElementById('kota').addEventListener('change', function() {
    var id_kabupaten = this.value;
    if(id_kabupaten) {
        fetch('/getkecamatan?id_kabupaten=' + id_kabupaten)
            .then(response => response.text())
            .then(data => {
                document.getElementById('kecamatan').innerHTML = data;
                document.getElementById('desa').innerHTML = '<option value="">Pilih Desa/Kelurahan</option>';
            });
    }
});

document.getElementById('kecamatan').addEventListener('change', function() {
    var id_kecamatan = this.value;
    if(id_kecamatan) {
        fetch('/getdesa?id_kecamatan=' + id_kecamatan)
            .then(response => response.text())
            .then(data => {
                document.getElementById('desa').innerHTML = data;
            });
    }
});
</script>
@endsection
