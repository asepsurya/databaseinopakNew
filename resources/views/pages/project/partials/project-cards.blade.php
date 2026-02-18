@forelse($projects as $project)
    <div class="col-md-6 col-xxl-3 project-card"
        data-project-name="{{ strtolower($project->NamaProjek) }}"
        data-project-year="{{ $project->created_at->format('Y') }}"
        data-ikms-count="{{ $project->ikms_count ?? 0 }}"
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
                                <a href="/project/dataikm/{{ $project->id }}" class="link-reset project-title">{{ $project->NamaProjek }}</a>
                            </h5>
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
                                <div class="fw-medium">{{ $project->ikms_count ?? 0 }}</div>
                                <small class="text-muted fs-xs">IKM Created</small>
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
                            <i class="ti ti-user text-muted fs-lg"></i>
                            <div>
                                <div class="fw-medium">{{ $project->user->name ?? '--' }}</div>
                                <small class="text-muted fs-xs">Dibuat oleh</small>
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

            </div>
        </div>
    </div>
@empty
    <!-- Empty State Project -->
    <div class="col-12 empty-state">
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
                    <button class="btn btn-primary rounded-pill px-4 reset-filters">
                        <i class="ti ti-refresh me-1"></i> Reset Filter
                    </button>
                </div>
            </div>
        </div>
    </div>
@endforelse
