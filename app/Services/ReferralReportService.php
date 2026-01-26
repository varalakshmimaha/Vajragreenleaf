<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ReferralReportService
{
    /**
     * Get comprehensive referral summary statistics
     */
    public function getSummary()
    {
        return Cache::remember('referral_summary', 3600, function () {
            $totalUsers = User::count();
            $totalReferences = User::whereNotNull('sponsor_referral_id')->count();
            $rootUsers = User::whereNull('sponsor_referral_id')->count();
            
            // Calculate max depth
            $maxDepth = $this->calculateMaxDepth();
            
            // Calculate average referrals per user
            $usersWithReferrals = User::withCount('referrals')->get();
            $avgReferrals = $usersWithReferrals->avg('referrals_count') ?? 0;
            
            // Calculate total sub-references (Level 2+)
            $totalSubReferences = $this->calculateSubReferences();
            
            // Get growth rate (last 30 days)
            $growthRate = $this->calculateGrowthRate();
            
            return [
                'total_users' => $totalUsers,
                'total_references' => $totalReferences,
                'total_sub_references' => $totalSubReferences,
                'root_users' => $rootUsers,
                'max_depth' => $maxDepth,
                'avg_referrals_per_user' => round($avgReferrals, 2),
                'growth_rate_30d' => $growthRate,
                'active_users' => User::where('is_active', true)->count(),
                'inactive_users' => User::where('is_active', false)->count(),
            ];
        });
    }

    /**
     * Get level-wise distribution of users
     */
    public function getLevelWiseReport($maxLevel = null)
    {
        $levelData = [];
        $maxDepth = $maxLevel ?? $this->calculateMaxDepth();
        
        for ($level = 1; $level <= $maxDepth; $level++) {
            $users = $this->getUsersAtLevel($level);
            $levelData[] = [
                'level' => $level,
                'count' => count($users),
                'type' => $level === 1 ? 'Direct' : 'Sub',
                'percentage' => 0, // Will calculate after we have total
                'users' => $users,
            ];
        }
        
        // Calculate percentages
        $totalUsers = array_sum(array_column($levelData, 'count'));
        foreach ($levelData as &$level) {
            $level['percentage'] = $totalUsers > 0 ? round(($level['count'] / $totalUsers) * 100, 2) : 0;
        }
        
        return $levelData;
    }

    /**
     * Get user-wise referral report with filters
     */
    public function getUserWiseReport($filters = [])
    {
        $query = User::query();
        
        // Apply filters
        if (!empty($filters['user_id'])) {
            $query->where('id', $filters['user_id']);
        }
        
        if (!empty($filters['referral_id'])) {
            $query->where('referral_id', 'like', '%' . $filters['referral_id'] . '%');
        }
        
        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }
        
        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }
        
        if (isset($filters['min_referrals'])) {
            $query->has('referrals', '>=', $filters['min_referrals']);
        }
        
        if (isset($filters['max_referrals'])) {
            $query->has('referrals', '<=', $filters['max_referrals']);
        }
        
        // Get users with referral counts
        $users = $query->withCount('referrals')
            ->with('sponsorByReferralId:id,name,referral_id')
            ->select('id', 'name', 'email', 'referral_id', 'sponsor_referral_id', 'created_at', 'last_login_at')
            ->orderBy('created_at', 'desc')
            ->paginate(50);
        
        // Enhance with network size
        $users->getCollection()->transform(function ($user) {
            $user->direct_referrals = $user->referrals_count;
            $user->sub_referrals = $this->getSubReferralsCount($user->id);
            $user->total_network = $user->direct_referrals + $user->sub_referrals;
            $user->level = $this->getUserLevel($user->id);
            return $user;
        });
        
        return $users;
    }

    /**
     * Get drill-down data for a specific user
     */
    public function getDrillDownData($userId, $maxDepth = 3)
    {
        $user = User::with(['referrals' => function ($query) {
            $query->select('id', 'name', 'referral_id', 'sponsor_referral_id', 'created_at')
                  ->withCount('referrals');
        }])->find($userId);
        
        if (!$user) {
            return null;
        }
        
        return [
            'id' => $user->id,
            'name' => $user->name,
            'referral_id' => $user->referral_id,
            'direct_referrals_count' => $user->referrals->count(),
            'children' => $user->referrals->map(function ($child) use ($maxDepth) {
                return [
                    'id' => $child->id,
                    'name' => $child->name,
                    'referral_id' => $child->referral_id,
                    'direct_referrals_count' => $child->referrals_count,
                    'has_children' => $child->referrals_count > 0,
                    'created_at' => $child->created_at->format('Y-m-d'),
                ];
            }),
        ];
    }

    /**
     * Get growth report data
     */
    public function getGrowthReport($period = 'monthly', $months = 6, $dateFrom = null, $dateTo = null)
    {
        $data = [];
        
        // If custom date range is provided, use it
        if ($dateFrom && $dateTo) {
            $startDate = \Carbon\Carbon::parse($dateFrom);
            $endDate = \Carbon\Carbon::parse($dateTo);
            
            if ($period === 'daily') {
                $currentDate = $startDate->copy();
                while ($currentDate->lte($endDate)) {
                    $date = $currentDate->format('Y-m-d');
                    $count = User::whereDate('created_at', $date)->count();
                    $data[] = [
                        'label' => $currentDate->format('M d'),
                        'date' => $date,
                        'count' => $count,
                    ];
                    $currentDate->addDay();
                }
            } elseif ($period === 'weekly') {
                $currentDate = $startDate->copy()->startOfWeek();
                while ($currentDate->lte($endDate)) {
                    $weekStart = $currentDate->copy();
                    $weekEnd = $currentDate->copy()->endOfWeek();
                    $count = User::whereBetween('created_at', [$weekStart, $weekEnd])->count();
                    $data[] = [
                        'label' => 'Week ' . $weekStart->format('M d'),
                        'week_start' => $weekStart->format('Y-m-d'),
                        'count' => $count,
                    ];
                    $currentDate->addWeek();
                }
            } else {
                // Monthly
                $currentDate = $startDate->copy()->startOfMonth();
                while ($currentDate->lte($endDate)) {
                    $count = User::whereYear('created_at', $currentDate->year)
                        ->whereMonth('created_at', $currentDate->month)
                        ->count();
                    $data[] = [
                        'label' => $currentDate->format('M Y'),
                        'month' => $currentDate->format('Y-m'),
                        'count' => $count,
                    ];
                    $currentDate->addMonth();
                }
            }
            
            $startDateForGrowth = $startDate;
        } else {
            // Use default period-based logic
            $startDate = now()->subMonths($months);
            
            if ($period === 'daily') {
                // Last 30 days
                for ($i = 29; $i >= 0; $i--) {
                    $date = now()->subDays($i)->format('Y-m-d');
                    $count = User::whereDate('created_at', $date)->count();
                    $data[] = [
                        'label' => now()->subDays($i)->format('M d'),
                        'date' => $date,
                        'count' => $count,
                    ];
                }
            } elseif ($period === 'weekly') {
                // Last 12 weeks
                for ($i = 11; $i >= 0; $i--) {
                    $weekStart = now()->subWeeks($i)->startOfWeek();
                    $weekEnd = now()->subWeeks($i)->endOfWeek();
                    $count = User::whereBetween('created_at', [$weekStart, $weekEnd])->count();
                    $data[] = [
                        'label' => 'Week ' . $weekStart->format('M d'),
                        'week_start' => $weekStart->format('Y-m-d'),
                        'count' => $count,
                    ];
                }
            } else {
                // Monthly (default)
                for ($i = $months - 1; $i >= 0; $i--) {
                    $month = now()->subMonths($i);
                    $count = User::whereYear('created_at', $month->year)
                        ->whereMonth('created_at', $month->month)
                        ->count();
                    $data[] = [
                        'label' => $month->format('M Y'),
                        'month' => $month->format('Y-m'),
                        'count' => $count,
                    ];
                }
            }
            
            $startDateForGrowth = $startDate;
        }
        
        // Add level-wise growth for current period
        $levelGrowth = $this->getLevelWiseGrowth($startDateForGrowth);
        
        return [
            'timeline' => $data,
            'level_growth' => $levelGrowth,
            'total_growth' => array_sum(array_column($data, 'count')),
        ];
    }

    /**
     * Get inactive users report
     */
    public function getInactiveReport($filters = [])
    {
        $inactiveDays = $filters['inactive_days'] ?? 30;
        $inactiveDate = now()->subDays($inactiveDays);
        
        $query = User::query();
        
        // Users with zero referrals OR inactive for X days
        $query->where(function ($q) use ($inactiveDate) {
            $q->doesntHave('referrals')
              ->orWhere(function ($subQ) use ($inactiveDate) {
                  $subQ->where('last_login_at', '<', $inactiveDate)
                       ->orWhereNull('last_login_at');
              });
        });
        
        if (!empty($filters['has_referrals'])) {
            if ($filters['has_referrals'] === 'zero') {
                $query->doesntHave('referrals');
            } elseif ($filters['has_referrals'] === 'no_sub') {
                $query->has('referrals')->whereDoesntHave('referrals.referrals');
            }
        }
        
        $users = $query->withCount('referrals')
            ->select('id', 'name', 'email', 'referral_id', 'created_at', 'last_login_at')
            ->orderBy('last_login_at', 'asc')
            ->paginate(50);
        
        $users->getCollection()->transform(function ($user) {
            $user->days_inactive = $user->last_login_at 
                ? now()->diffInDays($user->last_login_at) 
                : now()->diffInDays($user->created_at);
            $user->sub_referrals_count = $this->getSubReferralsCount($user->id);
            return $user;
        });
        
        return $users;
    }

    /**
     * Calculate maximum referral depth
     */
    private function calculateMaxDepth()
    {
        $maxDepth = 0;
        $rootUsers = User::whereNull('sponsor_referral_id')->get();
        
        foreach ($rootUsers as $root) {
            $depth = $this->getMaxDepthForUser($root->id);
            if ($depth > $maxDepth) {
                $maxDepth = $depth;
            }
        }
        
        return $maxDepth;
    }

    /**
     * Get maximum depth for a specific user
     */
    private function getMaxDepthForUser($userId, $currentDepth = 0)
    {
        $children = User::where('sponsor_referral_id', function ($query) use ($userId) {
            $query->select('referral_id')
                  ->from('users')
                  ->where('id', $userId);
        })->pluck('id');
        
        if ($children->isEmpty()) {
            return $currentDepth;
        }
        
        $maxChildDepth = $currentDepth;
        foreach ($children as $childId) {
            $childDepth = $this->getMaxDepthForUser($childId, $currentDepth + 1);
            if ($childDepth > $maxChildDepth) {
                $maxChildDepth = $childDepth;
            }
        }
        
        return $maxChildDepth;
    }

    /**
     * Get users at a specific level
     */
    private function getUsersAtLevel($level, $parentIds = null)
    {
        if ($level === 1) {
            // Level 1: Users with no sponsor (root users)
            return User::whereNull('sponsor_referral_id')
                ->select('id', 'name', 'referral_id')
                ->get()
                ->toArray();
        }
        
        // For level > 1, we need to traverse the tree
        if ($parentIds === null) {
            $parentIds = User::whereNull('sponsor_referral_id')->pluck('id');
        }
        
        for ($i = 1; $i < $level; $i++) {
            $parentIds = User::whereIn('sponsor_referral_id', function ($query) use ($parentIds) {
                $query->select('referral_id')
                      ->from('users')
                      ->whereIn('id', $parentIds);
            })->pluck('id');
            
            if ($parentIds->isEmpty()) {
                return [];
            }
        }
        
        return User::whereIn('sponsor_referral_id', function ($query) use ($parentIds) {
            $query->select('referral_id')
                  ->from('users')
                  ->whereIn('id', $parentIds);
        })->select('id', 'name', 'referral_id')->get()->toArray();
    }

    /**
     * Calculate total sub-references (Level 2+)
     */
    private function calculateSubReferences()
    {
        $total = 0;
        $maxDepth = $this->calculateMaxDepth();
        
        for ($level = 2; $level <= $maxDepth; $level++) {
            $total += count($this->getUsersAtLevel($level));
        }
        
        return $total;
    }

    /**
     * Get sub-referrals count for a user (excluding direct referrals)
     */
    private function getSubReferralsCount($userId)
    {
        $user = User::find($userId);
        if (!$user) {
            return 0;
        }
        
        $allReferrals = $user->getAllReferrals();
        $directCount = $user->referrals()->count();
        
        return count($allReferrals) - $directCount;
    }

    /**
     * Get user's level in the referral tree
     */
    private function getUserLevel($userId)
    {
        $user = User::find($userId);
        if (!$user || !$user->sponsor_referral_id) {
            return 0; // Root user
        }
        
        $level = 1;
        $currentSponsorId = $user->sponsor_referral_id;
        
        while ($currentSponsorId) {
            $sponsor = User::where('referral_id', $currentSponsorId)->first();
            if (!$sponsor || !$sponsor->sponsor_referral_id) {
                break;
            }
            $currentSponsorId = $sponsor->sponsor_referral_id;
            $level++;
        }
        
        return $level;
    }

    /**
     * Calculate growth rate for last 30 days
     */
    private function calculateGrowthRate()
    {
        $last30Days = User::where('created_at', '>=', now()->subDays(30))->count();
        $previous30Days = User::whereBetween('created_at', [
            now()->subDays(60),
            now()->subDays(30)
        ])->count();
        
        if ($previous30Days == 0) {
            return $last30Days > 0 ? 100 : 0;
        }
        
        return round((($last30Days - $previous30Days) / $previous30Days) * 100, 2);
    }

    /**
     * Get level-wise growth since a date
     */
    private function getLevelWiseGrowth($sinceDate)
    {
        $levels = [];
        $maxDepth = $this->calculateMaxDepth();
        
        for ($level = 1; $level <= min($maxDepth, 5); $level++) {
            $users = $this->getUsersAtLevel($level);
            $newUsers = User::whereIn('id', array_column($users, 'id'))
                ->where('created_at', '>=', $sinceDate)
                ->count();
            
            $levels[] = [
                'level' => $level,
                'new_users' => $newUsers,
            ];
        }
        
        return $levels;
    }

    /**
     * Clear cached reports
     */
    public function clearCache()
    {
        Cache::forget('referral_summary');
    }
}
