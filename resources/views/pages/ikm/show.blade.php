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

    </style>
@endpush

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header justify-content-between d-flex align-items-center projek-header">
                <h4 class="mb-0"><i class="ti ti-user-group me-2"></i>Data Ikm - {{ $project->NamaProjek }}</h4>
                <div class="d-flex gap-2">
                    <a href="/project" class="btn btn-light btn-sm"><i class="ti ti-arrow-left me-1"></i> Kembali</a>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahIkm"><i class="ti ti-plus me-1"></i> Tambah</button>
                </div>
            </div>
            <div class="card-body px-0 m-0">

                <!-- Empty State -->
                @if($dataIkm->isEmpty())
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
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="ti ti-device-floppy me-2"></i>Simpan
                    </button>
                </form>
            </div>
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
