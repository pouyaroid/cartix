<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\LandingPage;
use App\Models\LandingPageFormSubmission;
use App\Services\LandingPageService;
use App\Services\PersianFontService;
use Illuminate\Http\Request;

class PublicLandingPageController extends Controller
{
    public function show(string $slug)
    {
        $page = LandingPage::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        if ($page->isPasswordProtected()) {
            if (session("lp_password_{$page->id}") !== true) {
                return view('public.landing-page-password', ['page' => $page]);
            }
        }

        $page->incrementViews();

        $service = app(LandingPageService::class);
        $html = $service->renderPage($page);
        $usedFonts = $service->getUsedFonts($page);
        $fontLinks = app(PersianFontService::class)->loadFonts($usedFonts);

        return view('public.landing-page', [
            'page' => $page,
            'html' => $html,
            'fontLinks' => $fontLinks,
        ]);
    }

    public function verifyPassword(Request $request, string $slug)
    {
        $page = LandingPage::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        if (!$page->isPasswordProtected()) {
            return redirect()->route('lp.show', $slug);
        }

        $request->validate(['password' => 'required']);

        if (password_verify($request->password, $page->password)) {
            session(["lp_password_{$page->id}" => true]);
            return redirect()->route('lp.show', $slug);
        }

        return back()->withErrors(['password' => 'رمز عبور اشتباه است.']);
    }

    public function submitForm(Request $request, string $slug)
    {
        $page = LandingPage::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $validated = $request->validate([
            'form_id' => 'required|string',
            'data' => 'required|array',
        ]);

        LandingPageFormSubmission::create([
            'landing_page_id' => $page->id,
            'form_id' => $validated['form_id'],
            'data' => $validated['data'],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'referrer' => $request->headers->get('referer'),
        ]);

        return response()->json(['success' => true, 'message' => 'پیام شما با موفقیت ارسال شد.']);
    }
}
