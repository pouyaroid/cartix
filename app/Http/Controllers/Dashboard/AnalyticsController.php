<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\QrCode;
use App\Models\QrScan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $stats = [
            'total_views' => Card::where('user_id', $user->id)->sum('views_count'),
            'total_scans' => QrCode::where('user_id', $user->id)->sum('scans_count'),
            'scans_today' => QrScan::whereHas('qrCode', fn($q) => $q->where('user_id', $user->id))
                ->whereDate('created_at', today())->count(),
            'scans_this_week' => QrScan::whereHas('qrCode', fn($q) => $q->where('user_id', $user->id))
                ->where('created_at', '>=', now()->subWeek())->count(),
            'scans_this_month' => QrScan::whereHas('qrCode', fn($q) => $q->where('user_id', $user->id))
                ->where('created_at', '>=', now()->subMonth())->count(),
        ];

        $topCards = Card::where('user_id', $user->id)
            ->orderByDesc('views_count')
            ->take(5)
            ->get(['id', 'title', 'slug', 'views_count']);

        $topQr = QrCode::where('user_id', $user->id)
            ->orderByDesc('scans_count')
            ->take(5)
            ->get(['id', 'title', 'scans_count']);

        return view('dashboard.analytics.index', compact('stats', 'topCards', 'topQr'));
    }

    public function data(Request $request)
    {
        $user = auth()->user();
        $period = $request->get('period', '30');
        $startDate = now()->subDays((int) $period);

        $dailyScans = QrScan::whereHas('qrCode', fn($q) => $q->where('user_id', $user->id))
            ->where('created_at', '>=', $startDate)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $deviceStats = QrScan::whereHas('qrCode', fn($q) => $q->where('user_id', $user->id))
            ->where('created_at', '>=', $startDate)
            ->select('device_type', DB::raw('count(*) as count'))
            ->groupBy('device_type')
            ->get();

        $browserStats = QrScan::whereHas('qrCode', fn($q) => $q->where('user_id', $user->id))
            ->where('created_at', '>=', $startDate)
            ->select('browser', DB::raw('count(*) as count'))
            ->groupBy('browser')
            ->get();

        $osStats = QrScan::whereHas('qrCode', fn($q) => $q->where('user_id', $user->id))
            ->where('created_at', '>=', $startDate)
            ->select('os', DB::raw('count(*) as count'))
            ->groupBy('os')
            ->get();

        return response()->json([
            'daily' => $dailyScans,
            'devices' => $deviceStats,
            'browsers' => $browserStats,
            'os' => $osStats,
        ]);
    }
}
