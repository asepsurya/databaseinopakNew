@extends('layouts.master')

@section('title', 'Manajemen Backup Database')

@push('css')
<style>
    .backup-card {
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .backup-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
    }

    .toggle-switch {
        position: relative;
        width: 50px;
        height: 26px;
    }

    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: 0.4s;
        border-radius: 34px;
    }

    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 20px;
        width: 20px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: 0.4s;
        border-radius: 50%;
    }

    input:checked + .toggle-slider {
        background-color: #28a745;
    }

    input:checked + .toggle-slider:before {
        transform: translateX(24px);
    }

    .table-checkbox {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }

    .progress-container {
        height: 8px;
        background-color: #e9ecef;
        border-radius: 4px;
        overflow: hidden;
    }

    .progress-bar-custom {
        height: 100%;
        background: linear-gradient(90deg, #28a745, #20c997);
        transition: width 0.3s ease;
    }

    .status-badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-completed { background-color: #d4edda; color: #155724; }
    .status-failed { background-color: #f8d7da; color: #721c24; }
    .status-in_progress { background-color: #fff3cd; color: #856404; }
    .status-pending { background-color: #e2e3e5; color: #383d41; }

    .table-select-card {
        max-height: 300px;
        overflow-y: auto;
    }

    .table-list-item {
        padding: 8px 12px;
        border-bottom: 1px solid #f0f0f0;
        transition: background-color 0.2s;
    }

    .table-list-item:hover {
        background-color: #f8f9fa;
    }

    .table-list-item:last-child {
        border-bottom: none;
    }

    .encryption-badge {
        font-size: 10px;
        padding: 2px 6px;
        background-color: #ffc107;
        color: #000;
        border-radius: 4px;
    }

    .btn-loading {
        pointer-events: none;
        opacity: 0.7;
    }

    .btn-loading i {
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    .spinner-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    .spinner-card {
        background: white;
        padding: 30px;
        border-radius: 12px;
        text-align: center;
        box-shadow: 0 10px 40px rgba(0,0,0,0.2);
    }

    .custom-toast {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        max-width: 350px;
    }
</style>
@endpush

@section('content')
<div >
    <div>
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-1">Manajemen Backup Database</h4>
                <p class="text-muted mb-0">Kelola backup database otomatis dan manual</p>
            </div>
            <div>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                    <i class="ti ti-arrow-left"></i> Kembali ke Dashboard
                </a>
            </div>
        </div>

        <!-- Status Info -->
        <div class="row">
            <div class="col-md-4">
                <div class="backup-card card p-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-success bg-opacity-10 p-3 rounded me-3">
                            <i class="ti ti-database text-success fs-4"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Total Tabel</small>
                            <h5 class="mb-0">{{ count($tables) }}</h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="backup-card card  p-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 p-3 rounded me-3">
                            <i class="ti ti-file text-primary fs-4"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Total Backup</small>
                            <h5 class="mb-0">{{ $backupHistory->total() }}</h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="backup-card card p-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-info bg-opacity-10 p-3 rounded me-3">
                            <i class="ti ti-calendar text-info fs-4"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Server Timezone</small>
                            <h5 class="mb-0">{{ config('app.timezone', 'Asia/Jakarta') }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Left Column - Settings -->
            <div class="col-lg-4">
                <!-- Auto Backup Settings -->
                <div class="card mb-4">
                    <div class="card-header py-3">
                        <h6 class="mb-0">
                            <i class="ti ti-settings me-2"></i>Pengaturan Auto Backup
                        </h6>
                    </div>
                    <div class="card-body">
                        <!-- Toggle Auto Backup -->
                        <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                            <div>
                                <h6 class="mb-1">Auto Backup</h6>
                                <small class="text-muted">Backup otomatis sesuai jadwal</small>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox" id="autoBackupToggle"
                                    {{ $settings->auto_backup_enabled ? 'checked' : '' }}>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <!-- Frequency -->
                        <div class="mb-3">
                            <label class="form-label">Frekuensi Backup</label>
                            <select class="form-select" id="backupFrequency">
                                <option value="daily" {{ $settings->frequency == 'daily' ? 'selected' : '' }}>Harian</option>
                                <option value="monthly" {{ $settings->frequency == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                            </select>
                        </div>

                        <!-- Daily Time -->
                        <div class="mb-3" id="dailyTimeContainer" style="{{ $settings->frequency == 'daily' ? '' : 'display:none;' }}">
                            <label class="form-label">Jam Backup (Harian)</label>
                            <input type="time" class="form-control" id="dailyTime"
                                value="{{ $settings->daily_time ? $settings->daily_time->format('H:i') : '02:00' }}">
                        </div>

                        <!-- Monthly Settings -->
                        <div class="mb-3" id="monthlySettingsContainer" style="{{ $settings->frequency == 'monthly' ? '' : 'display:none;' }}">
                            <label class="form-label">Tanggal Backup (Bulanan)</label>
                            <select class="form-select mb-2" id="monthlyDay">
                                @for($i = 1; $i <= 31; $i++)
                                    <option value="{{ $i }}" {{ $settings->monthly_day == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                            <label class="form-label">Jam Backup</label>
                            <input type="time" class="form-control" id="monthlyTime"
                                value="{{ $settings->monthly_time ? $settings->monthly_time->format('H:i') : '02:00' }}">
                        </div>

                        <!-- Retention -->
                        <div class="mb-3">
                            <label class="form-label">Penyimpanan (Hari)</label>
                            <input type="number" class="form-control" id="retentionDays"
                                value="{{ $settings->retention_days }}" min="1" max="365">
                            <small class="text-muted">Backup lama akan dihapus setelah X hari</small>
                        </div>

                        <!-- Auto Delete -->
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="autoDeleteOld"
                                {{ $settings->auto_delete_old ? 'checked' : '' }}>
                            <label class="form-check-label" for="autoDeleteOld">
                                Hapus backup lama otomatis
                            </label>
                        </div>

                        <button class="btn btn-primary w-100" id="saveSettingsBtn">
                            <i class="ti ti-device-floppy me-1"></i> Simpan Pengaturan
                        </button>
                    </div>
                </div>

                <!-- Encryption Settings -->
                <div class="card mb-4">
                    <div class="card-header py-3">
                        <h6 class="mb-0">
                            <i class="ti ti-lock me-2"></i>Keamanan
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="encryptionEnabled"
                                {{ $settings->encryption_enabled ? 'checked' : '' }}>
                            <label class="form-check-label" for="encryptionEnabled">
                                Enkripsi file backup
                            </label>
                        </div>

                        <div id="encryptionPasswordContainer" style="{{ $settings->encryption_enabled ? '' : 'display:none;' }}">
                            <label class="form-label">Password Enkripsi</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="encryptionPassword"
                                    placeholder="Masukkan password" value="{{ $settings->encryption_password }}">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="ti ti-eye"></i>
                                </button>
                            </div>
                            <small class="text-muted">Password digunakan untuk mengenkripsi file backup</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Backup Actions & History -->
            <div class="col-lg-8">
                <!-- Backup Actions -->
                <div class="card mb-4">
                    <div class="card-header py-3">
                        <h6 class="mb-0">
                            <i class="ti ti-download me-2"></i>Buat Backup
                        </h6>
                    </div>
                    <div class="card-body">
                        <!-- Tabs -->
                        <ul class="nav nav-tabs mb-3" id="backupTabs" role="tablist">
                            <li class="nav-item">
                                <button class="nav-link active" id="full-tab" data-bs-toggle="tab" data-bs-target="#full" type="button">
                                    <i class="ti ti-database me-1"></i> Full Database
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" id="per-table-tab" data-bs-toggle="tab" data-bs-target="#per-table" type="button">
                                    <i class="ti ti-table me-1"></i> Per-Tabel
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" id="csv-tab" data-bs-toggle="tab" data-bs-target="#csv" type="button">
                                    <i class="ti ti-file-spreadsheet me-1"></i> CSV Export
                                </button>
                            </li>
                        </ul>

                        <!-- Tab Content -->
                        <div class="tab-content" id="backupTabsContent">
                            <!-- Full Database Backup -->
                            <div class="tab-pane fade show active" id="full" role="tabpanel">
                                <div class="alert alert-info mb-3">
                                    <i class="ti ti-info-circle me-2"></i>
                                    Backup seluruh database termasuk semua tabel ({{ count($tables) }} tabel)
                                </div>
                                <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded mb-3">
                                    <div>
                                        <strong>Estimasi Ukuran:</strong>
                                        <span id="fullEstimateSize" class="ms-2 text-primary">Menghitung...</span>
                                    </div>
                                    <button class="btn btn-success" id="fullBackupBtn">
                                        <i class="ti ti-player-play me-1"></i> Jalankan Backup
                                    </button>
                                </div>
                            </div>

                            <!-- Per-Table Backup -->
                            <div class="tab-pane fade" id="per-table" role="tabpanel">
                                <div class="alert alert-info mb-3">
                                    <i class="ti ti-info-circle me-2"></i>
                                    Pilih tabel yang ingin di-backup
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="ti ti-search"></i></span>
                                            <input type="text" class="form-control" id="tableSearch" placeholder="Cari tabel...">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <button class="btn btn-outline-secondary btn-sm me-1" id="selectAllTables">Pilih Semua</button>
                                        <button class="btn btn-outline-secondary btn-sm" id="deselectAllTables">Hapus Pilihan</button>
                                    </div>
                                </div>

                                <div class="table-select-card border rounded mb-3 p-3" id="tableListContainer">
                                    @foreach($tables as $table)
                                    <div class="table-list-item d-flex align-items-center pb-2 ">
                                        <input type="checkbox" class="table-checkbox me-3"
                                            value="{{ $table }}" data-rows="{{ $tableCounts[$table] ?? 0 }}">
                                        <div class="flex-grow-1">
                                            <strong>{{ $table }}</strong>
                                            <small class="text-muted ms-2">({{ number_format($tableCounts[$table] ?? 0) }} baris)</small>
                                        </div>
                                    </div>
                                    @endforeach
                                    <div id="noTableFound" class="text-center text-muted py-3 d-none">
                                                Tidak ada tabel yang cocok
                                            </div>
                                </div>

                                <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded mb-3">
                                    <div>
                                        <strong>Estimasi Ukuran:</strong>
                                        <span id="perTableEstimateSize" class="ms-2 text-primary">0 B</span>
                                        <span class="ms-2 text-muted">|</span>
                                        <span class="ms-2 text-muted" id="selectedTablesCount">0 tabel dipilih</span>
                                    </div>
                                    <button class="btn btn-success" id="perTableBackupBtn" disabled>
                                        <i class="ti ti-player-play me-1"></i> Backup Tabel Terpilih
                                    </button>
                                </div>
                            </div>

                            <!-- CSV Export -->
                            <div class="tab-pane fade" id="csv" role="tabpanel">
                                <div class="alert alert-info mb-3">
                                    <i class="ti ti-info-circle me-2"></i>
                                    Export tabel yang dipilih ke format CSV
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="ti ti-search"></i></span>
                                            <input type="text" class="form-control" id="csvTableSearch" placeholder="Cari tabel...">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <button class="btn btn-outline-secondary btn-sm me-1" id="selectAllCsvTables">Pilih Semua</button>
                                        <button class="btn btn-outline-secondary btn-sm" id="deselectAllCsvTables">Hapus Pilihan</button>
                                    </div>
                                </div>

                                <div class="table-select-card border rounded mb-3 p-3" id="csvTableListContainer">
                                    @foreach($tables as $table)
                                    <div class="table-list-item d-flex align-items-center pb-2">
                                        <input type="checkbox" class="csv-table-checkbox table-checkbox me-3"
                                            value="{{ $table }}" data-rows="{{ $tableCounts[$table] ?? 0 }}">
                                        <div class="flex-grow-1">
                                            <strong>{{ $table }}</strong>
                                            <small class="text-muted ms-2">({{ number_format($tableCounts[$table] ?? 0) }} baris)</small>
                                        </div>
                                    </div>
                                    @endforeach
                                      <div id="noTableFoundcsv" class="text-center text-muted py-3 d-none">
                                                Tidak ada tabel yang cocok
                                            </div>
                                </div>

                                <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded mb-3">
                                    <div>
                                        <strong>Estimasi Ukuran:</strong>
                                        <span id="csvEstimateSize" class="ms-2 text-primary">0 B</span>
                                        <span class="ms-2 text-muted">|</span>
                                        <span class="ms-2 text-muted" id="selectedCsvTablesCount">0 tabel dipilih</span>
                                    </div>
                                    <button class="btn btn-warning" id="csvBackupBtn" disabled>
                                        <i class="ti ti-file-spreadsheet me-1"></i> Export CSV
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Progress -->
                        <div id="backupProgress" class="mt-3" style="display: none;">
                            <div class="d-flex justify-content-between mb-2">
                                <span id="progressText">Memproses backup...</span>
                                <span id="progressPercent">0%</span>
                            </div>
                            <div class="progress-container">
                                <div class="progress-bar-custom" id="progressBar" style="width: 0%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Backup History -->
                <div class="card">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">
                            <i class="ti ti-history me-2"></i>Riwayat Backup
                        </h6>
                        <div>
                            <button class="btn btn-outline-danger btn-sm" id="cleanupBtn">
                                <i class="ti ti-trash me-1"></i> Hapus Backup Lama
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="backupHistoryTable">
                                <thead>
                                    <tr>
                                        <th>Filename</th>
                                        <th>Tipe</th>
                                        <th>Ukuran</th>
                                        <th>Status</th>
                                        <th>Tanggal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="backupHistoryBody">
                                    @forelse($backupHistory as $backup)
                                    <tr data-id="{{ $backup->id }}">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="ti ti-file me-2 text-muted"></i>
                                                <div>
                                                    <strong>{{ $backup->filename }}</strong>
                                                    @if($backup->is_encrypted)
                                                    <span class="encryption-badge ms-1">ENC</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($backup->type == 'full')
                                                <span class="badge bg-primary">Full</span>
                                            @elseif($backup->type == 'per_table')
                                                <span class="badge bg-info">Per-Tabel</span>
                                            @else
                                                <span class="badge bg-secondary">{{ $backup->type }}</span>
                                            @endif
                                            <br>
                                            <small class="text-muted">{{ $backup->format }}</small>
                                        </td>
                                        <td>{{ $backup->file_size_human }}</td>
                                        <td>
                                            <span class="status-badge status-{{ $backup->status }}">
                                                @if($backup->status == 'completed')
                                                    Selesai
                                                @elseif($backup->status == 'failed')
                                                    Gagal
                                                @elseif($backup->status == 'in_progress')
                                                    Proses
                                                @else
                                                    Menunggu
                                                @endif
                                            </span>
                                        </td>
                                        <td>
                                            {{ $backup->created_at->format('d M Y, H:i') }}
                                            <br>
                                            <small class="text-muted">
                                                @if($backup->triggered_by == 'scheduled')
                                                    <i class="ti ti-calendar"></i> Terjadwal
                                                @else
                                                    <i class="ti ti-user"></i> Manual
                                                @endif
                                            </small>
                                        </td>
                                        <td>
                                            @if($backup->status == 'completed')
                                                <button class="btn btn-sm btn-primary btn-download"
                                                    data-id="{{ $backup->id }}"
                                                    data-filename="{{ $backup->filename }}"
                                                    title="Download">
                                                    <i class="ti ti-download"></i>
                                                </button>
                                            @endif
                                            <button class="btn btn-sm btn-danger btn-delete-backup"
                                                data-id="{{ $backup->id }}"
                                                title="Hapus">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr id="emptyRow">
                                        <td colspan="6" class="text-center py-4">
                                            <i class="ti ti-inbox fs-1 text-muted d-block mb-2"></i>
                                            <span class="text-muted">Belum ada riwayat backup</span>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($backupHistory->hasPages())
                        <div class="d-flex justify-content-center mt-3">
                            {{ $backupHistory->links() }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="confirmMessage">Apakah Anda yakin?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="confirmBtn">Ya, Lanjutkan</button>
            </div>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div id="loadingOverlay" style="display: none;">
    <div class="spinner-overlay">
        <div class="spinner-card">
            <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem;" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <h5 id="loadingText">Memproses...</h5>
            <p class="text-muted" id="loadingSubtext">Mohon tunggu sebentar</p>
        </div>
    </div>
</div>

<!-- Toast Container -->
<div class="custom-toast" id="toastContainer"></div>

@endsection

@push('scripts')
 <script>
document.addEventListener('DOMContentLoaded', function () {

    const searchInput = document.getElementById('csvTableSearch');
    const container = document.getElementById('csvTableListContainer');
    const items = container.querySelectorAll('.table-list-item');
    const noResult = document.getElementById('noTableFoundcsv');

    function filterTables() {
        const keyword = searchInput.value.toLowerCase().trim();
        let visibleCount = 0;

        items.forEach(item => {
            const tableName = item.querySelector('strong').innerText.toLowerCase();

            if (tableName.includes(keyword)) {
                item.classList.remove('d-none');
                visibleCount++;
            } else {
                item.classList.add('d-none');
            }
        });

        // Show empty message
        if (visibleCount === 0) {
            noResult.classList.remove('d-none');
        } else {
            noResult.classList.add('d-none');
        }
    }

    searchInput?.addEventListener('input', filterTables);

});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const searchInput = document.getElementById('tableSearch');
    const container = document.getElementById('tableListContainer');
    const items = container.querySelectorAll('.table-list-item');
    const noResult = document.getElementById('noTableFound');

    function filterTables() {
        const keyword = searchInput.value.toLowerCase().trim();
        let visibleCount = 0;

        items.forEach(item => {
            const tableName = item.querySelector('strong').innerText.toLowerCase();

            if (tableName.includes(keyword)) {
                item.classList.remove('d-none');
                visibleCount++;
            } else {
                item.classList.add('d-none');
            }
        });

        // Show empty message
        if (visibleCount === 0) {
            noResult.classList.remove('d-none');
        } else {
            noResult.classList.add('d-none');
        }
    }

    searchInput?.addEventListener('input', filterTables);

});
</script>
<script>
$(document).ready(function() {
    // CSRF Token
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Toast Functions
    function showToast(message, type = 'success', duration = 5000) {
        const bgColor = type === 'success' ? '#28a745' : (type === 'error' ? '#dc3545' : '#0dcaf0');
        const icon = type === 'success' ? 'ti-check' : (type === 'error' ? 'ti-x' : 'ti-info-circle');

        const toast = `
            <div class="toast show" role="alert" style="background: ${bgColor}; color: white;">
                <div class="toast-header" style="background: transparent; border: none; color: white;">
                    <i class="ti ${icon} me-2"></i>
                    <strong class="me-auto">${type === 'success' ? 'Berhasil' : (type === 'error' ? 'Gagal' : 'Info')}</strong>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
                </div>
                <div class="toast-body" style="color: white;">${message}</div>
            </div>
        `;

        $('#toastContainer').append(toast);

        setTimeout(function() {
            $('.toast').last().remove();
        }, duration);
    }

    // Loading Functions
    function showLoading(text = 'Memproses...', subtext = 'Mohon tunggu sebentar') {
        $('#loadingText').text(text);
        $('#loadingSubtext').text(subtext);
        $('#loadingOverlay').fadeIn();
    }

    function hideLoading() {
        $('#loadingOverlay').fadeOut();
    }

    // Initialize
    loadFullBackupEstimate();

    // Toggle Auto Backup
    $('#autoBackupToggle').change(function() {
        const enabled = $(this).is(':checked');
        const $btn = $(this);

        $.ajax({
            url: '{{ route("backup.toggle") }}',
            method: 'POST',
            data: { enabled: enabled },
            success: function(response) {
                if (response.success) {
                    showToast(response.message, 'success');
                } else {
                    showToast(response.message, 'error');
                    $btn.prop('checked', !enabled);
                }
            },
            error: function() {
                showToast('Terjadi kesalahan koneksi', 'error');
                $btn.prop('checked', !enabled);
            }
        });
    });

    // Frequency Change
    $('#backupFrequency').change(function() {
        const frequency = $(this).val();
        if (frequency === 'daily') {
            $('#dailyTimeContainer').show();
            $('#monthlySettingsContainer').hide();
        } else {
            $('#dailyTimeContainer').hide();
            $('#monthlySettingsContainer').show();
        }
    });

    // Encryption Toggle
    $('#encryptionEnabled').change(function() {
        if ($(this).is(':checked')) {
            $('#encryptionPasswordContainer').slideDown();
        } else {
            $('#encryptionPasswordContainer').slideUp();
        }
    });

    // Toggle Password Visibility
    $('#togglePassword').click(function() {
        const input = $('#encryptionPassword');
        const icon = $(this).find('i');
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icon.removeClass('ti-eye').addClass('ti-eye-off');
        } else {
            input.attr('type', 'password');
            icon.removeClass('ti-eye-off').addClass('ti-eye');
        }
    });

    // Save Settings
    $('#saveSettingsBtn').click(function() {
        const $btn = $(this);
        $btn.prop('disabled', true).addClass('btn-loading').html('<i class="ti ti-loader"></i> Menyimpan...');

        const settings = {
            auto_backup_enabled: $('#autoBackupToggle').is(':checked'),
            frequency: $('#backupFrequency').val(),
            daily_time: $('#dailyTime').val(),
            monthly_day: $('#monthlyDay').val(),
            monthly_time: $('#monthlyTime').val(),
            retention_days: $('#retentionDays').val(),
            auto_delete_old: $('#autoDeleteOld').is(':checked'),
            encryption_enabled: $('#encryptionEnabled').is(':checked'),
            encryption_password: $('#encryptionPassword').val()
        };

        $.ajax({
            url: '{{ route("backup.settings.update") }}',
            method: 'PUT',
            data: settings,
            success: function(response) {
                if (response.success) {
                    showToast('Pengaturan berhasil disimpan', 'success');
                } else {
                    showToast(response.message, 'error');
                }
            },
            error: function(xhr) {
                let errorMessage = 'Terjadi kesalahan saat menyimpan pengaturan';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                showToast(errorMessage, 'error');
            },
            complete: function() {
                $btn.prop('disabled', false).removeClass('btn-loading').html('<i class="ti ti-device-floppy me-1"></i> Simpan Pengaturan');
            }
        });
    });

    // Table Selection
    $('.table-checkbox').change(function() {
        updatePerTableEstimate();
    });

    $('#tableSearch').on('input', function() {
        const search = $(this).val().toLowerCase();
        $('#tableListContainer .table-list-item').each(function() {
            const text = $(this).text().toLowerCase();
            $(this).toggle(text.includes(search));
        });
    });

    $('#selectAllTables').click(function() {
        $('.table-checkbox').prop('checked', true);
        updatePerTableEstimate();
    });

    $('#deselectAllTables').click(function() {
        $('.table-checkbox').prop('checked', false);
        updatePerTableEstimate();
    });

    // CSV Table Selection
    $('.csv-table-checkbox').change(function() {
        updateCsvEstimate();
    });

    $('#csvTableSearch').on('input', function() {
        const search = $(this).val().toLowerCase();
        $('#csvTableListContainer .table-list-item').each(function() {
            const text = $(this).text().toLowerCase();
            $(this).toggle(text.includes(search));
        });
    });

    $('#selectAllCsvTables').click(function() {
        $('.csv-table-checkbox').prop('checked', true);
        updateCsvEstimate();
    });

    $('#deselectAllCsvTables').click(function() {
        $('.csv-table-checkbox').prop('checked', false);
        updateCsvEstimate();
    });

    // Update Estimates
    function updatePerTableEstimate() {
        const selected = $('.table-checkbox:checked');
        const count = selected.length;
        const tables = selected.map(function() { return $(this).val(); }).get();

        $('#selectedTablesCount').text(count + ' tabel dipilih');
        $('#perTableBackupBtn').prop('disabled', count === 0);

        if (count === 0) {
            $('#perTableEstimateSize').text('0 B');
            return;
        }

        $.post('{{ route("backup.estimate") }}', { tables: tables.join(',') })
            .done(function(response) {
                if (response.success) {
                    $('#perTableEstimateSize').text(response.data.total_estimated_size_human);
                }
            });
    }

    function updateCsvEstimate() {
        const selected = $('.csv-table-checkbox:checked');
        const count = selected.length;
        const tables = selected.map(function() { return $(this).val(); }).get();

        $('#selectedCsvTablesCount').text(count + ' tabel dipilih');
        $('#csvBackupBtn').prop('disabled', count === 0);

        if (count === 0) {
            $('#csvEstimateSize').text('0 B');
            return;
        }

        $.post('{{ route("backup.estimate") }}', { tables: tables.join(',') })
            .done(function(response) {
                if (response.success) {
                    $('#csvEstimateSize').text(response.data.total_estimated_size_human);
                }
            });
    }

    function loadFullBackupEstimate() {
        $.post('{{ route("backup.estimate") }}', {})
            .done(function(response) {
                if (response.success) {
                    $('#fullEstimateSize').text(response.data.total_estimated_size_human);
                }
            });
    }

    // Backup Functions
    function startBackup(type) {
        let url = '{{ route("backup.full") }}';
        let data = {};
        let btnId = '#fullBackupBtn';
        let loadingText = 'Membuat backup database...';

        if (type === 'per-table') {
            url = '{{ route("backup.per-table") }}';
            data.tables = $('.table-checkbox:checked').map(function() { return $(this).val(); }).get();
            btnId = '#perTableBackupBtn';
            loadingText = 'Membuat backup tabel...';
        } else if (type === 'csv') {
            url = '{{ route("backup.csv") }}';
            data.tables = $('.csv-table-checkbox:checked').map(function() { return $(this).val(); }).get();
            btnId = '#csvBackupBtn';
            loadingText = 'Export CSV...';
        }

        // Show progress
        $('#backupProgress').show();
        $('#progressBar').css('width', '10%');
        $('#progressText').text(loadingText);
        $('#progressPercent').text('10%');

        // Disable button
        $(btnId).prop('disabled', true).addClass('btn-loading');

        $.ajax({
            url: url,
            method: 'POST',
            data: data,
            success: function(response) {
                if (response.success) {
                    $('#progressBar').css('width', '100%');
                    $('#progressPercent').text('100%');

                    showToast(response.message + ' (' + (response.data?.size || '') + ')', 'success');

                    // Reload page after short delay
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    showToast(response.message, 'error');
                    $('#backupProgress').hide();
                }
            },
            error: function(xhr) {
                let message = 'Terjadi kesalahan saat memulai backup';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                showToast(message, 'error');
                $('#backupProgress').hide();
            },
            complete: function() {
                $(btnId).prop('disabled', false).removeClass('btn-loading');
            }
        });
    }

    // Backup Button Events
    $('#fullBackupBtn').click(function() {
        $('#confirmMessage').text('Apakah Anda yakin ingin membuat backup database penuh?');
        $('#confirmModal').modal('show');

        $('#confirmBtn').off('click').on('click', function() {
            $('#confirmModal').modal('hide');
            startBackup('full');
        });
    });

    $('#perTableBackupBtn').click(function() {
        const count = $('.table-checkbox:checked').length;
        $('#confirmMessage').text('Apakah Anda yakin ingin backup ' + count + ' tabel yang dipilih?');
        $('#confirmModal').modal('show');

        $('#confirmBtn').off('click').on('click', function() {
            $('#confirmModal').modal('hide');
            startBackup('per-table');
        });
    });

    $('#csvBackupBtn').click(function() {
        const count = $('.csv-table-checkbox:checked').length;
        $('#confirmMessage').text('Apakah Anda yakin ingin export ' + count + ' tabel ke CSV?');
        $('#confirmModal').modal('show');

        $('#confirmBtn').off('click').on('click', function() {
            $('#confirmModal').modal('hide');
            startBackup('csv');
        });
    });

    // Download Function - Direct download with proper error handling
    $(document).on('click', '.btn-download', function() {
        const id = $(this).data('id');
        const filename = $(this).data('filename');
        const $btn = $(this);

        // Disable button and show loading
        $btn.prop('disabled', true).addClass('btn-loading');

        // Show loading overlay
        $('#loadingOverlay').removeClass('d-none').find('.spinner-text').text('Mengunduh backup: ' + filename);

        // Use direct download via hidden iframe to avoid page navigation issues
        const downloadUrl = '/backup/download/' + id;

        // Create a hidden iframe for download
        const iframe = document.createElement('iframe');
        iframe.style.display = 'none';
        iframe.src = downloadUrl;
        document.body.appendChild(iframe);

        // Re-enable button after a short delay
        setTimeout(function() {
            $btn.prop('disabled', false).removeClass('btn-loading');
            $('#loadingOverlay').addClass('d-none');
            showToast('Download dimulai: ' + filename, 'success');

            // Clean up iframe
            setTimeout(function() {
                document.body.removeChild(iframe);
            }, 5000);
        }, 2000);
    });

    // Delete Backup
    $(document).on('click', '.btn-delete-backup', function() {
        const id = $(this).data('id');

        if (confirm('Apakah Anda yakin ingin menghapus backup ini?')) {
            showLoading('Menghapus backup...', 'Mohon tunggu');

            $.ajax({
                url: '{{ route("backup.delete") }}',
                method: 'DELETE',
                data: { id: id },
                success: function(response) {
                    if (response.success) {
                        showToast('Backup berhasil dihapus', 'success');
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    } else {
                        showToast(response.message, 'error');
                    }
                },
                error: function() {
                    showToast('Terjadi kesalahan', 'error');
                },
                complete: function() {
                    hideLoading();
                }
            });
        }
    });

    // Cleanup Button
    $('#cleanupBtn').click(function() {
        if (confirm('Apakah Anda yakin ingin menghapus semua backup lama?')) {
            showLoading('Membersihkan backup lama...', 'Mohon tunggu');

            $.post('{{ route("backup.cleanup") }}', {})
                .done(function(response) {
                    if (response.success) {
                        showToast(response.message, 'success');
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    } else {
                        showToast(response.message, 'error');
                    }
                })
                .fail(function() {
                    showToast('Terjadi kesalahan', 'error');
                })
                .always(function() {
                    hideLoading();
                });
        }
    });
});
</script>

@endpush
