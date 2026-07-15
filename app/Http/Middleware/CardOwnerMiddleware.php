<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CardOwnerMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $card = $request->route('card');

        if ($card && $card->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403, 'شما مالک این کارت نیستید.');
        }

        return $next($request);
    }
}
