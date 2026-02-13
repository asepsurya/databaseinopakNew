@extends('layouts.master')

@section('page-title', 'project')
@section('content')

<style>
.icon-wrapper {
    width: 90px;
    height: 90px;
    background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 42px;
    color: #6c757d;
    transition: 0.3s ease;
}

.icon-wrapper:hover {
    transform: scale(1.08);
}

.card {
    animation: fadeInUp 0.4s ease-in-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>

       <div class="row mb-3">
    <div class="col-12">
        <form class="bg-light-subtle rounded border p-3" action="/project" method="GET">

            <div class="row g-3 align-items-end">

                <!-- Search -->
                <div class="col-12 col-lg-4">
                    <label class="form-label fw-semibold">Search</label>
                    <input type="text"
                           class="form-control"
                           placeholder="Search project name..."
                           value="{{ request('search') }}"
                           name="search">
                </div>

                <!-- Year -->
                <div class="col-6 col-lg-2">
                    <label class="form-label fw-semibold">Tahun</label>
                    <select class="form-select" name="year">
                        <option value="">Pilih Tahun</option>
                        @php
                            $currentYear = date('Y');
                            for($y = $currentYear; $y >= $currentYear - 10; $y--):
                        @endphp
                        <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                        @php endfor; @endphp
                    </select>
                </div>

                <!-- UKM Count -->
                <div class="col-6 col-lg-3">
                    <label class="form-label fw-semibold">Jumlah Ikm</label>
                    <select class="form-select" name="ukm_count">
                        <option value="">Semua</option>
                        <option value="0" {{ request('ukm_count') == '0' ? 'selected' : '' }}>0 Peserta</option>
                        <option value="1" {{ request('ukm_count') == '1' ? 'selected' : '' }}>1+ Peserta</option>
                        <option value="5" {{ request('ukm_count') == '5' ? 'selected' : '' }}>5+ Peserta</option>
                        <option value="10" {{ request('ukm_count') == '10' ? 'selected' : '' }}>10+ Peserta</option>
                    </select>
                </div>

                <!-- Apply Button -->
                <div class="col-6 col-lg-1 d-grid">
                    <button type="submit" class="btn btn-secondary">
                        Apply
                    </button>
                </div>

                <!-- Add New -->
                <div class="col-6 col-lg-2 d-grid">
                    <button type="button"
                            class="btn btn-primary"
                            data-bs-toggle="modal"
                            data-bs-target="#addItem">
                        <i class="ti ti-plus me-1"></i> Add
                    </button>
                </div>

            </div>

        </form>
    </div>
</div>



        <div class="row" id="projectsGrid">
            @forelse($projects as $project)
                <div class="col-md-6 col-xxl-3 project-card"
                    data-project-name="{{ strtolower($project->NamaProjek) }}"
                    data-project-year="{{ $project->created_at->format('Y') }}"
                    data-Ikms-count="{{ $project->Ikms_count ?? 0 }}"
                    data-files-count="{{ $project->produk_designs_count ?? 0 }}">
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
                            <a href="/project/dataIkm/{{ $project->id }}" class="link-reset project-title">{{ $project->NamaProjek }}</a>
                            </h5>
                            {{-- <span class="badge badge-soft-success fs-xxs badge-label align-self-start">In Progress</span> --}}
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
                                <a class="dropdown-item" href="/project/dataIkm/{{ $project->id }}"><i class="ti ti-eye me-2"></i>View</a>
                            </li>
                            <li>
                                <a class="dropdown-item text-primary" data-bs-toggle="modal" data-bs-target="#updateItem-{{ $project->id }}"><i class="ti ti-edit me-2"></i>Edit</a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $project->id }}">
                                    <i class="ti ti-trash me-2"></i>Delete
                                </a>
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
                            <div class="fw-medium">{{ $project->ikms->count() ?? 0 }}</div>
                            <small class="text-muted fs-xs">Ikm Created</small>
                            </div>
                        </div>
                        </div>
                        <div class="col-6">
                        <div class="d-flex align-items-center gap-2">
                            <i class="ti ti-paperclip text-muted fs-lg"></i>
                            <div>
                            <div class="fw-medium">{{ $project->produk_designs_count ?? 0 }}</div>
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

                    </div>
                </div>
                </div>
            @empty
             <!-- Empty State Project -->
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body text-center py-5">

                            <!-- Icon -->
                            <div class="empty-icon mb-4">
                                <div class="icon-wrapper mx-auto">
                                    <i class="ti ti-folder-off"></i>
                                </div>
                            </div>

                            <!-- Title -->
                            <h4 class="fw-bold mb-2">Project Tidak Ditemukan</h4>

                            <!-- Description -->
                            <p class="text-muted mb-1">
                                Kami tidak menemukan project yang sesuai dengan pencarian Anda.
                            </p>
                            <p class="text-muted small mb-4">
                                Coba gunakan kata kunci lain atau reset filter untuk melihat semua project.
                            </p>

                            <!-- Action Buttons -->
                            <div class="d-flex justify-content-center gap-2">
                                <a href="/project" class="btn btn-primary rounded-pill px-4">
                                    <i class="ti ti-refresh me-1"></i> Reset Filter
                                </a>

                                <button onclick="history.back()"
                                        class="btn btn-outline-secondary rounded-pill px-4">
                                    <i class="ti ti-arrow-left me-1"></i> Kembali
                                </button>
                            </div>

                        </div>
                    </div>
                </div>

            @endforelse
        </div>

        <!-- No Results Message -->
        <div id="noResults" class="text-center text-muted mt-5 my-5" style="display: none;">
            <div class="mb-3">
                <i class="ti ti-search-off fa-3x text-secondary"></i>
            </div>
            <h5 class="fw-bold">Tidak Ada Data Ditemukan</h5>
            <p class="mb-2">Tidak ada project yang cocok dengan kriteria pencarian Anda.</p>
            <p class="text-muted fs-sm mb-3">Coba ubah kata kunci atau filter yang digunakan.</p>
            <a href="/project" class="btn btn-outline-primary btn-sm">
                <i class="ti ti-refresh me-1"></i>Tampilkan Semua Project
            </a>
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
                        <form action="/project/update" method="POST" id="updateProjectForm-{{ $project->id }}">
                            @csrf
                            <input type="text" name="id" hidden value="{{ $project->id }}">
                            <div class="row">
                                <div class="mb-3">
                                    <label for="projectName-{{ $project->id }}" class="form-label">Nama Projek</label>
                                    <input type="text" name="NamaProjek" id="projectName-{{ $project->id }}" class="form-control @error('NamaProjek') is-invalid @enderror" value="{{ $project->NamaProjek }}">
                                    @error('NamaProjek')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="keterangan-{{ $project->id }}" class="form-label">Deskripsi</label>
                                    <textarea name="keterangan" id="keterangan-{{ $project->id }}" cols="30" rows="4" class="form-control @error('keterangan') is-invalid @enderror">{{ $project->keterangan }}</textarea>
                                    @error('keterangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary" type="submit">Simpan</button>
                            </form>
                            <button class="btn btn-outline-primary" type="button" data-bs-dismiss="modal">Batal</button>
                        </div>
                </div>
            </div>
        </div>
        @endforeach

        <!-- Delete Confirmation Modals -->
        @foreach ($projects as $project)
        <div class="modal fade" id="deleteModal-{{ $project->id }}" tabindex="-1" aria-labelledby="deleteModalLabel-{{ $project->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-danger" id="deleteModalLabel-{{ $project->id }}">
                            <i class="ti ti-alert-triangle me-2"></i>Konfirmasi Hapus Project
                        </h5>
                        <button class="btn p-1" type="button" data-bs-dismiss="modal" aria-label="Close">
                            <span class="ti ti-times fs--1"></span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center mb-4">
                            <div class="avatar-md bg-danger-subtle rounded-circle d-inline-flex align-items-center justify-content-center mb-3">
                                <i class="ti ti-trash fs-32 text-danger"></i>
                            </div>
                            <h5 class="fw-bold">Apakah Anda Yakin?</h5>
                            <p class="text-muted">Anda akan menghapus project <strong>{{ $project->NamaProjek }}</strong> dan semua data terkait di dalamnya.</p>
                        </div>

                        <div class="alert alert-warning mb-4">
                            <h6 class="alert-heading fw-bold">Data yang akan dihapus:</h6>
                            <ul class="mb-0">
                                <li>Project: <strong>{{ $project->NamaProjek }}</strong></li>
                                <li>Total IKM: <strong>{{ $project->ikms_count ?? $project->ikms->count() ?? 0 }}</strong> data</li>
                                <li>Total Desain Produk: <strong>{{ $project->produk_designs_count ?? 0 }}</strong> file</li>
                            </ul>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="confirmDelete-{{ $project->id }}">
                            <label class="form-check-label" for="confirmDelete-{{ $project->id }}">
                                Saya yakin ingin menghapus data ini
                            </label>
                        </div>

                        <form action="/project/hapus/{{ $project->id }}" method="POST" id="deleteForm-{{ $project->id }}">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="id" value="{{ $project->id }}">
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" id="confirmDeleteBtn-{{ $project->id }}" disabled>
                            <i class="ti ti-trash me-1"></i>Ya, Hapus
                        </button>
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="ti ti-x me-1"></i>Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        <!-- Pagination -->
        @if($projects->hasPages())
        <div class="d-flex justify-content-center mt-4">
            <nav aria-label="Page navigation">
                <ul class="pagination pagination-rounded pagination-boxed justify-content-center">
                    {{-- Previous Page Link --}}
                    @if($projects->onFirstPage())
                    <li class="page-item disabled">
                        <a class="page-link" href="javascript: void(0);" aria-label="Previous">
                            <span aria-hidden="true">«</span>
                        </a>
                    </li>
                    @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $projects->previousPageUrl() }}" aria-label="Previous">
                            <span aria-hidden="true">«</span>
                        </a>
                    </li>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach($projects->getUrlRange(1, $projects->lastPage()) as $page => $url)
                    @if($page == $projects->currentPage())
                    <li class="page-item active">
                        <a class="page-link" href="javascript: void(0);">{{ $page }}</a>
                    </li>
                    @elseif($page >= $projects->currentPage() - 1 && $page <= $projects->currentPage() + 1)
                    <li class="page-item">
                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                    </li>
                    @elseif($page == 1 || $page == $projects->lastPage())
                    <li class="page-item">
                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                    </li>
                    @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if($projects->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $projects->nextPageUrl() }}" aria-label="Next">
                            <span aria-hidden="true">»</span>
                        </a>
                    </li>
                    @else
                    <li class="page-item disabled">
                        <a class="page-link" href="javascript: void(0);" aria-label="Next">
                            <span aria-hidden="true">»</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </nav>
        </div>
        @endif

        <!-- Showing info -->
        @if($projects->total() > 0)
        <div class="text-center mt-3 mb-4">
            <small class="text-muted">
                Menampilkan {{ $projects->firstItem() ?? 0 }} - {{ $projects->lastItem() ?? 0 }} dari {{ $projects->total() }} project
            </small>
        </div>
        @endif

    <!-- container -->

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const searchInput = document.querySelector('input[name="search"]');
        const yearFilter = document.querySelector('select[name="year"]');
        const ukmCountFilter = document.querySelector('select[name="ukm_count"]');
        const projectCards = document.querySelectorAll('.project-card');
        const noResults = document.getElementById('noResults');
        const totalUkmEl = document.getElementById('total-ukm');
        const totalFilesEl = document.getElementById('total-files');

        // Debounce function for performance optimization
        // Prevents excessive filtering while user is typing
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        // Function to filter cards based on all criteria
        function filterCards() {
            const keyword = searchInput ? searchInput.value.toLowerCase() : '';
            const selectedYear = yearFilter ? yearFilter.value : '';
            const selectedUkmCount = ukmCountFilter ? parseInt(ukmCountFilter.value) : 0;

            let visibleCount = 0;

            projectCards.forEach(card => {
                const projectName = card.dataset.projectName || '';
                const projectYear = card.dataset.projectYear || '';
                const IkmsCount = parseInt(card.dataset.IkmsCount || 0);

                // Check all filter conditions
                const matchesSearch = projectName.includes(keyword);
                const matchesYear = !selectedYear || projectYear === selectedYear;
                const matchesUkmCount = !selectedUkmCount || IkmsCount >= selectedUkmCount;

                const isVisible = matchesSearch && matchesYear && matchesUkmCount;

                card.style.display = isVisible ? '' : 'none';

                if (isVisible) visibleCount++;
            });

            // Update totals based on filtered results
            updateTotals();

            // Show/hide no results message
            if (noResults) {
                noResults.style.display = visibleCount === 0 ? 'block' : 'none';
            }
        }

        // Function to update totals based on visible cards
        function updateTotals() {
            let totalUkm = 0;
            let totalFiles = 0;

            projectCards.forEach(card => {
                if (card.style.display !== 'none') {
                    totalUkm += parseInt(card.dataset.IkmsCount || 0);
                    totalFiles += parseInt(card.dataset.filesCount || 0);
                }
            });

            if (totalUkmEl) totalUkmEl.textContent = totalUkm;
            if (totalFilesEl) totalFilesEl.textContent = totalFiles;
        }

        // Initialize totals on page load
        updateTotals();

        // Create debounced version of filter function (300ms delay for optimal performance)
        const debouncedFilter = debounce(filterCards, 300);

        // Add event listeners with debounced filtering for search input
        if (searchInput) {
            searchInput.addEventListener('input', debouncedFilter);
        }

        if (yearFilter) {
            yearFilter.addEventListener('change', filterCards);
        }

        if (ukmCountFilter) {
            ukmCountFilter.addEventListener('change', filterCards);
        }
    });

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
            const submitBtn = this.querySelector('button[type="submit"]');

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
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Menyimpan...';
                    }
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
                    // Find the modal footer and disable buttons
                    const modalFooter = this.closest('.modal-content').querySelector('.modal-footer');
                    if (modalFooter) {
                        const buttons = modalFooter.querySelectorAll('button');
                        buttons.forEach(btn => {
                            btn.disabled = true;
                            if (btn.type === 'submit') {
                                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Mengupdate...';
                            }
                        });
                    }
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

    // Delete Modal Confirmation Handler
    document.addEventListener('DOMContentLoaded', function() {
        // Handle checkbox change for each delete modal
        @foreach ($projects as $project)
        const checkbox{{ $project->id }} = document.getElementById('confirmDelete-{{ $project->id }}');
        const deleteBtn{{ $project->id }} = document.getElementById('confirmDeleteBtn-{{ $project->id }}');

        if (checkbox{{ $project->id }} && deleteBtn{{ $project->id }}) {
            checkbox{{ $project->id }}.addEventListener('change', function() {
                deleteBtn{{ $project->id }}.disabled = !this.checked;
            });

            // Handle delete button click
            deleteBtn{{ $project->id }}.addEventListener('click', function() {
                const form = document.getElementById('deleteForm-{{ $project->id }}');
                if (form) {
                    this.disabled = true;
                    this.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Menghapus...';
                    form.submit();
                }
            });
        }
        @endforeach
    });

</script>
@endsection
