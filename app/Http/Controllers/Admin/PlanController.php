<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::latest()->get();

        return view('admin.plans.index', compact('plans'));
    }

    public function create()
    {
        return view('admin.plans.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:plans,slug',
            'description' => 'nullable|string',
            'price_monthly' => 'required|numeric|min:0',
            'price_yearly' => 'required|numeric|min:0',
            'max_qr_codes' => 'required|integer|min:1',
            'max_storage_mb' => 'required|integer|min:1',
            'features' => 'nullable|array',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        Plan::create($validated);

        return redirect()->route('admin.plans.index')->with('success', 'پلن ایجاد شد.');
    }

    public function edit(Plan $plan)
    {
        return view('admin.plans.edit', compact('plan'));
    }

    public function update(Request $request, Plan $plan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:plans,slug,' . $plan->id,
            'description' => 'nullable|string',
            'price_monthly' => 'required|numeric|min:0',
            'price_yearly' => 'required|numeric|min:0',
            'max_qr_codes' => 'required|integer|min:1',
            'max_storage_mb' => 'required|integer|min:1',
            'features' => 'nullable|array',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $plan->update($validated);

        return redirect()->route('admin.plans.index')->with('success', 'پلن بروزرسانی شد.');
    }

    public function destroy(Plan $plan)
    {
        $plan->delete();

        return back()->with('success', 'پلن حذف شد.');
    }
}
