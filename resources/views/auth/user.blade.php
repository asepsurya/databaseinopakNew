@extends('layouts.master')

@section('title', 'My Profile')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<style>
    .profile-photo-container {
        position: relative;
        width: 150px;
        height: 150px;
        margin: 0 auto;
    }

    .profile-photo {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border-radius: 50%;
        border: 4px solid #e9ecef;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }

    .profile-photo-placeholder {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border-radius: 50%;
        border: 4px dashed #dee2e6;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #adb5bd;
        font-size: 48px;
    }

    .photo-upload-btn {
        position: absolute;
        bottom: 5px;
        right: 5px;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #435ebe;
        color: white;
        border: 3px solid white;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }

    .photo-upload-btn:hover {
        background: #364f9e;
        transform: scale(1.1);
    }

    .photo-remove-btn {
        position: absolute;
        bottom: 5px;
        left: 5px;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #dc3545;
        color: white;
        border: 3px solid white;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }

    .photo-remove-btn:hover {
        background: #c82333;
        transform: scale(1.1);
    }

    .profile-preview {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border-radius: 50%;
        border: 4px solid #435ebe;
        box-shadow: 0 0 20px rgba(67, 94, 190, 0.3);
        display: none;
    }

    .profile-preview.active {
        display: block;
    }

    .photo-upload-spinner {
        display: none;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 10;
    }

    .photo-upload-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 9990;
    }

    .photo-upload-loading {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: white;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        z-index: 9999;
        text-align: center;
    }

    .photo-upload-loading .spinner {
        width: 50px;
        height: 50px;
        border: 4px solid #e9ecef;
        border-top: 4px solid #435ebe;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 0 auto 15px;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .profile-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 0 30px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .profile-header {
        background: linear-gradient(135deg, #434bbe 0%, #6c7ccf 100%);
        padding: 40px 30px 30px;
        color: white;
    }

    .profile-content {
        padding: 30px;
    }

    .info-item {
        padding: 15px 0;
        border-bottom: 1px solid #e9ecef;
    }

    .info-item:last-child {
        border-bottom: none;
    }

    .info-label {
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6c757d;
        margin-bottom: 5px;
    }

    .info-value {
        font-size: 16px;
        color: #212529;
        font-weight: 500;
    }

    .form-label-custom {
        font-weight: 500;
        color: #495057;
        margin-bottom: 8px;
    }

    .form-control-custom {
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 12px 15px;
        transition: all 0.3s ease;
    }

    .form-control-custom:focus {
        border-color: #435ebe;
        box-shadow: 0 0 0 0.2rem rgba(67, 94, 190, 0.15);
    }

    .btn-profile {
        padding: 12px 25px;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-profile:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .tabs-profile .nav-link {
        border: none;
        border-radius: 8px;
        padding: 12px 20px;
        color: #6c757d;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .tabs-profile .nav-link.active {
        background: #435ebe;
        color: white;
    }

    .tabs-profile .nav-link:hover:not(.active) {
        background: #e9ecef;
    }

    .section-title {
        font-size: 18px;
        font-weight: 600;
        color: #212529;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #e9ecef;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .profile-photo-container {
            width: 120px;
            height: 120px;
        }

        .profile-photo,
        .profile-photo-placeholder,
        .profile-preview {
            width: 120px;
            height: 120px;
        }

        .profile-header {
            padding: 30px 20px 20px;
        }

        .profile-content {
            padding: 20px;
        }
    }
</style>
@endpush

@section('content')
<div >
    <div>
        <!-- Start Content-->
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">My Profile</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <!-- Profile Photo Upload Loading Overlay -->
            <div class="photo-upload-overlay" id="photoUploadOverlay"></div>
            <div class="photo-upload-loading" id="photoUploadLoading">
                <div class="spinner"></div>
                <p class="mb-0">Mengunggah foto profil...</p>
            </div>

            <div class="row">
                <!-- Profile Photo Section -->
                <div class="col-lg-4 col-xl-4">
                    <div class="card">
                        <div class="profile-header text-center">
                            <div class="profile-photo-container">
                                @if(auth()->user()->profile_photo && Storage::disk('public')->exists(auth()->user()->profile_photo))
                                    <img src="/storage/{{ auth()->user()->profile_photo }}"
                                         alt="Profile Photo"
                                         class="profile-photo"
                                         id="currentProfilePhoto">
                                @else
                                    <div class="profile-photo-placeholder" id="profilePhotoPlaceholder">
                                        <i class="ti ti-user"></i>
                                    </div>
                                    <img src=""
                                         alt="Profile Photo Preview"
                                         class="profile-preview"
                                         id="profilePhotoPreview">
                                @endif

                                <label for="profile_photo" class="photo-upload-btn" title="Ubah Foto Profil">
                                    <i class="ti ti-camera"></i>
                                </label>

                                @if(auth()->user()->profile_photo)
                                    <button type="button"
                                            class="photo-remove-btn"
                                            title="Hapus Foto Profil"
                                            onclick="event.preventDefault(); document.getElementById('removePhotoForm').submit();">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                @endif

                                <div class="photo-upload-spinner" id="photoUploadSpinner">
                                    <div class="spinner"></div>
                                </div>
                            </div>

                            <h4 class="mt-3 mb-1 text-white fw-semibold">{{ auth()->user()->nama ?? auth()->user()->name }}</h4>
                            <p class="text-white-50 mb-0">{{ auth()->user()->email }}</p>
                        </div>

                        <div class="profile-content">
                            <form action="{{ route('profile.photo.update') }}"
                                  method="POST"
                                  enctype="multipart/form-data"
                                  id="photoUploadForm">
                                @csrf
                                @method('POST')
                                <input type="file"
                                       name="profile_photo"
                                       id="profile_photo"
                                       accept="image/jpeg,image/png,image/jpg,image/gif"
                                       style="display: none;"
                                       onchange="previewPhoto(event)">
                            </form>

                            <form action="{{ route('profile.photo.remove') }}"
                                  method="POST"
                                  id="removePhotoForm"
                                  style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>

                            @error('profile_photo')
                                <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    {{ $message }}
                                </div>
                            @enderror

                            <div class="info-item">
                                <div class="info-label">Nama Lengkap</div>
                                <div class="info-value">{{ auth()->user()->nama ?? auth()->user()->name }}</div>
                            </div>

                            <div class="info-item">
                                <div class="info-label">Email</div>
                                <div class="info-value">{{ auth()->user()->email }}</div>
                            </div>

                            @if(auth()->user()->phone)
                            <div class="info-item">
                                <div class="info-label">Telepon</div>
                                <div class="info-value">{{ auth()->user()->phone }}</div>
                            </div>
                            @endif

                            @if(auth()->user()->bio)
                            <div class="info-item">
                                <div class="info-label">Bio</div>
                                <div class="info-value">{{ auth()->user()->bio }}</div>
                            </div>
                            @endif

                            @if(auth()->user()->address)
                            <div class="info-item">
                                <div class="info-label">Alamat</div>
                                <div class="info-value">{{ auth()->user()->address }}</div>
                            </div>
                            @endif

                            <div class="info-item">
                                <div class="info-label">Bergabung</div>
                                <div class="info-value">{{ auth()->user()->created_at->format('d M Y') }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Profile Edit Section -->
                <div class="col-lg-8 col-xl-8">
                    <div class="card">
                        <div class="card-body p-2">
                            <!-- Tabs Navigation -->
                            <ul class="nav nav-pills navtab-bg nav-justified tabs-profile mb-3" id="profileTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active"
                                            id="profile-tab"
                                            data-bs-toggle="tab"
                                            data-bs-target="#profile"
                                            type="button"
                                            role="tab"
                                            aria-controls="profile"
                                            aria-selected="true">
                                        <i class="ti ti-user me-1"></i> Profil
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link"
                                            id="password-tab"
                                            data-bs-toggle="tab"
                                            data-bs-target="#password"
                                            type="button"
                                            role="tab"
                                            aria-controls="password"
                                            aria-selected="false">
                                        <i class="ti ti-lock me-1"></i> Kata Sandi
                                    </button>
                                </li>
                            </ul>

                            <!-- Tabs Content -->
                            <div class="tab-content p-2" id="profileTabsContent">
                                <!-- Profile Edit Tab -->
                                <div class="tab-pane fade show active"
                                     id="profile"
                                     role="tabpanel"
                                     aria-labelledby="profile-tab">

                                    <form action="{{ route('profile.update', auth()->id()) }}"
                                          method="POST"
                                          class="profile-form">
                                        @csrf
                                        @method('PUT')

                                        <h5 class="section-title">Informasi Pribadi</h5>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="nama" class="form-label form-label-custom">Nama Lengkap <span class="text-danger">*</span></label>
                                                <input type="text"
                                                       class="form-control form-control-custom @error('nama') is-invalid @enderror"
                                                       id="nama"
                                                       name="nama"
                                                       value="{{ old('nama', auth()->user()->nama ?? auth()->user()->name) }}"
                                                       required
                                                       placeholder="Masukkan nama lengkap">
                                                @error('nama')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="email" class="form-label form-label-custom">Email <span class="text-danger">*</span></label>
                                                <input type="email"
                                                       class="form-control form-control-custom @error('email') is-invalid @enderror"
                                                       id="email"
                                                       name="email"
                                                       value="{{ old('email', auth()->user()->email) }}"
                                                       required
                                                       placeholder="Masukkan email">
                                                @error('email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="phone" class="form-label form-label-custom">Telepon</label>
                                                <input type="tel"
                                                       class="form-control form-control-custom @error('phone') is-invalid @enderror"
                                                       id="phone"
                                                       name="phone"
                                                       value="{{ old('phone', auth()->user()->phone) }}"
                                                       placeholder="Masukkan nomor telepon">
                                                @error('phone')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="bio" class="form-label form-label-custom">Bio</label>
                                            <textarea class="form-control form-control-custom @error('bio') is-invalid @enderror"
                                                      id="bio"
                                                      name="bio"
                                                      rows="3"
                                                      placeholder="Ceritakan tentang diri Anda">{{ old('bio', auth()->user()->bio) }}</textarea>
                                            @error('bio')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-4">
                                            <label for="address" class="form-label form-label-custom">Alamat</label>
                                            <textarea class="form-control form-control-custom @error('address') is-invalid @enderror"
                                                      id="address"
                                                      name="address"
                                                      rows="2"
                                                      placeholder="Masukkan alamat lengkap">{{ old('address', auth()->user()->address) }}</textarea>
                                            @error('address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="d-flex gap-2">
                                            <button type="submit" class="btn btn-primary btn-profile">
                                                <i class="ti ti-check me-1"></i> Simpan Perubahan
                                            </button>
                                            <button type="reset" class="btn btn-secondary btn-profile">
                                                <i class="ti ti-refresh me-1"></i> Reset
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Password Change Tab -->
                                <div class="tab-pane fade"
                                     id="password"
                                     role="tabpanel"
                                     aria-labelledby="password-tab">

                                    <form action="{{ route('profile.password.update') }}"
                                          method="POST"
                                          class="password-form">
                                        @csrf
                                        @method('PUT')

                                        <h5 class="section-title">Ubah Kata Sandi</h5>

                                        <div class="mb-3">
                                            <label for="current_password" class="form-label form-label-custom">Kata Sandi Saat Ini <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="password"
                                                       class="form-control form-control-custom @error('current_password') is-invalid @enderror"
                                                       id="current_password"
                                                       name="current_password"
                                                       required
                                                       placeholder="Masukkan kata sandi saat ini">
                                                <button class="btn btn-outline-secondary toggle-password"
                                                        type="button"
                                                        data-target="current_password">
                                                    <i class="ti ti-eye"></i>
                                                </button>
                                                @error('current_password')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="new_password" class="form-label form-label-custom">Kata Sandi Baru <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="password"
                                                       class="form-control form-control-custom @error('new_password') is-invalid @enderror"
                                                       id="new_password"
                                                       name="new_password"
                                                       required
                                                       minlength="8"
                                                       placeholder="Masukkan kata sandi baru (min. 8 karakter)">
                                                <button class="btn btn-outline-secondary toggle-password"
                                                        type="button"
                                                        data-target="new_password">
                                                    <i class="ti ti-eye"></i>
                                                </button>
                                                @error('new_password')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <label for="new_password_confirmation" class="form-label form-label-custom">Konfirmasi Kata Sandi Baru <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="password"
                                                       class="form-control form-control-custom"
                                                       id="new_password_confirmation"
                                                       name="new_password_confirmation"
                                                       required
                                                       placeholder="Konfirmasi kata sandi baru">
                                                <button class="btn btn-outline-secondary toggle-password"
                                                        type="button"
                                                        data-target="new_password_confirmation">
                                                    <i class="ti ti-eye"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="d-flex gap-2">
                                            <button type="submit" class="btn btn-primary btn-profile">
                                                <i class="ti ti-lock me-1"></i> Ubah Kata Sandi
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end container-fluid -->
    </div>
    <!-- end content -->
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
<script>
    // Photo preview functionality
    function previewPhoto(event) {
        const file = event.target.files[0];
        if (!file) return;

        // Validate file type
        const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
        if (!validTypes.includes(file.type)) {
            Swal.fire({
                icon: 'error',
                title: 'Format Tidak Valid',
                text: 'File harus berupa gambar dengan format JPEG, PNG, JPG, atau GIF.',
                confirmButtonColor: '#435ebe'
            });
            event.target.value = '';
            return;
        }

        // Validate file size (2MB = 2 * 1024 * 1024 bytes)
        const maxSize = 2 * 1024 * 1024;
        if (file.size > maxSize) {
            Swal.fire({
                icon: 'error',
                title: 'File Terlalu Besar',
                text: 'Ukuran file maksimal adalah 2MB.',
                confirmButtonColor: '#435ebe'
            });
            event.target.value = '';
            return;
        }

        // Show loading
        document.getElementById('photoUploadOverlay').style.display = 'block';
        document.getElementById('photoUploadLoading').style.display = 'block';

        // Preview using FileReader
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('profilePhotoPreview');
            const placeholder = document.getElementById('profilePhotoPlaceholder');
            const currentPhoto = document.getElementById('currentProfilePhoto');

            preview.src = e.target.result;
            preview.classList.add('active');

            if (placeholder) placeholder.style.display = 'none';
            if (currentPhoto) currentPhoto.style.display = 'none';

            // Submit the form
            document.getElementById('photoUploadForm').submit();
        };
        reader.readAsDataURL(file);
    }

    // Toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            const icon = this.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('ti-eye');
                icon.classList.add('ti-eye-off');
            } else {
                input.type = 'password';
                icon.classList.remove('ti-eye-off');
                icon.classList.add('ti-eye');
            }
        });
    });

    // Form submission handling
    document.querySelectorAll('.profile-form, .password-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const formElement = this;

            Swal.fire({
                title: 'Konfirmasi',
                text: 'Apakah Anda yakin ingin menyimpan perubahan?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#435ebe',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Simpan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Remove the event listener to prevent infinite loop, then submit natively
                    formElement.removeEventListener('submit', arguments.callee);
                    HTMLFormElement.prototype.submit.call(formElement);
                }
            });
        });
    });

    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        document.querySelectorAll('.alert-dismissible').forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);

    // Session success messages
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            confirmButtonColor: '#435ebe',
            timer: 3000,
            timerProgressBar: true
        });
    @endif

    @if(session('UpdateBerhasil'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('UpdateBerhasil') }}',
            confirmButtonColor: '#435ebe',
            timer: 3000,
            timerProgressBar: true
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '{{ session('error') }}',
            confirmButtonColor: '#435ebe'
        });
    @endif
</script>
@endpush
