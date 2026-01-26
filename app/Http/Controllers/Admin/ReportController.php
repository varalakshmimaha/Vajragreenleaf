<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ReferralReportService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ReportController extends Controller
{
    protected $reportService;

    public function __construct(ReferralReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    /**
     * Reports dashboard - shows all available reports
     */
    public function index()
    {
        $summary = $this->reportService->getSummary();
        
        return view('admin.reports.index', compact('summary'));
    }

    /**
     * Reference Summary Report
     */
    public function summary()
    {
        $summary = $this->reportService->getSummary();
        $levelData = $this->reportService->getLevelWiseReport(5); // Top 5 levels
        
        return view('admin.reports.summary', compact('summary', 'levelData'));
    }

    /**
     * Level-Wise Reference Report
     */
    public function levelWise(Request $request)
    {
        $maxLevel = $request->input('max_level', null);
        $levelData = $this->reportService->getLevelWiseReport($maxLevel);
        
        return view('admin.reports.level-wise', compact('levelData'));
    }

    /**
     * User-Wise Referral Report
     */
    public function userWise(Request $request)
    {
        $filters = $request->only([
            'user_id',
            'referral_id',
            'date_from',
            'date_to',
            'min_referrals',
            'max_referrals'
        ]);
        
        $users = $this->reportService->getUserWiseReport($filters);
        
        return view('admin.reports.user-wise', compact('users', 'filters'));
    }

    /**
     * Reference → Sub-Reference Drill-Down Report
     */
    public function drillDown(Request $request, $userId = null)
    {
        if ($userId) {
            $data = $this->reportService->getDrillDownData($userId);
            
            if ($request->ajax()) {
                return response()->json($data);
            }
        }
        
        // Get root users for initial view
        $rootUsers = User::whereNull('sponsor_referral_id')
            ->withCount('referrals')
            ->select('id', 'name', 'referral_id')
            ->orderBy('name')
            ->get();
        
        return view('admin.reports.drill-down', compact('rootUsers'));
    }

    /**
     * Dynamic Growth Report
     */
    public function growth(Request $request)
    {
        $period = $request->input('period', 'monthly');
        $months = $request->input('months', 6);
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        
        $growthData = $this->reportService->getGrowthReport($period, $months, $dateFrom, $dateTo);
        
        // Handle export
        if ($request->has('export')) {
            $format = $request->input('export');
            if ($format === 'csv') {
                return $this->exportGrowthCSV($growthData, $period);
            } elseif ($format === 'excel') {
                return $this->exportGrowthExcel($growthData, $period);
            }
        }
        
        if ($request->ajax()) {
            return response()->json($growthData);
        }
        
        return view('admin.reports.growth', compact('growthData', 'period', 'months'));
    }

    /**
     * Zero / Inactive Reference Report
     */
    public function inactive(Request $request)
    {
        $filters = $request->only(['inactive_days', 'has_referrals']);
        $users = $this->reportService->getInactiveReport($filters);
        
        return view('admin.reports.inactive', compact('users', 'filters'));
    }

    /**
     * Export Summary Report
     */
    public function exportSummary(Request $request)
    {
        $format = $request->input('format', 'csv');
        $summary = $this->reportService->getSummary();
        $levelData = $this->reportService->getLevelWiseReport();
        
        if ($format === 'csv') {
            return $this->exportSummaryCSV($summary, $levelData);
        } elseif ($format === 'excel') {
            return $this->exportSummaryExcel($summary, $levelData);
        } elseif ($format === 'pdf') {
            return $this->exportSummaryPDF($summary, $levelData);
        }
        
        return redirect()->back()->with('error', 'Invalid export format');
    }

    /**
     * Export Level-Wise Report
     */
    public function exportLevelWise(Request $request)
    {
        $format = $request->input('format', 'csv');
        $levelData = $this->reportService->getLevelWiseReport();
        
        if ($format === 'csv') {
            return $this->exportLevelWiseCSV($levelData);
        }
        
        return redirect()->back()->with('error', 'Invalid export format');
    }

    /**
     * Export User-Wise Report
     */
    public function exportUserWise(Request $request)
    {
        $format = $request->input('format', 'csv');
        $filters = $request->only([
            'user_id',
            'referral_id',
            'date_from',
            'date_to',
            'min_referrals',
            'max_referrals'
        ]);
        
        $users = $this->reportService->getUserWiseReport($filters);
        
        if ($format === 'csv') {
            return $this->exportUserWiseCSV($users);
        }
        
        return redirect()->back()->with('error', 'Invalid export format');
    }

    /**
     * Export Inactive Report
     */
    public function exportInactive(Request $request)
    {
        $format = $request->input('format', 'csv');
        $filters = $request->only(['inactive_days', 'has_referrals']);
        $users = $this->reportService->getInactiveReport($filters);
        
        if ($format === 'csv') {
            return $this->exportInactiveCSV($users);
        }
        
        return redirect()->back()->with('error', 'Invalid export format');
    }

    /**
     * Clear report cache
     */
    public function clearCache()
    {
        $this->reportService->clearCache();
        
        return redirect()->back()->with('success', 'Report cache cleared successfully');
    }

    // ==================== CSV Export Methods ====================

    private function exportSummaryCSV($summary, $levelData)
    {
        $filename = 'referral-summary-' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($summary, $levelData) {
            $file = fopen('php://output', 'w');
            
            // Summary section
            fputcsv($file, ['REFERRAL SUMMARY REPORT']);
            fputcsv($file, ['Generated', date('Y-m-d H:i:s')]);
            fputcsv($file, []);
            
            fputcsv($file, ['Metric', 'Value']);
            fputcsv($file, ['Total Users', $summary['total_users']]);
            fputcsv($file, ['Total References', $summary['total_references']]);
            fputcsv($file, ['Total Sub-References', $summary['total_sub_references']]);
            fputcsv($file, ['Root Users', $summary['root_users']]);
            fputcsv($file, ['Maximum Depth', 'Level ' . $summary['max_depth']]);
            fputcsv($file, ['Average Referrals/User', $summary['avg_referrals_per_user']]);
            fputcsv($file, ['Growth Rate (30d)', $summary['growth_rate_30d'] . '%']);
            fputcsv($file, ['Active Users', $summary['active_users']]);
            fputcsv($file, ['Inactive Users', $summary['inactive_users']]);
            
            fputcsv($file, []);
            fputcsv($file, ['LEVEL-WISE DISTRIBUTION']);
            fputcsv($file, ['Level', 'Users Count', 'Type', 'Percentage']);
            
            foreach ($levelData as $level) {
                fputcsv($file, [
                    'Level ' . $level['level'],
                    $level['count'],
                    $level['type'],
                    $level['percentage'] . '%'
                ]);
            }
            
            fclose($file);
        };
        
        return Response::stream($callback, 200, $headers);
    }

    private function exportLevelWiseCSV($levelData)
    {
        $filename = 'level-wise-report-' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($levelData) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, ['LEVEL-WISE REFERRAL REPORT']);
            fputcsv($file, ['Generated', date('Y-m-d H:i:s')]);
            fputcsv($file, []);
            fputcsv($file, ['Level', 'Users Count', 'Type', 'Percentage']);
            
            foreach ($levelData as $level) {
                fputcsv($file, [
                    'Level ' . $level['level'],
                    $level['count'],
                    $level['type'],
                    $level['percentage'] . '%'
                ]);
            }
            
            fclose($file);
        };
        
        return Response::stream($callback, 200, $headers);
    }

    private function exportUserWiseCSV($users)
    {
        $filename = 'user-wise-report-' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, ['USER-WISE REFERRAL REPORT']);
            fputcsv($file, ['Generated', date('Y-m-d H:i:s')]);
            fputcsv($file, []);
            fputcsv($file, ['User Name', 'Referral ID', 'Level', 'Direct Refs', 'Sub Refs', 'Total Network', 'Registered', 'Last Login']);
            
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->name,
                    $user->referral_id,
                    'Level ' . $user->level,
                    $user->direct_referrals,
                    $user->sub_referrals,
                    $user->total_network,
                    $user->created_at->format('Y-m-d'),
                    $user->last_login_at ? $user->last_login_at->format('Y-m-d') : 'Never'
                ]);
            }
            
            fclose($file);
        };
        
        return Response::stream($callback, 200, $headers);
    }

    private function exportInactiveCSV($users)
    {
        $filename = 'inactive-users-report-' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, ['INACTIVE USERS REPORT']);
            fputcsv($file, ['Generated', date('Y-m-d H:i:s')]);
            fputcsv($file, []);
            fputcsv($file, ['User Name', 'Referral ID', 'Direct Refs', 'Sub Refs', 'Days Inactive', 'Registered', 'Last Login']);
            
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->name,
                    $user->referral_id,
                    $user->referrals_count,
                    $user->sub_referrals_count,
                    $user->days_inactive,
                    $user->created_at->format('Y-m-d'),
                    $user->last_login_at ? $user->last_login_at->format('Y-m-d') : 'Never'
                ]);
            }
            
            fclose($file);
        };
        
        return Response::stream($callback, 200, $headers);
    }

    private function exportGrowthCSV($growthData, $period)
    {
        $filename = 'growth-report-' . $period . '-' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($growthData, $period) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, ['GROWTH REPORT - ' . strtoupper($period)]);
            fputcsv($file, ['Generated', date('Y-m-d H:i:s')]);
            fputcsv($file, []);
            
            fputcsv($file, ['Summary']);
            fputcsv($file, ['Total Growth', $growthData['total_growth']]);
            fputcsv($file, ['Data Points', count($growthData['timeline'])]);
            fputcsv($file, []);
            
            fputcsv($file, ['Timeline Data']);
            fputcsv($file, ['Period', 'New Users', 'Date']);
            
            foreach ($growthData['timeline'] as $point) {
                fputcsv($file, [
                    $point['label'],
                    $point['count'],
                    $point['date'] ?? ($point['week_start'] ?? ($point['month'] ?? ''))
                ]);
            }
            
            if (!empty($growthData['level_growth'])) {
                fputcsv($file, []);
                fputcsv($file, ['Level-Wise Growth']);
                fputcsv($file, ['Level', 'New Users']);
                
                foreach ($growthData['level_growth'] as $levelGrowth) {
                    fputcsv($file, [
                        'Level ' . $levelGrowth['level'],
                        $levelGrowth['new_users']
                    ]);
                }
            }
            
            fclose($file);
        };
        
        return Response::stream($callback, 200, $headers);
    }

    private function exportGrowthExcel($growthData, $period)
    {
        // For now, use CSV format until Laravel Excel is fully configured
        return $this->exportGrowthCSV($growthData, $period);
    }

    // Placeholder methods for Excel and PDF exports
    private function exportSummaryExcel($summary, $levelData)
    {
        // TODO: Implement Excel export using Laravel Excel package
        return redirect()->back()->with('info', 'Excel export coming soon');
    }

    private function exportSummaryPDF($summary, $levelData)
    {
        // TODO: Implement PDF export using DomPDF or similar
        return redirect()->back()->with('info', 'PDF export coming soon');
    }
}
