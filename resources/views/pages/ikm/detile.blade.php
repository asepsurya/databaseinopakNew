@extends('layouts.master')

@section('title', 'Detail Ikm - ' . ($Ikm->first()->nama ?? 'N/A'))


@section('content')
<style>
.design-gallery{
    padding: 0%;

}

.empty-box {
    background: #f8f9fc;
    border-radius: 14px;
    padding: 40px 20px;
    text-align: center;
    border: 1px dashed #e4e6ef;
    transition: 0.3s;
}

.empty-icon {
    width: 70px;
    height: 70px;
    margin: 0 auto 15px;
    border-radius: 50%;
    background: #eef2ff;
    display: flex;
    align-items: center;
    justify-content: center;
}

.empty-icon i {
    font-size: 32px;
    color: #6366f1;
}

.empty-title {
    font-weight: 600;
    margin-bottom: 6px;
}

.empty-desc {
    color: #888;
    font-size: 13px;
    margin-bottom: 18px;
}

/* ========================= */
/* DARK MODE SUPPORT */
/* ========================= */

[data-bs-theme="dark"] .empty-box {
    background: #1f1f2e;
    border-color: #33354a;
}

[data-bs-theme="dark"] .empty-icon {
    background: #2a2c40;
}

[data-bs-theme="dark"] .empty-icon i {
    color: #8b8dff;
}

[data-bs-theme="dark"] .empty-title {
    color: #e6e7f2;
}

[data-bs-theme="dark"] .empty-desc {
    color: #a1a4c0;
}

/* COTS Dark Mode Support */
[data-bs-theme="dark"] #tab-Cots .table-bordered {
    border-color: #33354a;
}

[data-bs-theme="dark"] #tab-Cots .table-bordered td {
    border-color: #33354a;
}

[data-bs-theme="dark"] #tab-Cots .cots-label-cell {
    background-color: #2a2c4000 !important;
    color: #e6e7f2;
}

[data-bs-theme="dark"] #tab-Cots .cots-label-cell strong {
    color: #e6e7f2;
}

[data-bs-theme="dark"] #tab-Cots td:nth-child(2) {

}

[data-bs-theme="dark"] #tab-Cots .inline-editor {
    color: #e6e7f2;

}

[data-bs-theme="dark"] #tab-Cots .inline-editor:empty:before {
    color: #6c757d;
}

[data-bs-theme="dark"] #tab-Cots h5.cots-title {
    color: #e6e7f2;
}

[data-bs-theme="dark"] #tab-Cots .table {
    --bs-table-bg: #1f1f2e00;
}

/* COTS Table Styles */
.cots-title {
    color: #333;
}

/* COTS Auto-save Indicator Styles */
#autosaveIndicatorCots {
    transition: all 0.3s ease;
}

#autosaveIndicatorCots.autosave-indicator.saving {
    background-color: #fff3cd !important;
    border-left: 3px solid #ffc107;
}



#autosaveIndicatorCots.autosave-indicator.error {
    background-color: #f8d7da !important;
    border-left: 3px solid #dc3545;
}

[data-bs-theme="dark"] #autosaveIndicatorCots {
    background-color: #2a2c40 !important;
}

[data-bs-theme="dark"] #autosaveIndicatorCots.autosave-indicator.saving {
    background-color: #3d3200 !important;
}

[data-bs-theme="dark"] #autosaveIndicatorCots.autosave-indicator.success {
    background-color: #1a3324 !important;
}

[data-bs-theme="dark"] #autosaveIndicatorCots.autosave-indicator.error {
    background-color: #3d1a1f !important;
}

[data-bs-theme="dark"] #autosaveMessageCots {
    color: #adb5bd !important;
}

.cots-label-cell {
    padding-left: 10px;
    background-color: #f5f5f5;
    width: 200px;
}

.cots-label-cell strong {
    color: #333;
}

.inline-editor {
    background: transparent;
    border-top-style: hidden;
    border-right-style: hidden;
    border-left-style: hidden;
    border-bottom-style: hidden;
    outline: none !important;
    outline-width: 0 !important;
    box-shadow: none;
    -moz-box-shadow: none;
    -webkit-box-shadow: none;
    margin: 0;
    padding: 0;
    min-height: 60px;

}

.inline-editor:empty:before {
    content: attr(data-placeholder);
    color: #999;
}

.design-gallery {
    display: grid;
    grid-template-columns: repeat(2, 1fr); /* jumlah kolom bebas */
    /* grid-template-rows: repeat(2, 1fr); */
    gap: 14px;
}

.gallery-item {
    position: relative;
}

.gallery-thumb {
    position: relative;
    width: 100%;
    aspect-ratio: 1;
    overflow: hidden;
    border-radius: 12px;
    background: #f6f7fb;
}

.gallery-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: 0.3s;
}

.gallery-thumb:hover img {
    transform: scale(1.06);
}

.gallery-actions {
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0.45);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    opacity: 0;
    transition: 0.25s;
    flex-wrap: nowrap;
}

.gallery-thumb:hover .gallery-actions {
    opacity: 1;
}


.action-btn {
    width: clamp(28px, 6vw, 38px);
    height: clamp(28px, 6vw, 38px);
    border-radius: 50%;
    background: #fff;
    color: #444;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    text-decoration: none;
    font-size: clamp(12px, 2.5vw, 18px);
}

.action-btn:hover {
    background: #0d6efd;
    color: white;
}

.action-btn.danger:hover {
    background: #dc3545;
}

.empty-gallery {
    grid-column: 1 / -1;
    text-align: center;
    color: #999;
}
/* Desktop default */
.myth-th {
    width: 200px;
}

/* Mobile */
@media (max-width: 767.98px) {
    .myth-th {
        width: 30%;
    }
}

