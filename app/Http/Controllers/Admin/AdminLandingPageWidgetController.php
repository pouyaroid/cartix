<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LandingPageWidget;
use Illuminate\Http\Request;

class AdminLandingPageWidgetController extends Controller
{
    public function index()
    {
        $widgets = LandingPageWidget::latest()->get()->groupBy('category');

        return view('admin.landing-page-widgets.index', compact('widgets'));
    }

    public function toggle(LandingPageWidget $widget)
    {
        $widget->update(['is_active' => !$widget->is_active]);

        return back()->with('success', 'وضعیت ویجت تغییر کرد.');
    }
}
