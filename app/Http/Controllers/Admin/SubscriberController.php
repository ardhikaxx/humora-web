<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubscriberController extends Controller
{
    /**
     * Display premium subscribers list with real expiration dates and remaining days
     */
    public function index(Request $request): View
    {
        $query = Subscription::with(['user', 'plan', 'transaction'])
            ->orderByDesc('end_date');

        if ($search = $request->input('search')) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($statusFilter = $request->input('status')) {
            if ($statusFilter === 'active') {
                $query->where('status', 'active')
                    ->where('end_date', '>=', Carbon::now());
            } elseif ($statusFilter === 'expired') {
                $query->where(function ($q) {
                    $q->where('status', 'expired')
                        ->orWhere('end_date', '<', Carbon::now());
                });
            }
        }

        $subscriptions = $query->paginate(15)->withQueryString();

        // Calculate counts
        $activeCount = Subscription::where('status', 'active')
            ->where('end_date', '>=', Carbon::now())
            ->count();

        $expiredCount = Subscription::where(function ($q) {
            $q->where('status', 'expired')
                ->orWhere('end_date', '<', Carbon::now());
        })->count();

        return view('admin.subscribers.index', compact('subscriptions', 'activeCount', 'expiredCount'));
    }
}