</style>
@if($Ikm->first())
<div class="row">
    <div class="col-xxl-12">
        <div class="row g-0">
            <!-- Main Content -->
            <div class="col-xl-9">
                <div class="card card-h-100 rounded-0 rounded-start border-end border-dashed">
                    <!-- Profile Header -->
                    <div class="card-header align-items-start p-4">
                        <div class="avatar-xxl me-3 position-relative">
                            <a data-fslightbox href="{{ asset('storage/'.$Ikm->first()->gambar) }}" title="Klik untuk perbesar">
                                @if($Ikm->first()->gambar && \App\Helpers\ThumbnailHelper::isValidImage($Ikm->first()->gambar))
                                    @php
                                        $thumbnailUrl = \App\Helpers\ThumbnailHelper::thumbnailUrl($Ikm->first()->gambar, 'large', true);
                                        $originalUrl = \App\Helpers\ThumbnailHelper::originalUrl($Ikm->first()->gambar);
                                    @endphp
                                    <img src="{{ $thumbnailUrl ?? asset('storage/'.$Ikm->first()->gambar) }}"
                                         alt="{{ $Ikm->first()->nama }}"
                                         class="rounded thumbnail-avatar-lg thumbnail-image"
                                         style="width: 72px; height: 72px; object-fit: cover;"
                                         loading="lazy">
                                @else
                                    <div class="rounded d-flex align-items-center justify-content-center" style="width: 72px; height: 72px; background-color: #e9ecef;">
                                        <i class="ti ti-user" style="font-size: 32px; color: #6c757d;"></i>
                                    </div>
                                @endif
                            </a>
                            @if($Ikm->first()->gambar && \App\Helpers\ThumbnailHelper::isValidImage($Ikm->first()->gambar))
                                <a href="{{ \App\Helpers\ThumbnailHelper::originalUrl($Ikm->first()->gambar) }}"
                                   class="btn btn-light btn-sm position-absolute bottom-0 end-0 rounded-circle p-1"
                                   style="width: 24px; height: 24px; line-height: 1;"
                                   title="Unduh gambar asli"
                                   download="{{ basename($Ikm->first()->gambar) }}"
                                   onclick="event.stopPropagation();">
                                    <i class="ti ti-download" style="font-size: 10px;"></i>
                                </a>
                            @endif
                            <button class="btn btn-light btn-sm position-absolute bottom-0 start-0 rounded-circle p-1"
                                    data-bs-toggle="modal"
                                    data-bs-target="#UpdatePicture"
                                    title="Ubah foto"
                                    style="width: 24px; height: 24px; line-height: 1; margin-left: -8px;">
                                <i class="ti ti-pencil" style="font-size: 10px;"></i>
                            </button>
                        </div>
                        <div>
                            <h3 class="mb-1 d-flex fs-xl align-items-center">{{ $Ikm->first()->nama }} - {{ $project->NamaProjek }} </h3>
                            <p class="text-muted mb-2 fs-xxs">Updated {{ $Ikm->first()->updated_at->diffForHumans() }}</p>
                            <span class="badge badge-soft-success fs-xxs badge-label">In Progress</span>
                        </div>
                        <div class="ms-auto d-flex gap-2">
                            <a href="/project/dataIkm/{{ $project->id }}" class="btn btn-light">
                                <i class="ti ti-arrow-left me-1"></i> Kembali
                            </a>
                            <form action="{{ route('ikm.edit', ['ikm' => $Ikm->first()->id]) }}" method="GET" class="d-inline">
                                @csrf

                                <button type="submit" class="btn btn-light" title="Ubah">
                                    <i class="ti ti-pencil me-2"></i> Edit
                                </button>
                            </form>

                            <a class="btn btn-soft-secondary btn-sm" href="/report/brainstorming/{{ $Ikm->first()->id }}/{{ $Ikm->first()->nama }}" target="_blank">
                                <i class="ti ti-file-export me-1"></i> Export
                            </a>
                        </div>

                    </div>

                    <div class="card-body ">
                        <!-- Project Info -->
                        <div class="mb-4">
                            <h5 class="fs-base mb-2">Informasi Ikm:</h5>
                            <p class="text-muted">{!! $Ikm->first()->jenisProduk !!} - {!! $Ikm->first()->namaUsaha ?? 'N/A' !!}</p>
                            <p class="text-muted">
                                {{ $Ikm->first()->alamat }}{{ $Ikm->first()->district->name ?? '' }} {{ $Ikm->first()->regency->name ?? '' }} {{ $Ikm->first()->province->name ?? '' }}
                            </p>
                            <p class="text-muted">
                                Telepon: {{ $Ikm->first()->telp }}
                            </p>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-4 col-xl-3">
                                <h6 class="mb-1 text-muted text-uppercase">Tanggal Bergabung:</h6>
                                <p class="fw-medium mb-0">{{ $Ikm->first()->created_at->format('F d, Y') }}</p>
                            </div>
                            <div class="col-md-4 col-xl-3">
                                <h6 class="mb-1 text-muted text-uppercase">Jenis Produk:</h6>
                                <p class="fw-medium mb-0">{!! $Ikm->first()->jenisProduk !!}</p>
                            </div>
                            <div class="col-md-4 col-xl-3">
                                <h6 class="mb-1 text-muted text-uppercase">Merk:</h6>
                                <p class="fw-medium mb-0">{!! $Ikm->first()->merk ?? 'N/A' !!}</p>
                            </div>
                            <div class="col-md-4 col-xl-3">
                                <h6 class="mb-1 text-muted text-uppercase">Nama Usaha:</h6>
                                <p class="fw-medium mb-0">{!! $Ikm->first()->namaUsaha ?? 'N/A' !!}</p>
                            </div>
                        </div>

                        <!-- Tabs -->
                        <ul class="nav nav-tabs nav-bordered mb-3" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" data-bs-toggle="tab" href="#tab-info" role="tab" aria-selected="false">
                                    <i class="ti ti-user fs-lg me-md-1 align-middle"></i>
                                    <span class="d-none d-md-inline-block align-middle">Informasi Ikm</span>
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" data-bs-toggle="tab" href="#tab-bencmark" role="tab" aria-selected="true">
                                    <i class="ti ti-file fs-lg me-md-1 align-middle"></i>
                                    <span class="d-none d-md-inline-block align-middle">Brainstorming</span>
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" data-bs-toggle="tab" href="#tab-Cots" role="tab" aria-selected="false" tabindex="-1">
                                    <i class="ti ti-home fs-lg me-md-1 align-middle"></i>
                                    <span class="d-none d-md-inline-block align-middle">Cots</span>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content p-0 m-0">
                            <!-- Ikm Info Tab -->
                            <div class="tab-pane fade" id="tab-info" role="tabpanel">
                                <div class="section1">
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <input class="form-control" id="nama" type="text" placeholder="Nama Lengkap"
                                                    name="nama" required value="{{ $Ikm->first()->nama }}" readonly />
                                                <label class="form-label" for="provinsi">Nama Lengkap<span style="color:red">*</span></label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <input required class="form-control" type="text" placeholder="Nomor Telepon"
                                                    name="telp" id="telp" value="{{ $Ikm->first()->telp }}" readonly />
                                                <label class="form-label" for="name">No Telepon<span style="color:red">*</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <select required class="form-control" aria-label="Default select example"
                                                    name="gender" id="gender" disabled>
                                                    @if ($Ikm->first()->gender == 1)
                                                        <option value="1">Laki - Laki</option>
                                                    @else
                                                        <option value="2">Perempuan</option>
                                                    @endif
                                                </select>
                                                <label class="form-label" for="email">Jenis Kelamin<span style="color:red">*</span></label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <select name="id_Project" id="" class="form-control" disabled>
                                                    <option value="">{{ $project->namaProject }}</option>
                                                </select>
                                                <label for="id_Project" class="form-label">Asosiasi / Komunitas</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="section2">
                                    <div class="mb-3 text-start">
                                        <div class="form-floating">
                                            <input required class="form-control" id="alamat" name="alamat" type="text"
                                                placeholder="Alamat" value="{{ $Ikm->first()->alamat }}" readonly />
                                            <label class="form-label" for="alamat">Alamat<span style="color:red">*</span></label>
                                        </div>
                                    </div>
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <select required class="form-control" id="provinsi" name="id_provinsi" disabled>
                                                    <option value="">
                                                        @if ($Ikm->first()->province)
                                                            {{ $Ikm->first()->province->name }}
                                                        @endif
                                                    </option>
                                                </select>
                                                <label class="form-label" for="provinsi">Provinsi<span style="color:red">*</span></label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <select required id="kabupaten" name="id_kota" class="form-control" disabled>
                                                    <option value="">
                                                        @if ($Ikm->first()->regency)
                                                            {{ $Ikm->first()->regency->name }}
                                                        @endif
                                                    </option>
                                                </select>
                                                <label class="form-label" for="kabupaten">Kota/Kabupaten<span style="color:red">*</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3 mb-2">
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <select required class="form-select" id="kecamatan" name="id_kecamatan" disabled>
                                                    <option value="">
                                                        @if ($Ikm->first()->district)
                                                            {{ $Ikm->first()->district->name }}
                                                        @endif
                                                    </option>
                                                </select>
                                                <label class="form-label" for="kecamatan">Kecamatan<span style="color:red">*</span></label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <select required class="form-select" id="desa" name="id_desa" disabled>
                                                    <option value="">
                                                        @if ($Ikm->first()->village)
                                                            {{ $Ikm->first()->village->name }}
                                                        @endif
                                                    </option>
                                                </select>
                                                <label class="form-label" for="desa">Kelurahan/Desa<span style="color:red">*</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3 mb-2">
                                        <div class="col-md-6">
                                            <div class="mb-3 text-start">
                                                <div class="form-floating">
                                                    <input required class="form-control" id="rt" name="rt" type="text"
                                                        placeholder="RT" value="{{ $Ikm->first()->rt }}" readonly />
                                                    <label class="form-label" for="rt">RT<span style="color:red">*</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3 text-start">
                                                <div class="form-floating">
                                                    <input required class="form-control" id="rw" name="rw" type="text"
                                                        placeholder="RW" value="{{ $Ikm->first()->rw }}" readonly />
                                                    <label class="form-label" for="rw">RW<span style="color:red">*</span></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Brainstorming Tab -->
                            <div class="tab-pane fade active show" id="tab-bencmark" role="tabpanel">

                                <div class="table-responsive">
                                    <form action="/project/Ikms/updateBrainstorming" method="post">
                                        @csrf

                                        <input type="hidden" name="id_Ikm" value="{{ $Ikm->first()->id }}">
                                        <input type="hidden" name="id_Project" value="{{ $Ikm->first()->id_Project }}">

                                        <table class="table table-bordered table-responsive" >
                                            <thead>
                                                <tr>
                                                    <th  class="myth-th">Produk</th>
                                                    <th>Keterangan</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                @php
                                                $fields = [
                                                'jenisProduk' => 'Jenis Produk',
                                                'merk' => 'Merk',
                                                'komposisi' => 'Komposisi',
                                                'varian' => 'Varian Produk',
                                                'kelebihan' => 'Kelebihan Produk',
                                                'namaUsaha' => 'Nama Perusahaan',
                                                'noPIRT' => 'PIRT',
                                                'noHalal' => 'Halal',
                                                'legalitasLain' => 'Legalitas lainnya',
                                                'other' => 'Saran Penyajian',
                                                'segmentasi' => 'Segmentasi',
                                                'jenisKemasan' => 'Jenis Kemasan',
                                                'harga' => 'Kemasan Pendukung',
                                                'tagline' => 'Tagline',
                                                'redaksi' => 'Redaksi',
                                                'gramasi' => 'Gramasi'
                                                ];
                                                @endphp

                                                @foreach($fields as $key => $label)
                                                <tr>
                                                    <td>{{ $label }}</td>
                                                    <td>
                                                        <div class="position-relative">
                                                            <div id="{{ $key }}" class="inline-editor" style="background: transparent;
                                                            border-top-style: hidden;
                                                            border-right-style: hidden;
                                                            border-left-style: hidden;
                                                            border-bottom-style: hidden;
                                                            outline:none !important;
                                                            outline-width: 0 !important;
                                                            box-shadow: none;
                                                            -moz-box-shadow: none;
                                                            -webkit-box-shadow: none;
                                                            margin:0;
                                                            padding:0;
                                                            padding-right: 40px;" contenteditable="true" data-placeholder="{{ $label }}">
                                                                {!! $Ikm->first()->$key ?? '' !!}
                                                            </div>
                                                            <a type="button" class=" ai-generate-btn position-absolute" style="top: 50%; right: 0; transform: translateY(-50%); border: none;" data-field="{{ $key }}" data-label="{{ $label }}" title="Generate dengan AI">
                                                                <i class="ti ti-sparkles"></i>
                                                            </a>
                                                        </div>
                                                        <input type="hidden" name="{{ $key }}" id="{{ $key }}_input" data-field="{{ $key }}">
                                                    </td>
                                                </tr>
                                                @endforeach

                                            </tbody>
                                        </table>
                                        <div class="d-flex flex-column flex-sm-row align-items-center gap-2 mt-3 w-100">

                                            <!-- Auto-save di kiri, fleksibel -->
                                            <div id="autosaveIndicator" class="d-flex align-items-center flex-grow-1 mb-2 mb-sm-0">
                                                <div class="autosave-icon d-flex align-items-center gap-2 w-100">
                                                    <span id="autosaveIconContent"></span>
                                                    <span id="autosaveMessage" class="text-truncate">Menyimpan data secara otomatis...</span>
                                                </div>
                                            </div>

                                            <!-- Tombol Cancel & Simpan -->
                                            <div class="d-flex gap-2 flex-wrap w-100 w-sm-auto justify-content-center justify-content-sm-end">
                                                <a href="{{ url()->previous() }}" class="btn btn-secondary flex-grow-1 flex-sm-grow-0">
                                                    Cancel
                                                </a>

                                                <button type="submit" class="btn btn-primary flex-grow-1 flex-sm-grow-0">
                                                    Simpan Data
                                                </button>
                                            </div>

                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Cots Tab -->
                            <div class="tab-pane fade" id="tab-Cots" role="tabpanel">
                                @if($cots > 0 && $cotsview->count() > 0)
                                    @foreach($cotsview as $a)
                                        <div class="row justify-content-between align-items-end g-3 mb-4">
                                            <div class="col-12 col-sm-auto">
                                                <h5 class="mb-0 cots-title">Form Coaching on The Spot (Cots)</h5>
                                            </div>
                                            <div class="col-12 col-sm-auto">
                                                <div class="d-flex gap-2">
                                                    @if($Ikm->first()->id_provinsi != NULL)
                                                        <a class="btn btn-soft-secondary btn-sm" href="/report/Cots/{{ $Ikm->first()->id }}/{{ $Ikm->first()->nama }}">
                                                            <i class="far fa-file-pdf me-1"></i> Export
                                                        </a>
                                                    @else
                                                        <button class="btn btn-soft-secondary btn-sm" onclick="alert('Mohon Lengkapi Data Ikm terlebih dahulu!')">
                                                            <i class="far fa-file-pdf me-1"></i> Export
                                                        </button>
                                                    @endif
                                                    <a class="btn btn-soft-primary btn-sm" id="enableCots">
                                                        <i class="fas fa-pencil-alt me-1"></i> Edit
                                                    </a>
                                                </div>
                                            </div>
                                        </div>

                                        <form action="/project/Ikms/{{ $Ikm->first()->id }}/updateCots" method="POST">
                                            @csrf
                                            <div class="d-flex gap-2 mb-3" id="CotsActions" style="display:none;">
                                                <button class="btn btn-phoenix-primary btn-sm" type="button" id="batalCots">Batal</button>
                                                <button class="btn btn-primary btn-sm" type="submit" id="simpanCots">Simpan</button>
                                            </div>

                                            <input type="text" name="id_Ikm" value="{{ $Ikm->first()->id }}" hidden>
                                            <input type="text" name="id_Project" value="{{ $project->id }}" hidden>
                                            <input type="text" name="id_Cots" value="{{ $a->id }}" hidden>
                                              <!-- COTS Auto-save indicator -->
                                            <div id="autosaveIndicatorCots" class="d-flex align-items-center mb-3 p-2 rounded" style=" transition: all 0.3s;margin-bottom:10px;">
                                                <div class="autosave-icon d-flex align-items-center gap-2">
                                                    <span id="autosaveIconContentCots"><i class="ti ti-check" style="color: #28a745;"></i></span>
                                                    <span id="autosaveMessageCots" class="text-muted">Auto-save aktif</span>
                                                </div>
                                            </div>


                                            <table class="table table-bordered table-dark-mode" style="table-layout: fixed; overflow-wrap: break-word;">
                                                <tbody>
                                                    @php
                                                    $CotsFields = [
                                                        'sejarahSingkat' => 'Sejarah Singkat',
                                                        'produkjual' => 'Produk yang Dijual',
                                                        'carapemasaran' => 'Cara Pemasaran',
                                                        'bahanbaku' => 'Bahan Baku',
                                                        'prosesproduksi' => 'Proses Produksi',
                                                        'omset' => 'Omset',
                                                        'kapasitasProduksi' => 'Kapasitas Produksi',
                                                        'kendala' => 'Kendala',
                                                        'solusi' => 'Solusi'
                                                    ];
                                                    @endphp

                                                    @foreach($CotsFields as $key => $label)
                                                    <tr>
                                                        <td class="cots-label-cell"><strong>{{ $label }}</strong></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="p-2">
                                                            <div id="{{ $key }}" class="inline-editor" contenteditable="true" data-placeholder="{{ $label }}" readonly>
                                                                {!! $a->$key ?? '' !!}
                                                            </div>
                                                            <input type="hidden" name="{{ $key }}" id="{{ $key }}_input">
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </form>
                                    @endforeach
                                @else
                                    <div class="text-center py-5">
                                        <i class="ti ti-report fs-3 text-muted mb-3"></i>
                                        <p class="text-muted mb-3">Belum ada Laporan Cots</p>
                                        <form action="/project/Ikms/{{ $Ikm->first()->id }}/Cots" method="post" class="d-inline">
                                            @csrf
                                            <input type="text" name="id_Ikm" value="{{ $Ikm->first()->id }}" hidden>
                                            <input type="text" name="id_Project" value="{{ $Ikm->first()->id_Project }}" hidden>
                                            <button class="btn btn-phoenix-primary" type="submit">
                                                <i class="fas fa-plus me-1"></i> Buat Laporan Cots
                                            </button>
                                        </form>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                    <!-- end card-body -->
                </div>
                <!-- end card -->
            </div>
            <!-- end col-xl-9 -->

            <!-- Sidebar -->
            <div class="col-xl-3">
                <div class="card card-h-100 rounded-0 rounded-end border-start border-dashed shadow-none">
                    <div class="card-body p-0">

                        <!-- Bencmark Produk -->
                        <div class="p-3 border-bottom border-dashed">
                            <div class="d-flex mb-3 justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    Bencmark Produk ({{ $Ikm->first()->bencmark->count() }})
                                </h5>
                                <button class="btn btn-phoenix-primary btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#verticallyCentered">
                                    <i class="ti ti-plus"></i> Upload
                                </button>
                            </div>
                            <div class="design-gallery">
                                @forelse($Ikm->first()->bencmark as $image)

                                    <div class="gallery-item">

                                        <form action="/project/Ikms/{{ $image->id }}/deletebencmark" method="POST">
                                            @csrf
                                            <input type="hidden" name="oldImage" value="{{ $image->gambar }}">

                                            <div class="gallery-thumb">

                                                {{-- Thumbnail --}}
                                                <a href="{{ \App\Helpers\ThumbnailHelper::originalUrl($image->gambar) }}"
                                                data-fslightbox="benchmark-gallery" >
                                                    <img src="{{ \App\Helpers\ThumbnailHelper::thumbnailUrl($image->gambar, 'medium', true) ?? \App\Helpers\ThumbnailHelper::originalUrl($image->gambar) }}"
                                                        loading="lazy" >
                                                </a>

                                                {{-- Overlay Actions --}}
                                                <div class="gallery-actions">

                                                    {{-- Preview --}}
                                                    <a href="{{ \App\Helpers\ThumbnailHelper::originalUrl($image->gambar) }}"
                                                    data-fslightbox="benchmark-gallery"
                                                    class="action-btn"
                                                    title="Preview">
                                                        <i class="ti ti-eye"></i>
                                                    </a>

                                                    {{-- Download --}}
                                                    @if(\App\Helpers\ThumbnailHelper::isValidImage($image->gambar))
                                                        <a href="{{ \App\Helpers\ThumbnailHelper::originalUrl($image->gambar) }}"
                                                        download
                                                        class="action-btn"
                                                        title="Download">
                                                            <i class="ti ti-download"></i>
                                                        </a>
                                                    @endif

                                                    {{-- Delete --}}
                                                    <button type="submit"
                                                            class="action-btn danger"
                                                            title="Hapus"
                                                            onclick="return confirm('Hapus gambar ini?')">
                                                        <i class="ti ti-trash"></i>
                                                    </button>

                                                </div>

                                            </div>

                                        </form>

                                    </div>

                                @empty
                                  <div class="empty-gallery">
                                        <div class="empty-box">

                                            <div class="empty-icon">
                                                <i class="ti ti-photo"></i>
                                            </div>

                                            <h6 class="empty-title">Belum Ada Benchmark Produk</h6>

                                            <p class="empty-desc">
                                                Upload referensi produk pesaing sebagai bahan perbandingan.
                                            </p>

                                            <button class="btn btn-primary btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#verticallyCentered">
                                                <i class="ti ti-upload me-1"></i> Upload Benchmark
                                            </button>

                                        </div>
                                  </div>
                                @endforelse
                            </div>
                        </div>
                       <!-- Desain Produk -->
                            <div class="p-3 border-bottom border-dashed">
                                <div class="d-flex mb-3 justify-content-between align-items-center">
                                    <h5 class="mb-0">
                                        Desain Produk ({{ $Ikm->first()->produkDesign->count() }})
                                    </h5>

                                    <button class="btn btn-phoenix-primary btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#uploadDesign">
                                        <i class="ti ti-plus"></i> Upload
                                    </button>
                                </div>
                                <div class="design-gallery">

                                    @forelse($Ikm->first()->produkDesign as $image)

                                        <div class="gallery-item">

                                            <form action="/project/Ikms/{{ $image->id }}/deleteDesain" method="POST">
                                                @csrf
                                                <input type="hidden" name="oldImage" value="{{ $image->gambar }}">

                                                <div class="gallery-thumb">

                                                    {{-- Thumbnail --}}
                                                    <a href="{{ \App\Helpers\ThumbnailHelper::originalUrl($image->gambar) }}"
                                                    data-fslightbox="gallery">
                                                        <img src="{{ \App\Helpers\ThumbnailHelper::thumbnailUrl($image->gambar, 'medium', true) ?? \App\Helpers\ThumbnailHelper::originalUrl($image->gambar) }}"
                                                            loading="lazy">
                                                    </a>

                                                    {{-- Overlay Actions --}}
                                                    <div class="gallery-actions">

                                                        {{-- Preview --}}
                                                        <a href="{{ \App\Helpers\ThumbnailHelper::originalUrl($image->gambar) }}"
                                                        data-fslightbox="gallery"
                                                        class="action-btn"
                                                        title="Preview">
                                                            <i class="ti ti-eye"></i>
                                                        </a>

                                                        {{-- Download --}}
                                                        @if(\App\Helpers\ThumbnailHelper::isValidImage($image->gambar))
                                                            <a href="{{ \App\Helpers\ThumbnailHelper::originalUrl($image->gambar) }}"
                                                            download
                                                            class="action-btn"
                                                            title="Download">
                                                                <i class="ti ti-download"></i>
                                                            </a>
                                                        @endif

                                                        {{-- Delete --}}
                                                        <button type="submit"
                                                                class="action-btn danger"
                                                                title="Hapus"
                                                                onclick="return confirm('Hapus gambar ini?')">
                                                            <i class="ti ti-trash"></i>
                                                        </button>

                                                    </div>

                                                </div>

                                            </form>

                                        </div>

                                    @empty
                                        <div class="empty-gallery">
                                            <div class="empty-box">
                                                <div class="empty-icon">
                                                    <i class="ti ti-palette"></i>
                                                </div>

                                                <h6 class="empty-title">Belum Ada Desain Produk</h6>

                                                <p class="empty-desc">
                                                    Tambahkan desain kemasan atau produk untuk mengisi galeri.
                                                </p>

                                                <button class="btn btn-primary btn-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#uploadDesign">
                                                    <i class="ti ti-upload me-1"></i> Upload Desain
                                                </button>

                                            </div>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        <!-- Dokumentasi Cots -->
                        <div class="p-3">
                            <div class="d-flex mb-3 justify-content-between align-items-center">
                                <h5 class="mb-0">Dokumentasi</h5>

                                <button class="btn btn-phoenix-primary btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#addDokumentasi">
                                    <i class="ti ti-plus"></i> Upload
                                </button>
                            </div>
                            <div class="design-gallery">

                                @forelse($dokumentasiCots as $img)

                                    <div class="gallery-item">

                                        <form action="/project/Ikms/{{ $Ikm->first()->id }}/deleteDoc" method="POST">
                                            @csrf

                                            <input type="hidden" name="id_gambar" value="{{ $img->id }}">
                                            <input type="hidden" name="old_gambar" value="{{ $img->gambar }}">

                                            <div class="gallery-thumb">

                                                {{-- Thumbnail --}}
                                                <a href="{{ \App\Helpers\ThumbnailHelper::originalUrl($img->gambar) }}"
                                                data-fslightbox="dokumentasi-gallery">
                                                    <img src="{{ \App\Helpers\ThumbnailHelper::thumbnailUrl($img->gambar, 'medium', true) ?? \App\Helpers\ThumbnailHelper::originalUrl($img->gambar) }}"
                                                        loading="lazy">
                                                </a>

                                                {{-- Overlay Actions --}}
                                                <div class="gallery-actions">

                                                    {{-- Preview --}}
                                                    <a href="{{ \App\Helpers\ThumbnailHelper::originalUrl($img->gambar) }}"
                                                    data-fslightbox="dokumentasi-gallery"
                                                    class="action-btn"
                                                    title="Preview">
                                                        <i class="ti ti-eye"></i>
                                                    </a>

                                                    {{-- Download --}}
                                                    @if(\App\Helpers\ThumbnailHelper::isValidImage($img->gambar))
                                                        <a href="{{ \App\Helpers\ThumbnailHelper::originalUrl($img->gambar) }}"
                                                        download
                                                        class="action-btn"
                                                        title="Download">
                                                            <i class="ti ti-download"></i>
                                                        </a>
                                                    @endif

                                                    {{-- Delete --}}
                                                    <button type="submit"
                                                            class="action-btn danger"
                                                            title="Hapus"
                                                            onclick="return confirm('Hapus gambar ini?')">
                                                        <i class="ti ti-trash"></i>
                                                    </button>

                                                </div>

                                            </div>

                                        </form>

                                    </div>

                                @empty
                                   <div class="empty-gallery">
                                        <div class="empty-box">

                                            <div class="empty-icon">
                                                <i class="ti ti-photo"></i>
                                            </div>

                                            <h6 class="empty-title">Belum Ada Dokumentasi</h6>

                                            <p class="empty-desc">
                                                Upload foto dokumentasi untuk mulai mengisi galeri.
                                            </p>

                                            <button class="btn btn-primary btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#addDokumentasi">
                                                <i class="ti ti-upload me-1"></i> Upload Sekarang
                                            </button>

                                        </div>
                                    </div>
                                @endforelse

                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end col-xl-3 -->
        </div>
        <!-- end row -->
    </div>
    <!-- end col-xxl-12 -->
