<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Episode;
use App\Models\Series;
use App\Models\Transaction;
use App\Models\User;
use App\Models\ViewingEvent;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
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
        if (! in_array($days, [7, 30, 90], true)) {
            $days = 30;
        }

        $data = $this->getAnalyticsData($days);

        return view('admin.analytics', $data);
    }

    /**
     * Provide real-time analytics data as JSON for live updates
     */
    public function realtimeData(Request $request): JsonResponse
    {
        $days = (int) $request->input('range', 30);
        if (! in_array($days, [7, 30, 90], true)) {
            $days = 30;
        }

        $data = $this->getAnalyticsData($days);

        // Format topSeries and topLikedEpisodes for clean JSON serialization
        $topSeriesList = collect($data['topSeries'])->map(function ($item, $idx) {
            return [
                'rank' => $idx + 1,
                'id' => data_get($item, 'id'),
                'title' => data_get($item, 'title'),
                'release_year' => data_get($item, 'release_year'),
                'thumbnail_url' => data_get($item, 'thumbnail_url'),
                'genre' => data_get($item, 'genre', 'Komedi'),
                'views_count' => (int) data_get($item, 'viewing_events_count', 0),
                'views_formatted' => number_format(data_get($item, 'viewing_events_count', 0), 0, ',', '.'),
                'url' => route('admin.series.show', data_get($item, 'id')),
            ];
        });

        $topLikedList = collect($data['topLikedEpisodes'])->map(function ($ep, $idx) {
            return [
                'rank' => $idx + 1,
                'id' => data_get($ep, 'id'),
                'episode_number' => data_get($ep, 'episode_number'),
                'title' => data_get($ep, 'title'),
                'series_title' => data_get($ep, 'series_title', '-'),
                'is_free' => (bool) data_get($ep, 'is_free'),
                'likes_count' => (int) data_get($ep, 'likes_count', 0),
                'likes_formatted' => number_format(data_get($ep, 'likes_count', 0), 0, ',', '.'),
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
     * Compute analytics metrics for a given date range (Cached for 5 minutes)
     */
    protected function getAnalyticsData(int $days): array
    {
        return Cache::remember("admin_analytics_data_{$days}", 300, function () use ($days) {
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

            // 6. Top 5 Series by View Count (Mapped to pure arrays for 100% safe, fast serialization)
            $topSeries = Series::withCount(['viewingEvents' => function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            }])
                ->with(['categories'])
                ->orderByDesc('viewing_events_count')
                ->limit(5)
                ->get()
                ->map(fn ($item) => [
                    'id' => $item->id,
                    'title' => $item->title,
                    'release_year' => $item->release_year,
                    'thumbnail_url' => $item->thumbnail_url,
                    'genre' => $item->categories->first()?->name ?? 'Komedi',
                    'viewing_events_count' => (int) ($item->viewing_events_count ?? 0),
                ])
                ->values()
                ->all();

            // 7. Top 5 Episodes by Likes (Mapped to pure arrays for 100% safe, fast serialization)
            $topLikedEpisodes = Episode::with('series')
                ->withCount('likes')
                ->orderByDesc('likes_count')
                ->limit(5)
                ->get()
                ->map(fn ($ep) => [
                    'id' => $ep->id,
                    'episode_number' => $ep->episode_number,
                    'title' => $ep->title,
                    'series_title' => $ep->series?->title ?? '-',
                    'is_free' => (bool) $ep->is_free,
                    'likes_count' => (int) $ep->likes_count,
                ])
                ->values()
                ->all();

            // 8. Daily Views Chart Data across the selected range (Aggregated in 1 query)
            $viewsByDate = ViewingEvent::whereBetween('created_at', [$startDate, $endDate])
                ->select(DB::raw('DATE(created_at) as view_date'), DB::raw('COUNT(*) as total'))
                ->groupBy('view_date')
                ->pluck('total', 'view_date');

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

                // Sum views in interval from the pre-aggregated map
                $intervalViews = 0;
                $curr = $dateFrom->copy();
                while ($curr->lte($dateTo)) {
                    $intervalViews += (int) ($viewsByDate->get($curr->format('Y-m-d')) ?? 0);
                    $curr->addDay();
                }
                $chartViews[] = $intervalViews;
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
        });
    }
}
