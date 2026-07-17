<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\LandingPage;
use App\Services\LandingPageService;
use App\Services\PersianFontService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class LandingPagePreviewController extends Controller
{
    use AuthorizesRequests;

    public function preview(LandingPage $landingPage)
    {
        $this->authorize('update', $landingPage);

        $landingPage->load(['blocks' => fn($q) => $q->orderBy('sort_order')]);

        $service = app(LandingPageService::class);
        $html = $service->renderPage($landingPage);
        $usedFonts = $service->getUsedFonts($landingPage);
        $fontLinks = app(PersianFontService::class)->loadFonts($usedFonts);

        return view('dashboard.landing-pages.preview', [
            'page' => $landingPage,
            'html' => $html,
            'fontLinks' => $fontLinks,
        ]);
    }
}
