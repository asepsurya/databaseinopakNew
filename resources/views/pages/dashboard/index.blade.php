@extends('layouts.master')

@section('title', $title ?? 'Dashboard')

@section('page-title')
    <i class="ti-home me-1"></i> Dashboard
@endsection

<style>
    .container-fluid {
        padding: 0 !important;
    }

    @media (max-width: 768px) {
        .container-fluid {
            padding: 0 !important;
        }

        footer {
            display: none !important;
        }
    }

</style>

@section('content')


    <!-- Nav Tabs -->
    <ul class="nav nav-tabs" id="myTab" role="tablist">
     <li class="nav-item" role="presentation">
    <button class="nav-link active d-flex align-items-center gap-2"
            id="home-tab"
            data-bs-toggle="tab"
            data-bs-target="#home"
            type="button"
            role="tab">
        <i class="ti ti-home"></i>
        Home
    </button>
</li>

<li class="nav-item" role="presentation">
    <button class="nav-link d-flex align-items-center gap-2"
            id="profile-tab"
            data-bs-toggle="tab"
            data-bs-target="#profile"
            type="button"
            role="tab">
        <i class="ti ti-chart-bar"></i>
        Statistik
    </button>
</li>

    </ul>

    <!-- Tab Content -->
    <div class="tab-content  border border-top-0" id="myTabContent">

        <div class="tab-pane fade show active"
             id="home"
             role="tabpanel">
            <div class="card border-0  h-100 w-100 overflow-hidden position-relative">

 <!-- Background Top Right -->
<div class="position-absolute top-0 end-0 d-none d-md-block opacity-25"
     style="background: url('{{ asset('assets/images/32.png') }}') no-repeat top right;
            width: 300px;
            height: 300px;
            background-size: contain;">
</div>

<!-- Background Bottom Right -->
<div class="position-absolute bottom-0 end-0 d-none d-xl-block opacity-25"
     style="background: url('{{ asset('assets/images/21.png') }}') no-repeat bottom right;
            width: 250px;
            height: 250px;
            background-size: contain;">
</div>

    <div class="card-body  position-relative">

        <!-- Badge Date Time -->
        <span class="badge bg-warning text-dark mb-4 fs-6">
            <span id="date-time"></span>
            <i class="fas fa-award ms-2"></i>
        </span>

        <!-- Heading -->
        <h3 class="mb-4 fw-bold">
            Selamat Datang, {{ auth()->user()->name }}
        </h3>

        <!-- Text -->
        <p class="text-secondary fw-semibold">
            Kami Percaya Pertumbuhan Ekonomi yang
            <br class="d-none d-sm-block">
            Kuat ditopang oleh UMKM
            <br class="d-none d-sm-block">
            yang Berkelanjutan
        </p>

    </div>

    <div class="  border-0 px-3 pb-4">
        <p class="text-secondary fw-semibold mb-0">
            Follow Us
            <a href="https://inopakinstitute.or.id" class="fw-bold text-decoration-none">
                Inopakinstitute.or.id
            </a>
        </p>
    </div>

