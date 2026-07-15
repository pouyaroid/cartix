<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->hasRole(['super-admin', 'admin'])) {
            abort(403, 'شما دسترسی به پنل مدیریت ندارید.');
        }

        return $next($request);
    }
}
