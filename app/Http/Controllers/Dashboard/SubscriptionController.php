<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Plan;

class SubscriptionController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $plans = Plan::active()->get();
        $currentSubscription = $user->subscriptions()->with('plan')->latest()->first();

        return view('dashboard.subscription.index', compact('plans', 'currentSubscription'));
    }

    public function upgrade(Plan $plan)
    {
        // Redirect to payment gateway (placeholder)
        return redirect()->route('dashboard.subscription.index')
            ->with('success', 'در حال انتقال به درگاه پرداخت...');
    }
}
