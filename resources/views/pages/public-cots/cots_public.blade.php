@extends('layouts.public')

@section('title', 'Form COTS - Database INOPAK')

@section('content')
<h4 class="text-center mb-4">Formulir Pendaftaran COTS</h4>
<p class="text-center text-muted mb-4">Silakan lengkapi data di bawah ini dengan benar.</p>

<form action="{{ route('cots.save') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-md-6">
            <h5 class="mb-3"><i class="mdi mdi-account text-primary"></i> Data Pribadi</h5>
            <div class="mb-3">
                <label class="form-label">Nama <span class="text-danger">*</span></label>
                <input type="text" name="nama" class="form-control" required value="{{ old('nama') }}">
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
                <label class="form-label">Telp <span class="text-danger">*</span></label>
                <input type="text" name="telp" class="form-control" required value="{{ old('telp') }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Alamat <span class="text-danger">*</span></label>
                <textarea name="alamat" class="form-control" required>{{ old('alamat') }}</textarea>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="mb-3">
                        <label class="form-label">RT <span class="text-danger">*</span></label>
                        <input type="text" name="rt" class="form-control" required value="{{ old('rt') }}">
                    </div>
                </div>
                <div class="col-6">
                    <div class="mb-3">
                        <label class="form-label">RW <span class="text-danger">*</span></label>
                        <input type="text" name="rw" class="form-control" required value="{{ old('rw') }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <h5 class="mb-3"><i class="mdi mdi-map-marker text-primary"></i> Lokasi</h5>
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
                <label class="form-label">Project <span class="text-danger">*</span></label>
                <select name="id_Project" class="form-select" required>
                    <option value="">Pilih Project</option>
                    @foreach($project as $p)
                        <option value="{{ $p->id }}">{{ $p->namaProject }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <hr class="my-4">

    <div class="row">
        <div class="col-12">
            <h5 class="mb-3"><i class="mdi mdi-file-document text-primary"></i> Data COTS</h5>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Sejarah Singkat</label>
                <textarea name="sejarahSingkat" class="form-control" rows="3">{{ old('sejarahSingkat') }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Produk yang Dijual</label>
                <textarea name="produkjual" class="form-control" rows="2">{{ old('produkjual') }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Cara Pemasaran</label>
                <textarea name="carapemasaran" class="form-control" rows="2">{{ old('carapemasaran') }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Bahan Baku</label>
                <textarea name="bahanbaku" class="form-control" rows="2">{{ old('bahanbaku') }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Proses Produksi</label>
                <textarea name="prosesproduksi" class="form-control" rows="2">{{ old('prosesproduksi') }}</textarea>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Omset</label>
                <input type="text" name="omset" class="form-control" value="{{ old('omset') }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Kapasitas Produksi</label>
                <input type="text" name="kapasitasProduksi" class="form-control" value="{{ old('kapasitasProduksi') }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Kendala</label>
                <textarea name="kendala" class="form-control" rows="2">{{ old('kendala') }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Solusi</label>
                <textarea name="solusi" class="form-control" rows="2">{{ old('solusi') }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Foto Profil</label>
                <input type="file" name="gambar" class="form-control" accept="image/*">
            </div>
            <div class="mb-3">
                <label class="form-label">Gallery Foto (bisa lebih dari 1)</label>
                <input type="file" name="gambargallery[]" class="form-control" accept="image/*" multiple>
            </div>
        </div>
    </div>

    <div class="d-grid gap-2">
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="mdi mdi-content-save"></i> Simpan Data
        </button>
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
