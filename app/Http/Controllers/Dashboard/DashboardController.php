<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\QrCode;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $stats = [
            'qr_codes' => QrCode::where('user_id', $user->id)->count(),
            'total_scans' => QrCode::where('user_id', $user->id)->sum('scans_count'),
        ];

        $recentQrCodes = QrCode::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        $subscription = $user->currentSubscription();

        return view('dashboard.index', compact('stats', 'recentQrCodes', 'subscription'));
    }
}
