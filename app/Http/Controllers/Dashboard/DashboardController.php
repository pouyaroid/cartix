<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\QrCode;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $stats = [
            'cards' => Card::where('user_id', $user->id)->count(),
            'published_cards' => Card::where('user_id', $user->id)->published()->count(),
            'qr_codes' => QrCode::where('user_id', $user->id)->count(),
            'total_views' => Card::where('user_id', $user->id)->sum('views_count'),
            'total_scans' => QrCode::where('user_id', $user->id)->sum('scans_count'),
        ];

        $recentCards = Card::where('user_id', $user->id)
            ->with('template')
            ->latest()
            ->take(5)
            ->get();

        $recentQrCodes = QrCode::where('user_id', $user->id)
            ->with('card')
            ->latest()
            ->take(5)
            ->get();

        $subscription = $user->currentSubscription();

        return view('dashboard.index', compact('stats', 'recentCards', 'recentQrCodes', 'subscription'));
    }
}
