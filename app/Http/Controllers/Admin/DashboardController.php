<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\Payment;
use App\Models\QrCode;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'users' => User::count(),
            'active_users' => User::active()->count(),
            'cards' => Card::count(),
            'published_cards' => Card::published()->count(),
            'qr_codes' => QrCode::count(),
            'total_views' => Card::sum('views_count'),
            'total_scans' => QrCode::sum('scans_count'),
            'revenue' => Payment::completed()->sum('amount'),
            'revenue_month' => Payment::completed()->whereMonth('paid_at', now()->month)->sum('amount'),
            'active_subscriptions' => Subscription::active()->count(),
        ];

        $recentUsers = User::latest()->take(5)->get();
        $recentCards = Card::with(['user', 'template'])->latest()->take(5)->get();
        $recentPayments = Payment::with(['user', 'plan'])->latest()->take(5)->get();

        $monthlyRevenue = Payment::completed()
            ->where('paid_at', '>=', now()->subMonths(12))
            ->select(
                DB::raw("DATE_FORMAT(paid_at, '%Y-%m') as month"),
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $userRegistrations = User::select(
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
            DB::raw('count(*) as count')
        )
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $recentActivity = \Spatie\Activitylog\Models\Activity::query()->latest()->take(10)->get();

        return view('admin.dashboard.index', compact(
            'stats',
            'recentUsers',
            'recentCards',
            'recentPayments',
            'monthlyRevenue',
            'userRegistrations',
            'recentActivity'
        ));
    }
}
