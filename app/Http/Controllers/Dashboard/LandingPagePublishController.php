<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\LandingPage;
use App\Services\LandingPageService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class LandingPagePublishController extends Controller
{
    use AuthorizesRequests;

    public function toggle(LandingPage $landingPage, LandingPageService $service)
    {
        $this->authorize('update', $landingPage);

        if ($landingPage->isPublished()) {
            $service->unpublishPage($landingPage);
            $message = 'لندینگ پیج پیش‌نویس شد.';
        } else {
            $service->publishPage($landingPage);
            $message = 'لندینگ پیج منتشر شد.';
        }

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'status' => $landingPage->fresh()->status]);
        }

        return back()->with('success', $message);
    }
}
