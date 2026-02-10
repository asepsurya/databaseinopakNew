@extends('layouts.master')

@section('page-title', 'Update IKM - ' . $project->namaProject)
@section('content')
<form action="{{ route('ikm.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @foreach($dataIkm as $ikm)
    <input type="hidden" name="id_ikm" value="{{ $ikm->id }}">
    <input type="hidden" name="id_Project" value="{{ $project->id }}">

    <div class="row">
        <div class="col-12 mb-3">
            <a href="/project/dataikm/{{ $project->id }}" class="btn btn-secondary">
                <i class="mdi mdi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-warning">
                    <h5 class="mb-0"><i class="mdi mdi-account me-2"></i>Data Pribadi</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Nama <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control" value="{{ $ikm->nama }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jenis Kelamin</label>
                        <select name="gender" class="form-select">
                            <option value="L" {{ $ikm->gender == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ $ikm->gender == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Telp</label>
                        <input type="text" name="telp" class="form-control" value="{{ $ikm->telp }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" class="form-control" rows="3">{{ $ikm->alamat }}</textarea>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <label class="form-label">RT</label>
                                <input type="text" name="rt" class="form-control" value="{{ $ikm->rt }}">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label class="form-label">RW</label>
                                <input type="text" name="rw" class="form-control" value="{{ $ikm->rw }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="mdi mdi-package-variant me-2"></i>Data Produk</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Jenis Produk <span class="text-danger">*</span></label>
                        <input type="text" name="jenisProduk" class="form-control" value="{{ $ikm->jenisProduk }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Merk</label>
                        <input type="text" name="merk" class="form-control" value="{{ $ikm->merk }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tagline</label>
                        <input type="text" name="tagline" class="form-control" value="{{ $ikm->tagline }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kelebihan</label>
                        <textarea name="kelebihan" class="form-control" rows="2">{{ $ikm->kelebihan }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gramasi</label>
                        <input type="text" name="gramasi" class="form-control" value="{{ $ikm->gramasi }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jenis Kemasan</label>
                        <input type="text" name="jenisKemasan" class="form-control" value="{{ $ikm->jenisKemasan }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Segmentasi</label>
                        <input type="text" name="segmentasi" class="form-control" value="{{ $ikm->segmentasi }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Harga</label>
                        <input type="text" name="harga" class="form-control" value="{{ $ikm->harga }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Varian</label>
                        <input type="text" name="varian" class="form-control" value="{{ $ikm->varian }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Komposisi</label>
                        <textarea name="komposisi" class="form-control" rows="2">{{ $ikm->komposisi }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="mdi mdi-certificate me-2"></i>Legalitas</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nama Usaha</label>
                                <input type="text" name="namaUsaha" class="form-control" value="{{ $ikm->namaUsaha }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">No PIRT</label>
                                <input type="text" name="noPIRT" class="form-control" value="{{ $ikm->noPIRT }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">No Halal</label>
                                <input type="text" name="noHalal" class="form-control" value="{{ $ikm->noHalal }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Legalitas Lain</label>
                                <input type="text" name="legalitasLain" class="form-control" value="{{ $ikm->legalitasLain }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12 text-end">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="mdi mdi-content-save"></i> Simpan Perubahan
            </button>
        </div>
    </div>
    @endforeach
</form>
@endsection
