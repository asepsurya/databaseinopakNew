@extends('layouts.master')

@section('title', 'Pengaturan Notifikasi')

@push('styles')
<style>
    .notification-preferences-page { padding: 24px; }

    .preference-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        margin-bottom: 24px;
    }

    .preference-card-header {
        padding: 16px 20px;
        border-bottom: 1px solid #e9ecef;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .preference-card-header h5 { margin: 0; font-weight: 600; }

    .preference-card-body { padding: 20px; }

    .category-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .category-icon.authentication { background: #e3f2fd; color: #1976d2; }
    .category-icon.data_operation { background: #e8f5e9; color: #388e3c; }
    .category-icon.form { background: #fff3e0; color: #f57c00; }
    .category-icon.profile { background: #fce4ec; color: #c2185b; }
    .category-icon.transaction { background: #f3e5f5; color: #7b1fa2; }
    .category-icon.system { background: #e0f7fa; color: #0097a7; }
    .category-icon.content { background: #e8eaf6; color: #3f51b5; }
    .category-icon.import_export { background: #fff8e1; color: #ffa000; }

    .preference-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid #f1f3f5;
    }

    .preference-item:last-child { border-bottom: none; }

    .preference-info { display: flex; align-items: center; gap: 12px; }

    .preference-icon {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
    }

    .preference-icon.success { background: #d1e7dd; color: #198754; }
    .preference-icon.danger { background: #f8d7da; color: #dc3545; }
    .preference-icon.warning { background: #fff3cd; color: #ffc107; }
    .preference-icon.info { background: #cff4fc; color: #0dcaf0; }
    .preference-icon.primary { background: #435ebe; color: #fff; }
    .preference-icon.secondary { background: #6c757d; color: #fff; }

    .preference-text h6 { margin: 0 0 2px; font-size: 14px; font-weight: 600; }
    .preference-text p { margin: 0; font-size: 12px; color: #6c757d; }

    .preference-controls { display: flex; align-items: center; gap: 16px; }

    .frequency-select { width: 130px; }

    .toggle-switch {
        position: relative;
        width: 48px;
        height: 26px;
    }

    .toggle-switch input { opacity: 0; width: 0; height: 0; }

    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: 0.3s;
        border-radius: 26px;
    }

    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 20px;
        width: 20px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: 0.3s;
        border-radius: 50%;
    }

    .toggle-switch input:checked + .toggle-slider { background-color: #435ebe; }
    .toggle-switch input:checked + .toggle-slider:before { transform: translateX(22px); }

    .save-btn { padding: 12px 32px; font-weight: 600; }

    .stats-card {
        background: linear-gradient(135deg, #435ebe 0%, #2c4a9e 100%);
        color: #fff;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 24px;
    }

    .stats-number { font-size: 32px; font-weight: 700; }
    .stats-label { font-size: 14px; opacity: 0.9; }
</style>
@endpush

@section('content')
<div class="notification-preferences-page">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Pengaturan Notifikasi</h4>
            <p class="text-muted mb-0">Kelola preferensi notifikasi Anda</p>
        </div>
        <button class="btn btn-primary save-btn" onclick="savePreferences()">
            <i class="ti ti-check me-2"></i>Simpan Perubahan
        </button>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="stats-card">
                <div class="stats-number" id="totalNotifications">{{ auth()->user()->notifications()->count() }}</div>
                <div class="stats-label">Total Notifikasi</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card" style="background: linear-gradient(135deg, #198754 0%, #146c43 100%);">
                <div class="stats-number" id="unreadNotifications">{{ auth()->user()->unreadNotifications()->count() }}</div>
                <div class="stats-label">Belum Dibaca</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card" style="background: linear-gradient(135deg, #6c757d 0%, #495057 100%);">
                <div class="stats-number" id="enabledCount">0</div>
                <div class="stats-label">Jenis Notifikasi Diaktifkan</div>
            </div>
        </div>
    </div>

    <form id="preferencesForm">
        @csrf

        <!-- Authentication -->
        <div class="preference-card">
            <div class="preference-card-header">
                <div class="category-icon authentication"><i class="ti ti-login"></i></div>
                <div>
                    <h5>Autentikasi</h5>
                    <p class="text-muted mb-0 fs-xs">Notifikasi terkait login dan keamanan akun</p>
                </div>
            </div>
            <div class="preference-card-body" id="authentication-prefs"></div>
        </div>

        <!-- Data Operations -->
        <div class="preference-card">
            <div class="preference-card-header">
                <div class="category-icon data_operation"><i class="ti ti-database"></i></div>
                <div>
                    <h5>Operasi Data</h5>
                    <p class="text-muted mb-0 fs-xs">Notifikasi saat data dibuat, diubah, atau dihapus</p>
                </div>
            </div>
            <div class="preference-card-body" id="data_operation-prefs"></div>
        </div>

        <!-- Forms -->
        <div class="preference-card">
            <div class="preference-card-header">
                <div class="category-icon form"><i class="ti ti-file-description"></i></div>
                <div>
                    <h5>Formulir</h5>
                    <p class="text-muted mb-0 fs-xs">Notifikasi pengiriman dan status formulir</p>
                </div>
            </div>
            <div class="preference-card-body" id="form-prefs"></div>
        </div>

        <!-- Profile -->
        <div class="preference-card">
            <div class="preference-card-header">
                <div class="category-icon profile"><i class="ti ti-user"></i></div>
                <div>
                    <h5>Profil</h5>
                    <p class="text-muted mb-0 fs-xs">Notifikasi perubahan profil dan akun</p>
                </div>
            </div>
            <div class="preference-card-body" id="profile-prefs"></div>
        </div>

        <!-- Content -->
        <div class="preference-card">
            <div class="preference-card-header">
                <div class="category-icon content"><i class="ti ti-folder"></i></div>
                <div>
                    <h5>Konten</h5>
                    <p class="text-muted mb-0 fs-xs">Notifikasi Proyek, IKM, dan COTS</p>
                </div>
            </div>
            <div class="preference-card-body" id="content-prefs"></div>
        </div>

        <!-- Transactions -->
        <div class="preference-card">
            <div class="preference-card-header">
                <div class="category-icon transaction"><i class="ti ti-receipt"></i></div>
                <div>
                    <h5>Transaksi</h5>
                    <p class="text-muted mb-0 fs-xs">Notifikasi aktivitas transaksi</p>
                </div>
            </div>
            <div class="preference-card-body" id="transaction-prefs"></div>
        </div>

        <!-- Import/Export -->
        <div class="preference-card">
            <div class="preference-card-header">
                <div class="category-icon import_export"><i class="ti ti-download"></i></div>
                <div>
                    <h5>Import/Export</h5>
                    <p class="text-muted mb-0 fs-xs">Notifikasi import dan export data</p>
                </div>
            </div>
            <div class="preference-card-body" id="import_export-prefs"></div>
        </div>

        <!-- System -->
        <div class="preference-card">
            <div class="preference-card-header">
                <div class="category-icon system"><i class="ti ti-settings"></i></div>
                <div>
                    <h5>Sistem</h5>
                    <p class="text-muted mb-0 fs-xs">Notifikasi kesalahan dan peringatan sistem</p>
                </div>
            </div>
            <div class="preference-card-body" id="system-prefs"></div>
        </div>

        <div class="d-flex justify-content-end mt-4">
            <button type="button" class="btn btn-primary save-btn" onclick="savePreferences()">
                <i class="ti ti-check me-2"></i>Simpan Perubahan
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    let preferences = {};

    document.addEventListener('DOMContentLoaded', function() {
        loadPreferences();
    });

    function loadPreferences() {
        fetch('/notifications/preferences')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    renderPreferences(data.data);
                    updateEnabledCount(data.data);
                }
            })
            .catch(error => console.error('Error loading preferences:', error));
    }

    function renderPreferences(groupedData) {
        Object.keys(groupedData).forEach(category => {
            const container = document.getElementById(`${category}-prefs`);
            if (!container) return;

            let html = '';
            groupedData[category].forEach(item => {
                preferences[item.type] = { enabled: item.enabled, frequency: item.frequency };

                html += `
                    <div class="preference-item">
                        <div class="preference-info">
                            <div class="preference-icon ${item.color}">
                                <i class="${item.icon}"></i>
                            </div>
                            <div class="preference-text">
                                <h6>${item.title}</h6>
                                <p>Notifikasi ${item.title}</p>
                            </div>
                        </div>
                        <div class="preference-controls">
                            <select class="form-select form-select-sm frequency-select"
                                    id="frequency_${item.type}"
                                    onchange="updatePreference('${item.type}', 'frequency', this.value)">
                                <option value="realtime" ${item.frequency === 'realtime' ? 'selected' : ''}>Real-time</option>
                                <option value="hourly" ${item.frequency === 'hourly' ? 'selected' : ''}>Per Jam</option>
                                <option value="daily" ${item.frequency === 'daily' ? 'selected' : ''}>Harian</option>
                                <option value="weekly" ${item.frequency === 'weekly' ? 'selected' : ''}>Mingguan</option>
                            </select>
                            <label class="toggle-switch">
                                <input type="checkbox"
                                       ${item.enabled ? 'checked' : ''}
                                       onchange="updatePreference('${item.type}', 'enabled', this.checked)">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                    </div>
                `;
            });
            container.innerHTML = html;
        });
    }

    function updatePreference(type, key, value) {
        if (!preferences[type]) {
            preferences[type] = { enabled: true, frequency: 'realtime' };
        }
        preferences[type][key] = value;
        updateEnabledCount();
    }

    function updateEnabledCount(groupedData = null) {
        let count = 0;
        if (groupedData) {
            Object.values(groupedData).forEach(category => {
                category.forEach(item => {
                    if (item.enabled) count++;
                });
            });
        } else {
            Object.values(preferences).forEach(pref => {
                if (pref.enabled) count++;
            });
        }
        document.getElementById('enabledCount').textContent = count;
    }

    function savePreferences() {
        fetch('/notifications/preferences', {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ preferences: preferences })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Preferensi notifikasi telah disimpan',
                    timer: 2000,
                    showConfirmButton: false
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Gagal menyimpan preferensi'
                });
            }
        })
        .catch(error => {
            console.error('Error saving preferences:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Terjadi kesalahan saat menyimpan'
            });
        });
    }
</script>
@endpush
