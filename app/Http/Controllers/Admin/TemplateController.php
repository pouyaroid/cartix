<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Template;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    public function index()
    {
        $templates = Template::latest()->paginate(20);

        return view('admin.templates.index', compact('templates'));
    }

    public function create()
    {
        return view('admin.templates.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:templates,slug',
            'description' => 'nullable|string',
            'category' => 'nullable|string',
            'blade_view' => 'required|string',
            'is_active' => 'boolean',
            'is_premium' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_premium'] = $request->boolean('is_premium');

        Template::create($validated);

        return redirect()->route('admin.templates.index')
            ->with('success', 'قالب با موفقیت ایجاد شد.');
    }

    public function edit(Template $template)
    {
        return view('admin.templates.edit', compact('template'));
    }

    public function update(Request $request, Template $template)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:templates,slug,' . $template->id,
            'description' => 'nullable|string',
            'category' => 'nullable|string',
            'blade_view' => 'required|string',
            'is_active' => 'boolean',
            'is_premium' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_premium'] = $request->boolean('is_premium');

        $template->update($validated);

        return redirect()->route('admin.templates.index')
            ->with('success', 'قالب با موفقیت بروزرسانی شد.');
    }

    public function destroy(Template $template)
    {
        $template->delete();

        return back()->with('success', 'قالب حذف شد.');
    }
}