</div>
<style>
.pro-upload{
    border:2px dashed #e0e6ed;
    padding:35px;
    border-radius:14px;
    text-align:center;
    cursor:pointer;
    transition:.3s;

    position:relative;
}
.pro-upload:hover{
    border-color:#0d6efd;
}
.pro-upload.drag{
    border-color:#0d6efd;

}
.pro-input{
    position:absolute;
    width:100%;
    height:100%;
    opacity:0;
    cursor:pointer;
    top:0;
    left:0;
}

.pro-preview img{
    height:110px;
    width:100%;
    object-fit:cover;
    border-radius:10px;
}

.preview-card{
    position:relative;
}

.remove-btn{
    position:absolute;
    top:6px;
    right:6px;
    background:red;
    color:white;
    border:none;
    border-radius:50%;
    width:24px;
    height:24px;
    font-size:13px;
    line-height:20px;
}
</style>

<!-- Upload Bencmark Modal -->
<div class="modal fade" id="verticallyCentered" tabindex="-1" aria-labelledby="verticallyCenteredModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="verticallyCenteredModalLabel"><i class="ti ti-photo me-2"></i>Upload Bencmark Produk</h5>
                <button class="btn p-1" type="button" data-bs-dismiss="modal" aria-label="Close">
                    <span class="fas fa-times fs--1"></span>
                </button>
            </div>
            <form action="/project/Ikms/{{ encrypt($Ikm->first()->id) }}/bencmark" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="text" name="id_Ikm" value="{{ $Ikm->first()->id }}" hidden>
                    <input type="text" name="id_Project" value="{{ $project->id }}" hidden>

                    <div class="mb-3">
                        <label class="form-label">Pilih Gambar Benchmark:</label>

                        <div class="pro-upload">
                            <input type="file" name="gambar[]" multiple accept="image/*" class="pro-input">

                            <div class="pro-upload-box">
                                <i class="ti ti-cloud-upload fs-1 text-primary"></i>
                                <h6 class="mt-2">Drag & Drop Gambar</h6>
                                <small class="text-muted">atau klik untuk memilih</small>
                            </div>

                            <div class="pro-preview row g-2 mt-3"></div>
                        </div>
                    </div>


                    <div class="alert alert-soft-info" role="alert">
                        <small><i class="ti ti-info-circle me-1"></i> Format yang didukung: JPG, PNG, GIF</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-primary" type="button" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary" type="submit"><i class="ti ti-upload me-1"></i> Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Upload Design Modal -->
