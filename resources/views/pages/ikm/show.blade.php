@extends('layouts.master')

@section('page-title', 'Data IKM - ' . $project->NamaProjek)

@push('styles')
<!-- DataTables CSS -->
<link href="{{ asset('assets/plugins/datatables/dataTables.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/plugins/datatables/responsive.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />
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

</style>
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header justify-content-between d-flex align-items-center">
                <h4 class="mb-0"><i class="ti ti-user-group me-2"></i>Data IKM - {{ $project->NamaProjek }}</h4>
                <div class="d-flex gap-2">
                    <a href="/project" class="btn btn-light btn-sm"><i class="ti ti-arrow-left me-1"></i> Kembali</a>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahIkm"><i class="ti ti-plus me-1"></i> Tambah</button>
                </div>
            </div>
            <div class="card-body">

                <!-- Empty State -->
                @if($dataIkm->isEmpty())
                <div class="text-center py-5">
                    <div class="d-flex flex-column align-items-center">
                        <i class="ti ti-inbox fs-1 d-block mb-2 text-muted"></i>
                        <p class="mb-3 text-muted">Belum ada data IKM</p>
                        <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahIkm">
                            <i class="ti ti-plus me-2"></i>Tambah IKM Pertama
                        </a>
                    </div>
                </div>
                @else
                <!-- Table -->
                <table data-tables="basic" class="table table-striped dt-responsive align-middle mb-0" id="ikm-table">
                    <thead class="thead-sm text-uppercase fs-xxs">
                        <tr>
                            <th>No</th>
                            <th>Nama IKM</th>
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
                                    <div class="avatar-sm me-3">
                                        @if($data->gambar && file_exists(storage_path('app/public/' . $data->gambar)))
                                            <img src="{{ asset('storage/' . $data->gambar) }}" alt="{{ $data->nama }}" class="rounded" style="width: 40px; height: 40px; object-fit: cover;" loading="lazy">
                                        @else
                                            <div class="avatar-title bg-light text-muted rounded">
                                                <i class="ti ti-user"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <a href="/project/ikms/{{ encrypt($data->id) }}/{{ $project->id }}" class="link-reset fw-medium">{{ $data->nama }}</a>
                                        <p class="text-muted mb-0 small" style="font-size: 12px;">{{ $data->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>{!! $data->jenisProduk !!}</td>
                            <td>
                                @if($data->merk)
                                    <span class="badge badge-label badge-soft-secondary">{!! $data->merk !!}</span>
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
                                    <a href="/project/ikms/{{ encrypt($data->id) }}/{{ $project->id }}" class="btn btn-sm btn-light btn-icon" title="Detail">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                    <form action="/project/dataikm/{{ $project->id }}/update" method="POST" class="d-inline">
                                        @csrf
                                        <input type="text" value="{{ $data->id_provinsi }}" name="getId_provinsi" hidden>
                                        <input type="text" value="{{ $data->id_kota }}" name="getId_kota" hidden>
                                        <input type="text" value="{{ $data->id_kecamatan }}" name="getId_kecamatan" hidden>
                                        <input type="text" value="{{ $data->id_desa }}" name="getId_desa" hidden>
                                        <input type="text" value="{{ $project->id }}" name="getId_project" hidden>
                                        <input type="text" value="{{ $data->id }}" name="getId_IKM" hidden>
                                        <input type="text" value="{{ $project->NamaProjek }}" name="get_Nmproject" hidden>
                                        <button type="submit" class="btn btn-sm btn-light btn-icon" title="Ubah">
                                            <i class="ti ti-pencil"></i>
                                        </button>
                                    </form>
                                    <form action="/project/dataikm/{{ $project->id }}/delete" method="POST" class="d-inline">
                                        @csrf
                                        <input type="text" value="{{ $data->id }}" name="id_ikm" hidden>
                                        <input type="text" value="{{ $project->id }}" name="id_Project" hidden>
                                        <button type="submit" class="btn btn-sm btn-light btn-icon" title="Hapus" onclick="return confirm('Anda Yakin data ini akan dihapus?')">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah IKM -->
<div class="modal fade" id="tambahIkm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addMemberModalLabel">+ Tambah IKM</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/project/dataikm/tambahIkm" method="post">
                    @csrf
                    <div class="mb-3">
                        <div class="form-floating">
                            <input type="text" class="form-control" name="nama" placeholder="Nama" required>
                            <label for="nama" class="form-label">Nama IKM</label>
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
@endsection

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
        $('#ikm-table').DataTable({
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
    });
</script>
@endpush
