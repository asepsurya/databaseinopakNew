@extends('layouts.master')

@section('page-title', 'project')
@section('content')
<div class="page-title-head d-flex align-items-center ">
    <div class="flex-grow-1">
        <h4 class="page-main-title m-0">Projects</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="javascript: void(0);">Paces</a></li>
            <li class="breadcrumb-item"><a href="javascript: void(0);">Apps</a></li>
            <li class="breadcrumb-item active">Projects</li>
        </ol>
    </div>
</div>

        <div class="row mb-3">
            <div class="col-lg-12">
                <form class="bg-light-subtle rounded border p-3">
                    <div class="row gap-3">
                        <div class="col">
                            <div class="row gap-3">
                                <div class="col-lg-4">
                                    <div class="app-search">
                                        <input type="text" class="form-control search-input" placeholder="Search project name..." value="{{ request('search') }}" name="search">
                                        <i class="ti ti-search app-search-icon text-muted"></i>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="d-flex flex-wrap align-items-center gap-2">
                                        <span class="me-2 fw-semibold">Filter By:</span>

                                        <!-- Status Filter -->
                                        <div class="app-search">
                                            <select class="form-select form-control my-1 my-md-0">
                                                <option selected="">Status</option>
                                                <option value="On Track">On Track</option>
                                                <option value="Delayed">Delayed</option>
                                                <option value="At Risk">At Risk</option>
                                                <option value="Completed">Completed</option>
                                            </select>
                                            <i class="ti ti-activity app-search-icon text-muted"></i>
                                        </div>

                                        <!-- Team Filter -->
                                        <div class="app-search">
                                            <select class="form-select form-control my-1 my-md-0">
                                                <option selected="">Team</option>
                                                <option value="Design">Design</option>
                                                <option value="Development">Development</option>
                                                <option value="Marketing">Marketing</option>
                                                <option value="QA">QA</option>
                                            </select>
                                            <i class="ti ti-users app-search-icon text-muted"></i>
                                        </div>

                                        <!-- Deadline Filter -->
                                        <div class="app-search">
                                            <select class="form-select form-control my-1 my-md-0">
                                                <option selected="">Deadline</option>
                                                <option value="This Week">This Week</option>
                                                <option value="This Month">This Month</option>
                                                <option value="Next Month">Next Month</option>
                                                <option value="No Deadline">No Deadline</option>
                                            </select>
                                            <i class="ti ti-calendar-clock app-search-icon text-muted"></i>
                                        </div>

                                        <button type="submit" class="btn btn-secondary">Apply</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Add New Project Button -->
                        <div class="col-auto">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addItem">
                                <i class="ti ti-plus me-2"></i>Add New
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Projects Grid -->
        <div class="row" id="projectsGrid">
            @foreach ($projects as $project)
            <div class="col-md-6 col-xxl-3 project-card" data-project-name="{{ strtolower($project->NamaProjek) }}">
            <div class="card position-relative">
                <div class="position-absolute top-0 end-0" style="width: 180px">
                <img src="{{ asset('assets/images/auth-card-bg.svg') }}" class="auth-card-bg-img" alt="auth-card-bg">
                </div>
                <div class="card-body d-flex flex-column">
                <!-- Header: icon + title + status + menu -->
                <div class="d-flex align-items-start border-bottom border-dashed pb-3 mb-3">
                  @php
                    $colors = [
                    'text-bg-primary',
                    'text-bg-success',
                    'text-bg-warning',
                    'text-bg-danger',
                    'text-bg-info',
                    'text-bg-secondary',
                    'text-bg-dark',
                    ];
                @endphp

                <div class="avatar-xl me-3">
                    <span class="avatar-title rounded fw-semibold fs-6 {{ $colors[array_rand($colors)] }}">
                    {{ strtoupper(
                        collect(explode(' ', $project->NamaProjek))
                        ->map(fn($w) => substr($w, 0, 1))
                        ->take(2)
                        ->join('')
                    ) }}
                    </span>
                </div>

                    <div class="flex-grow-1">
                    <div class="d-flex align-items-start gap-2">
                        <h5 class="mb-1 lh-sm flex-grow-1">
                        <a href="/project/dataikm/{{ $project->id }}" class="link-reset project-title">{{ $project->NamaProjek }}</a>
                        </h5>
                        <span class="badge badge-soft-success fs-xxs badge-label align-self-start">In Progress</span>
                    </div>
                    <p class="text-muted fs-xxs mb-0">Updated {{ $project->updated_at->diffForHumans() }}</p>
                    </div>

                    <div class="ms-2">
                    <div class="dropdown">
                        <a href="#" class="btn btn-icon btn-ghost-light text-muted" data-bs-toggle="dropdown">
                        <i class="ti ti-dots-vertical fs-xl"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="/project/dataikm/{{ $project->id }}"><i class="ti ti-eye me-2"></i>View</a>
                        </li>
                        <li>
                            <a class="dropdown-item text-primary" data-bs-toggle="modal" data-bs-target="#updateItem-{{ $project->id }}"><i class="ti ti-edit me-2"></i>Edit</a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form action="/project/hapus/{{ $project->id }}" method="post" class="delete-form">
                            @csrf
                            <input type="text" name="id" hidden value="{{ $project->id }}">
                            <button class="dropdown-item text-danger" type="submit">
                                <i class="ti ti-trash me-2"></i>Delete
                            </button>
                            </form>
                        </li>
                        </ul>
                    </div>
                    </div>
                </div>

                <!-- Project Description -->
                <div class="mb-3">
                    <p class="text-muted fs-sm mb-0">{{ $project->keterangan ?? 'No description available' }}</p>
                </div>

                <!-- Quick stats: tasks / files / comments / due -->
                <div class="row g-3 mb-3">
                    <div class="col-6">
                    <div class="d-flex align-items-center gap-2">
                        <i class="ti ti-list-check text-muted fs-lg"></i>
                        <div>
                        <div class="fw-medium">--</div>
                        <small class="text-muted fs-xs">Assets & docs</small>
                        </div>
                    </div>
                    </div>
                    <div class="col-6">
                    <div class="d-flex align-items-center gap-2">
                        <i class="ti ti-paperclip text-muted fs-lg"></i>
                        <div>
                        <div class="fw-medium">--</div>
                        <small class="text-muted fs-xs">Files</small>
                        </div>
                    </div>
                    </div>
                    <div class="col-6">
                    <div class="d-flex align-items-center gap-2">
                        <i class="ti ti-message text-muted fs-lg"></i>
                        <div>
                        <div class="fw-medium">--</div>
                        <small class="text-muted fs-xs">Comments</small>
                        </div>
                    </div>
                    </div>
                    <div class="col-6">
                    <div class="d-flex align-items-center gap-2">
                        <i class="ti ti-calendar-clock text-muted fs-lg"></i>
                        <div>
                        <div class="fw-medium">{{ $project->created_at->format('d M, Y') }}</div>
                        <small class="text-muted fs-xs">Created</small>
                        </div>
                    </div>
                    </div>
                </div>

                <!-- Progress -->
                <div class="mt-auto">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                    <p class="mb-0 text-muted fw-semibold fs-xs">Progress</p>
                    <p class="fw-semibold mb-0">0%</p>
                    </div>
                    <div class="progress" style="height: 5px">
                    <div class="progress-bar bg-success" style="width: 0%"></div>
                    </div>
                </div>
                </div>
            </div>
            </div>
            @endforeach
        </div>

        <!-- No Results Message -->
        <div id="noResults" class="text-center text-muted mt-5 my-5" style="display: none;">
            <div class="mb-3">
                <i class="ti ti-search fa-3x text-secondary"></i>
            </div>
            <h5 class="fw-bold">Oops!</h5>
            <p class="mb-0">Tidak ada proyek yang cocok dengan pencarianmu.</p>
        </div>

        <!-- Add Project Modal -->
        <div class="modal fade" id="addItem" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addItemModalLabel">+ Tambah Project</h5>
                        <button class="btn p-1" type="button" data-bs-dismiss="modal" aria-label="Close">
                            <span class="ti ti-times fs--1"></span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="/project/create" method="post" id="addProjectForm">
                            @csrf
                            <div class="row">
                                <div class="mb-3">
                                    <label for="projectName" class="form-label">Nama Projek</label>
                                    <input type="text" name="NamaProjek" id="projectName" class="form-control @error('NamaProjek') is-invalid @enderror" placeholder="Enter project name">
                                    @error('NamaProjek')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="keterangan" class="form-label">Deskripsi</label>
                                    <textarea name="keterangan" id="keterangan" cols="30" rows="4" class="form-control @error('keterangan') is-invalid @enderror" placeholder="Enter project description"></textarea>
                                    @error('keterangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit" id="submitAddBtn">Simpan</button>
                        </form>
                        <button class="btn btn-outline-primary" type="button" data-bs-dismiss="modal">Batal</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Update Project Modals -->
        @foreach ($projects as $project)
        <div class="modal fade" id="updateItem-{{ $project->id }}" tabindex="-1" aria-labelledby="updateItemModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateItemModalLabel">Edit Project</h5>
                        <button class="btn p-1" type="button" data-bs-dismiss="modal" aria-label="Close">
                            <span class="ti ti-times fs--1"></span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="/project/update" method="post" id="updateProjectForm-{{ $project->id }}">
                            @csrf
                            <input type="text" name="id" hidden value="{{ $project->id }}">
                            <div class="row">
                                <div class="mb-3">
                                    <label for="projectName-{{ $project->id }}" class="form-label">Nama Projek</label>
                                    <input type="text" name="NamaProjek" id="projectName-{{ $project->id }}" class="form-control" value="{{ $project->NamaProjek }}">
                                </div>
                                <div class="mb-3">
                                    <label for="keterangan-{{ $project->id }}" class="form-label">Deskripsi</label>
                                    <textarea name="keterangan" id="keterangan-{{ $project->id }}" cols="30" rows="4" class="form-control">{{ $project->keterangan }}</textarea>
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit">Update</button>
                        </form>
                        <button class="btn btn-outline-primary" type="button" data-bs-dismiss="modal">Batal</button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        <!-- Pagination -->
        {{-- <ul class="pagination pagination-rounded pagination-boxed justify-content-center mt-4">
            <li class="page-item">
                <a class="page-link" href="javascript: void(0);" aria-label="Previous">
                    <span aria-hidden="true">«</span>
                </a>
            </li>
            <li class="page-item active">
                <a class="page-link" href="javascript: void(0);">1</a>
            </li>
            <li class="page-item">
                <a class="page-link" href="javascript: void(0);">2</a>
            </li>
            <li class="page-item">
                <a class="page-link" href="javascript: void(0);">3</a>
            </li>
            <li class="page-item">
                <a class="page-link" href="javascript: void(0);">4</a>
            </li>
            <li class="page-item">
                <a class="page-link" href="javascript: void(0);">5</a>
            </li>
            <li class="page-item">
                <a class="page-link" href="javascript: void(0);" aria-label="Next">
                    <span aria-hidden="true">»</span>
                </a>
            </li>
        </ul> --}}

    <!-- container -->

<script>
document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.querySelector('input[name="search"]');
    const projectCards = document.querySelectorAll('.project-card');
    const noResults = document.getElementById('noResults');

    // Search functionality
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const keyword = this.value.toLowerCase();
            let visibleCount = 0;

            projectCards.forEach(card => {
                const projectName = card.dataset.projectName || '';
                const isVisible = projectName.includes(keyword);

                card.style.display = isVisible ? '' : 'none';

                if (isVisible) visibleCount++;
            });

            if (noResults) {
                noResults.style.display = visibleCount === 0 ? 'block' : 'none';
            }
        });
    }

    // SweetAlert2 for session flash messages - consolidated handling
    @php
    $alertType = '';
    $alertMessage = '';
    $alertIcon = '';

    if(session()->has('Berhasil')) {
        $alertType = 'success';
        $alertMessage = '{{ session("Berhasil") }}';
    } elseif(session()->has('HapusBerhasil')) {
        $alertType = 'success';
        $alertMessage = '{{ session("HapusBerhasil") }}';
    } elseif(session()->has('UpdateBerhasil')) {
        $alertType = 'success';
        $alertMessage = '{{ session("UpdateBerhasil") }}';
    } elseif(session()->has('gagalSimpan')) {
        $alertType = 'error';
        $alertMessage = '{{ session("gagalSimpan") }}';
    }
    @endphp

    @if(!empty($alertType))
    Swal.fire({
        icon: '{{ $alertType }}',
        title: '<span style="font-size: 14px;">{{ $alertType === "success" ? "Berhasil!" : "Gagal!" }}</span>',
        html: '<span style="font-size: 13px;">{!! $alertMessage !!}</span>',
        toast: true,
        position: 'top-end',
        timer: 3000,
        timerProgressBar: true,
        showConfirmButton: false,
        customClass: {
            popup: 'swal2-toast-small'
        }
    });
    @endif

    // Add Project form submission with SweetAlert
    const addForm = document.getElementById('addProjectForm');
    if (addForm) {
        addForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const submitBtn = document.getElementById('submitAddBtn');

            Swal.fire({
                title: 'Simpan Project?',
                text: 'Apakah Anda yakin ingin menyimpan project ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Simpan!',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33'
            }).then((result) => {
                if (result.isConfirmed) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Menyimpan...';
                    this.submit();
                }
            });
        });
    }

    // Update Project forms submission with SweetAlert
    const updateForms = document.querySelectorAll('[id^="updateProjectForm-"]');
    updateForms.forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const submitBtn = this.querySelector('button[type="submit"]');

            Swal.fire({
                title: 'Update Project?',
                text: 'Apakah Anda yakin ingin mengupdate project ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Update!',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33'
            }).then((result) => {
                if (result.isConfirmed) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Mengupdate...';
                    this.submit();
                }
            });
        });
    });

    // Delete form - SweetAlert confirmation
    const deleteForms = document.querySelectorAll('.delete-form');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const btn = this.querySelector('button[type="submit"]');

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Data project akan dihapus permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                customClass: {
                    confirmButton: 'btn btn-danger',
                    cancelButton: 'btn btn-secondary'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    btn.disabled = true;
                    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Menghapus...';
                    this.submit();
                }
            });
        });
    });
});
</script>
@endsection