<div class="modal fade" id="uploadDesign" tabindex="-1" aria-labelledby="uploadDesignModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadDesignModalLabel"><i class="ti ti-palette me-2"></i>Upload Desain Produk</h5>
                <button class="btn p-1" type="button" data-bs-dismiss="modal" aria-label="Close">
                    <span class="ti ti-x"></span>
                </button>
            </div>
            <form action="/project/Ikms/{{ encrypt($Ikm->first()->id) }}/tambahDesain" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="text" name="id_Ikm" value="{{ $Ikm->first()->id }}" hidden>
                    <input type="text" name="id_project" value="{{ $project->id }}" hidden>

                    <div class="mb-3">
                        <label class="form-label">Pilih Gambar Desain:</label>

                        <div class="pro-upload">
                            <input type="file" name="gambar[]" multiple accept="image/*" class="pro-input">

                            <div class="pro-upload-box">
                                <i class="ti ti-cloud-upload fs-1 text-primary"></i>
                                <h6 class="mt-2">Drag & Drop Gambar</h6>
                                <small class="text-muted">atau klik untuk memilih</small>
                            </div>

                            <div class="pro-preview row g-2 mt-3"></div>
                        </div>
                    </div>


                    <div class="alert alert-soft-info" role="alert">
                        <small><i class="ti ti-info-circle me-1"></i> Format yang didukung: JPG, PNG, GIF</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-primary" type="button" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary" type="submit"><i class="ti ti-upload me-1"></i> Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Documentation Modal -->
<div class="modal fade" id="addDokumentasi" tabindex="-1" aria-labelledby="addDokumentasiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addDokumentasiModalLabel"><i class="ti ti-photo me-2"></i>Upload Photo Dokumentasi</h5>
                <button class="btn p-1" type="button" data-bs-dismiss="modal" aria-label="Close">
                    <span class="ti ti-x"></span>
                </button>
            </div>
            <div class="modal-body">
                <form action="/project/Ikms/{{ $Ikm->first()->id }}/dokumentasi" method="POST" enctype="multipart/form-data">
                    @csrf
                <div class="mb-3">
                    <label class="form-label">Pilih Photo:</label>
                     <input type="text" name="id_Ikm" value="{{ $Ikm->first()->id }}" hidden>
                    <input type="text" name="id_project" value="{{ $project->id }}" hidden>
                    <div class="pro-upload">
                        <input type="file" name="gambar[]" multiple accept="image/*" class="pro-input">

                        <div class="pro-upload-box">
                            <i class="ti ti-cloud-upload fs-1 text-primary"></i>
                            <h6 class="mt-2">Drag & Drop Gambar</h6>
                            <small class="text-muted">atau klik untuk memilih</small>
                        </div>

                        <div class="pro-preview row g-2 mt-3"></div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" type="submit"><i class="ti ti-upload me-1"></i> Upload</button>
                </form>
                <button class="btn btn-outline-primary" type="button" data-bs-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>

<!-- Update Picture Modal -->
<div class="modal fade" id="UpdatePicture" tabindex="-1" aria-labelledby="UpdatePictureModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="UpdatePictureModalLabel"><i class="ti ti-user me-2"></i>Ubah Foto Ikm</h5>
                <button class="btn p-1" type="button" data-bs-dismiss="modal" aria-label="Close">
                    <span class="ti ti-x"></span>
                </button>
            </div>
            <div class="modal-body">
                <form action="/project/dataIkm/Ikm/{{ $Ikm->first()->id }}/update" method="POST" enctype="multipart/form-data" id="cropForm">
                    @csrf
                    <input type="text" name="id_projek" value="{{ $project->id }}" hidden>
                    <input type="text" name="id_Ikm" value="{{ $Ikm->first()->id }}" hidden>
                    <input type="text" name="oldImage" value="{{ $Ikm->first()->gambar }}" hidden>

                    <!-- Image Input -->
                    <div class="mb-3">
                        <label class="form-label">Pilih Foto</label>
                        <input type="file" name="gambar" class="form-control" id="imageInput" accept="image/*">
                    </div>

                    <!-- Cropper Container -->
                    <div class="cropper-container" style="display:none;">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="img-container" style="max-height: 400px; background: #333;">
                                    <img id="imageToCrop" src="" alt="Gambar untuk di-crop">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="preview-container" style="width: 150px; height: 150px; overflow: hidden; border-radius: 50%; border: 3px solid #fff; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                                    <div class="preview" style="width: 100%; height: 100%;"></div>
                                </div>
                                <div class="mt-3">
                                    <p class="text-muted mb-2">Preview:</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden input for cropped image -->
                    <input type="hidden" name="croppedImage" id="croppedImage">
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" id="cropButton" style="display:none;">
                    <i class="ti ti-crop me-1"></i> Crop Foto
                </button>
                <button class="btn btn-outline-secondary" type="button" id="resetButton" style="display:none;">
                    <i class="ti ti-refresh me-1"></i> Reset
                </button>
                <button class="btn btn-primary" type="button" id="saveButton" style="display:none;" onclick="document.getElementById('cropForm').submit()">
                    <i class="ti ti-check me-1"></i> Simpan
                </button>
                <button class="btn btn-outline-primary" type="button" data-bs-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.pro-upload').forEach(zone => {

        const input = zone.querySelector('.pro-input');
        const preview = zone.querySelector('.pro-preview');
        let filesArray = [];

        // DRAG EVENTS
        zone.addEventListener('dragover', e => {
            e.preventDefault();
            zone.classList.add('drag');
        });

        zone.addEventListener('dragleave', () => {
            zone.classList.remove('drag');
        });

        zone.addEventListener('drop', e => {
            e.preventDefault();
            zone.classList.remove('drag');
            addFiles(e.dataTransfer.files);
        });

        input.addEventListener('change', () => {
            addFiles(input.files);
        });

        function addFiles(files){
            for(let file of files){
                if(!file.type.startsWith("image/")) continue;
                filesArray.push(file);
            }
            updateInput();
            renderPreview();
        }

        function renderPreview(){
            preview.innerHTML = '';

            filesArray.forEach((file,index)=>{
                const url = URL.createObjectURL(file);

                preview.innerHTML += `
                <div class="col-md-3">
                    <div class="preview-card">
                        <img src="${url}">
                        <button type="button" class="remove-btn" onclick="removeFile(this, ${index})">×</button>
                    </div>
                </div>`;
            });
        }

        function updateInput(){
            const dt = new DataTransfer();
            filesArray.forEach(f => dt.items.add(f));
            input.files = dt.files;
        }

        window.removeFile = function(btn, index){
            filesArray.splice(index,1);
            updateInput();
            renderPreview();
        }
    });
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const fields = [
        'jenisProduk', 'merk', 'komposisi', 'varian', 'kelebihan',
        'namaUsaha', 'noPIRT', 'noHalal', 'legalitasLain', 'other',
        'segmentasi', 'jenisKemasan', 'harga', 'tagline', 'redaksi', 'gramasi'
    ];

    const CotsFields = [
        'sejarahSingkat', 'produkjual', 'carapemasaran', 'bahanbaku',
        'prosesproduksi', 'omset', 'kapasitasProduksi', 'kendala', 'solusi'
    ];

    // Initialize TinyMCE for all fields
    fields.forEach(function(id) {
        tinymce.init({
            selector: '#' + id,
            inline: true,
            menubar: false,
            plugins: 'lists wordcount',
            toolbar: 'bold italic underline | bullist numlist',
            setup: function (editor) {
                editor.on('init', function () {
                    const hidden = document.getElementById(id + '_input');
                    if (hidden) hidden.value = editor.getContent();
                });

                editor.on('change keyup', function () {
                    const hidden = document.getElementById(id + '_input');
                    if (hidden) hidden.value = editor.getContent();
                });
            }
        });
    });

    // Initialize TinyMCE for Cots fields
    CotsFields.forEach(function(id) {
        if (document.getElementById(id)) {
            tinymce.init({
                selector: '#' + id,
                inline: true,
                menubar: false,
                plugins: 'lists wordcount',
                toolbar: 'bold italic underline | bullist numlist',
                setup: function (editor) {
                    editor.on('init', function () {
                        const hidden = document.getElementById(id + '_input');
                        if (hidden) hidden.value = editor.getContent();
                    });

                    editor.on('change keyup', function () {
                        const hidden = document.getElementById(id + '_input');
                        if (hidden) hidden.value = editor.getContent();
                    });
                }
            });
        }
    });

    // Cots form submit handler - copy TinyMCE values to hidden inputs before submit
    const cotsForm = document.querySelector('form[action*="updateCots"]');
    if (cotsForm) {
        cotsForm.addEventListener('submit', function(e) {
            // Save all TinyMCE editors before submit
            CotsFields.forEach(function(id) {
                const editor = tinymce.get(id);
                if (editor) {
                    const hiddenInput = document.getElementById(id + '_input');
                    if (hiddenInput) {
                        hiddenInput.value = editor.getContent();
                    }
                }
            });
        });
    }

    // Cots Edit functionality
    const enableCotsBtn = document.getElementById('enableCots');
    const batalCotsBtn = document.getElementById('batalCots');
    const CotsActionsDiv = document.getElementById('CotsActions');

    if (enableCotsBtn) {
        enableCotsBtn.addEventListener('click', function () {
            CotsFields.forEach(id => {
                const el = document.getElementById(id);
                if (el) el.removeAttribute('readonly');
            });
            if (CotsActionsDiv) CotsActionsDiv.style.display = 'flex';
        });
    }

    if (batalCotsBtn) {
        batalCotsBtn.addEventListener('click', function () {
            CotsFields.forEach(id => {
                const el = document.getElementById(id);
                if (el) el.setAttribute('readonly', 'readonly');
            });
            if (CotsActionsDiv) CotsActionsDiv.style.display = 'none';
        });
    }

    // AI Generate Button Event Listeners
    document.querySelectorAll('.ai-generate-btn').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const fieldId = this.getAttribute('data-field');
            const fieldLabel = this.getAttribute('data-label');

            // Set BOTH hidden fields to ensure consistency
            document.getElementById('aiTargetField').value = fieldId;
            document.getElementById('aiOptionsTargetField').value = fieldId;
            const promptInput = document.getElementById('aiPrompt');
            promptInput.value = `Tuliskan konten untuk field "${fieldLabel}" yang menarik dan profesional untuk produk Ikm ini.`;

            const modal = new bootstrap.Modal(document.getElementById('aiGenerateModal'));
            modal.show();
        });
    });


});

