<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Config\WidgetConfig;
use App\Http\Controllers\Controller;
use App\Models\LandingPage;
use App\Models\LandingPageBlock;
use App\Models\LandingPageReusableBlock;
use App\Models\LandingPageTemplate;
use App\Models\LandingPageWidget;
use App\Services\PersianFontService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class LandingPageBuilderController extends Controller
{
    use AuthorizesRequests;

    public function index(LandingPage $landingPage)
    {
        $this->authorize('update', $landingPage);

        $landingPage->load(['blocks' => fn($q) => $q->orderBy('sort_order')]);

        $widgets = LandingPageWidget::active()->ordered()->get()->groupBy('category');
        $templates = LandingPageTemplate::active()->get();
        $reusableBlocks = LandingPageReusableBlock::forUser(auth()->id())->active()->get();
        $categories = WidgetConfig::getCategories();

        // Font options
        $fontService = app(PersianFontService::class);
        $fontOptions = $fontService->getFontOptions();

        // Collect fonts used in this page for preloading
        $usedFonts = [];
        foreach ($landingPage->blocks as $block) {
            $styles = $block->styles ?? [];
            $desktop = $styles['desktop'] ?? [];
            if (!empty($desktop['font-family']) && !in_array($desktop['font-family'], $usedFonts)) {
                $usedFonts[] = $desktop['font-family'];
            }
        }
        $fontLinks = $fontService->loadFonts($usedFonts);

        return view('dashboard.landing-pages.builder', [
            'page' => $landingPage,
            'blocks' => $landingPage->getBlocksTree(),
            'widgets' => $widgets,
            'templates' => $templates,
            'reusableBlocks' => $reusableBlocks,
            'categories' => $categories,
            'fontOptions' => $fontOptions,
            'fontLinks' => $fontLinks,
        ]);
    }
}
