<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\ikm;
use App\Models\Project;
use App\Models\cots;
use App\Models\BencmarkProduk;
use App\Models\ProdukDesign;
use App\Models\DokumentasiCots;
use App\Models\User;
use App\Models\Province;
use App\Models\Regency;
use App\Models\District;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with comprehensive metrics and charts.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $title = 'Dashboard';

        // Get period filter from request
        $period = $request->input('period', 'month');

        // Calculate date range based on period
        $startDate = match($period) {
            'today' => now()->startOfDay(),
            'week' => now()->startOfWeek(),
            'year' => now()->startOfYear(),
            default => now()->startOfMonth()
        };
        $endDate = now();

        // Core Metrics - Use caching for performance
        $cacheDuration = now()->addMinutes(5); // 5-minute cache

        // Apply period filter for counts
        $totalProjects = Project::where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->count();

        $totalIKM = ikm::where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->count();

        $totalCOTS = cots::where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->count();

        $totalUsers = User::where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->count();

        // Monthly trends for the last 6 months
        $monthlyIKMTrend = cache()->remember('dashboard_monthly_ikm_trend', $cacheDuration, function () {
            return ikm::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month', 'ASC')
            ->pluck('count', 'month')
            ->toArray();
        });

        // Monthly project trends
        $monthlyProjectTrend = cache()->remember('dashboard_monthly_project_trend', $cacheDuration, function () {
            return Project::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month', 'ASC')
            ->pluck('count', 'month')
            ->toArray();
        });

        // IKM by Category/Distribusi
        $ikmByCategory = cache()->remember('dashboard_ikm_by_category', $cacheDuration, function () {
            return ikm::select('jenisProduk as category', DB::raw('COUNT(*) as count'))
                ->groupBy('jenisProduk')
                ->orderBy('count', 'DESC')
                ->limit(10)
                ->pluck('count', 'category')
                ->toArray();
        });

        // IKM by Province
        $ikmByProvince = cache()->remember('dashboard_ikm_by_province', $cacheDuration, function () {
            return ikm::with('province')
                ->select('id_provinsi', DB::raw('COUNT(*) as count'))
                ->groupBy('id_provinsi')
                ->orderBy('count', 'DESC')
                ->limit(10)
                ->get()
                ->map(function ($item) {
                    return [
                        'province' => $item->province->name ?? 'Unknown',
                        'count' => $item->count
                    ];
                })
                ->pluck('count', 'province')
                ->toArray();
        });

        // IKM by Regency
        $ikmByRegency = cache()->remember('dashboard_ikm_by_regency', $cacheDuration, function () {
            return ikm::with('regency')
                ->select('id_kota', DB::raw('COUNT(*) as count'))
                ->groupBy('id_kota')
                ->orderBy('count', 'DESC')
                ->limit(10)
                ->get()
                ->map(function ($item) {
                    return [
                        'regency' => $item->regency->name ?? 'Unknown',
                        'count' => $item->count
                    ];
                })
                ->pluck('count', 'regency')
                ->toArray();
        });

        // Recent IKM entries
        $recentIKM = cache()->remember('dashboard_recent_ikm', now()->addMinutes(2), function () {
            return ikm::with('province', 'regency')
                ->latest()
                ->limit(5)
                ->get();
        });

        // Recent Projects
        $recentProjects = cache()->remember('dashboard_recent_projects', now()->addMinutes(2), function () {
            return Project::latest()
                ->limit(5)
                ->get();
        });

        // COTS Statistics
        $cotsStats = cache()->remember('dashboard_cots_stats', $cacheDuration, function () {
            return [
                'total' => cots::count(),
                'this_month' => cots::whereMonth('created_at', now()->month)->count(),
                'with_omset' => cots::where('omset', '!=', '')->where('omset', '!=', '0')->count(),
                'recent' => cots::latest()->first() ? 1 : 0,
            ];
        });

        // Monthly COTS trend
        $monthlyCOTSTrend = cache()->remember('dashboard_monthly_cots_trend', $cacheDuration, function () {
            return cots::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month', 'ASC')
            ->pluck('count', 'month')
            ->toArray();
        });

        // User activity (last 7 days)
        $userActivity = cache()->remember('dashboard_user_activity', now()->addMinutes(10), function () {
            $dates = [];
            $counts = [];

            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i)->format('Y-m-d');
                $dates[] = now()->subDays($i)->format('D');
                $counts[] = User::whereDate('created_at', $date)->count();
            }

            return [
                'labels' => $dates,
                'counts' => $counts
            ];
        });

        // Growth percentages (compared to last month)
        $ikmGrowth = $this->calculateGrowth('ikm');
        $projectGrowth = $this->calculateGrowth('projects');
        $cotsGrowth = $this->calculateGrowth('cots');

        // Chart data for IKM Distribution by Type
        $ikmTypeDistribution = cache()->remember('dashboard_ikm_type_distribution', $cacheDuration, function () {
            $types = ikm::select('jenisProduk', DB::raw('COUNT(*) as total'))
                ->groupBy('jenisProduk')
                ->pluck('total', 'jenisProduk')
                ->toArray();

            // Generate colors for chart
            $colors = ['#435ebe', '#28a745', '#ffc107', '#dc3545', '#17a2b8', '#6c757d', '#20c997', '#6610f2', '#e83e8c', '#fd7e14'];
            $chartColors = [];

            foreach (array_keys($types) as $index => $type) {
                $chartColors[$type] = $colors[$index % count($colors)];
            }

            return [
                'labels' => array_keys($types),
                'data' => array_values($types),
                'colors' => $chartColors
            ];
        });

        // Full month names for chart labels
        $monthNames = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthNames[] = now()->subMonths($i)->format('M Y');
        }

        // Fill missing months with zeros
        $filledMonthlyIKMTrend = $this->fillMissingMonths($monthlyIKMTrend, 6);
        $filledMonthlyProjectTrend = $this->fillMissingMonths($monthlyProjectTrend, 6);
        $filledMonthlyCOTSTrend = $this->fillMissingMonths($monthlyCOTSTrend, 6);

        return view('pages.dashboard.index', compact(
            'title',
            'user',
            // Core Metrics
            'totalProjects',
            'totalIKM',
            'totalCOTS',
            'totalUsers',
            // Trends
            'monthlyIKMTrend',
            'monthlyProjectTrend',
            'monthlyCOTSTrend',
            'filledMonthlyIKMTrend',
            'filledMonthlyProjectTrend',
            'filledMonthlyCOTSTrend',
            // Distribution
            'ikmByCategory',
            'ikmByProvince',
            'ikmByRegency',
            'ikmTypeDistribution',
            // Recent Data
            'recentIKM',
            'recentProjects',
            // Stats
            'cotsStats',
            // Activity
            'userActivity',
            // Growth
            'ikmGrowth',
            'projectGrowth',
            'cotsGrowth',
            // Chart Labels
            'monthNames'
        ));
    }

    /**
     * Calculate growth percentage compared to last month
     */
    private function calculateGrowth($type)
    {
        $currentMonth = now()->month;
        $lastMonth = now()->subMonth()->month;
        $currentYear = now()->year;
        $lastYear = now()->subMonth()->year;

        $currentCount = match($type) {
            'ikm' => ikm::whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear)
                ->count(),
            'projects' => Project::whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear)
                ->count(),
            'cots' => cots::whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear)
                ->count(),
            default => 0
        };

        $lastCount = match($type) {
            'ikm' => ikm::whereMonth('created_at', $lastMonth)
                ->whereYear('created_at', $lastYear)
                ->count(),
            'projects' => Project::whereMonth('created_at', $lastMonth)
                ->whereYear('created_at', $lastYear)
                ->count(),
            'cots' => cots::whereMonth('created_at', $lastMonth)
                ->whereYear('created_at', $lastYear)
                ->count(),
            default => 0
        };

        if ($lastCount == 0) {
            return $currentCount > 0 ? 100 : 0;
        }

        return round((($currentCount - $lastCount) / $lastCount) * 100, 2);
    }

    /**
     * Fill missing months with zero values
     */
    private function fillMissingMonths($data, $monthsCount)
    {
        $filled = [];
        $monthKeys = [];

        for ($i = $monthsCount - 1; $i >= 0; $i--) {
            $monthKeys[] = now()->subMonths($i)->format('Y-m');
        }

        foreach ($monthKeys as $key) {
            $filled[$key] = $data[$key] ?? 0;
        }

        return $filled;
    }

    /**
     * API endpoint for chart data refresh (AJAX)
     */
    public function refreshChartData(Request $request)
    {
        $chartType = $request->input('chart', 'ikm_trend');

        $cacheDuration = now()->addMinutes(2);

        $data = match($chartType) {
            'ikm_trend' => cache()->remember('api_ikm_trend', $cacheDuration, function () {
                return ikm::select(
                    DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                    DB::raw('COUNT(*) as count')
                )
                ->where('created_at', '>=', now()->subMonths(12))
                ->groupBy('month')
                ->orderBy('month', 'ASC')
                ->pluck('count', 'month')
                ->toArray();
            }),
            'project_trend' => cache()->remember('api_project_trend', $cacheDuration, function () {
                return Project::select(
                    DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                    DB::raw('COUNT(*) as count')
                )
                ->where('created_at', '>=', now()->subMonths(12))
                ->groupBy('month')
                ->orderBy('month', 'ASC')
                ->pluck('count', 'month')
                ->toArray();
            }),
            'ikm_distribution' => cache()->remember('api_ikm_distribution', $cacheDuration, function () {
                return ikm::select('jenisProduk as label', DB::raw('COUNT(*) as value'))
                    ->groupBy('jenisProduk')
                    ->get()
                    ->toArray();
            }),
            default => ['error' => 'Invalid chart type']
        };

        return response()->json([
            'chart' => $chartType,
            'data' => $data,
            'timestamp' => now()->toIso8601String()
        ]);
    }

    /**
     * Export dashboard data
     */
    public function export(Request $request)
    {
        $format = $request->input('format', 'json');

        $data = [
            'exported_at' => now()->toIso8601String(),
            'generated_by' => Auth::user()->name,
            'summary' => [
                'total_projects' => Project::count(),
                'total_ikm' => ikm::count(),
                'total_cots' => cots::count(),
                'total_users' => User::count(),
            ],
            'monthly_trends' => [
                'ikm' => ikm::select(
                    DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                    DB::raw('COUNT(*) as count')
                )
                ->whereYear('created_at', now()->year)
                ->groupBy('month')
                ->orderBy('month', 'ASC')
                ->pluck('count', 'month')
                ->toArray(),
                'projects' => Project::select(
                    DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                    DB::raw('COUNT(*) as count')
                )
                ->whereYear('created_at', now()->year)
                ->groupBy('month')
                ->orderBy('month', 'ASC')
                ->pluck('count', 'month')
                ->toArray(),
            ],
            'ikm_by_province' => ikm::with('province')
                ->select('id_provinsi', DB::raw('COUNT(*) as count'))
                ->groupBy('id_provinsi')
                ->get()
                ->map(fn($item) => [
                    'province' => $item->province->nama ?? 'Unknown',
                    'count' => $item->count
                ])
                ->toArray(),
        ];

        return match($format) {
            'csv' => $this->exportToCsv($data),
            'json' => response()->json($data),
            default => response()->json($data)
        };
    }

    /**
     * Export data to CSV format
     */
    private function exportToCsv($data)
    {
        $filename = 'dashboard_export_' . now()->format('Y_m_d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');

            // Summary section
            fputcsv($file, ['DASHBOARD SUMMARY']);
            fputcsv($file, ['Exported At', $data['exported_at']]);
            fputcsv($file, ['Generated By', $data['generated_by']]);
            fputcsv($file, []);

            fputcsv($file, ['Metric', 'Value']);
            fputcsv($file, ['Total Projects', $data['summary']['total_projects']]);
            fputcsv($file, ['Total IKM', $data['summary']['total_ikm']]);
            fputcsv($file, ['Total COTS', $data['summary']['total_cots']]);
            fputcsv($file, ['Total Users', $data['summary']['total_users']]);
            fputcsv($file, []);

            // Monthly Trends
            fputcsv($file, ['MONTHLY IKM TREND']);
            fputcsv($file, ['Month', 'Count']);
            foreach ($data['monthly_trends']['ikm'] as $month => $count) {
                fputcsv($file, [$month, $count]);
            }
            fputcsv($file, []);

            fputcsv($file, ['MONTHLY PROJECT TREND']);
            fputcsv($file, ['Month', 'Count']);
            foreach ($data['monthly_trends']['projects'] as $month => $count) {
                fputcsv($file, [$month, $count]);
            }
            fputcsv($file, []);

            // IKM by Province
            fputcsv($file, ['IKM BY PROVINCE']);
            fputcsv($file, ['Province', 'Count']);
            foreach ($data['ikm_by_province'] as $item) {
                fputcsv($file, [$item['province'], $item['count']]);
            }

            fclose($file);
        };

        return response()->streamDownload($callback, $filename, $headers);
    }
}