// AI Field Rules Configuration
    const aiFieldRules = {

        merk: {
            title: 'Nama Merek',
            instruction: `Buatkan 10 ide nama merek unik untuk sebuah produk [sebutkan jenis produk, misal: cemilan sehat, aplikasi, fashion, dsb].
Kriteria:
1. Nama merek harus **mudah diingat, catchy, dan terdengar profesional**.
2. Nama boleh terinspirasi dari **bahasa asing (Inggris, Latin, Jepang, dsb)**, bahasa Indonesia, atau gabungan keduanya menjadi satu kata atau frasa yang unik.
3. Nama terdengar **modern, elegan, dan relevan** dengan tipe produk dan target audiens (misal: anak muda, profesional, keluarga).
4. Panjangnya **tidak lebih dari 2 kata atau 1 kata gabungan**.
5. Sertakan **tagline singkat (opsional)** yang menggambarkan nilai atau keunggulan produk secara jelas.
6. Hindari nama yang terlalu generik, sulit dieja, atau terdengar murahan.
7. Pastikan nama tersebut **belum umum digunakan** untuk produk sejenis di pasar Indonesia.
8.Nama harus cocok untuk branding jangka panjang dan mudah dipakai sebagai domain atau media sosial.
9.Nama harus mencerminkan karakter produk, seperti rasa, fungsi, atau keunikan produk.
10.Nama Boleh terinspirasi dari **Nama Ikm atau lokasi**
11. Format output harus:
<p><strong>Nama Merek</strong><br><em>Tagline singkat (opsional)</em></p>
12. Tambahkan **alasan singkat** kenapa nama tersebut cocok untuk produk.  `
        },

        jenisProduk: {
            title: 'Jenis Produk',
            instruction: `Buatkan 4 variasi <strong>deskripsi jenis produk</strong>
yang jelas dan menarik.
Fokus pada fungsi utama produk dan keunggulannya.`
        },

 komposisi: {
    title: 'Komposisi',
    instruction: `
Buatkan 4 variasi <strong>komposisi produk</strong> dengan format berikut:

Format wajib:
Komposisi : bahan1, bahan2, bahan3.baris baru
tambah keterangan italic Mengandung alergen, lihat yang dicetak tebal.

Aturan:
- Jika suatu bahan termasuk alergen, maka nama bahan tersebut harus dicetak tebal (<strong>) di dalam komposisi.
- Hanya bahan yang mengandung alergen yang dicetak tebal.
- Daftar alergen tidak perlu dicetak tebal.
- Pisahkan bahan dengan koma.
- Gunakan bahasa Indonesia yang rapi dan profesional.
- Output dalam format HTML menggunakan <p> untuk setiap variasi.
`
},
        varian: {
            title: 'Varian Produk',
            instruction: `Buatkan 4 variasi <strong>varian produk</strong>.
Sebutkan perbedaan rasa, ukuran, atau keunikan.
Gunakan <ul><li>.`
        },

 kelebihan: {
    title: 'Kelebihan Produk',
    instruction: `
Buatkan 5 poin kelebihan produk dengan format PERSIS seperti berikut:

1. Poin pertama
2. Poin kedua
3. Poin ketiga
4. Poin keempat
5. Poin kelima

Aturan wajib:
- Gunakan angka 1 sampai 5 diikuti titik dan spasi.
- Setiap poin maksimal 6 kata.
- Tidak boleh menggunakan HTML.
- Tidak boleh menggunakan tanda bullet (- atau •).
- Tidak boleh menambahkan teks pembuka atau penutup.
- Fokus pada manfaat singkat dan jelas.
`
},

        namaUsaha: {
            title: 'Nama Usaha',
            instruction: `Buatkan 3 rekomendasi <strong>nama usaha</strong> yang:
- Relevan dengan produk
- Cocok untuk branding jangka panjang
- Mudah dipakai sebagai domain / media sosial`
        },

        segmentasi: {
            title: 'Segmentasi Pasar',
            instruction: `Buatkan 4 variasi <strong>segmentasi target pasar</strong>.
Jelaskan usia, kebiasaan, dan kebutuhan konsumen.`
        },

        other: {
            title: 'Saran Penyajian',
            instruction: `Buatkan <strong>saran penyajian produk</strong>
yang menarik, aman, dan mudah dipahami.`
        },

        jenisKemasan: {
            title: 'Jenis Kemasan',
            instruction: `Buatkan 3 deskripsi <strong>jenis kemasan</strong>.
Jelaskan bahan, ukuran, dan keunggulannya.`
        },

        harga: {
            title: 'Harga Produk',
            instruction: `Buatkan <strong>deskripsi harga produk</strong>
yang wajar dan menarik.
Boleh sertakan kisaran harga.`
        },

        gramasi: {
            title: 'Gramasi',
            instruction: `Buatkan <strong>deskripsi berat / gramasi produk</strong>
dan alasan gramasi tersebut sesuai untuk konsumen.`
        },

tagline: {
    title: 'Tagline',
    instruction: `
Buatkan 5 opsi <strong>tagline</strong> yang singkat, kuat, dan mudah diingat.

Aturan:
- Tidak terpaku pada jumlah kata atau format tertentu.
- Bisa berupa satu kalimat utuh atau frasa kreatif.
- Gunakan bahasa yang natural, menarik, dan memiliki daya jual.
- Hindari kalimat terlalu panjang.
- Tidak menggunakan emoji.
- Output dalam format HTML menggunakan <p> untuk setiap tagline.
`
},
        redaksi: {
    title: 'Redaksi Label',
    instruction: `Buatkan deskripsi produk makanan berdasarkan jenis produk dengan bahasa Indonesia yang menarik, profesional, dan menggugah selera.
Sesuaikan redaksi dengan karakter jenis produk (tekstur, cara konsumsi, fungsi).
Tulis 3–4 kalimat, cocok untuk website, marketplace, dan katalog brand.`
},

        noPIRT: {
            title: 'Nomor PIRT',
            instruction: `Buatkan <strong>keterangan Nomor PIRT</strong>.
Jika belum ada, gunakan redaksi "dalam proses pengajuan".`
        },

        noHalal: {
            title: 'Nomor Halal',
            instruction: `Buatkan <strong>keterangan status halal</strong>
dengan bahasa aman dan profesional.`
        },

        legalitasLain: {
            title: 'Legalitas Lainnya',
            instruction: `Buatkan <strong>informasi legalitas lainnya</strong>
seperti sertifikasi, izin, atau pengakuan resmi.`
        }
    };

// Generate AI Multiple Options
async function generateAIMultiple() {
    const targetField = document.getElementById('aiTargetField').value;
    const prompt = document.getElementById('aiPrompt').value;
    const loading = document.getElementById('aiLoading');
    const generateBtn = document.getElementById('generateAIBtn');

    if (!targetField || !prompt) {
        alert('Mohon masukkan prompt terlebih dahulu.');
        return;
    }

    loading.style.display = 'block';
    generateBtn.disabled = true;
    generateBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Generating...';

    const editor = tinymce.get(targetField);
    const existingContent = editor ? editor.getContent({format: 'text'}).trim() : '';

    // Build context
    const contextFields = [
       'jenisProduk', 'merk', 'komposisi', 'varian', 'kelebihan',
        'namaUsaha', 'noPIRT', 'noHalal', 'lainnya', 'other',
        'segmentasi', 'jenisKemasan', 'harga', 'tagline', 'redaksi', 'gramasi'
    ];
    let contextText = '';

    contextFields.forEach(field => {
        if (field !== targetField) {
            const fieldEditor = tinymce.get(field);
            if (fieldEditor) {
                const content = fieldEditor.getContent({format: 'text'}).trim();
                if (content) {
                    const label = document.querySelector(`[data-field="${field}"]`)?.getAttribute('data-label') || field;
                    contextText += `${label}: ${content}\n`;
                }
            }
        }
    });

    const fieldRule = aiFieldRules[targetField];
    const enhancedPrompt = `
${contextText}

Konteks kolom: ${fieldRule?.title || targetField}

${fieldRule?.instruction || prompt}

Gunakan format HTML:
- <strong> untuk teks penting
- <em> untuk penekanan
- <p> untuk paragraf
- <ul><li> jika berupa daftar

Pisahkan setiap variasi dengan:
===OPTION_START===
dan
===OPTION_END===
`;
// ollama run gemini-3-flash-preview  gpt-oss:120b-cloud
    try {
        const res = await fetch('https://myollama.scrollwebid.com/api/generate', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                model: 'gemini-3-flash-preview',
                prompt: enhancedPrompt,
                stream: true,
                web_search: { enabled: true, search_depth: 'high' },
                options: { temperature: 0.8, top_p: 0.95 }
            })
        });

        if (!res.ok) throw new Error('API request failed');

        const reader = res.body.getReader();
        const decoder = new TextDecoder();
        let result = '';

        generateBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Receiving...';

        while (true) {
            const { done, value } = await reader.read();
            if (done) break;

            const chunk = decoder.decode(value);
            const lines = chunk.split('\n');

            for (const line of lines) {
                if (line.trim()) {
                    try {
                        const data = JSON.parse(line);
                        if (data.response) result += data.response;
                        if (data.done) break;
                    } catch (e) {
                        // Ignore parse errors
                    }
                }
            }
        }

        const options = parseOptions(result);
        displayOptions(options.length > 0 ? options : [result]);

        const generateModalEl = document.getElementById('aiGenerateModal');
        const generateModal = bootstrap.Modal.getInstance(generateModalEl);
        if (generateModal) generateModal.hide();

        document.getElementById('aiOptionsTargetField').value = targetField;
        const optionsModal = new bootstrap.Modal(document.getElementById('aiOptionsModal'));
        optionsModal.show();

    } catch (error) {
        console.error('AI Generation Error:', error);
        alert('Gagal menghasilkan opsi. Error: ' + error.message);
    } finally {
        loading.style.display = 'none';
        generateBtn.disabled = false;
        generateBtn.innerHTML = '<i class="ti ti-sparkles me-1"></i> Generate Opsi';
    }
}

