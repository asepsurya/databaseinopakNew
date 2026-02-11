@extends('layouts.master')

@section('page-title', 'Detail IKM - ' . ($ikm->first()->nama ?? 'N/A'))

@section('styles')

<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">



<style>
/* MATIKAN SEMUA FOCUS TABLE */
table:focus,
table:focus-within,
tr:focus,
tr:focus-within,
td:focus,
td:focus-within {
    outline: none !important;
    box-shadow: none !important;
    border-color: inherit !important;
}

/* MATIKAN CONTENTEDITABLE */
[contenteditable],
[contenteditable]:focus,
[contenteditable]:focus-visible {
    outline: none !important;
    box-shadow: none !important;
}

/* MATIKAN TINYMCE INLINE TOTAL */
.tox-tinymce-inline,
.tox-tinymce-inline *,
.tox-edit-area,
.tox-edit-area__iframe {
    outline: none !important;
    box-shadow: none !important;
}

/* EDITOR ITSELF */
.inline-editor{
    border: none !important;
}



    .dark input { color: #9fa6bc; }
    .mytr { background-color: #f8f8f8; }
    .dark .mytr { background-color: transparent; }
    .transparent-input {
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
        width: 100%;
    }
    .transparent-textarea {
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
        width: 100%;
        resize: vertical;
        min-height: 60px;
    }
    .ql-container {
        border-bottom-left-radius: 0.375rem;
        border-bottom-right-radius: 0.375rem;
        font-size: 14px;
    }
    .ql-toolbar {
        border-top-left-radius: 0.375rem;
        border-top-right-radius: 0.375rem;
    }
    .editor-wrapper {
        background: #fff;
        border-radius: 0.375rem;
        border: 1px solid #e1e5eb;
    }
    .dark .editor-wrapper {
        background: #2e3344;
        border-color: #3d4458;
    }
    .editor-wrapper:focus-within {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.125rem rgba(13, 110, 253, 0.15);
    }
    .image-editor-container {
        max-height: 400px;
        background: #f8f9fa;
    }
    .dark .image-editor-container {
        background: #2e3344;
    }
    .cropper-view-box,
    .cropper-face {
        border-radius: 0;
    }
    .discussion-card {
        transition: all 0.2s ease;
    }
    .discussion-card:hover {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    .dark .discussion-card:hover {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.25);
    }
    .comment-input-wrapper {
        position: relative;
    }
    .comment-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 14px;
    }
    .benchmark-card {
        position: relative;
        overflow: hidden;
        border-radius: 0.5rem;
    }
    .benchmark-card img {
        transition: transform 0.3s ease;
    }
    .benchmark-card:hover img {
        transform: scale(1.05);
    }
    .benchmark-actions {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 0.5rem;
        background: linear-gradient(transparent, rgba(0,0,0,0.7));
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .benchmark-card:hover .benchmark-actions {
        opacity: 1;
    }
    .design-preview-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 0.75rem;
    }
    .design-preview-item {
        position: relative;
        aspect-ratio: 1;
        border-radius: 0.5rem;
        overflow: hidden;
        cursor: pointer;
    }
    .design-preview-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .design-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0,0,0,0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.2s ease;
    }
    .design-preview-item:hover .design-overlay {
        opacity: 1;
    }
    .tab-content-editor {
        min-height: 200px;
    }
    .color-picker-btn {
        width: 36px;
        height: 36px;
        border-radius: 0.375rem;
        border: 2px solid #e1e5eb;
        cursor: pointer;
    }
    .dark .color-picker-btn {
        border-color: #3d4458;
    }
    .ai-generate-btn {
    opacity: 0;
    pointer-events: none;
    transition: opacity .15s ease;
    top: 50%;
    right: 0;
    transform: translateY(-50%);
}

.ai-editor-wrapper:focus-within .ai-generate-btn {
    opacity: 1;
    pointer-events: auto;
}

