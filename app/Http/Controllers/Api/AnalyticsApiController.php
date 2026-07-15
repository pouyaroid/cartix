<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LandingPage;
use App\Models\LandingPageAnalyticsEvent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AnalyticsApiController extends Controller
{
    public function track(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'page_slug' => 'required|string',
            'event_type' => 'required|string',
            'data' => 'nullable|array',
        ]);

        $page = LandingPage::where('slug', $validated['page_slug'])->where('status', 'published')->first();

        if (!$page) {
            return response()->json(['success' => false], 404);
        }

        LandingPageAnalyticsEvent::create([
            'landing_page_id' => $page->id,
            'event_type' => $validated['event_type'],
            'data' => $validated['data'] ?? null,
            'session_id' => $request->session()->getId(),
            'created_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }
}
