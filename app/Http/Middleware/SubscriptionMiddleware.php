<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SubscriptionMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && !auth()->user()->hasActiveSubscription()) {
            return redirect()->route('dashboard.subscription.index')
                ->with('warning', 'برای استفاده از این امکان، لطفاً اشتراک فعال داشته باشید.');
        }

        return $next($request);
    }
}
