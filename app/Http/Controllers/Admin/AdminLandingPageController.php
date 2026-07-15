<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LandingPage;
use Illuminate\Http\Request;

class AdminLandingPageController extends Controller
{
    public function index(Request $request)
    {
        $query = LandingPage::with('user');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $pages = $query->latest()->paginate(20);

        return view('admin.landing-pages.index', compact('pages'));
    }

    public function edit(LandingPage $landingPage)
    {
        $landingPage->load('user');

        return view('admin.landing-pages.edit', ['page' => $landingPage]);
    }

    public function update(Request $request, LandingPage $landingPage)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'status' => 'nullable|in:draft,published,archived',
        ]);

        $landingPage->update($validated);

        return redirect()->route('admin.landing-pages.index')
            ->with('success', 'لندینگ پیج بروزرسانی شد.');
    }

    public function destroy(LandingPage $landingPage)
    {
        $landingPage->delete();

        return back()->with('success', 'لندینگ پیج حذف شد.');
    }
}
