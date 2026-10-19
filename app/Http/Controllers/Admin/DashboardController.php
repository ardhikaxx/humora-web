<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Episode;
use App\Models\Series;
use App\Models\Transaction;
use App\Models\User;
use App\Models\ViewingEvent;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the Admin Dashboard with real database statistics and Chart.js datasets
     */
    public function index(): View
    {
        $now = Carbon::now();

        // Real Database KPIs
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

        // Recent pending transactions needing verification
        $pendingTransactions = Transaction::with(['user', 'plan'])
            ->where('status', 'pending')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        // Recent published series
        $recentSeries = Series::with(['categories', 'publishedEpisodes'])
            ->where('status', 'published')
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        // Chart 1: Daily Viewing Trends for the last 7 days
        $dailyViewLabels = [];
        $dailyViewCounts = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $dailyViewLabels[] = Carbon::now()->subDays($i)->isoFormat('dddd, D MMM');

            $views = ViewingEvent::whereDate('created_at', $date)->count();
            $dailyViewCounts[] = $views;
        }

        // Chart 2: Weekly Subscriber Conversions (Last 4 weeks)
        $weeklyLabels = [];
        $weeklyConversions = [];
        $weeklyRevenue = [];

        for ($w = 3; $w >= 0; $w--) {
            $startWeek = Carbon::now()->subWeeks($w)->startOfWeek();
            $endWeek = Carbon::now()->subWeeks($w)->endOfWeek();

            $weeklyLabels[] = 'Mgg ' . $startWeek->format('d M') . ' - ' . $endWeek->format('d M');

            $count = Transaction::where('status', 'success')
                ->whereBetween('verified_at', [$startWeek, $endWeek])
                ->count();
            $weeklyConversions[] = $count;

            $rev = (float) Transaction::where('status', 'success')
                ->whereBetween('verified_at', [$startWeek, $endWeek])
                ->sum('amount');
            $weeklyRevenue[] = $rev;
        }

        return view('admin.dashboard', compact(
            'totalEpisodes',
            'totalSeries',
            'totalUsers',
            'activeSubscribers',
            'pendingTransactionsCount',
            'totalRevenue',
            'pendingTransactions',
            'recentSeries',
            'dailyViewLabels',
            'dailyViewCounts',
            'weeklyLabels',
            'weeklyConversions',
            'weeklyRevenue'
        ));
    }
}
