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

        $data = $this->getAnalyticsData($days);

        return view('admin.analytics', $data);
    }

    /**
     * Provide real-time analytics data as JSON for live updates
     */
    public function realtimeData(Request $request): \Illuminate\Http\JsonResponse
    {
        $days = (int) $request->input('range', 30);
        if (!in_array($days, [7, 30, 90], true)) {
            $days = 30;
        }

        $data = $this->getAnalyticsData($days);

        // Format topSeries and topLikedEpisodes for clean JSON serialization
        $topSeriesList = $data['topSeries']->map(function ($item, $idx) {
            return [
                'rank' => $idx + 1,
                'id' => $item->id,
                'title' => $item->title,
                'release_year' => $item->release_year,
                'thumbnail_url' => $item->thumbnail_url,
                'genre' => $item->categories->first()?->name ?? 'Komedi',
                'views_count' => (int) ($item->viewing_events_count ?? 0),
                'views_formatted' => number_format($item->viewing_events_count ?? 0, 0, ',', '.'),
                'url' => route('admin.series.show', $item->id),
            ];
        });

        $topLikedList = $data['topLikedEpisodes']->map(function ($ep, $idx) {
            return [
                'rank' => $idx + 1,
                'id' => $ep->id,
                'episode_number' => $ep->episode_number,
                'title' => $ep->title,
                'series_title' => $ep->series?->title ?? '-',
                'is_free' => (bool) $ep->is_free,
                'likes_count' => (int) $ep->likes_count,
                'likes_formatted' => number_format($ep->likes_count, 0, ',', '.'),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'days' => $data['days'],
                'totalViews' => $data['totalViews'],
                'totalViewsFormatted' => number_format($data['totalViews'], 0, ',', '.'),
                'uniqueViewers' => $data['uniqueViewers'],
                'uniqueViewersFormatted' => number_format($data['uniqueViewers'], 0, ',', '.'),
                'totalWatchSeconds' => $data['totalWatchSeconds'],
                'formattedWatchTime' => $data['formattedWatchTime'],
                'avgDurationSeconds' => $data['avgDurationSeconds'],
                'formattedAvgDuration' => $data['formattedAvgDuration'],
                'conversionRate' => $data['conversionRate'],
                'chartLabels' => $data['chartLabels'],
                'chartViews' => $data['chartViews'],
                'topSeries' => $topSeriesList,
                'topLikedEpisodes' => $topLikedList,
                'lastUpdated' => Carbon::now()->format('H:i:s'),
            ],
        ]);
    }

    /**
     * Compute analytics metrics for a given date range
     */
    protected function getAnalyticsData(int $days): array
    {
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
        $watchSecs = $totalWatchSeconds % 60;

        if ($watchHours > 0) {
            $formattedWatchTime = "{$watchHours}j {$watchMinutes}m";
        } else {
            $formattedWatchTime = "{$watchMinutes}m {$watchSecs}s";
        }

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

        return compact(
            'days',
            'totalViews',
            'uniqueViewers',
            'totalWatchSeconds',
            'formattedWatchTime',
            'avgDurationSeconds',
            'formattedAvgDuration',
            'conversionRate',
            'topSeries',
            'topLikedEpisodes',
            'chartLabels',
            'chartViews'
        );
    }
}
