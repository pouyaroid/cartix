<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\LandingPage;
use App\Models\LandingPageFormSubmission;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class LandingPageAnalyticsController extends Controller
{
    use AuthorizesRequests;

    public function index(LandingPage $landingPage)
    {
        $this->authorize('update', $landingPage);

        $analytics = $landingPage->analytics()
            ->where('date', '>=', now()->subDays(30))
            ->orderBy('date')
            ->get();

        $totalViews = $analytics->sum('views');
        $totalSubmissions = LandingPageFormSubmission::where('landing_page_id', $landingPage->id)->count();
        $unreadSubmissions = LandingPageFormSubmission::where('landing_page_id', $landingPage->id)->where('is_read', false)->count();

        return view('dashboard.landing-pages.analytics', [
            'page' => $landingPage,
            'analytics' => $analytics,
            'totalViews' => $totalViews,
            'totalSubmissions' => $totalSubmissions,
            'unreadSubmissions' => $unreadSubmissions,
        ]);
    }
}
