<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LandingPageTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminLandingPageTemplateController extends Controller
{
    public function index()
    {
        $templates = LandingPageTemplate::latest()->get();

        return view('admin.landing-page-templates.index', compact('templates'));
    }

    public function create()
    {
        return view('admin.landing-page-templates.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:255',
            'data' => 'nullable|array',
            'is_active' => 'boolean',
            'is_premium' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_premium'] = $request->boolean('is_premium');
        $validated['data'] = $validated['data'] ?? ['blocks' => []];

        LandingPageTemplate::create($validated);

        return redirect()->route('admin.landing-page-templates.index')
            ->with('success', 'قالب ایجاد شد.');
    }

    public function edit(LandingPageTemplate $landingPageTemplate)
    {
        return view('admin.landing-page-templates.edit', ['template' => $landingPageTemplate]);
    }

    public function update(Request $request, LandingPageTemplate $landingPageTemplate)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:255',
            'data' => 'nullable|array',
            'is_active' => 'boolean',
            'is_premium' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_premium'] = $request->boolean('is_premium');

        $landingPageTemplate->update($validated);

        return redirect()->route('admin.landing-page-templates.index')
            ->with('success', 'قالب بروزرسانی شد.');
    }

    public function destroy(LandingPageTemplate $landingPageTemplate)
    {
        $landingPageTemplate->delete();

        return back()->with('success', 'قالب حذف شد.');
    }
}