// Parse Options from AI Response
function parseOptions(result) {
    result = result.trim();

    const optionStartMarker = '===OPTION_START===';
    const optionEndMarker = '===OPTION_END===';

    if (result.includes(optionStartMarker)) {
        const parts = result.split(optionStartMarker);
        const options = [];

        for (let i = 1; i < parts.length; i++) {
            let option = parts[i];
            if (option.includes(optionEndMarker)) {
                option = option.split(optionEndMarker)[0];
            }
            option = option.trim();
            if (option) options.push(formatHTML(option));
        }
        return options;
    }

    const numberedPattern = /^(\d+)[\.\)]\s*(.+)$/gm;
    const matches = result.match(numberedPattern);
    if (matches && matches.length > 0) {
        return matches.map(m => formatHTML(m.replace(/^(\d+)[\.\)]\s*/, '').trim()));
    }

    const paragraphs = result.split(/\n\n+/);
    return paragraphs.filter(p => p.trim().length > 20).map(p => formatHTML(p.trim()));
}

// Format Text to HTML
function formatHTML(text) {
    let formatted = text;
    formatted = formatted.replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>');
    formatted = formatted.replace(/(?<!\*)\*([^\*]+)\*(?!\*)/g, '<em>$1</em>');
    formatted = formatted.replace(/_(.+?)_/g, '<em>$1</em>');
    formatted = formatted.replace(/^(\d+)[\.\)]\s*(.+)$/gm, '<li>$2</li>');

    if (formatted.includes('<li>')) {
        formatted = '<ul>' + formatted + '</ul>';
    }

    formatted = formatted.replace(/\n/g, '<br>');

    if (!formatted.includes('<') && !formatted.includes('>')) {
        formatted = '<p>' + formatted + '</p>';
    }

    return formatted;
}

// Display Options in Modal
function displayOptions(options) {
    const container = document.getElementById('aiOptionsContainer');
    container.innerHTML = '';

    const targetField = document.getElementById('aiOptionsTargetField').value;

    options.forEach((option, index) => {
        const card = document.createElement('div');
        card.className = 'ai-option-card position-relative';
        card.setAttribute('data-index', index);
        card.onclick = function() { selectOption(this, targetField); };
        card.innerHTML = `
            <span class="option-number">${index + 1}</span>
            <div class="option-content">${option}</div>
        `;
        container.appendChild(card);
    });
}

// Select Option and Insert into Editor
function selectOption(card, targetField) {
    try {
        document.querySelectorAll('.ai-option-card').forEach(c => c.classList.remove('selected'));
        card.classList.add('selected');

        const optionIndex = card.getAttribute('data-index');
        const options = document.querySelectorAll('.ai-option-card');
        const selectedOption = options[optionIndex].querySelector('.option-content').innerHTML;

        console.log('Selecting option for field:', targetField);
        console.log('Selected option:', selectedOption);

        // Check if TinyMCE editor exists
        const editor = typeof tinymce !== 'undefined' ? tinymce.get(targetField) : null;
        if (editor) {
            // TinyMCE editor path
            const currentContent = editor.getContent();
            if (currentContent && currentContent !== '<p>&nbsp;</p>') {
                editor.setContent(currentContent + '<br><br>' + selectedOption);
            } else {
                editor.setContent(selectedOption);
            }

            const hiddenInput = document.getElementById(targetField + '_input');
            if (hiddenInput) hiddenInput.value = editor.getContent();
            console.log('Content inserted into TinyMCE editor');
        } else {
            // Fallback: Use contenteditable div directly (the actual editor type used in this page)
            console.log('TinyMCE not available, using contenteditable div');

            const editorElement = document.querySelector(
                `.inline-editor[data-field="${targetField}"]`
            );
            if (editorElement && editorElement.classList.contains('inline-editor')) {
                // Get current content from contenteditable div
                let currentContent = editorElement.innerHTML.trim();

                // Check if content is empty or just whitespace/nbsp
                const isEmpty = !currentContent || currentContent === '&nbsp;' || currentContent === '<br>';

                if (!isEmpty && currentContent.length > 0) {
                    // Append with separator
                    editorElement.innerHTML = currentContent + '<br><br>' + selectedOption;
                } else {
                    // Set content directly
                    editorElement.innerHTML = selectedOption;
                }

                const hiddenInput = document.getElementById(targetField + '_input');
                if (hiddenInput) hiddenInput.value = editorElement.innerHTML;
                console.log('Content inserted into contenteditable div');
            } else {
                console.error('Could not find target field:', targetField);
            }
        }
    } catch (error) {
        console.error('Error in selectOption:', error);
    }

    const modalEl = document.getElementById('aiOptionsModal');
    const modal = bootstrap.Modal.getInstance(modalEl);
    if (modal) modal.hide();
}

// Regenerate Options
async function regenerateOptions() {
    const loading = document.getElementById('aiOptionsLoading');
    const container = document.getElementById('aiOptionsContainer');

    loading.style.display = 'block';
    container.style.opacity = '0.5';

    const targetField = document.getElementById('aiTargetField').value;
    const prompt = document.getElementById('aiPrompt').value;

    const editor = tinymce.get(targetField);
    const contextFields = ['jenisProduk', 'merk', 'komposisi', 'namaUsaha', 'segmentasi'];
    let contextText = '';

    contextFields.forEach(field => {
        if (field !== targetField) {
            const fieldEditor = tinymce.get(field);
            if (fieldEditor) {
                const content = fieldEditor.getContent({format: 'text'}).trim();
                if (content) {
                    const label = document.querySelector(`[data-field="${field}"]`)?.getAttribute('data-label') || field;
                    contextText += `${label}: ${content}\n`;
                }
            }
        }
    });

    const fieldRule = aiFieldRules[targetField];
    const enhancedPrompt = `
${contextText}

Konteks kolom: ${fieldRule?.title || targetField}

${fieldRule?.instruction || prompt}

Gunakan format HTML:
- <strong> untuk teks penting
- <em> untuk penekanan
- <p> untuk paragraf
- <ul><li> jika berupa daftar

Pisahkan setiap variasi dengan:
===OPTION_START===
dan
===OPTION_END===
`;

    try {
        const res = await fetch('https://myollama.scrollwebid.com/api/generate', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                model: 'gpt-oss:120b-cloud',
                prompt: enhancedPrompt,
                stream: true,
                web_search: { enabled: true, search_depth: 'high' },
                options: { temperature: 0.9, top_p: 0.98 }
            })
        });

        if (!res.ok) throw new Error('API request failed');

        const reader = res.body.getReader();
        const decoder = new TextDecoder();
        let result = '';

        while (true) {
            const { done, value } = await reader.read();
            if (done) break;

            const chunk = decoder.decode(value);
            const lines = chunk.split('\n');

            for (const line of lines) {
                if (line.trim()) {
                    try {
                        const data = JSON.parse(line);
                        if (data.response) result += data.response;
                        if (data.done) break;
                    } catch (e) {
                        // Ignore parse errors
                    }
                }
            }
        }

        const options = parseOptions(result);
        displayOptions(options.length > 0 ? options : ['<p>Opsi tidak tersedia. Silakan coba lagi.</p>']);

    } catch (error) {
        console.error('Regenerate Error:', error);
        alert('Gagal menghasilkan opsi baru. Error: ' + error.message);
    } finally {
        loading.style.display = 'none';
        container.style.opacity = '1';
    }
}
</script>

<!-- Cropper.js Library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

<!-- Image Cropping Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('imageInput');
    const imageToCrop = document.getElementById('imageToCrop');
    const cropperContainer = document.querySelector('.cropper-container');
    const cropButton = document.getElementById('cropButton');
    const resetButton = document.getElementById('resetButton');
    const saveButton = document.getElementById('saveButton');
    const croppedImageInput = document.getElementById('croppedImage');
    const updatePictureModal = document.getElementById('UpdatePicture');

    let cropper = null;

    // Handle file selection
    imageInput.addEventListener('change', function(e) {
        const files = e.target.files;

        if (files && files.length > 0) {
            const file = files[0];

            if (!file.type.match('image.*')) {
                alert('Please select an image file');
                return;
            }

            const reader = new FileReader();

            reader.onload = function(e) {
                if (cropper) cropper.destroy();

                imageToCrop.src = e.target.result;
                cropperContainer.style.display = 'block';

                cropper = new Cropper(imageToCrop, {
                    aspectRatio: 1,
                    viewMode: 1,
                    autoCropArea: 1,
                    responsive: true,
                    preview: '.preview'
                });

                cropButton.style.display = 'inline-block';
                resetButton.style.display = 'inline-block';
                saveButton.style.display = 'inline-block';
                imageInput.parentElement.style.display = 'none';
            };

            reader.readAsDataURL(file);
        }
    });

    // Handle crop button click
    cropButton.addEventListener('click', function() {
        if (cropper) {
            const canvas = cropper.getCroppedCanvas({
                width: 300,
                height: 300,
                fillColor: '#fff',
                imageSmoothingEnabled: true,
                imageSmoothingQuality: 'high'
            });

            const croppedDataUrl = canvas.toDataURL('image/jpeg', 0.9);
            croppedImageInput.value = croppedDataUrl;

            alert('Foto berhasil di-crop! Klik Simpan untuk menyimpan.');

            const preview = document.querySelector('.preview');
            preview.style.backgroundImage = `url(${croppedDataUrl})`;
            preview.style.backgroundSize = 'cover';
            preview.style.backgroundPosition = 'center';
        }
    });

    // Handle reset button click
    resetButton.addEventListener('click', function() {
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }

        imageToCrop.src = '';
        cropperContainer.style.display = 'none';
        cropButton.style.display = 'none';
        resetButton.style.display = 'none';
        saveButton.style.display = 'none';
        imageInput.value = '';
        croppedImageInput.value = '';
        imageInput.parentElement.style.display = 'block';

        const preview = document.querySelector('.preview');
        preview.style.backgroundImage = '';
    });

    // Handle modal close
    updatePictureModal.addEventListener('hidden.bs.modal', function() {
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }

        cropperContainer.style.display = 'none';
        cropButton.style.display = 'none';
        resetButton.style.display = 'none';
        saveButton.style.display = 'none';
        imageInput.value = '';
        croppedImageInput.value = '';
        imageInput.parentElement.style.display = 'block';

        const preview = document.querySelector('.preview');
        preview.style.backgroundImage = '';
    });
});
</script>

<!-- AI Generate Modal -->
<div class="modal fade" id="aiGenerateModal" tabindex="-1" aria-labelledby="aiGenerateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="aiGenerateModalLabel"><i class="ti ti-sparkles me-2"></i>Generate with AI</h5>
                <button class="btn p-1" type="button" data-bs-dismiss="modal" aria-label="Close">
                    <span class="ti ti-x"></span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="aiTargetField" value="">
                <div class="mb-3">
                    <label for="aiPrompt" class="form-label">Prompt:</label>
                    <textarea class="form-control" id="aiPrompt" rows="4" placeholder="Masukkan instruksi untuk AI... (contoh: Tuliskan tagline menarik untuk produk makanan sehat)"></textarea>
                </div>
                <div class="alert alert-soft-info" role="alert">
                    <small><i class="ti ti-info-circle me-1"></i> AI akan menghasilkan beberapa opsi teks berdasarkan instruksi Anda. Pilih yang paling sesuai.</small>
                </div>
                <div id="aiLoading" style="display:none;">
                    <div class="d-flex align-items-center">
                        <div class="spinner-border spinner-border-sm me-2" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <small class="text-muted">Sedang menghasilkan opsi...</small>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-primary" type="button" id="generateAIBtn" onclick="generateAIMultiple()">
                    <i class="ti ti-sparkles me-1"></i> Generate Opsi
                </button>
            </div>
        </div>
    </div>