</div>
        </div>

        <div class="tab-pane fade p-2"
             id="profile"
             role="tabpanel">
            <!-- Page Title Start -->
    <div class="page-title-head d-flex align-items-center ">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-bold m-0">Dashboard Overview</h4>
        </div>

        <div class="d-flex align-items-center gap-2 ">
            <!-- Date Range Picker -->
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="ti ti-calendar me-1"></i>
                    <span id="dateRangeLabel">This Month</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" id="dateRangeOptions">
                    <li><a class="dropdown-item" href="#" data-range="today" data-url="{{ route('dashboard') }}">Today</a></li>
                    <li><a class="dropdown-item" href="#" data-range="week" data-url="{{ route('dashboard') }}">This Week</a></li>
                    <li><a class="dropdown-item" href="#" data-range="month" data-url="{{ route('dashboard') }}">This Month</a></li>
                    <li><a class="dropdown-item" href="#" data-range="year" data-url="{{ route('dashboard') }}">This Year</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="#" data-range="custom">Custom Range</a></li>
                </ul>
            </div>

            <!-- Refresh Button -->
            <button class="btn btn-sm btn-outline-primary" id="refreshDashboard" title="Refresh Data">
                <i class="ti ti-refresh"></i>
            </button>

            <!-- Export Button -->
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="ti ti-download me-1"></i> Export
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#" id="exportJson"><i class="ti ti-file-js me-2"></i>JSON</a></li>
                    <li><a class="dropdown-item" href="#" id="exportCsv"><i class="ti ti-file-spreadsheet me-2"></i>CSV</a></li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Page Title End -->

    <!-- Breadcrumb -->
    <div class="mb-3">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="javascript: void(0);">INOPAK</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
    </div>

    <!-- KPI Cards Row -->
    <div class="row mb-3">
        <!-- Total Projects -->
        <div class="col-md-6 col-xl-3">
            <div class="card card-h-100 shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted text-uppercase fs-12 fw-semibold mb-1" title="Total Number of Projects">Total Projects</p>
                            <h3 class="fw-bold mb-0 fs-22">{{ number_format($totalProjects ?? 0) }}</h3>
                            <div class="mt-2">
                                <span class="badge {{ ($projectGrowth ?? 0) >= 0 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} fw-semibold">
                                    <i class="ti ti-arrow-{{ ($projectGrowth ?? 0) >= 0 ? 'up' : 'down' }} me-1"></i>
                                    {{ abs($projectGrowth ?? 0) }}%
                                </span>
                                <span class="text-muted fs-11 ms-1">vs last month</span>
                            </div>
                        </div>
                        <div class="avatar-md bg-primary-subtle rounded-circle d-flex align-items-center justify-content-center">
                            <i class="ti ti-briefcase fs-22 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total IKM -->
        <div class="col-md-6 col-xl-3">
            <div class="card card-h-100 shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted text-uppercase fs-12 fw-semibold mb-1" title="Total Number of IKM">Total IKM</p>
                            <h3 class="fw-bold mb-0 fs-22">{{ number_format($totalIKM ?? 0) }}</h3>
                            <div class="mt-2">
                                <span class="badge {{ ($ikmGrowth ?? 0) >= 0 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} fw-semibold">
                                    <i class="ti ti-arrow-{{ ($ikmGrowth ?? 0) >= 0 ? 'up' : 'down' }} me-1"></i>
                                    {{ abs($ikmGrowth ?? 0) }}%
                                </span>
                                <span class="text-muted fs-11 ms-1">vs last month</span>
                            </div>
                        </div>
                        <div class="avatar-md bg-success-subtle rounded-circle d-flex align-items-center justify-content-center">
                            <i class="ti ti-building-store fs-22 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total COTS -->
        <div class="col-md-6 col-xl-3">
            <div class="card card-h-100 shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted text-uppercase fs-12 fw-semibold mb-1" title="Total Number of COTS">Total COTS</p>
                            <h3 class="fw-bold mb-0 fs-22">{{ number_format($totalCOTS ?? 0) }}</h3>
                            <div class="mt-2">
                                <span class="badge {{ ($cotsGrowth ?? 0) >= 0 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} fw-semibold">
                                    <i class="ti ti-arrow-{{ ($cotsGrowth ?? 0) >= 0 ? 'up' : 'down' }} me-1"></i>
                                    {{ abs($cotsGrowth ?? 0) }}%
                                </span>
                                <span class="text-muted fs-11 ms-1">vs last month</span>
                            </div>
                        </div>
                        <div class="avatar-md bg-warning-subtle rounded-circle d-flex align-items-center justify-content-center">
                            <i class="ti ti-devices fs-22 text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Users -->
        <div class="col-md-6 col-xl-3">
            <div class="card card-h-100 shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted text-uppercase fs-12 fw-semibold mb-1" title="Total Registered Users">Total Users</p>
                            <h3 class="fw-bold mb-0 fs-22">{{ number_format($totalUsers ?? 0) }}</h3>
                            <div class="mt-2">
                                <span class="badge bg-info-subtle text-info fw-semibold">
                                    <i class="ti ti-users me-1"></i>
                                    Active
                                </span>
                                <span class="text-muted fs-11 ms-1">registered</span>
                            </div>
                        </div>
                        <div class="avatar-md bg-info-subtle rounded-circle d-flex align-items-center justify-content-center">
                            <i class="ti ti-user fs-22 text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- KPI Cards Row End -->

    <!-- Charts Row -->
    <div class="row mb-3">
        <!-- Monthly Trends Chart -->
        <div class="col-xl-8">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-dashed justify-content-between d-flex align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="ti ti-chart-line me-2 text-primary"></i>
                        Monthly Trends
                        <span class="text-muted fs-12 fw-normal ms-2">(Last 6 Months)</span>
                    </h4>
                    <div class="dropdown">
                        <a href="#" class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">
                            <i class="ti ti-dots-vertical"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#" id="trendExport"><i class="ti ti-download me-2"></i>Export</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex gap-3 mb-3">
                        <div class="d-flex align-items-center">
                            <span class="badge bg-primary me-2">&nbsp;</span>
                            <span class="text-muted fs-12">IKM</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-success me-2">&nbsp;</span>
                            <span class="text-muted fs-12">Projects</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-warning me-2">&nbsp;</span>
                            <span class="text-muted fs-12">COTS</span>
                        </div>
                    </div>
                    <div id="monthlyTrendsChart" class="apex-charts" dir="ltr"></div>
                </div>
            </div>
        </div>

        <!-- IKM Distribution by Type -->
        <div class="col-xl-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-dashed justify-content-between d-flex align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="ti ti-chart-pie me-2 text-primary"></i>
                        IKM by Category
                    </h4>
                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip" title="View Details" onclick="window.location.href='/project/dataikm/1'">
                        <i class="ti ti-arrow-right"></i>
                    </button>
                </div>
                <div class="card-body">
                    <div id="ikmDistributionChart" class="apex-charts" dir="ltr"></div>
                    @if(!empty($ikmByCategory) && count($ikmByCategory) > 0)
                        <div class="mt-3">
                            @foreach(array_slice($ikmByCategory, 0, 5) as $category => $count)
                                <div class="d-flex justify-content-between align-items-center py-1 border-bottom">
                                    <span class="text-muted fs-12 text-truncate" style="max-width: 150px;">{{ $category ?? 'Unknown' }}</span>
                                    <span class="fw-semibold">{{ number_format($count) }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="ti ti-chart-bar fs-48 text-opacity-25"></i>
                            <p class="mt-2 mb-0">No data available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- Charts Row End -->

    <!-- Second Row -->
    <div class="row mb-3">
        <!-- IKM by Province -->
        <div class="col-xl-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-dashed justify-content-between d-flex align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="ti ti-map-2 me-2 text-primary"></i>
                        IKM Distribution by Province
                    </h4>
                </div>
                <div class="card-body">
                    @if(!empty($ikmByProvince) && count($ikmByProvince) > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th class="border-top-0">Province</th>
                                        <th class="border-top-0 text-end">Count</th>
                                        <th class="border-top-0" style="width: 40%;">Distribution</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalByProvince = array_sum($ikmByProvince);
                                        $maxCount = max($ikmByProvince);
                                    @endphp
                                    @foreach($ikmByProvince as $province => $count)
                                        @php
                                            $percentage = $totalByProvince > 0 ? round(($count / $totalByProvince) * 100, 1) : 0;
                                            $width = $maxCount > 0 ? round(($count / $maxCount) * 100) : 0;
                                        @endphp
                                        <tr>
                                            <td>
                                                <i class="ti ti-map-pin text-muted me-1"></i>
                                                {{ $province }}
                                            </td>
                                            <td class="text-end fw-semibold">{{ number_format($count) }}</td>
                                            <td>
                                                <div class="progress" style="height: 8px; min-width: 100px;">
                                                    <div class="progress-bar bg-primary" role="progressbar"
                                                         style="width: {{ $width }}%"
                                                         aria-valuenow="{{ $width }}"
                                                         aria-valuemin="0"
                                                         aria-valuemax="100"
                                                         data-bs-toggle="tooltip"
                                                         title="{{ $percentage }}%">
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted py-5">
                            <i class="ti ti-map-off fs-48 text-opacity-25"></i>
                            <p class="mt-2 mb-0">No province data available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent IKM Entries -->
        <div class="col-xl-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-dashed justify-content-between d-flex align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="ti ti-clock me-2 text-primary"></i>
                        Recent IKM Entries
                    </h4>
                    <a href="/project/dataikm/1" class="btn btn-sm btn-outline-primary">
                        View All <i class="ti ti-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    @if(!empty($recentIKM) && count($recentIKM) > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentIKM as $ikm)
                                <div class="list-group-item d-flex align-items-center py-2 px-3">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar-sm bg-light rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="ti ti-building-store text-muted"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0 fs-13">{{ $ikm->nama ?? 'Unknown IKM' }}</h6>
                                        <p class="mb-0 text-muted fs-11">
                                            {{ $ikm->jenisProduk ?? 'N/A' }} •
                                            {{ $ikm->regency->name ?? $ikm->id_kota ?? 'Unknown' }}
                                        </p>
                                    </div>
                                    <div class="flex-shrink-0 text-end">
                                        <span class="badge bg-light text-dark fs-10">
                                            {{ $ikm->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-muted py-5">
                            <i class="ti ti-inbox fs-48 text-opacity-25"></i>
                            <p class="mt-2 mb-0">No recent IKM entries</p>
                            <a href="/project/dataikm/1" class="btn btn-sm btn-outline-primary mt-2">
                                <i class="ti ti-plus me-1"></i> Add New IKM
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Third Row -->
    <div class="row">
        <!-- Recent Projects -->
        <div class="col-xl-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-dashed justify-content-between d-flex align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="ti ti-briefcase me-2 text-primary"></i>
                        Recent Projects
                    </h4>
                    <a href="/project" class="btn btn-sm btn-outline-primary">
                        View All <i class="ti ti-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    @if(!empty($recentProjects) && count($recentProjects) > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentProjects as $project)
                                <div class="list-group-item d-flex align-items-center py-2 px-3">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar-sm bg-primary-subtle rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="ti ti-folder text-primary"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0 fs-13">{{ $project->NamaProjek ?? 'Unnamed Project' }}</h6>
                                        <p class="mb-0 text-muted fs-11">
                                            {{ $project->keterangan ?? 'No description' }}
                                        </p>
                                    </div>
                                    <div class="flex-shrink-0 text-end">
                                        <span class="badge bg-light text-dark fs-10">
                                            {{ $project->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-muted py-5">
                            <i class="ti ti-folder-off fs-48 text-opacity-25"></i>
                            <p class="mt-2 mb-0">No recent projects</p>
                            <a href="/project" class="btn btn-sm btn-outline-primary mt-2">
                                <i class="ti ti-plus me-1"></i> Create Project
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sales Report Chart -->
        <div class="col-xl-6">
            <div class="card shadow-sm border-0">
                <div class="card-header border-dashed card-tabs">
                    <div class="flex-grow-1">
                        <h4 class="card-title">Sales Report <span class="text-muted fs-base fw-normal">({{ number_format($totalIKM ?? 0) }} IKM)</span></h4>
                    </div>
                    <ul class="nav nav-tabs nav-justified card-header-tabs nav-bordered" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a href="#!" data-bs-toggle="tab" data-period="today" aria-expanded="false" class="nav-link period-tab" aria-selected="false" tabindex="-1" role="tab">
                                <span class="d-md-none d-block">1D</span>
                                <span class="d-none d-md-block">Today</span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a href="#!" data-bs-toggle="tab" data-period="monthly" aria-expanded="true" class="nav-link active period-tab" aria-selected="true" role="tab">
                                <span class="d-md-none d-block">1M</span>
                                <span class="d-none d-md-block">Monthly</span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a href="#!" data-bs-toggle="tab" data-period="annual" aria-expanded="false" class="nav-link period-tab" aria-selected="false" tabindex="-1" role="tab">
                                <span class="d-md-none d-block">1Y</span>
                                <span class="d-none d-md-block">Annual</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body p-0">
                    <div class="bg-light bg-opacity-25 border-bottom border-dashed">
                        <div class="row text-center">
                            <div class="col-sm-4">
                                <p class="text-muted mt-3 mb-1">Total IKM</p>
                                <h4 class="mb-3">
                                    <i class="ti ti-building-store text-primary me-1"></i>
                                    <span>{{ number_format($totalIKM ?? 0) }}</span>
                                </h4>
                            </div>
                            <div class="col-sm-4">
                                <p class="text-muted mt-3 mb-1">Projects</p>
                                <h4 class="mb-3">
                                    <i class="ti ti-briefcase text-success me-1"></i>
                                    <span>{{ number_format($totalProjects ?? 0) }}</span>
                                </h4>
                            </div>
                            <div class="col-sm-4">
                                <p class="text-muted mt-3 mb-1">Growth Rate</p>
                                <h4 class="mb-3">
                                    <i class="ti ti-trending-up text-success me-1"></i>
                                    <span>{{ abs($ikmGrowth ?? 0) }}%</span>
                                </h4>
                            </div>
                        </div>
                    </div>

                    <div class="p-3 pt-1">
                        <div class="dash-item-overlay d-none d-md-block" dir="ltr">
                            <h5>IKM Performance Overview</h5>
                            <p class="text-muted mb-0 mt-2">Track your IKM growth and performance metrics over time.</p>
                        </div>
                        <div dir="ltr">
                            <div id="salesReportChart" class="apex-charts" style="min-height: 280px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        </div>

        <div class="tab-pane fade"
             id="contact"
             role="tabpanel">
            <h5>Contact</h5>
            <p>Ini isi tab Contact.</p>
        </div>

    </div>




    <!-- Hidden data container for JavaScript -->
    <script type="application/json" id="dashboardData">
    {
        "monthlyTrends": {
            "ikm": @json(array_values($filledMonthlyIKMTrend ?? [])),
            "projects": @json(array_values($filledMonthlyProjectTrend ?? [])),
            "cots": @json(array_values($filledMonthlyCOTSTrend ?? [])),
            "labels": @json($monthNames ?? [])
        },
        "ikmDistribution": {
            "labels": @json($ikmTypeDistribution['labels'] ?? []),
            "data": @json($ikmTypeDistribution['data'] ?? []),
            "colors": @json($ikmTypeDistribution['colors'] ?? [])
        },
        "ikmByProvince": @json($ikmByProvince ?? []),
        "growth": {
            "ikm": {{ $ikmGrowth ?? 0 }},
            "projects": {{ $projectGrowth ?? 0 }},
            "cots": {{ $cotsGrowth ?? 0 }}
        },
        "cotsStats": @json($cotsStats ?? [])
    }
    </script>
@endsection

@push('scripts')
    <!-- ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <!-- Dashboard Scripts -->
    <script>
        // Global dashboard data and chart instances
        let dashboardData = {};
        let monthlyTrendsChart = null;
        let ikmDistributionChart = null;
        let salesReportChart = null;
        let currentPeriod = 'monthly';

        // Wait for document ready
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize dashboard
            initDashboard();

            // Initialize tooltips
            initTooltips();

            // Setup event listeners
            setupEventListeners();
        });

        function initDashboard() {
            // Parse dashboard data from hidden container
            const dataContainer = document.getElementById('dashboardData');
            if (dataContainer) {
                try {
                    dashboardData = JSON.parse(dataContainer.textContent);
                } catch (e) {
                    console.error('Error parsing dashboard data:', e);
                    dashboardData = {};
                }
            }

            // Initialize charts
            initMonthlyTrendsChart();
            initIkmDistributionChart();
            initSalesReportChart();
        }

        function initMonthlyTrendsChart() {
            const chartElement = document.getElementById('monthlyTrendsChart');
            if (!chartElement) return;

            const data = dashboardData.monthlyTrends || {
                ikm: [12, 19, 15, 25, 22, 30],
                projects: [5, 8, 6, 12, 10, 15],
                cots: [3, 5, 4, 8, 7, 12],
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun']
            };

            const options = {
                series: [
                    {
                        name: 'IKM',
                        type: 'line',
                        data: data.ikm,
                        color: '#435ebe'
                    },
                    {
                        name: 'Projects',
                        type: 'bar',
                        data: data.projects,
                        color: '#28a745'
                    },
                    {
                        name: 'COTS',
                        type: 'bar',
                        data: data.cots,
                        color: '#ffc107'
                    }
                ],
                chart: {
                    height: 320,
                    type: 'line',
                    toolbar: {
                        show: true,
                        tools: {
                            download: true,
                            selection: true,
                            zoom: true,
                            zoominout: true,
                            pan: true,
                            reset: true
                        },
                        export: {
                            svg: { filename: 'monthly-trends' },
                            png: { filename: 'monthly-trends' }
                        }
                    },
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 800
                    }
                },
                stroke: {
                    width: [3, 0, 0],
                    curve: 'smooth'
                },
                plotOptions: {
                    bar: {
                        columnWidth: '40%',
                        borderRadius: 4,
                        borderRadiusApplication: 'end'
                    }
                },
                dataLabels: {
                    enabled: false
                },
                xaxis: {
                    categories: data.labels,
                    labels: {
                        style: {
                            colors: '#6c757d',
                            fontSize: '12px'
                        }
                    },
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    }
                },
                yaxis: [{
                    title: {
                        text: 'Count',
                        style: {
                            color: '#6c757d'
                        }
                    },
                    labels: {
                        style: {
                            colors: '#6c757d',
                            fontSize: '12px'
                        }
                    }
                }],
                legend: {
                    show: false
                },
                grid: {
                    borderColor: '#e9ecef',
                    strokeDashArray: 4,
                    xaxis: {
                        lines: {
                            show: false
                        }
                    }
                },
                tooltip: {
                    shared: true,
                    intersect: false,
                    y: [{
                        formatter: function (y) {
                            return y ? y.toFixed(0) : y;
                        }
                    }],
                    x: {
                        show: true
                    }
                },
                fill: {
                    type: ['solid', 'solid', 'solid'],
                    opacity: [1, 0.7, 0.7]
                }
            };

            // Create chart
            if (typeof ApexCharts !== 'undefined') {
                monthlyTrendsChart = new ApexCharts(chartElement, options);
                monthlyTrendsChart.render();
            }
        }

        function initIkmDistributionChart() {
            const chartElement = document.getElementById('ikmDistributionChart');
            if (!chartElement) return;

            const data = dashboardData.ikmDistribution || {
                labels: ['Food', 'Beverage', 'Textile', 'Chemical', 'Other'],
                data: [30, 25, 20, 15, 10],
                colors: {
                    'Food': '#435ebe',
                    'Beverage': '#28a745',
                    'Textile': '#ffc107',
                    'Chemical': '#dc3545',
                    'Other': '#6c757d'
                }
            };

            // Generate colors array based on labels
            const defaultColors = ['#435ebe', '#28a745', '#ffc107', '#dc3545', '#17a2b8', '#6c757d', '#20c997', '#6610f2'];
            const colors = data.labels.map((label, index) => {
                return data.colors[label] || defaultColors[index % defaultColors.length];
            });

            const options = {
                series: data.data,
                labels: data.labels,
                chart: {
                    height: 220,
                    type: 'donut',
                    toolbar: {
                        show: false
                    }
                },
                colors: colors,
                legend: {
                    show: false
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '70%',
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: 'Total',
                                    color: '#6c757d',
                                    fontSize: '12px',
                                    fontFamily: 'inherit',
                                    formatter: function(w) {
                                        return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                    }
                                },
                                value: {
                                    show: true,
                                    fontSize: '18px',
                                    fontWeight: 600,
                                    color: '#495057',
                                    formatter: function(val) {
                                        return parseInt(val).toLocaleString();
                                    }
                                }
                            }
                        }
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    width: 0
                },
                tooltip: {
                    y: {
                        formatter: function(value) {
                            return value.toLocaleString() + ' IKM';
                        }
                    }
                }
            };

            // Create chart
            if (typeof ApexCharts !== 'undefined') {
                ikmDistributionChart = new ApexCharts(chartElement, options);
                ikmDistributionChart.render();
            }
        }

        function initSalesReportChart() {
            const chartElement = document.getElementById('salesReportChart');
            if (!chartElement) return;

            // Generate sample data based on period
            const chartData = generateChartDataForPeriod(currentPeriod);

            const options = {
                series: [{
                    name: 'IKM',
                    data: chartData.data
                }],
                chart: {
                    height: 220,
                    type: 'area',
                    toolbar: {
                        show: false
                    },
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 800
                    },
                    sparkline: {
                        enabled: false
                    }
                },
                colors: ['#435ebe'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.5,
                        opacityTo: 0.1,
                        stops: [0, 90, 100]
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 2
                },
                xaxis: {
                    categories: chartData.labels,
                    labels: {
                        show: true,
                        style: {
                            colors: '#6c757d',
                            fontSize: '10px'
                        }
                    },
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: '#6c757d',
                            fontSize: '10px'
                        }
                    }
                },
                grid: {
                    borderColor: '#e9ecef',
                    strokeDashArray: 3,
                    xaxis: {
                        lines: {
                            show: false
                        }
                    }
                },
                tooltip: {
                    x: {
                        show: true
                    },
                    y: {
                        formatter: function(value) {
                            return value.toLocaleString();
                        }
                    },
                    theme: 'light'
                }
            };

            // Create chart
            if (typeof ApexCharts !== 'undefined') {
                salesReportChart = new ApexCharts(chartElement, options);
                salesReportChart.render();
            }
        }

        function generateChartDataForPeriod(period) {
            let labels = [];
            let data = [];
            const now = new Date();

            switch(period) {
                case 'today':
                    // Hourly data for today
                    for (let i = 0; i < 24; i++) {
                        labels.push(i + ':00');
                        data.push(Math.floor(Math.random() * 50) + 10);
                    }
                    break;
                case 'monthly':
                    // Daily data for current month (simplified to 25 days)
                    for (let i = 1; i <= 25; i++) {
                        labels.push(i);
                        data.push(Math.floor(Math.random() * 100) + 20);
                    }
                    break;
                case 'annual':
                    // Monthly data for current year
                    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                    const currentMonth = now.getMonth();
                    for (let i = 0; i <= currentMonth; i++) {
                        labels.push(months[i]);
                        data.push(Math.floor(Math.random() * 200) + 50);
                    }
                    break;
                default:
                    labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
                    data = [30, 45, 55, 40, 60, 75];
            }

            return { labels, data };
        }

        function initTooltips() {
            // Initialize Bootstrap tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl, {
                    trigger: 'hover',
                    placement: 'top'
                });
            });
        }

        function setupEventListeners() {
            // Period tabs
            const periodTabs = document.querySelectorAll('.period-tab');
            periodTabs.forEach(tab => {
                tab.addEventListener('click', function(e) {
                    e.preventDefault();
                    periodTabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');

                    currentPeriod = this.dataset.period;

                    // Update chart data
                    if (salesReportChart) {
                        const chartData = generateChartDataForPeriod(currentPeriod);
                        salesReportChart.updateSeries([{
                            name: 'IKM',
                            data: chartData.data
                        }]);
                        salesReportChart.updateOptions({
                            xaxis: {
                                categories: chartData.labels
                            }
                        });
                    }
                });
            });

            // Refresh button
            const refreshBtn = document.getElementById('refreshDashboard');
            if (refreshBtn) {
                refreshBtn.addEventListener('click', function() {
                    const icon = this.querySelector('i');
                    icon.classList.add('ti-spin');

                    // Reload page to fetch fresh data
                    window.location.reload();
                });
            }

            // Export buttons
            const exportJsonBtn = document.getElementById('exportJson');
            if (exportJsonBtn) {
                exportJsonBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    exportDashboard('json');
                });
            }

            const exportCsvBtn = document.getElementById('exportCsv');
            if (exportCsvBtn) {
                exportCsvBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    exportDashboard('csv');
                });
            }

            // Date range options
            const dateRangeOptions = document.querySelectorAll('#dateRangeOptions .dropdown-item');
            dateRangeOptions.forEach(option => {
                option.addEventListener('click', function(e) {
                    e.preventDefault();
                    const range = this.dataset.range;
                    document.getElementById('dateRangeLabel').textContent = this.textContent;

                    // Apply filter - reload with query parameter
                    const url = this.dataset.url || window.location.href;
                    window.location.href = url + '?period=' + range;
                });
            });
        }

        function exportDashboard(format) {
            const exportUrl = `/api/dashboard/export?format=${format}`;

            fetch(exportUrl, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                }
            })
            .then(response => {
                if (format === 'csv') {
                    return response.blob();
                }
                return response.json();
            })
            .then(data => {
                if (format === 'json') {
                    downloadFile(JSON.stringify(data, null, 2), 'dashboard_data.json', 'application/json');
                } else if (format === 'csv') {
                    const url = window.URL.createObjectURL(data);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = 'dashboard_export.csv';
                    a.click();
                    window.URL.revokeObjectURL(url);
                }
            })
            .catch(error => {
                console.error('Export error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Export Failed',
                    text: 'Could not export dashboard data. Please try again.',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
            });
        }

        function downloadFile(content, filename, type) {
            const blob = new Blob([content], { type: type });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = filename;
            a.click();
            window.URL.revokeObjectURL(url);
        }
    </script>
    <script>
function updateDateTime() {
    const now = new Date();
    document.getElementById("date-time").innerText = now.toLocaleString("id-ID", {
        weekday: "long",
        year: "numeric",
        month: "long",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
        second: "2-digit"
    });
}
setInterval(updateDateTime, 1000);
updateDateTime();
</script>
@endpush
