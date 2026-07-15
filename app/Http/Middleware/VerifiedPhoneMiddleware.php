<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifiedPhoneMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && is_null(auth()->user()->phone_verified_at)) {
            return redirect()->route('dashboard.index')
                ->with('warning', 'لطفاً ابتدا شماره تلفن خود را تأیید کنید.');
        }

        return $next($request);
    }
}
