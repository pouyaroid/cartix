<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Card;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function show(string $slug)
    {
        $card = Card::where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();

        return view('public.card', compact('card'));
    }
}