</div>

<!-- AI Description Options Modal -->
<div class="modal fade" id="aiOptionsModal" tabindex="-1" aria-labelledby="aiOptionsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="aiOptionsModalLabel"><i class="ti ti-list-check me-2"></i>Pilih Opsi Deskripsi</h5>
                <button class="btn p-1" type="button" data-bs-dismiss="modal" aria-label="Close">
                    <span class="ti ti-x"></span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="aiOptionsTargetField" value="">
                <p class="text-muted mb-3">Pilih salah satu opsi di bawah ini atau <button type="button" class="btn btn-link p-0" onclick="regenerateOptions()">generate ulang</button></p>

                <div id="aiOptionsLoading" style="display:none;">
                    <div class="d-flex align-items-center justify-content-center py-4">
                        <div class="spinner-border spinner-border-sm me-2" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <small class="text-muted">Sedang menghasilkan opsi baru...</small>
                    </div>
                </div>

                <div id="aiOptionsContainer" class="d-flex flex-column gap-3">
                    <!-- Options will be inserted here -->
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>

<!-- AI Loading Indicator (for editor) -->
<style>
.ai-generate-btn {
    font-size: 11px;
    padding: 4px 10px;
    border-radius: 4px;
}
.ai-generate-btn i {
    font-size: 14px;
}
.ai-generate-btn:hover {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-color: transparent;
}
.ai-option-card {
    border: 2px solid #e1e5eb;
    border-radius: 0.5rem;
    padding: 1rem;
    cursor: pointer;
    transition: all 0.2s ease;

}
.dark .ai-option-card {
    background: #2e3344;
    border-color: #3d4458;
}
.ai-option-card:hover {
    border-color: #667eea;
    box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
}
.ai-option-card.selected {
    border-color: #667eea;
    background: rgba(102, 126, 234, 0.05);
}
.ai-option-card .option-number {
    position: absolute;
    top: -10px;
    left: 10px;
    background: #667eea;
    color: white;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: bold;
}
.ai-option-card .option-content {
    font-size: 14px;
    line-height: 1.6;
}
.ai-option-card .option-content strong {
    color: #667eea;
}
.ai-option-card .option-content em {
    color: #6c757d;
}
.ai-option-card .select-btn {
    position: absolute;
    bottom: 10px;
    right: 10px;
}
.ai-loading-overlay {
    position: relative;
}
.ai-loading-overlay::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10;
}
.ai-loading-overlay::after .spinner-border {
    width: 3rem;
    height: 3rem;
}
</style>


@else
<div class="alert alert-warning">
    <p>Data Ikm tidak ditemukan.</p>
</div>
@endif


<script>
/**
 * Auto-save functionality for Brainstorming form
 * Features:
 * - Debounced save (5 seconds after last typing)
 * - Interval save (every 30 seconds)
 * - Visual indicators (spinner, success, error states)
 * - Error handling with user-friendly messages
 */

