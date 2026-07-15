<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\LandingPage;
use App\Models\LandingPageFormSubmission;
use App\Services\LandingPageService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $pages = LandingPage::where('user_id', auth()->id())
            ->latest()
            ->paginate(12);

        return view('dashboard.landing-pages.index', compact('pages'));
    }

    public function create()
    {
        $this->checkLimit();

        return view('dashboard.landing-pages.create');
    }

    public function store(Request $request, LandingPageService $service)
    {
        $this->checkLimit();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $page = $service->createPage($validated, auth()->id());

        return redirect()->route('dashboard.landing-pages.builder', $page)
            ->with('success', 'لندینگ پیج با موفقیت ایجاد شد.');
    }

    public function edit(LandingPage $landingPage)
    {
        $this->authorize('update', $landingPage);

        return view('dashboard.landing-pages.edit', ['page' => $landingPage]);
    }

    public function update(Request $request, LandingPage $landingPage, LandingPageService $service)
    {
        $this->authorize('update', $landingPage);

        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
            'og_image' => 'nullable|string|max:500',
            'favicon' => 'nullable|string|max:500',
            'custom_css' => 'nullable|string',
            'custom_js' => 'nullable|string',
            'password' => 'nullable|string|min:4',
            'scheduled_publish_at' => 'nullable|date|after:now',
            'settings' => 'nullable|array',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $landingPage->update($validated);

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'تنظیمات ذخیره شد.');
    }

    public function destroy(LandingPage $landingPage)
    {
        $this->authorize('delete', $landingPage);
        $landingPage->delete();

        return redirect()->route('dashboard.landing-pages.index')
            ->with('success', 'لندینگ پیج حذف شد.');
    }

    public function submissions(LandingPage $landingPage)
    {
        $this->authorize('update', $landingPage);

        $submissions = LandingPageFormSubmission::where('landing_page_id', $landingPage->id)
            ->latest()
            ->paginate(20);

        return view('dashboard.landing-pages.submissions', ['page' => $landingPage, 'submissions' => $submissions]);
    }

    public function generateQr(LandingPage $landingPage, LandingPageService $service)
    {
        $this->authorize('update', $landingPage);

        $service->ensureQrCode($landingPage);

        return back()->with('success', 'کد QR ایجاد شد.');
    }

    private function checkLimit(): void
    {
        $user = auth()->user();
        $count = $user->landingPages()->count();
        $plan = $user->getActivePlan();
        $limit = $plan->max_landing_pages ?? 5;

        if ($count >= $limit) {
            abort(403, 'شما به حد اشتراک خود رسیده‌اید. لطفاً اشتراک خود را ارتقا دهید.');
        }
    }
}
