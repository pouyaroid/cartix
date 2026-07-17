<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\LandingPage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class LandingPageBuilderController extends Controller
{
    use AuthorizesRequests;

    public function index(LandingPage $landingPage)
    {
        $this->authorize('update', $landingPage);

        return view('dashboard.landing-pages.builder', [
            'page' => $landingPage,
        ]);
    }
}
