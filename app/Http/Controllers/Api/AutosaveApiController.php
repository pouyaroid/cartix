<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LandingPage;
use App\Services\LandingPageService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AutosaveApiController extends Controller
{
    use AuthorizesRequests;
    public function save(Request $request, LandingPage $landingPage, LandingPageService $service): JsonResponse
    {
        $this->authorize('update', $landingPage);

        $validated = $request->validate([
            'blocks' => 'nullable|array',
            'settings' => 'nullable|array',
            'custom_css' => 'nullable|string',
            'custom_js' => 'nullable|string',
        ]);

        if (!empty($validated['settings'])) {
            $landingPage->update(['settings' => $validated['settings']]);
        }

        if (isset($validated['custom_css'])) {
            $landingPage->update(['custom_css' => $validated['custom_css']]);
        }

        if (isset($validated['custom_js'])) {
            $landingPage->update(['custom_js' => $validated['custom_js']]);
        }

        if (!empty($validated['blocks'])) {
            $service->saveBlocks($landingPage, $validated['blocks']);
        }

        $version = $service->createVersion($landingPage, 'autosave');

        // Prune old autosaves (keep max 50)
        $autosaves = $landingPage->versions()->autosaves()->orderByDesc('version')->skip(50)->get();
        foreach ($autosaves as $old) {
            $old->delete();
        }

        return response()->json([
            'success' => true,
            'version' => $version->version,
            'saved_at' => $version->created_at->toIso8601String(),
        ]);
    }
}
