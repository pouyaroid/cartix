<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LandingPage;
use App\Models\LandingPageVersion;
use App\Services\LandingPageService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;

class VersionApiController extends Controller
{
    use AuthorizesRequests;
    public function index(LandingPage $landingPage): JsonResponse
    {
        $this->authorize('update', $landingPage);

        $versions = $landingPage->versions()
            ->with('creator')
            ->latest()
            ->limit(50)
            ->get();

        return response()->json(['versions' => $versions]);
    }

    public function restore(LandingPage $landingPage, LandingPageVersion $version, LandingPageService $service): JsonResponse
    {
        $this->authorize('update', $landingPage);

        $service->restoreVersion($landingPage, $version);

        return response()->json(['success' => true]);
    }
}
