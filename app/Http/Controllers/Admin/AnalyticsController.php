<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Episode;
use App\Models\Series;
use App\Models\Transaction;
use App\Models\User;
use App\Models\ViewingEvent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    /**
     * Display analytics dashboard with 7, 30, and 90 days filters
     */
    public function index(Request $request): View
    {
        $days = (int) $request->input('range', 30);
        if (!in_array($days, [7, 30, 90], true)) {
            $days = 30;
        }

        $startDate = Carbon::now()->subDays($days)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        // 1. Total Views in range
        $totalViews = ViewingEvent::whereBetween('created_at', [$startDate, $endDate])->count();

        // 2. Unique Viewers (distinct user_id or IP address)
        $uniqueUsers = ViewingEvent::whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('user_id')
            ->distinct('user_id')
            ->count('user_id');

        $uniqueGuests = ViewingEvent::whereBetween('created_at', [$startDate, $endDate])
            ->whereNull('user_id')
            ->distinct('ip_address')
            ->count('ip_address');

        $uniqueViewers = $uniqueUsers + $uniqueGuests;

        // 3. Watch Time in range (in seconds)
        $totalWatchSeconds = (int) ViewingEvent::whereBetween('created_at', [$startDate, $endDate])
            ->sum('watched_seconds');

        $watchHours = floor($totalWatchSeconds / 3600);
        $watchMinutes = floor(($totalWatchSeconds % 3600) / 60);
        $formattedWatchTime = "{$watchHours}j {$watchMinutes}m";

        // 4. Average Watch Duration
        $avgDurationSeconds = $totalViews > 0 ? (int) round($totalWatchSeconds / $totalViews) : 0;
        $avgMinutes = floor($avgDurationSeconds / 60);
        $avgSecs = $avgDurationSeconds % 60;
        $formattedAvgDuration = "{$avgMinutes}m {$avgSecs}s";

        // 5. Conversion Rate: (New paid subscribers / Total registered users active in range)
        $totalUsersInRange = User::whereBetween('created_at', [$startDate, $endDate])->count();
        $successfulTransactionsInRange = Transaction::where('status', 'success')
            ->whereBetween('verified_at', [$startDate, $endDate])
            ->count();

        $conversionRate = $totalUsersInRange > 0
            ? round(($successfulTransactionsInRange / $totalUsersInRange) * 100, 1)
            : 0.0;

        // 6. Top 5 Series by View Count
        $topSeries = Series::withCount(['viewingEvents' => function ($q) use ($startDate, $endDate) {
            $q->whereBetween('created_at', [$startDate, $endDate]);
        }])
            ->with(['categories'])
            ->orderByDesc('viewing_events_count')
            ->limit(5)
            ->get();

        // 7. Top 5 Episodes by Likes
        $topLikedEpisodes = Episode::with('series')
            ->withCount('likes')
            ->orderByDesc('likes_count')
            ->limit(5)
            ->get();

        // 8. Daily Views Chart Data across the selected range
        $chartLabels = [];
        $chartViews = [];

        // For 7 days, step by 1 day; for 30 days, step by 1 day; for 90 days, step by 3 days
        $stepDays = $days === 90 ? 3 : 1;
        $intervals = (int) ceil($days / $stepDays);

        for ($i = $intervals - 1; $i >= 0; $i--) {
            $subDaysFrom = $i * $stepDays;
            $subDaysTo = max(0, ($i - 1) * $stepDays);

            $dateFrom = Carbon::now()->subDays($subDaysFrom)->startOfDay();
            $dateTo = Carbon::now()->subDays($subDaysTo)->endOfDay();

            $chartLabels[] = $dateFrom->format('d M');
            $vCount = ViewingEvent::whereBetween('created_at', [$dateFrom, $dateTo])->count();
            $chartViews[] = $vCount;
        }

        return view('admin.analytics', compact(
            'days',
            'totalViews',
            'uniqueViewers',
            'formattedWatchTime',
            'formattedAvgDuration',
            'conversionRate',
            'topSeries',
            'topLikedEpisodes',
            'chartLabels',
            'chartViews'
        ));
    }
}
