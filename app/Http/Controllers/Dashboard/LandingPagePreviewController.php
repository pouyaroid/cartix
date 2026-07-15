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

        $html = app(LandingPageService::class)->renderPage($landingPage);

        // Load fonts used in this page
        $usedFonts = [];
        foreach ($landingPage->blocks as $block) {
            $styles = $block->styles ?? [];
            $desktop = $styles['desktop'] ?? [];
            if (!empty($desktop['font-family']) && !in_array($desktop['font-family'], $usedFonts)) {
                $usedFonts[] = $desktop['font-family'];
            }
        }
        $fontLinks = app(PersianFontService::class)->loadFonts($usedFonts);

        return view('dashboard.landing-pages.preview', [
            'page' => $landingPage,
            'html' => $html,
            'fontLinks' => $fontLinks,
        ]);
    }
}