/* Hilangkan semua efek fokus */
.inline-editor {
    background: transparent;
    border: none;
    outline: none !important;
    box-shadow: none !important;
    padding-right: 40px;
}

</style>
@endsection

@section('content')
@if($ikm->first())
<div class="row">
    <div class="col-xxl-12">
        <div class="row g-0">
            <!-- Main Content -->
            <div class="col-xl-9">
                <div class="card card-h-100 rounded-0 rounded-start border-end border-dashed">
                    <!-- Profile Header -->
                    <div class="card-header align-items-start p-4">
                        <div class="avatar-xxl me-3 position-relative">
                                <a data-fslightbox href="{{ asset('storage/'.$ikm->first()->gambar) }}" title="Klik untuk perbesar">
                                    @if($ikm->first()->gambar && file_exists(storage_path('app/public/' . $ikm->first()->gambar)))
                                        <img src="{{ asset('storage/'.$ikm->first()->gambar) }}" alt="{{ $ikm->first()->nama }}" class="rounded" style="width: 72px; height: 72px; object-fit: cover;">
                                    @else
                                        <div class="rounded d-flex align-items-center justify-content-center" style="width: 72px; height: 72px; background-color: #e9ecef;">
                                            <i class="ti ti-user" style="font-size: 32px; color: #6c757d;"></i>
                                        </div>
                                    @endif
                                </a>
                                </a>
                                <button class="btn btn-light btn-sm position-absolute bottom-0 end-0 rounded-circle p-1" data-bs-toggle="modal" data-bs-target="#UpdatePicture" title="Ubah foto" style="width: 24px; height: 24px; line-height: 1;">
                                    <i class="ti ti-pencil" style="font-size: 12px;"></i>
                                </button>
                            </div>
                        <div>
                            <h3 class="mb-1 d-flex fs-xl align-items-center">{{ $ikm->first()->nama }} - {{ $project->NamaProjek }} </h3>
                            <p class="text-muted mb-2 fs-xxs">Updated {{ $ikm->first()->updated_at->diffForHumans() }}</p>
                            <span class="badge badge-soft-success fs-xxs badge-label">In Progress</span>
                        </div>
                        <div class="ms-auto d-flex gap-2">
                            <a href="{{ url()->previous() }}" class="btn btn-light">
                                <i class="ti ti-arrow-left me-1"></i> Kembali
                            </a>
                            <a href="/project/dataikm/{{ $project->id }}/update" class="btn btn-light">
                                <i class="ti ti-pencil me-1"></i> Edit
                            </a>
                               <a class="btn btn-soft-secondary btn-sm" href="/report/brainstorming/{{ $ikm->first()->id }}/{{ $ikm->first()->nama }}" target="_blank">
                                                <i class="far fa-file-pdf me-1"></i> Export
                                            </a>
                        </div>

                    </div>
                    <div class="card-body px-4">
                        <!-- Project Info -->
                        <div class="mb-4">
                            <h5 class="fs-base mb-2">Informasi IKM:</h5>
                            <p class="text-muted">{!! $ikm->first()->jenisProduk !!} - {!! $ikm->first()->namaUsaha ?? 'N/A' !!}</p>
                            <p class="text-muted">
                                {{ $ikm->first()->alamat }}{{ $ikm->first()->district->name ?? '' }} {{ $ikm->first()->regency->name ?? '' }} {{ $ikm->first()->province->name ?? '' }}
                            </p>
                            <p class="text-muted">
                                Telepon: {{ $ikm->first()->telp }}
                            </p>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-4 col-xl-3">
                                <h6 class="mb-1 text-muted text-uppercase">Tanggal Bergabung:</h6>
                                <p class="fw-medium mb-0">{{ $ikm->first()->created_at->format('F d, Y') }}</p>
                            </div>
                            <div class="col-md-4 col-xl-3">
                                <h6 class="mb-1 text-muted text-uppercase">Jenis Produk:</h6>
                                <p class="fw-medium mb-0">{!! $ikm->first()->jenisProduk !!}</p>
                            </div>
                            <div class="col-md-4 col-xl-3">
                                <h6 class="mb-1 text-muted text-uppercase">Merk:</h6>
                                <p class="fw-medium mb-0">{!! $ikm->first()->merk ?? 'N/A' !!}</p>
                            </div>
                            <div class="col-md-4 col-xl-3">
                                <h6 class="mb-1 text-muted text-uppercase">Nama Usaha:</h6>
                                <p class="fw-medium mb-0">{!! $ikm->first()->namaUsaha ?? 'N/A' !!}</p>
                            </div>
                        </div>

                        <!-- Tabs -->
                        <ul class="nav nav-tabs nav-bordered mb-3" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" data-bs-toggle="tab" href="#tab-info" role="tab" aria-selected="false">
                                    <i class="ti ti-user fs-lg me-md-1 align-middle"></i>
                                    <span class="d-none d-md-inline-block align-middle">Informasi IKM</span>
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" data-bs-toggle="tab" href="#tab-bencmark" role="tab" aria-selected="true">
                                    <i class="ti ti-file fs-lg me-md-1 align-middle"></i>
                                    <span class="d-none d-md-inline-block align-middle">Brainstorming</span>
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" data-bs-toggle="tab" href="#tab-cots" role="tab" aria-selected="false" tabindex="-1">
                                    <i class="ti ti-home fs-lg me-md-1 align-middle"></i>
                                    <span class="d-none d-md-inline-block align-middle">COTS</span>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <!-- IKM Info Tab -->
                            <div class="tab-pane fade" id="tab-info" role="tabpanel">
                                <div class="section1">
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <input class="form-control" id="nama" type="text" placeholder="Nama Lengkap"
                                                    name="nama" required value="{{ $ikm->first()->nama }}" readonly />
                                                <label class="form-label" for="provinsi">Nama Lengkap<span style="color:red">*</span></label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <input required class="form-control" type="text" placeholder="Nomor Telepon"
                                                    name="telp" id="telp" value="{{ $ikm->first()->telp }}" readonly />
                                                <label class="form-label" for="name">No Telepon<span style="color:red">*</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <select required class="form-control" aria-label="Default select example"
                                                    name="gender" id="gender" disabled>
                                                    @if ($ikm->first()->gender == 1)
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
                                                placeholder="Alamat" value="{{ $ikm->first()->alamat }}" readonly />
                                            <label class="form-label" for="alamat">Alamat<span style="color:red">*</span></label>
                                        </div>
                                    </div>
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <select required class="form-control" id="provinsi" name="id_provinsi" disabled>
                                                    <option value="">
                                                        @if ($ikm->first()->province)
                                                            {{ $ikm->first()->province->name }}
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
                                                        @if ($ikm->first()->regency)
                                                            {{ $ikm->first()->regency->name }}
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
                                                        @if ($ikm->first()->district)
                                                            {{ $ikm->first()->district->name }}
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
                                                        @if ($ikm->first()->village)
                                                            {{ $ikm->first()->village->name }}
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
                                                        placeholder="RT" value="{{ $ikm->first()->rt }}" readonly />
                                                    <label class="form-label" for="rt">RT<span style="color:red">*</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3 text-start">
                                                <div class="form-floating">
                                                    <input required class="form-control" id="rw" name="rw" type="text"
                                                        placeholder="RW" value="{{ $ikm->first()->rw }}" readonly />
                                                    <label class="form-label" for="rw">RW<span style="color:red">*</span></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Brainstorming Tab -->
                            <div class="tab-pane fade active show" id="tab-bencmark" role="tabpanel">


                                <form action="/project/ikms/updateBrainstorming" method="post">
                                    @csrf

                                    <input type="hidden" name="id_ikm" value="{{ $ikm->first()->id }}">
                                    <input type="hidden" name="id_Project" value="{{ $ikm->first()->id_Project }}">

                                    <table class="table table-bordered table-responsive" style="table-layout: fixed;">
                                        <thead>
                                            <tr>
                                                <th style="width:200px;">Produk</th>
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
                                                            {!! $ikm->first()->$key ?? '' !!}
                                                        </div>
                                                        <a type="button" class=" ai-generate-btn position-absolute" style="top: 50%; right: 0; transform: translateY(-50%); border: none;" data-field="{{ $key }}" data-label="{{ $label }}" title="Generate dengan AI">
                                                            <i class="ti ti-sparkles"></i>
                                                        </a>
                                                    </div>
                                                    <input type="hidden" name="{{ $key }}" id="{{ $key }}_input">
                                                </td>
                                            </tr>
                                            @endforeach

                                        </tbody>
                                    </table>

                                    <div class="text-end mt-3">
                                        <a href="{{ url()->previous() }}" class="btn btn-secondary">Cancel</a>
                                        <button type="submit" class="btn btn-primary">Simpan Data</button>
                                    </div>
                                </form>

                                </div>


                            <!-- COTS Tab -->
                            <div class="tab-pane fade" id="tab-cots" role="tabpanel">
                                @if(isset($cots) && $cots != 0 && isset($cotsview))
                                    @foreach($cotsview as $a)
                                        <div class="row justify-content-between align-items-end g-3 mb-4">
                                            <div class="col-12 col-sm-auto">
                                                <h5 class="mb-0">Form Coaching on The Spot (COTS)</h5>
                                            </div>
                                            <div class="col-12 col-sm-auto">
                                                <div class="d-flex gap-2">
                                                    @if($ikm->first()->id_provinsi != NULL)
                                                        <a class="btn btn-soft-secondary btn-sm" href="/report/cots/{{ $ikm->first()->id }}/{{ $ikm->first()->nama }}">
                                                            <i class="far fa-file-pdf me-1"></i> Export
                                                        </a>
                                                    @else
                                                        <button class="btn btn-soft-secondary btn-sm" onclick="alert('Mohon Lengkapi Data IKM terlebih dahulu!')">
                                                            <i class="far fa-file-pdf me-1"></i> Export
                                                        </button>
                                                    @endif
                                                    <a class="btn btn-soft-primary btn-sm" id="enableCots">
                                                        <i class="fas fa-pencil-alt me-1"></i> Edit
                                                    </a>
                                                </div>
                                            </div>
                                        </div>

                                        <form action="/project/ikms/{{ $ikm->first()->id }}/updateCots" method="POST">
                                            @csrf
                                            <div class="d-flex gap-2 mb-3" id="cotsActions" style="display:none;">
                                                <button class="btn btn-phoenix-primary btn-sm" type="button" id="batalCots">Batal</button>
                                                <button class="btn btn-primary btn-sm" type="submit" id="simpanCots">Simpan</button>
                                            </div>

                                            <input type="text" name="id_ikm" value="{{ $ikm->first()->id }}" hidden>
                                            <input type="text" name="id_project" value="{{ $project->id }}" hidden>
                                            <input type="text" name="id_cots" value="{{ $a->id }}" hidden>

                                            <table class="table table-bordered" style="table-layout: fixed; overflow-wrap: break-word;">
                                                <tbody>
                                                    @php
                                                    $cotsFields = [
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

                                                    @foreach($cotsFields as $key => $label)
                                                    <tr>
                                                        <td style="padding-left:10px;background-color:#f5f5f5;width:200px;"><strong>{{ $label }}</strong></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="p-2">
                                                            <div id="{{ $key }}" class="inline-editor" style="background: transparent; border-top-style: hidden; border-right-style: hidden; border-left-style: hidden; border-bottom-style: hidden; outline:none !important; outline-width: 0 !important; box-shadow: none; -moz-box-shadow: none; -webkit-box-shadow: none; margin:0; padding:0; min-height: 60px;" contenteditable="true" data-placeholder="{{ $label }}" readonly>
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
                                        <p class="text-muted mb-3">Belum ada Laporan COTS</p>
                                        <form action="/project/ikms/{{ $ikm->first()->id }}/cots" method="post" class="d-inline">
                                            @csrf
                                            <input type="text" name="id_ikm" value="{{ $ikm->first()->id }}" hidden>
                                            <input type="text" name="id_project" value="{{ $ikm->first()->id_Project }}" hidden>
                                            <button class="btn btn-phoenix-primary" type="submit">
                                                <i class="fas fa-plus me-1"></i> Buat Laporan COTS
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
                                <h5 class="mb-0">Bencmark Produk ({{ $ikm->first()->bencmark->count() }})</h5>
                                <button class="btn btn-phoenix-primary btn-sm" data-bs-toggle="modal" data-bs-target="#verticallyCentered" title="Upload Bencmark">
                                    <i class="ti ti-plus"></i>
                                </button>
                            </div>
                            <div class="row g-2">
                                @if($ikm->first()->bencmark && $ikm->first()->bencmark->count())
                                    @foreach($ikm->first()->bencmark as $image)
                                        <div class="col-6">
                                            <form action="/project/ikms/{{ $image->id }}/deletebencmark" method="post">
                                                @csrf
                                                <input type="text" value="{{ $image->gambar }}" name="oldImage" hidden>
                                                <div class="position-relative rounded overflow-hidden" style="height: 80px;">
                                                    <button class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 z-3" type="submit" onclick="return confirm('Yakin hapus?')">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                    <a data-fslightbox href="{{ asset('storage/'.$image->gambar) }}">
                                                        <img class="w-100 h-100" src="{{ asset('storage/'.$image->gambar) }}" alt="Bencmark" style="object-fit: cover;">
                                                    </a>
                                                </div>
                                            </form>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="col-12">
                                        <div class="d-flex flex-column align-items-center justify-content-center py-3 bg-light rounded">
                                            <i class="ti ti-photo fs-4 text-muted mb-1"></i>
                                            <small class="text-muted">Belum ada</small>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Desain Produk -->
                        <div class="p-3 border-bottom border-dashed">
                            <div class="d-flex mb-3 justify-content-between align-items-center">
                                <h5 class="mb-0">Desain Produk ({{ $ikm->first()->produkDesign->count() }})</h5>
                                <button class="btn btn-phoenix-primary btn-sm" data-bs-toggle="modal" data-bs-target="#uploadDesign" title="Upload Desain">
                                    <i class="ti ti-plus"></i>
                                </button>
                            </div>
                            <div class="row g-2">
                                @if($ikm->first()->produkDesign && $ikm->first()->produkDesign->count())
                                  @foreach($ikm->first()->produkDesign as $image)
                                <div class="col-6">
                                    <form action="/project/ikms/{{ $image->id }}/deleteDesain" method="post">
                                        @csrf
                                        <input type="hidden" value="{{ $image->gambar }}" name="oldImage">

                                        <div class="image-wrapper position-relative rounded overflow-hidden" style="height:80px;">

                                            <button
                                                class="btn btn-danger btn-sm delete-btn position-absolute top-0 end-0 m-1 z-3"
                                                type="submit"
                                                onclick="return confirm('Yakin hapus?')"
                                            >
                                                <i class="ti ti-trash"></i>
                                            </button>

                                            <a data-fslightbox href="{{ asset('storage/'.$image->gambar) }}">
                                                <img
                                                    src="{{ asset('storage/'.$image->gambar) }}"
                                                    class="w-100 h-100"
                                                    style="object-fit:cover;"
                                                    alt="Desain"
                                                >
                                            </a>

                                        </div>
                                    </form>
                                </div>
                            @endforeach

                                @else
                                    <div class="col-12">
                                        <div class="d-flex flex-column align-items-center justify-content-center py-3 bg-light rounded">
                                            <i class="ti ti-palette fs-4 text-muted mb-1"></i>
                                            <small class="text-muted">Belum ada</small>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Dokumentasi COTS -->
                        <div class="p-3">
                            <div class="d-flex mb-3 justify-content-between align-items-center">
                                <h5 class="mb-0">Dokumentasi</h5>
                                <button class="btn btn-phoenix-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addDokumentasi" title="Upload Dokumentasi">
                                    <i class="ti ti-plus"></i>
                                </button>
                            </div>
                            <div class="row g-2">
                                @if(isset($dokumentasicots) && $dokumentasicots->count())
                                    @foreach($dokumentasicots as $img)
                                        <div class="col-6">
                                            <form action="/project/ikms/{{ $ikm->first()->id }}/deleteDoc" method="post">
                                                @csrf
                                                <input type="text" value="{{ $img->id }}" name="id_gambar" hidden>
                                                <input type="text" value="{{ $img->gambar }}" name="old_gambar" hidden>
                                                <div class="position-relative rounded overflow-hidden" style="height: 80px;">
                                                    <button class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 z-3" type="submit" onclick="return confirm('Yakin hapus?')">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                    <a data-fslightbox href="{{ asset('storage/'.$img->gambar) }}">
                                                        <img class="w-100 h-100" src="{{ asset('storage/'.$img->gambar) }}" alt="Dokumentasi" style="object-fit: cover;">
                                                    </a>
                                                </div>
                                            </form>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="col-12">
                                        <div class="d-flex flex-column align-items-center justify-content-center py-3 bg-light rounded">
                                            <i class="ti ti-photo fs-4 text-muted mb-1"></i>
                                            <small class="text-muted">Belum ada</small>
                                        </div>
                                    </div>
                                @endif
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
            <form action="/project/ikms/{{ encrypt($ikm->first()->id) }}/bencmark" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="text" name="id_ikm" value="{{ $ikm->first()->id }}" hidden>
                    <input type="text" name="id_Project" value="{{ $project->id }}" hidden>

                    <div class="mb-3">
                        <label for="bencmarkFiles" class="form-label">Pilih Gambar Bencmark:</label>
                        <input class="form-control" type="file" id="bencmarkFiles" name="gambar[]" multiple accept="image/*">
                        <small class="text-muted">Bisa upload lebih dari 1 gambar</small>
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
            <form action="/project/ikms/{{ encrypt($ikm->first()->id) }}/tambahDesain" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="text" name="id_ikm" value="{{ $ikm->first()->id }}" hidden>
                    <input type="text" name="id_project" value="{{ $project->id }}" hidden>

                    <div class="mb-3">
                        <label for="designFiles" class="form-label">Pilih Gambar Desain:</label>
                        <input class="form-control" type="file" id="designFiles" name="gambar[]" multiple accept="image/*">
                        <small class="text-muted">Bisa upload lebih dari 1 gambar</small>
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
                <form action="/project/ikms/{{ $ikm->first()->id }}/dokumentasi" method="POST" enctype="multipart/form-data">
                    @csrf
                    <label for="foto" class="form-label">Pilih Photo :</label>
                    <input type="text" name="id_ikm" value="{{ $ikm->first()->id }}" hidden>
                    <input type="text" name="id_project" value="{{ $project->id }}" hidden>
                    <input type="file" name="gambar[]" id="gambar" class="form-control" multiple>
                    <br>
                    <div class="alert alert-soft-primary" role="alert">
                        <small>Upload max 3 foto secara bertahap</small>
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
                <h5 class="modal-title" id="UpdatePictureModalLabel"><i class="ti ti-user me-2"></i>Ubah Foto IKM</h5>
                <button class="btn p-1" type="button" data-bs-dismiss="modal" aria-label="Close">
                    <span class="ti ti-x"></span>
                </button>
            </div>
            <div class="modal-body">
                <form action="/project/ikms/{{ $ikm->first()->id }}/update" method="POST" enctype="multipart/form-data" id="cropForm">
                    @csrf
                    <input type="text" name="id_projek" value="{{ $project->id }}" hidden>
                    <input type="text" name="id_ikm" value="{{ $ikm->first()->id }}" hidden>
                    <input type="text" name="oldImage" value="{{ $ikm->first()->gambar }}" hidden>

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
document.addEventListener("DOMContentLoaded", function () {
    const fields = [
        'jenisProduk', 'merk', 'komposisi', 'varian', 'kelebihan',
        'namaUsaha', 'noPIRT', 'noHalal', 'legalitasLain', 'other',
        'segmentasi', 'jenisKemasan', 'harga', 'tagline', 'redaksi', 'gramasi'
    ];

    const cotsFields = [
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
                    hidden.value = editor.getContent();
                });

                editor.on('change keyup', function () {
                    const hidden = document.getElementById(id + '_input');
                    hidden.value = editor.getContent();
                });
            }
        });
    });

    // COTS Edit functionality
    const enableCotsBtn = document.getElementById('enableCots');
    const batalCotsBtn = document.getElementById('batalCots');
    const cotsActionsDiv = document.getElementById('cotsActions');

    if (enableCotsBtn) {
        enableCotsBtn.addEventListener('click', function () {
            cotsFields.forEach(id => {
                const el = document.getElementById(id);
                if (el) el.removeAttribute('readonly');
            });
            if (cotsActionsDiv) cotsActionsDiv.style.display = 'flex';
        });
    }

    if (batalCotsBtn) {
        batalCotsBtn.addEventListener('click', function () {
            cotsFields.forEach(id => {
                const el = document.getElementById(id);
                if (el) el.setAttribute('readonly', 'readonly');
            });
            if (cotsActionsDiv) cotsActionsDiv.style.display = 'none';
        });
    }

    // AI Generate Button Event Listeners
    document.querySelectorAll('.ai-generate-btn').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const fieldId = this.getAttribute('data-field');
            const fieldLabel = this.getAttribute('data-label');

            document.getElementById('aiTargetField').value = fieldId;
            const promptInput = document.getElementById('aiPrompt');
            promptInput.value = `Tuliskan konten untuk field "${fieldLabel}" yang menarik dan profesional untuk produk IKM ini.`;

            const modal = new bootstrap.Modal(document.getElementById('aiGenerateModal'));
            modal.show();
        });
    });

    // Delegated event listener for AI option cards (backup for onclick)
    const optionsContainer = document.getElementById('aiOptionsContainer');
    if (optionsContainer) {
        optionsContainer.addEventListener('click', function(e) {
            const card = e.target.closest('.ai-option-card');
            if (card) {
                const targetField = document.getElementById('aiOptionsTargetField').value;
                console.log('Delegated click handler - field:', targetField);
                if (targetField) {
                    selectOption(card, targetField);
                } else {
                    console.error('Target field not set in delegated handler');
                }
            }
        });
    }
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
10.Nama Boleh terinspirasi dari **Nama IKM atau lokasi**
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
            instruction: `Buatkan 4 variasi <strong>komposisi produk</strong>.
Gunakan <ul><li> untuk daftar bahan.
Bahasa harus rapi, aman, dan profesional.`
        },

        varian: {
            title: 'Varian Produk',
            instruction: `Buatkan 4 variasi <strong>varian produk</strong>.
Sebutkan perbedaan rasa, ukuran, atau keunikan.
Gunakan <ul><li>.`
        },

        kelebihan: {
            title: 'Kelebihan Produk',
            instruction: `Buatkan 5 poin <strong>kelebihan produk</strong>.
Fokus pada manfaat dan nilai tambah.
Gunakan <ul><li>.`
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
            instruction: `Buatkan 5 opsi <strong>tagline</strong>
yang singkat, kuat, dan mudah diingat.`
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

            const editorElement = document.getElementById(targetField);
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
    background: #fff;
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
    <p>Data IKM tidak ditemukan.</p>
</div>
@endif

@endsection
