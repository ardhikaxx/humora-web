<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Episode;
use App\Models\Series;
use App\Models\Transaction;
use App\Models\User;
use App\Models\ViewingEvent;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the Admin Dashboard with real database statistics and Chart.js datasets
     */
    public function index(): View
    {
        // 1. Heavy Aggregate KPIs & Charts (Cached for 2 minutes to keep admin load instant)
        $cachedStats = Cache::remember('admin_dashboard_stats', 120, function () {
            $now = Carbon::now();

            $totalEpisodes = Episode::count();
            $totalSeries = Series::count();
            $totalUsers = User::count();

            $activeSubscribers = User::where('tier', 'premium')
                ->where('status', 'active')
                ->where('valid_until', '>=', $now)
                ->count();

            $pendingTransactionsCount = Transaction::where('status', 'pending')->count();

            // Revenue from verified (status: success) transactions ONLY
            $totalRevenue = (float) Transaction::where('status', 'success')->sum('amount');

            // Chart 1: Daily Viewing Trends for the last 7 days (Aggregated in 1 query)
            $sevenDaysAgo = Carbon::now()->subDays(6)->startOfDay();
            $dailyViews = ViewingEvent::where('created_at', '>=', $sevenDaysAgo)
                ->select(DB::raw('DATE(created_at) as view_date'), DB::raw('COUNT(*) as total'))
                ->groupBy('view_date')
                ->pluck('total', 'view_date');

            $dailyViewLabels = [];
            $dailyViewCounts = [];

            for ($i = 6; $i >= 0; $i--) {
                $dateObj = Carbon::now()->subDays($i);
                $dailyViewLabels[] = $dateObj->isoFormat('dddd, D MMM');
                $dailyViewCounts[] = (int) ($dailyViews->get($dateObj->format('Y-m-d')) ?? 0);
            }

            // Chart 2: Weekly Subscriber Conversions (Last 4 weeks) (Aggregated in 1 query)
            $fourWeeksAgo = Carbon::now()->subWeeks(3)->startOfWeek();
            $successfulTxns = Transaction::where('status', 'success')
                ->where('verified_at', '>=', $fourWeeksAgo)
                ->get(['verified_at', 'amount']);

            $weeklyLabels = [];
            $weeklyConversions = [];
            $weeklyRevenue = [];

            for ($w = 3; $w >= 0; $w--) {
                $startWeek = Carbon::now()->subWeeks($w)->startOfWeek();
                $endWeek = Carbon::now()->subWeeks($w)->endOfWeek();

                $weeklyLabels[] = 'Mgg '.$startWeek->format('d M').' - '.$endWeek->format('d M');

                $weekTxns = $successfulTxns->filter(function ($t) use ($startWeek, $endWeek) {
                    return $t->verified_at && $t->verified_at >= $startWeek && $t->verified_at <= $endWeek;
                });

                $weeklyConversions[] = $weekTxns->count();
                $weeklyRevenue[] = (float) $weekTxns->sum('amount');
            }

            return compact(
                'totalEpisodes',
                'totalSeries',
                'totalUsers',
                'activeSubscribers',
                'pendingTransactionsCount',
                'totalRevenue',
                'dailyViewLabels',
                'dailyViewCounts',
                'weeklyLabels',
                'weeklyConversions',
                'weeklyRevenue'
            );
        });

        // 2. Fresh Lists (Always real-time for immediate admin actions)
        $pendingTransactions = Transaction::with(['user', 'plan'])
            ->where('status', 'pending')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        $recentSeries = Series::with(['categories', 'publishedEpisodes'])
            ->where('status', 'published')
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        return view('admin.dashboard', array_merge($cachedStats, compact('pendingTransactions', 'recentSeries')));
    }
}