(function() {
    'use strict';

    // Configuration
    const CONFIG = {
        debounceDelay: 5000,        // Save 5 seconds after user stops typing
        intervalDelay: 30000,        // Save every 30 seconds regardless
        maxRetries: 3,               // Maximum retry attempts
        retryDelay: 2000             // Delay between retries (ms)
    };

    // State
    let saveTimeout = null;
    let intervalId = null;
    let isSaving = false;
    let lastSavedContent = {};
    let retryCount = 0;

    // Brainstorming fields to auto-save
    const brainstormingFields = [
        'jenisProduk', 'merk', 'komposisi', 'varian', 'kelebihan',
        'namaUsaha', 'noPIRT', 'noHalal', 'legalitasLain', 'other',
        'segmentasi', 'jenisKemasan', 'harga', 'tagline', 'redaksi', 'gramasi'
    ];

    // Get Ikm and Project IDs from hidden inputs
    function getIds() {
        const idIkmInput = document.querySelector('input[name="id_Ikm"]');
        const idProjectInput = document.querySelector('input[name="id_Project"]');

        return {
            id_Ikm: idIkmInput ? idIkmInput.value : null,
            id_Project: idProjectInput ? idProjectInput.value : null
        };
    }

    // Get current content from all brainstorming fields
    function getCurrentContent() {
        const content = {};

        brainstormingFields.forEach(field => {
            const editor = tinymce.get(field);
            const hiddenInput = document.getElementById(field + '_input');

            if (editor) {
                content[field] = editor.getContent();
            } else if (hiddenInput) {
                content[field] = hiddenInput.value;
            }
        });

        return content;
    }

    // Show autosave indicator
    function showIndicator(type, message, duration = 3000) {
        const indicator = document.getElementById('autosaveIndicator');
        const iconContent = document.getElementById('autosaveIconContent');
        const messageEl = document.getElementById('autosaveMessage');

        // Reset classes
        indicator.className = 'autosave-indicator show ' + type;

        // Set icon
        if (type === 'saving') {
            iconContent.innerHTML = '<div class="autosave-spinner"></div>';
        } else if (type === 'success') {
            iconContent.innerHTML = '<i class="ti ti-check" style="color: #28a745;"></i>';
        } else if (type === 'error') {
            iconContent.innerHTML = '<i class="ti ti-alert-triangle" style="color: #dc3545;"></i>';
        }

        messageEl.textContent = message;

        // Auto-hide after duration (except for error - user must click to dismiss)
        if (type !== 'error' && duration > 0) {
            setTimeout(() => {
                indicator.classList.remove('show');
            }, duration);
        }
    }

// Function untuk mendapatkan waktu Indonesia sekarang
function getCurrentWIBTime() {
    const now = new Date();

    return now.toLocaleString('id-ID', {
        timeZone: 'Asia/Jakarta',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
    });
}

// Update last saved timestamp in UI
function updateLastSavedTimestamp() {
    const timestamp = getCurrentWIBTime();

    let timestampEl = document.getElementById('lastAutoSaveTime');
    if (!timestampEl) {
        const form = document.querySelector('form[action="/project/Ikms/updateBrainstorming"]');
        if (form) {
            timestampEl = document.createElement('small');
            timestampEl.id = 'lastAutoSaveTime';
            timestampEl.className = 'text-muted d-block mt-2';
            timestampEl.style.fontSize = '12px';
            form.appendChild(timestampEl);
        }
    }

    if (timestampEl) {
        timestampEl.innerHTML = `
            <i class="ti ti-check-circle me-1" style="color: #28a745;"></i>
            Terakhir disimpan: ${timestamp} WIB
        `;
    }
}

    // Send data to server
    async function sendAutoSave(data) {
        const ids = getIds();

        if (!ids.id_Ikm || !ids.id_Project) {
            console.warn('Auto-save: Missing Ikm or Project ID');
            return { success: false, message: 'ID tidak valid' };
        }

        const payload = {
            id_Ikm: ids.id_Ikm,
            id_Project: ids.id_Project,
            ...data
        };

        try {
            const response = await fetch('/project/Ikms/auto-save-brainstorming', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            });

            const result = await response.json();

            if (!response.ok) {
                throw new Error(result.message || 'Server error');
            }

            return result;

        } catch (error) {
            console.error('Auto-save fetch error:', error);
            throw error;
        }
    }

    // Perform auto-save
    async function performAutoSave() {
        if (isSaving) {
            console.log('Auto-save already in progress, skipping...');
            return;
        }

        const currentContent = getCurrentContent();

        // Check if content has changed
        const contentChanged = Object.keys(currentContent).some(key => {
            return lastSavedContent[key] !== currentContent[key];
        });

        if (!contentChanged) {
            console.log('No content changes detected, skipping auto-save');
            return;
        }

        isSaving = true;
        showIndicator('saving', 'Menyimpan...');

        try {
            const result = await sendAutoSave(currentContent);

            if (result.success) {
                lastSavedContent = { ...currentContent };
                retryCount = 0;

                // Show success message with timestamp
                const time = result.saved_at || new Date().toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                });

                showIndicator('success', 'Tersimpan ' + time, 4000);
                updateLastSavedTimestamp(time);

                console.log('Auto-save successful:', result);
            } else {
                throw new Error(result.message || 'Save failed');
            }

        } catch (error) {
            retryCount++;

            console.error('Auto-save error:', error);

            if (retryCount <= CONFIG.maxRetries) {
                showIndicator('saving', `Gagal, mencoba lagi (${retryCount}/${CONFIG.maxRetries})...`);

                // Retry after delay
                setTimeout(async () => {
                    isSaving = false;
                    await performAutoSave();
                }, CONFIG.retryDelay);
                return;
            }

            // Max retries reached
            showIndicator('error', 'Gagal menyimpan. Klik untuk coba lagi.', 0);

            // Add click handler to dismiss error and retry
            const indicator = document.getElementById('autosaveIndicator');
            indicator.style.cursor = 'pointer';
            indicator.onclick = async function() {
                indicator.onclick = null;
                indicator.style.cursor = 'default';
                retryCount = 0;
                isSaving = false;
                await performAutoSave();
            };
        } finally {
            if (retryCount === 0 || retryCount > CONFIG.maxRetries) {
                isSaving = false;
            }
        }
    }

    // Debounced save trigger
    function triggerDebouncedSave() {
        if (saveTimeout) {
            clearTimeout(saveTimeout);
        }

        saveTimeout = setTimeout(() => {
            console.log('Debounce timer fired, triggering save...');
            performAutoSave();
        }, CONFIG.debounceDelay);
    }

    // Initialize auto-save
    function initAutoSave() {
        // Check if we're on the brainstorming tab form
        const form = document.querySelector('form[action="/project/Ikms/updateBrainstorming"]');
        if (!form) {
            console.log('Auto-save: Brainstorming form not found');
            return;
        }

        // Store initial content
        lastSavedContent = getCurrentContent();

        // Set up TinyMCE change listeners
        brainstormingFields.forEach(field => {
            const editor = tinymce.get(field);
            if (editor) {
                editor.on('change keyup', triggerDebouncedSave);
                editor.on('paste', triggerDebouncedSave);
            }
        });

        // Also listen for form input changes (fallback)
        form.addEventListener('input', triggerDebouncedSave);

        // Set up interval save
        intervalId = setInterval(() => {
            console.log('Interval timer fired, triggering save...');
            performAutoSave();
        }, CONFIG.intervalDelay);

        // Save before unload (optional - to ensure last changes are saved)
        window.addEventListener('beforeunload', function(e) {
            if (isSaving) {
                e.preventDefault();
                e.returnValue = '';
                return '';
            }
        });

        console.log('Auto-save initialized');

        // Show initial status
        showIndicator('success', 'Auto-save aktif', 3000);
    }

    // Wait for TinyMCE to be ready, then initialize
    function waitForMCE() {
        return new Promise((resolve) => {
            // Check if TinyMCE is already loaded
            if (typeof tinymce !== 'undefined') {
                // Check if first editor is ready
                const firstEditor = tinymce.get(brainstormingFields[0]);
                if (firstEditor) {
                    resolve();
                    return;
                }
            }

            // Wait for DOM ready
            if (document.readyState === 'complete') {
                checkMCE();
            } else {
                window.addEventListener('load', checkMCE);
            }

            function checkMCE() {
                if (typeof tinymce !== 'undefined') {
                    // Give TinyMCE a moment to initialize editors
                    setTimeout(() => {
                        const firstEditor = tinymce.get(brainstormingFields[0]);
                        if (firstEditor) {
                            resolve();
                        } else {
                            // Try again after a bit more time
                            setTimeout(() => {
                                resolve();
                            }, 1000);
                        }
                    }, 500);
                } else {
                    // TinyMCE not available, initialize anyway
                    setTimeout(resolve, 1000);
                }
            }
        });
    }

    // Initialize when DOM is ready
    document.addEventListener('DOMContentLoaded', async function() {
        // Wait for TinyMCE to initialize
        await waitForMCE();

        // Initialize auto-save
        initAutoSave();
    });

    // Also initialize on tab show (in case user switches to this tab later)
    document.addEventListener('shown.bs.tab', function(e) {
        if (e.target.getAttribute('href') === '#tab-bencmark') {
            console.log('Brainstorming tab shown, re-initializing auto-save...');
            initAutoSave();
        }
    });

    // ========================================
    // COTS AUTO-SAVE FUNCTIONALITY
    // ========================================

    // COTS Configuration
    const COTS_CONFIG = {
        debounceDelay: 5000,        // Save 5 seconds after user stops typing
        intervalDelay: 30000,        // Save every 30 seconds regardless
        maxRetries: 3,               // Maximum retry attempts
        retryDelay: 2000             // Delay between retries (ms)
    };

    // COTS State
    let cotsSaveTimeout = null;
    let cotsIntervalId = null;
    let cotsIsSaving = false;
    let cotsLastSavedContent = {};
    let cotsRetryCount = 0;

    // COTS fields to auto-save
    const cotsFields = [
        'sejarahSingkat', 'produkjual', 'carapemasaran', 'bahanbaku',
        'prosesproduksi', 'omset', 'kapasitasProduksi', 'kendala', 'solusi'
    ];

    // Get COTS IDs from hidden inputs
    function getCotsIds() {
        const idIkmInput = document.querySelector('form[action*="updateCots"] input[name="id_Ikm"]');
        const idProjectInput = document.querySelector('form[action*="updateCots"] input[name="id_Project"]');
        const idCotsInput = document.querySelector('form[action*="updateCots"] input[name="id_Cots"]');

        return {
            id_Ikm: idIkmInput ? idIkmInput.value : null,
            id_Project: idProjectInput ? idProjectInput.value : null,
            id_Cots: idCotsInput ? idCotsInput.value : null
        };
    }

    // Get current content from all COTS fields
    function getCotsCurrentContent() {
        const content = {};

        cotsFields.forEach(field => {
            const editor = document.getElementById(field);
            const hiddenInput = document.getElementById(field + '_input');

            if (editor && editor.contentEditable === 'true') {
                content[field] = editor.innerHTML;
            } else if (hiddenInput) {
                content[field] = hiddenInput.value;
            }
        });

        return content;
    }

    // Sync COTS contenteditable to hidden inputs
    function syncCotsInputs() {
        cotsFields.forEach(field => {
            const editor = document.getElementById(field);
            const hiddenInput = document.getElementById(field + '_input');

            if (editor && hiddenInput) {
                hiddenInput.value = editor.innerHTML;
            }
        });
    }

    // Send COTS data to server
    async function sendCotsAutoSave(data) {
        const ids = getCotsIds();

        if (!ids.id_Ikm || !ids.id_Project || !ids.id_Cots) {
            console.warn('COTS Auto-save: Missing required IDs');
            return { success: false, message: 'ID tidak valid' };
        }

        // Sync inputs first
        syncCotsInputs();

        const payload = {
            id_Ikm: ids.id_Ikm,
            id_Project: ids.id_Project,
            id_Cots: ids.id_Cots,
            ...data
        };

        try {
            const response = await fetch('/project/Ikms/auto-save-cots', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            });

            const result = await response.json();

            if (!response.ok) {
                throw new Error(result.message || 'Server error');
            }

            return result;

        } catch (error) {
            console.error('COTS Auto-save fetch error:', error);
            throw error;
        }
    }

    // Perform COTS auto-save
    async function performCotsAutoSave() {
        if (cotsIsSaving) {
            console.log('COTS Auto-save already in progress, skipping...');
            return;
        }

        const currentContent = getCotsCurrentContent();

        // Check if content has changed
        const contentChanged = Object.keys(currentContent).some(key => {
            return cotsLastSavedContent[key] !== currentContent[key];
        });

        if (!contentChanged) {
            console.log('COTS No content changes detected, skipping auto-save');
            return;
        }

        cotsIsSaving = true;
        showCotsIndicator('saving', 'Menyimpan...');

        try {
            const result = await sendCotsAutoSave(currentContent);

            if (result.success) {
                cotsLastSavedContent = { ...currentContent };
                cotsRetryCount = 0;

                // Show success message with timestamp
                const time = result.saved_at || new Date().toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                });

                showCotsIndicator('success', 'Tersimpan ' + time, 4000);
                updateCotsLastSavedTimestamp(time);

                console.log('COTS Auto-save successful:', result);
            } else {
                throw new Error(result.message || 'Save failed');
            }

        } catch (error) {
            cotsRetryCount++;

            console.error('COTS Auto-save error:', error);

            if (cotsRetryCount <= COTS_CONFIG.maxRetries) {
                showCotsIndicator('saving', `Gagal, mencoba lagi (${cotsRetryCount}/${COTS_CONFIG.maxRetries})...`);

                // Retry after delay
                setTimeout(async () => {
                    cotsIsSaving = false;
                    await performCotsAutoSave();
                }, COTS_CONFIG.retryDelay);
                return;
            }

            // Max retries reached
            showCotsIndicator('error', 'Gagal menyimpan. Klik untuk coba lagi.', 0);

            // Add click handler to dismiss error and retry
            const indicator = document.getElementById('autosaveIndicatorCots');
            if (indicator) {
                indicator.style.cursor = 'pointer';
                indicator.onclick = async function() {
                    indicator.onclick = null;
                    indicator.style.cursor = 'default';
                    cotsRetryCount = 0;
                    cotsIsSaving = false;
                    await performCotsAutoSave();
                };
            }
        } finally {
            if (cotsRetryCount === 0 || cotsRetryCount > COTS_CONFIG.maxRetries) {
                cotsIsSaving = false;
            }
        }
    }

    // Show COTS indicator
    function showCotsIndicator(type, message, duration = 3000) {
        const indicator = document.getElementById('autosaveIndicatorCots');
        if (!indicator) return;

        const iconContent = document.getElementById('autosaveIconContentCots');
        const messageEl = document.getElementById('autosaveMessageCots');

        // Reset classes
        indicator.className = 'autosave-indicator show ' + type;

        // Set icon
        if (type === 'saving') {
            iconContent.innerHTML = '<div class="autosave-spinner"></div>';
        } else if (type === 'success') {
            iconContent.innerHTML = '<i class="ti ti-check" style="color: #28a745;"></i>';
        } else if (type === 'error') {
            iconContent.innerHTML = '<i class="ti ti-alert-triangle" style="color: #dc3545;"></i>';
        }

        messageEl.textContent = message;

        // Auto-hide after duration (except for error - user must click to dismiss)
        if (type !== 'error' && duration > 0) {
            setTimeout(() => {
                indicator.classList.remove('show');
            }, duration);
        }
    }

    // Update COTS last saved timestamp in UI
    function updateCotsLastSavedTimestamp() {
        const now = new Date();
        const timestamp = now.toLocaleString('id-ID', {
            timeZone: 'Asia/Jakarta',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });

        let timestampEl = document.getElementById('lastCotsAutoSaveTime');
        if (!timestampEl) {
            const form = document.querySelector('form[action*="updateCots"]');
            if (form) {
                timestampEl = document.createElement('small');
                timestampEl.id = 'lastCotsAutoSaveTime';
                timestampEl.className = 'text-muted d-block mt-2';
                timestampEl.style.fontSize = '12px';
                form.appendChild(timestampEl);
            }
        }

        if (timestampEl) {
            timestampEl.innerHTML = `
                <i class="ti ti-check-circle me-1" style="color: #28a745;"></i>
                Terakhir disimpan: ${timestamp} WIB
            `;
        }
    }

    // Debounced COTS save trigger
    function triggerCotsDebouncedSave() {
        if (cotsSaveTimeout) {
            clearTimeout(cotsSaveTimeout);
        }

        cotsSaveTimeout = setTimeout(() => {
            console.log('COTS Debounce timer fired, triggering save...');
            performCotsAutoSave();
        }, COTS_CONFIG.debounceDelay);
    }

    // Initialize COTS auto-save
    function initCotsAutoSave() {
        // Check if we're on the COTS tab form
        const form = document.querySelector('form[action*="updateCots"]');
        if (!form) {
            console.log('COTS Auto-save: Form not found');
            return;
        }

        // Check if COTS record exists
        const ids = getCotsIds();
        if (!ids.id_Cots) {
            console.log('COTS Auto-save: No COTS record ID found');
            return;
        }

        // Store initial content
        cotsLastSavedContent = getCotsCurrentContent();

        // Set up contenteditable change listeners
        cotsFields.forEach(field => {
            const editor = document.getElementById(field);
            if (editor) {
                editor.addEventListener('input', triggerCotsDebouncedSave);
                editor.addEventListener('paste', triggerCotsDebouncedSave);
            }
        });

        // Also listen for form input changes
        form.addEventListener('input', triggerCotsDebouncedSave);

        // Set up interval save
        cotsIntervalId = setInterval(() => {
            console.log('COTS Interval timer fired, triggering save...');
            // Sync inputs before interval save
            syncCotsInputs();
            performCotsAutoSave();
        }, COTS_CONFIG.intervalDelay);

        // Save before unload
        window.addEventListener('beforeunload', function(e) {
            if (cotsIsSaving) {
                e.preventDefault();
                e.returnValue = '';
                return '';
            }
        });

        console.log('COTS Auto-save initialized');

        // Show initial status
        showCotsIndicator('success', 'Auto-save aktif', 3000);
    }

    // Initialize COTS auto-save when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        // Small delay to ensure DOM is fully loaded
        setTimeout(() => {
            initCotsAutoSave();
        }, 500);
    });

    // Also initialize on COTS tab show
    document.addEventListener('shown.bs.tab', function(e) {
        if (e.target.getAttribute('href') === '#tab-Cots') {
            console.log('COTS tab shown, initializing auto-save...');
            initCotsAutoSave();
        }
    });

    // Check for success message and switch to COTS tab
    @if(Session::has('UpdateBerhasil') || Session::has('Berhasil'))
        const cotsTab = document.querySelector('a[href="#tab-Cots"]');
        if (cotsTab) {
            cotsTab.click();
        }
    @endif

})();
</script>

@endsection
