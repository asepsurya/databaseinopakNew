@extends('layouts.master')

@section('title', 'Data Ikm - ' . $project->NamaProjek)

@section('content')
@push('styles')
<!-- DataTables CSS -->
<link href="{{ asset('assets/plugins/datatables/dataTables.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/plugins/datatables/responsive.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/css/thumbnail.css') }}" rel="stylesheet" type="text/css" />
<style>
    .table {
        border-collapse: separate;
        border-spacing: 0;
    }
    .table thead th {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 12px 16px;
        border-bottom: 1px solid #e0e5ef;
        background-color: #f8f9fa;
        white-space: nowrap;
    }
    .table tbody td {
        padding: 14px 16px;
        vertical-align: middle;
        border-bottom: 1px solid #e0e5ef;
        font-size: 14px;
    }
    .table tbody tr:hover {
        background-color: #f8f9fa;
    }
    .badge-soft-success {
        background-color: rgba(28, 187, 140, 0.1);
        color: #1cbb8c;
    }
    .badge-soft-danger {
        background-color: rgba(250, 92, 124, 0.1);
        color: #fa5c7d;
    }
    .badge-soft-primary {
        background-color: rgba(45, 104, 254, 0.1);
        color: #2d68fe;
    }
    .badge-soft-warning {
        background-color: rgba(255, 162, 0, 0.1);
        color: #ffa200;
    }
    .badge-soft-info {
        background-color: rgba(0, 150, 255, 0.1);
        color: #0096ff;
    }
    .badge-soft-secondary {
        background-color: rgba(172, 181, 193, 0.1);
        color: #8e99a4;
    }
    .dataTables_wrapper .dataTables_length select {
        border-radius: 4px;
        padding: 6px 12px;
        border: 1px solid #e0e5ef;
    }
    .dataTables_wrapper .dataTables_filter input {
        border-radius: 4px;
        padding: 8px 12px;
        border: 1px solid #e0e5ef;
        margin-left: 8px;
    }
    .dataTables_wrapper .dataTables_info {
        font-size: 13px;
        color: #6c757d;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border-radius: 4px;
        padding: 6px 12px;
        margin: 0 2px;
        border: 1px solid #e0e5ef;
        color: #6c757d;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background-color: #f8f9fa;
        color: #333 !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background-color: var(--bs-primary);
        border-color: var(--bs-primary);
        color: white !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
        color: #dee2e6 !important;
        border-color: transparent !important;
    }
    .btn-icon {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .avatar-sm {
        width: 40px;
        height: 40px;
    }
    .avatar-title {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
        font-size: 1.1rem;
    }
    .link-reset {
        color: inherit;
        text-decoration: none;
    }
    .link-reset:hover {
        color: var(--bs-primary);
    }
    .dt-buttons {
        gap: 8px;
    }
    .dt-button {
        padding: 8px 16px;
        border-radius: 4px;
        font-size: 13px;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    .dt-button.btn-primary {
        background-color: var(--bs-primary);
        border-color: var(--bs-primary);
        color: white;
    }
    .dt-button.btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
        color: white;
    }
    /* DARK MODE DATATABLES */
    [data-bs-theme="dark"] table.dataTable {
        color: #dee2e6;
    }

    [data-bs-theme="dark"] .table-striped > tbody > tr:nth-of-type(odd) {
        background-color: rgba(255,255,255,.03);
    }

    [data-bs-theme="dark"] .dataTables_wrapper .dataTables_length,
    [data-bs-theme="dark"] .dataTables_wrapper .dataTables_filter,
    [data-bs-theme="dark"] .dataTables_wrapper .dataTables_info,
    [data-bs-theme="dark"] .dataTables_wrapper .dataTables_paginate {
        color: #adb5bd;
    }

    [data-bs-theme="dark"] .table tbody td, .table thead th{
        border-bottom: none;
    }

    [data-bs-theme="dark"] .dataTables_wrapper .dataTables_filter input,
    [data-bs-theme="dark"] .dataTables_wrapper .dataTables_length select {
        background-color: #212529;
        color: #dee2e6;
        border-color: #495057;
    }

    [data-bs-theme="dark"] .dataTables_wrapper .paginate_button {
        color: #dee2e6 !important;
    }
    .empty-icon {
        width: 85px;
        height: 85px;
        background: linear-gradient(135deg, #eef2ff, #e0e7ff);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 38px;
        color: #4f46e5;
        transition: 0.3s ease;
    }

    .empty-icon:hover {
        transform: scale(1.08);
    }

    .card {
        animation: fadeFade 0.4s ease-in-out;
    }

    @keyframes fadeFade {
        from {
            opacity: 0;
            transform: translateY(8px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    @media (max-width: 576px) {
        .projek-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
            text-align: center;
        }

    }
@media (max-width: 768px) {
    .thumbnail-wrapper {
        display: none;
    }
}

.folder-tree-item.active,
.ikm-folder-tree-item.active {
    background-color: #e7f1ff !important;
    border-color: #b3d7ff !important;
}

.folder-tree-item:hover {
    background-color: #f8f9fa;
}

.folder-tree-root,
.folder-tree-bc {
    font-size: 12px;
    padding: 2px 8px;
}

.breadcrumb {
    background-color: transparent;
    padding: 0;
    margin-bottom: 4px;
}

.breadcrumb-item a {
    color: #4b5563;
    text-decoration: none;
    font-weight: 500;
    font-size: 13px;
}

.breadcrumb-item a:hover {
    color: #2563eb;
    text-decoration: underline;
}

.breadcrumb-item.active {
    color: #111827;
    font-weight: 600;
    font-size: 13px;
}

.breadcrumb-item + .breadcrumb-item::before {
    color: #9ca3af;
    font-size: 11px;
}

    </style>
@endpush

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header justify-content-between d-flex align-items-center projek-header">
                <div >
                    <h4 class="mb-1"><i class="ti ti-user-group me-2"></i>Data Ikm - {{ $project->NamaProjek }}</h4>
                    @if(count($breadcrumbs) > 0)
                        <nav aria-label="breadcrumb" class="ms-2 ">
                            <ol class="breadcrumb mb-0 ">
                                <li class="breadcrumb-item"><a href="/project/dataIkm/{{ $project->id }}">Root</a></li>
                                @foreach($breadcrumbs as $bc)
                                    @if($loop->last)
                                        <li class="breadcrumb-item text-primary" aria-current="page">{{ $bc->name }}</li>
                                    @else
                                        <li class="breadcrumb-item"><a href="/project/dataIkm/{{ $project->id }}/folder/{{ $bc->id }}">{{ $bc->name }}</a></li>
                                    @endif
                                @endforeach
                            </ol>
                        </nav>
                    @endif
                </div>
                <div class="d-flex gap-2">
                    @if($currentFolder)
                        <a href="{{ $currentFolder->parent_id ? '/project/dataIkm/'.$project->id.'/folder/'.$currentFolder->parent_id : '/project/dataIkm/'.$project->id }}" class="btn btn-light btn-sm"><i class="ti ti-arrow-left me-1"></i> Kembali</a>
                    @else
                        <a href="/project" class="btn btn-light btn-sm"><i class="ti ti-arrow-left me-1"></i> Kembali</a>
                    @endif
                    <button class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahFolder"><i class="ti ti-folder-plus me-1"></i> Folder</button>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahIkm"><i class="ti ti-plus me-1"></i> Ikm</button>
                </div>
            </div>
            <div class="card-body px-0 m-0">

                <!-- Empty State -->
                @if($dataIkm->isEmpty() && $folders->isEmpty())
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body text-center py-5">

                            <!-- Icon -->
                            <div class="mb-4">
                                <div class="empty-icon mx-auto">
                                    <i class="ti ti-inbox"></i>
                                </div>
                            </div>

                            <!-- Title -->
                            <h5 class="fw-bold mb-2">Belum Ada Data Ikm</h5>

                            <!-- Description -->
                            <p class="text-muted mb-4">
                                Saat ini belum ada data Ikm yang tersedia.
                                Silakan tambahkan data pertama untuk mulai mengelola Ikm Anda.
                            </p>

                            <!-- Button -->
                            <button class="btn btn-primary rounded-pill px-4"
                                    data-bs-toggle="modal"
                                    data-bs-target="#tambahIkm">
                                <i class="ti ti-plus me-2"></i>
                                Tambah Ikm Pertama
                            </button>

            </div>
        </div>
    </div>
</div>



                @else
                <!-- Table -->
                <div class="table-responsive">
                <table class="table table-striped align-middle mb-0 dt-responsive" id="Ikm-table">

                    <thead class="thead-sm text-uppercase fs-xxs">
                        <tr>
                            <th>No</th>
                            <th>Nama Ikm</th>
                            <th>Jenis Produk</th>
                            <th>Merk</th>
                            <th>Telepon</th>
                            <th>Perusahaan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @foreach($folders as $folder)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="thumbnail-wrapper thumbnail-avatar me-3 d-flex align-items-center justify-content-center bg-light text-warning" style="width: 40px; height: 40px; border-radius: 8px;">
                                        <i class="ti ti-folder-filled fs-4"></i>
                                    </div>
                                    <div>
                                        <a href="/project/dataIkm/{{ $project->id }}/folder/{{ $folder->id }}" class="link-reset fw-medium">{{ $folder->name }}</a>
                                        <p class="text-muted mb-0 small" style="font-size: 12px;">Folder</p>
                                    </div>
                                </div>
                            </td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>
                                <span class="badge badge-label badge-soft-info">Folder</span>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <button type="button" class="btn btn-sm btn-light btn-icon rename-folder-btn" 
                                        title="Rename Folder"
                                        data-id="{{ $folder->id }}" 
                                        data-name="{{ $folder->name }}">
                                        <i class="ti ti-pencil"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-light btn-icon move-folder-btn" 
                                        title="Pindah Folder"
                                        data-id="{{ $folder->id }}" 
                                        data-name="{{ $folder->name }}"
                                        data-project="{{ $project->id }}">
                                        <i class="ti ti-arrows-exchange"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-light btn-icon text-danger delete-folder-btn" 
                                        title="Hapus Folder"
                                        data-id="{{ $folder->id }}" 
                                        data-name="{{ $folder->name }}"
                                        data-project="{{ $project->id }}"
                                        data-token="{{ csrf_token() }}">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @foreach($dataIkm as $data)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="thumbnail-wrapper thumbnail-avatar me-3">
                                        @if($data->gambar && \App\Helpers\ThumbnailHelper::isValidImage($data->gambar))
                                            <a href="{{ \App\Helpers\ThumbnailHelper::originalUrl($data->gambar) }}"
                                               data-fslightbox
                                               title="Klik untuk perbesar">
                                                <img src="{{ \App\Helpers\ThumbnailHelper::thumbnailUrl($data->gambar, 'small', true) ?? \App\Helpers\ThumbnailHelper::originalUrl($data->gambar) }}"
                                                     alt="{{ $data->nama }}"
                                                     class="thumbnail-image"
                                                     loading="lazy">
                                            </a>

                                        @else
                                            <div class="thumbnail-fallback d-flex align-items-center justify-content-center w-100 h-100">
                                                <i class="ti ti-user"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <a href="/project/Ikms/{{ encrypt($data->id) }}/{{ $project->id }}" class="link-reset fw-medium">{{ $data->nama }}</a>
                                        <p class="text-muted mb-0 small" style="font-size: 12px;">{{ $data->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>{!! $data->jenisProduk !!}</td>
                            <td>
                                @if($data->merk)
                                    {!! $data->merk !!}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($data->telp)
                                    <a href="https://wa.me/{{ $data->telp }}" target="_blank">{!! $data->telp !!}</a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{!! $data->namaUsaha ?? '-' !!}</td>
                            <td>
                                <span class="badge badge-label badge-soft-success">Aktif</span>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="/project/Ikms/{{ encrypt($data->id) }}/{{ $project->id }}" class="btn btn-sm btn-light btn-icon" title="Detail">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                    <form action="{{ route('ikm.edit', ['ikm' => $data->id]) }}" method="GET" class="d-inline">
                                        @csrf

                                        <button type="submit" class="btn btn-sm btn-light btn-icon" title="Ubah">
                                            <i class="ti ti-pencil"></i>
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-sm btn-light btn-icon move-ikm-btn" title="Pindah Folder" data-id="{{ $data->id }}" data-name="{{ $data->nama }}" data-project="{{ $project->id }}">
                                        <i class="ti ti-arrows-exchange"></i>
                                    </button>
                                    <form action="/project/dataIkm/{{ $project->id }}/delete" method="POST" class="d-inline delete-form" data-ikm-name="{{ $data->nama }}">
                                        @csrf
                                        <input type="text" value="{{ $data->id }}" name="id_Ikm" hidden>
                                        <input type="text" value="{{ $project->id }}" name="id_Project" hidden>
                                        <button type="button" class="btn btn-sm btn-light btn-icon delete-btn" title="Hapus" data-id="{{ $data->id }}" data-name="{{ $data->nama }}">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
                @endif
            </div>
        </div>
    </div> 
</div>

<!-- Modal Tambah Ikm -->
<div class="modal fade" id="tambahIkm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addMemberModalLabel">+ Tambah Ikm</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/project/dataIkm/tambahIkm" method="post">
                    @csrf
                    <div class="mb-3">
                        <div class="form-floating">
                            <input type="text" class="form-control" name="nama" placeholder="Nama" required>
                            <label for="nama" class="form-label">Nama Ikm</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-floating">
                            <input type="text" class="form-control" name="jenisProduk" placeholder="Jenis Produk" required>
                            <label for="jenisProduk" class="form-label">Jenis Produk</label>
                        </div>
                    </div>
                    <input type="text" name="id_Project" id="id_Project" value="{{ $project->id }}" hidden>
                    @if(isset($currentFolder))
                    <input type="text" name="folder_id" value="{{ $currentFolder->id }}" hidden>
                    @endif
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="ti ti-device-floppy me-2"></i>Simpan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>




<!-- Modal Tambah Folder -->
<div class="modal fade" id="tambahFolder" tabindex="-1" role="dialog" aria-labelledby="tambahFolderLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahFolderLabel">+ Tambah Folder</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/project/dataIkm/createFolder" method="post">
                    @csrf
                    <div class="mb-3">
                        <div class="form-floating">
                            <input type="text" class="form-control" name="name" placeholder="Nama Folder" required>
                            <label for="name" class="form-label">Nama Folder</label>
                        </div>
                    </div>
                    <input type="text" name="id_Project" value="{{ $project->id }}" hidden>
                    @if(isset($currentFolder))
                    <input type="text" name="parent_id" value="{{ $currentFolder->id }}" hidden>
                    @endif
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="ti ti-device-floppy me-2"></i>Simpan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Rename Folder -->
<div class="modal fade" id="renameFolderModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ti ti-pencil me-2"></i>Ubah Nama Folder</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="form-floating">
                    <input type="text" class="form-control" id="renameFolderInput" placeholder="Nama Folder" required>
                    <label>Nama Folder Baru</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="confirmRenameBtn">
                    <i class="ti ti-check me-1"></i>Simpan
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Move Folder -->
<div class="modal fade" id="moveFolderModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ti ti-arrows-exchange me-2"></i>Pindah Folder: <span id="moveFolderName"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-2">
                    <label class="form-label fw-semibold">Pilih Folder Tujuan</label>
                    <div class="border rounded p-2" style="min-height: 220px; max-height: 260px; overflow-y: auto;">
                        <div id="folderBreadcrumb" class="mb-2"></div>
                        <ul class="list-group list-group-flush" id="folderTreeList"></ul>
                        <div id="folderTreeEmpty" class="text-center text-muted py-3" style="display:none;">
                            <i class="ti ti-folder-off"></i>
                            <p class="mb-0 small">Tidak ada subfolder.</p>
                        </div>
                    </div>
                    <small class="text-muted mt-1 d-block">Klik folder untuk masuk, atau pilih folder sebagai tujuan.</small>
                    <input type="hidden" id="moveFolderTargetSelect" value="">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="confirmMoveFolderBtn">
                    <i class="ti ti-arrows-exchange me-1"></i>Pindahkan
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Dokumen Folder -->
<div class="modal fade" id="uploadDocumentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ti ti-plus me-2"></i>Tambah Dokumen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="uploadDocumentForm">
                    @csrf
                    <input type="hidden" name="folder_id" value="{{ $currentFolder->id ?? '' }}">
                    <input type="hidden" name="id_Project" value="{{ $project->id }}">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Dokumen</label>
                        <input type="text" class="form-control" name="nama_file" placeholder="Contoh: Surat Pengantar" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">URL Dokumen</label>
                        <input type="url" class="form-control" name="url" placeholder="https://drive.google.com/..." required>
                        <small class="text-muted">Masukkan link dokumen eksternal (Google Drive, Dropbox, dll).</small>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="ti ti-device-floppy me-2"></i>Simpan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Pindah IKM -->
<div class="modal fade" id="moveIkmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pindah IKM: <span id="moveIkmName"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="/project/dataIkm/moveIkm" method="post" id="moveIkmForm">
                    @csrf
                    <input type="hidden" name="id_Ikm" id="moveIkmId">
                    <input type="hidden" name="id_Project" value="{{ $project->id }}">
                    @if(isset($currentFolder))
                    <input type="hidden" name="current_folder_id" value="{{ $currentFolder->id }}">
                    @endif
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pilih Folder Tujuan</label>
                        <div class="border rounded p-2" style="min-height: 220px; max-height: 260px; overflow-y: auto;">
                            <div id="ikmFolderBreadcrumb" class="mb-2"></div>
                            <ul class="list-group list-group-flush" id="ikmFolderTreeList"></ul>
                            <div id="ikmFolderTreeEmpty" class="text-center text-muted py-3" style="display:none;">
                                <i class="ti ti-folder-off"></i>
                                <p class="mb-0 small">Tidak ada subfolder.</p>
                            </div>
                        </div>
                        <small class="text-muted mt-1 d-block">Klik folder untuk masuk, atau pilih folder sebagai tujuan.</small>
                        <input type="hidden" name="target_folder_id" id="moveIkmTargetSelect" value="">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="ti ti-arrows-exchange me-2"></i>Pindahkan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus IKM -->
<div class="modal fade" id="deleteIkmModal" tabindex="-1" aria-labelledby="deleteIkmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteIkmModalLabel">
                    <i class="ti ti-alert-circle text-danger me-2"></i>Konfirmasi Penghapusan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus data <strong id="deleteIkmName"></strong>?</p>
                <div class="alert alert-warning">
                    <i class="ti ti-info-circle me-2"></i>
                    <strong>Peringatan:</strong> Data yang dihapus meliputi:
                    <ul class="mb-0 mt-2">
                        <li>Data utama IKM</li>
                        <li>Data Benchmark Produk</li>
                        <li>Data Desain Produk</li>
                        <li>Data COTS dan dokumentasinya</li>
                        <li>Semua file gambar terkait</li>
                    </ul>
                </div>
                <p class="text-muted small mb-0">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    <i class="ti ti-x me-1"></i>Batal
                </button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                    <i class="ti ti-trash me-1"></i>Hapus
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Success Notification Toast -->
<div class="position-fixed top-0 end-0 p-3" style="z-index: 9999">
    <div id="successToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <i class="ti ti-check-circle me-2"></i>
                <span id="successMessage"></span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<!-- Error Notification Toast -->
<div class="position-fixed top-0 end-0 p-3" style="z-index: 9999">
    <div id="errorToast" class="toast align-items-center text-white bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <i class="ti ti-alert-circle me-2"></i>
                <span id="errorMessage"></span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

@push('scripts')
<!-- jQuery -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>

<!-- DataTables JS -->
<script src="{{ asset('assets/plugins/datatables/dataTables.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables/responsive.bootstrap5.min.js') }}"></script>

<script>
    $(document).ready(function () {
        // Initialize DataTable
        $('#Ikm-table').DataTable({
            pageLength: 10,
            lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
            columnDefs: [{
                targets: '_all',
                defaultContent: ''
            }],
            language: {
                search: "Cari:",
                zeroRecords: "Data tidak ditemukan",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                paginate: {
                    previous: '<i class="ti ti-chevron-left"></i>',
                    next: '<i class="ti ti-chevron-right"></i>',
                    first: '<i class="ti ti-chevron-left"></i>',
                    last: '<i class="ti ti-chevron-right"></i>'
                }
            }
        });

        // Delete button click handler
        let currentDeleteBtn = null;
        let currentForm = null;

        $(document).on('click', '.delete-btn', function(e) {
            e.preventDefault();
            currentDeleteBtn = $(this);
            currentForm = currentDeleteBtn.closest('.delete-form');

            const ikmName = currentDeleteBtn.data('name');
            const ikmId = currentDeleteBtn.data('id');

            // Set the IKM name in the modal
            $('#deleteIkmName').text(ikmName);

            // Show the modal
            $('#deleteIkmModal').modal('show');
        });

        // Confirm delete button handler
        $('#confirmDeleteBtn').on('click', function() {
            if (!currentForm) return;

            const $btn = $(this);
            const originalText = $btn.html();

            // Show loading state
            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Menghapus...');

            // Get form data
            const formData = {
                _token: currentForm.find('input[name="_token"]').val(),
                id_Ikm: currentForm.find('input[name="id_Ikm"]').val(),
                id_Project: currentForm.find('input[name="id_Project"]').val()
            };

            // Send AJAX request
            $.ajax({
                url: '{{ route("ikm.ajaxDelete") }}',
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    // Hide modal
                    $('#deleteIkmModal').modal('hide');

                    if (response.success) {
                        // Show success toast
                        $('#successMessage').text(response.message);
                        $('#successToast').toast('show');

                        // Reload page after short delay to show the updated data
                        setTimeout(function() {
                            window.location.href = response.redirect;
                        }, 1500);
                    } else {
                        // Show error toast
                        $('#errorMessage').text(response.message);
                        $('#errorToast').toast('show');
                    }
                },
                error: function(xhr, status, error) {
                    // Hide modal
                    $('#deleteIkmModal').modal('hide');

                    // Show error toast
                    let errorMessage = 'Terjadi kesalahan saat menghapus data';
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.message) {
                            errorMessage = response.message;
                        }
                    } catch(e) {}

                    $('#errorMessage').text(errorMessage);
                    $('#errorToast').toast('show');
                },
                complete: function() {
                    // Reset button state
                    $btn.prop('disabled', false).html(originalText);
                    currentDeleteBtn = null;
                    currentForm = null;
                }
            });
        });

        // Folder Delete button click handler - SweetAlert2 Custom Tailwind
        $(document).on('click', '.delete-folder-btn', function(e) {
            e.preventDefault();
            const btn = $(this);
            const folderId = btn.data('id');
            const folderName = btn.data('name');
            const projectId = btn.data('project');
            const token = btn.data('token');

            Swal.fire({
                title: 'Hapus Folder?',
                html: `Apakah Anda yakin ingin menghapus folder <strong>"${folderName}"</strong>?<br><small style="color:#6b7280">Semua data di dalam folder ini (IKM, desain, COTS, benchmark, dan file gambar) akan ikut dihapus permanen.</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#f3f4f6',
                customClass: {
                    popup: 'swal2-tailwind-popup',
                    title: 'swal2-tailwind-title',
                    htmlContainer: 'swal2-tailwind-content',
                    confirmButton: 'swal2-tailwind-confirm',
                    cancelButton: 'swal2-tailwind-cancel'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const originalText = btn.html();
                    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');

                    $.ajax({
                        url: '{{ route("ikm.deleteFolder") }}',
                        type: 'POST',
                        data: {
                            _token: token,
                            id_Folder: folderId,
                            id_Project: projectId
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                $('#successMessage').text(response.message);
                                $('#successToast').toast('show');
                                setTimeout(function() {
                                    window.location.href = response.redirect;
                                }, 1500);
                            } else {
                                $('#errorMessage').text(response.message);
                                $('#errorToast').toast('show');
                                btn.prop('disabled', false).html(originalText);
                            }
                        },
                        error: function(xhr) {
                            let errorMessage = 'Terjadi kesalahan saat menghapus folder.';
                            try {
                                const response = JSON.parse(xhr.responseText);
                                if (response.message) {
                                    errorMessage = response.message;
                                }
                            } catch(e) {}
                            $('#errorMessage').text(errorMessage);
                            $('#errorToast').toast('show');
                            btn.prop('disabled', false).html(originalText);
                        }
                    });
                }
            });
        });

        // Rename Folder button click handler
        let currentRenameFolderId = null;
        $(document).on('click', '.rename-folder-btn', function(e) {
            e.preventDefault();
            const btn = $(this);
            currentRenameFolderId = btn.data('id');
            const folderName = btn.data('name');

            $('#renameFolderInput').val(folderName);
            $('#renameFolderModal').modal('show');
        });

        $('#confirmRenameBtn').on('click', function() {
            const newName = $('#renameFolderInput').val().trim();
            if (!newName) {
                $('#errorMessage').text('Nama folder tidak boleh kosong.');
                $('#errorToast').toast('show');
                return;
            }

            const $btn = $(this);
            const originalText = $btn.html();
            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Menyimpan...');

            $.ajax({
                url: '{{ route("ikm.renameFolder") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id_Folder: currentRenameFolderId,
                    name: newName
                },
                dataType: 'json',
                success: function(response) {
                    $('#renameFolderModal').modal('hide');
                    if (response.success) {
                        $('#successMessage').text(response.message);
                        $('#successToast').toast('show');
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    } else {
                        $('#errorMessage').text(response.message);
                        $('#errorToast').toast('show');
                    }
                },
                error: function(xhr) {
                    $('#renameFolderModal').modal('hide');
                    let errorMessage = 'Terjadi kesalahan saat mengubah nama folder.';
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.message) {
                            errorMessage = response.message;
                        }
                    } catch(e) {}
                    $('#errorMessage').text(errorMessage);
                    $('#errorToast').toast('show');
                },
                complete: function() {
                    $btn.prop('disabled', false).html(originalText);
                }
            });
        });

        // Move Folder button click handler
        let currentMoveFolderId = null;
        let currentMoveProjectId = null;
        let currentMoveTargetParentId = null;
        let moveFolderStack = [];

        function renderFolderTree(folders, parentName) {
            const $list = $('#folderTreeList');
            const $empty = $('#folderTreeEmpty');
            $list.empty();

            if (folders.length === 0) {
                $empty.show();
                return;
            }
            $empty.hide();

            folders.forEach(function(folder) {
                const $item = $(`
                    <li class="list-group-item list-group-item-action ikm-folder-tree-item d-flex align-items-center justify-content-between"
                        data-id="${folder.id}"
                        data-name="${folder.name}"
                        style="cursor:pointer; padding: 10px 12px;">
                        <span class="d-flex align-items-center gap-2">
                            <i class="ti ti-folder text-warning"></i>
                            <span class="fw-medium">${folder.name}</span>
                        </span>
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" class="btn btn-sm btn-success ikm-folder-select-btn" data-id="${folder.id}" data-name="${folder.name}">
                                <i class="ti ti-check"></i>&nbsp;Pilih
                            </button>
                            <i class="ti ti-chevron-right text-muted"></i>
                        </div>
                    </li>
                `);

                $item.on('click', function(e) {
                    if ($(e.target).closest('.folder-select-btn').length) return;
                    loadFolderTree(folder.id, folder.name);
                });

                $list.append($item);
            });
        }

        function loadFolderTree(parentId, parentName) {
            const projectId = currentMoveProjectId;
            const excludeId = currentMoveFolderId;

            if (!projectId) {
                $('#folderTreeEmpty').show();
                $('#folderTreeList').empty();
                return;
            }

            if (parentId) {
                moveFolderStack.push({ id: parentId, name: parentName });
            }

            $.ajax({
                url: '{{ route("ikm.folderTree") }}',
                method: 'GET',
                data: { project_id: projectId, parent_id: parentId, exclude_folder_id: excludeId },
                dataType: 'json',
                success: function(data) {
                    renderBreadcrumb();
                    renderFolderTree(data.folders, parentName);
                },
                error: function() {
                    $('#folderTreeEmpty').show();
                    $('#folderTreeList').empty();
                    $('#folderTreeEmpty p').text('Gagal memuat daftar folder.');
                }
            });
        }

        function renderBreadcrumb() {
            const $bc = $('#folderBreadcrumb');
            $bc.empty();

            const $root = $(`
                <button type="button" class="btn btn-sm btn-light folder-tree-root">
                    <i class="ti ti-home me-1"></i>Root
                </button>
            `);
            $root.on('click', function() {
                moveFolderStack = [];
                loadFolderTree(null, null);
            });
            $bc.append($root);

            moveFolderStack.forEach(function(item, index) {
                $bc.append(' <span class="text-muted">/</span> ');
                const $btn = $(`<button type="button" class="btn btn-sm btn-light folder-tree-bc">${item.name}</button>`);
                $btn.on('click', function() {
                    moveFolderStack = moveFolderStack.slice(0, index + 1);
                    loadFolderTree(item.id, item.name);
                });
                $bc.append($btn);
            });
        }

        $(document).on('click', '.folder-tree-item', function(e) {
            const id = $(this).data('id');
            const name = $(this).data('name');

            if ($(e.target).closest('.folder-select-btn').length) return;

            $('#moveFolderTargetSelect').val(id);
            currentMoveTargetParentId = id;

            $('#folderTreeList .folder-tree-item').removeClass('active');
            $(this).addClass('active');
        });

        $(document).on('click', '.folder-select-btn', function(e) {
            e.stopPropagation();
            const id = $(this).data('id');
            const name = $(this).data('name');

            $('#moveFolderTargetSelect').val(id);
            currentMoveTargetParentId = id;

            Swal.fire({
                title: 'Pilih Folder Tujuan?',
                html: `Pindahkan folder ini ke <strong>"${name}"</strong>?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Pindah!',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#f3f4f6',
                customClass: {
                    popup: 'swal2-tailwind-popup',
                    title: 'swal2-tailwind-title',
                    htmlContainer: 'swal2-tailwind-content',
                    confirmButton: 'swal2-tailwind-confirm',
                    cancelButton: 'swal2-tailwind-cancel'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#moveFolderModal').modal('hide');
                    $.post('{{ route("ikm.moveFolderAction") }}', {
                        _token: '{{ csrf_token() }}',
                        id_Folder: currentMoveFolderId,
                        target_parent_id: currentMoveTargetParentId,
                        id_Project: currentMoveProjectId
                    }, function(response) {
                        if (response.success) {
                            $('#successMessage').text(response.message);
                            $('#successToast').toast('show');
                            setTimeout(function() {
                                location.reload();
                            }, 1500);
                        } else {
                            $('#errorMessage').text(response.message);
                            $('#errorToast').toast('show');
                        }
                    }, 'json').fail(function() {
                        $('#errorMessage').text('Terjadi kesalahan saat memindahkan folder.');
                        $('#errorToast').toast('show');
                    });
                }
            });
        });

        $(document).on('click', '.move-folder-btn', function(e) {
            e.preventDefault();
            const btn = $(this);
            currentMoveFolderId = btn.data('id');
            currentMoveProjectId = btn.data('project');
            const folderName = btn.data('name');

            moveFolderStack = [];
            currentMoveTargetParentId = '';

            $('#moveFolderName').text(folderName);
            $('#moveFolderTargetSelect').val('');
            $('#folderTreeList').empty();
            $('#folderBreadcrumb').empty();
            $('#folderTreeEmpty').hide();

            $('#moveFolderModal').modal('show');
            loadFolderTree(null, null);
        });

        $('#moveFolderModal').on('shown.bs.modal', function() {
            if (!moveFolderStack.length) {
                loadFolderTree(null, null);
            }
        });

        // Upload Folder Document
        $('#uploadDocumentForm').on('submit', function(e) {
            e.preventDefault();
            const formData = {
                _token: '{{ csrf_token() }}',
                folder_id: $(this).find('input[name="folder_id"]').val(),
                id_Project: $(this).find('input[name="id_Project"]').val(),
                nama_file: $(this).find('input[name="nama_file"]').val(),
                url: $(this).find('input[name="url"]').val()
            };
            const $btn = $(this).find('button[type="submit"]');
            const originalText = $btn.html();

            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Menyimpan...');

            $.ajax({
                url: '{{ route("ikm.folder.uploadDocument") }}',
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    $('#uploadDocumentModal').modal('hide');
                    if (response.success) {
                        $('#successMessage').text(response.message);
                        $('#successToast').toast('show');
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    } else {
                        $('#errorMessage').text(response.message);
                        $('#errorToast').toast('show');
                    }
                },
                error: function(xhr) {
                    $('#uploadDocumentModal').modal('hide');
                    let errorMessage = 'Terjadi kesalahan saat menambahkan dokumen.';
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.message) {
                            errorMessage = response.message;
                        }
                    } catch(e) {}
                    $('#errorMessage').text(errorMessage);
                    $('#errorToast').toast('show');
                },
                complete: function() {
                    $btn.prop('disabled', false).html(originalText);
                    $('#uploadDocumentForm')[0].reset();
                }
            });
        });

        // Delete Folder Document
        $(document).on('click', '.delete-document-btn', function(e) {
            e.preventDefault();
            const btn = $(this);
            const documentId = btn.data('id');
            const documentName = btn.data('name');
            const folderId = btn.data('folder');
            const token = btn.data('token');

            Swal.fire({
                title: 'Hapus Dokumen?',
                html: `Apakah Anda yakin ingin menghapus dokumen <strong>"${documentName}"</strong>?<br><small style="color:#6b7280">Tindakan ini tidak dapat dibatalkan.</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#f3f4f6',
                customClass: {
                    popup: 'swal2-tailwind-popup',
                    title: 'swal2-tailwind-title',
                    htmlContainer: 'swal2-tailwind-content',
                    confirmButton: 'swal2-tailwind-confirm',
                    cancelButton: 'swal2-tailwind-cancel'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const originalText = btn.html();
                    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');

                    $.ajax({
                        url: '/project/dataIkm/folder/' + folderId + '/document/' + documentId,
                        type: 'DELETE',
                        data: {
                            _token: token
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                $('#successMessage').text(response.message);
                                $('#successToast').toast('show');
                                setTimeout(function() {
                                    location.reload();
                                }, 1500);
                            } else {
                                $('#errorMessage').text(response.message);
                                $('#errorToast').toast('show');
                                btn.prop('disabled', false).html(originalText);
                            }
                        },
                        error: function(xhr) {
                            let errorMessage = 'Terjadi kesalahan saat menghapus dokumen.';
                            try {
                                const response = JSON.parse(xhr.responseText);
                                if (response.message) {
                                    errorMessage = response.message;
                                }
                            } catch(e) {}
                            $('#errorMessage').text(errorMessage);
                            $('#errorToast').toast('show');
                            btn.prop('disabled', false).html(originalText);
                        }
                    });
                }
            });
        });

        // Move IKM button click handler
        let currentMoveIkmId = null;
        let currentMoveIkmProjectId = null;
        let moveIkmFolderStack = [];

        function renderIkmFolderTree(folders) {
            const $list = $('#ikmFolderTreeList');
            const $empty = $('#ikmFolderTreeEmpty');
            $list.empty();

            if (folders.length === 0) {
                $empty.show();
                return;
            }
            $empty.hide();

            folders.forEach(function(folder) {
                const $item = $(`
                    <li class="list-group-item list-group-item-action ikm-folder-tree-item d-flex align-items-center justify-content-between"
                        data-id="${folder.id}"
                        data-name="${folder.name}"
                        style="cursor:pointer; padding: 10px 12px;">
                        <span class="d-flex align-items-center gap-2">
                            <i class="ti ti-folder text-warning"></i>
                            <span class="fw-medium">${folder.name}</span>
                        </span>
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" class="btn btn-sm btn-success ikm-folder-select-btn" data-id="${folder.id}" data-name="${folder.name}">
                                <i class="ti ti-check"></i>&nbsp;Pilih
                            </button>
                            <i class="ti ti-chevron-right text-muted"></i>
                        </div>
                    </li>
                `);

                $item.on('click', function(e) {
                    if ($(e.target).closest('.ikm-folder-select-btn').length) return;
                    loadIkmFolderTree(folder.id, folder.name);
                });

                $list.append($item);
            });
        }

        function loadIkmFolderTree(parentId, parentName) {
            const projectId = currentMoveIkmProjectId;

            if (!projectId) {
                $('#ikmFolderTreeEmpty').show();
                $('#ikmFolderTreeList').empty();
                return;
            }

            if (parentId) {
                moveIkmFolderStack.push({ id: parentId, name: parentName });
            }

            $.ajax({
                url: '{{ route("ikm.folderTree") }}',
                method: 'GET',
                data: { project_id: projectId, parent_id: parentId },
                dataType: 'json',
                success: function(data) {
                    renderIkmBreadcrumb();
                    renderIkmFolderTree(data.folders);
                },
                error: function() {
                    $('#ikmFolderTreeEmpty').show();
                    $('#ikmFolderTreeList').empty();
                    $('#ikmFolderTreeEmpty p').text('Gagal memuat daftar folder.');
                }
            });
        }

        function renderIkmBreadcrumb() {
            const $bc = $('#ikmFolderBreadcrumb');
            $bc.empty();

            const $root = $(`
                <button type="button" class="btn btn-sm btn-light folder-tree-root">
                    <i class="ti ti-home me-1"></i>Root
                </button>
            `);
            $root.on('click', function() {
                moveIkmFolderStack = [];
                loadIkmFolderTree(null, null);
            });
            $bc.append($root);

            moveIkmFolderStack.forEach(function(item, index) {
                $bc.append(' <span class="text-muted">/</span> ');
                const $btn = $(`<button type="button" class="btn btn-sm btn-light folder-tree-bc">${item.name}</button>`);
                $btn.on('click', function() {
                    moveIkmFolderStack = moveIkmFolderStack.slice(0, index + 1);
                    loadIkmFolderTree(item.id, item.name);
                });
                $bc.append($btn);
            });
        }

        $(document).on('click', '.ikm-folder-tree-item', function(e) {
            const id = $(this).data('id');
            $('#moveIkmTargetSelect').val(id);

            $('#ikmFolderTreeList .ikm-folder-tree-item').removeClass('active');
            $(this).addClass('active');
        });

        $(document).on('click', '.ikm-folder-select-btn', function(e) {
            e.stopPropagation();
            const id = $(this).data('id');
            const name = $(this).data('name');
            $('#moveIkmTargetSelect').val(id);

            Swal.fire({
                title: 'Pindahkan IKM?',
                html: `Pindahkan IKM ini ke folder <strong>"${name}"</strong>?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Pindah!',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#f3f4f6',
                customClass: {
                    popup: 'swal2-tailwind-popup',
                    title: 'swal2-tailwind-title',
                    htmlContainer: 'swal2-tailwind-content',
                    confirmButton: 'swal2-tailwind-confirm',
                    cancelButton: 'swal2-tailwind-cancel'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#moveIkmModal').modal('hide');
                    $('#moveIkmForm').submit();
                }
            });
        });

        $(document).on('click', '.move-ikm-btn', function(e) {
            e.preventDefault();
            const btn = $(this);
            currentMoveIkmId = btn.data('id');
            currentMoveIkmProjectId = btn.data('project') || '{{ $project->id }}';
            const ikmName = btn.data('name');

            moveIkmFolderStack = [];
            $('#moveIkmName').text(ikmName);
            $('#moveIkmId').val(currentMoveIkmId);
            $('#moveIkmTargetSelect').val('');
            $('#ikmFolderTreeList').empty();
            $('#ikmFolderBreadcrumb').empty();
            $('#ikmFolderTreeEmpty').hide();

            $('#moveIkmModal').modal('show');
            loadIkmFolderTree(null, null);
        });

        $('#moveIkmModal').on('shown.bs.modal', function() {
            if (!moveIkmFolderStack.length) {
                loadIkmFolderTree(null, null);
            }
        });

        // Reset form when modal is closed
        $('#deleteIkmModal').on('hidden.bs.modal', function() {
            currentDeleteBtn = null;
            currentForm = null;
        });

        // Check for flash messages and show toasts
        @if(Session::has('HapusBerhasil'))
            $('#successMessage').text('{{ Session::get("HapusBerhasil") }}');
            $('#successToast').toast('show');
        @endif

        @if(Session::has('HapusGagal'))
            $('#errorMessage').text('{{ Session::get("HapusGagal") }}');
            $('#errorToast').toast('show');
        @endif
    });
</script>
@endpush
@endsection
