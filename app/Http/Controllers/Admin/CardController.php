<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Card;

class CardController extends Controller
{
    public function index()
    {
        return view('admin.cards.index');
    }

    public function show(Card $card)
    {
        $card->load('user', 'media');

        return view('admin.cards.show', compact('card'));
    }
}
