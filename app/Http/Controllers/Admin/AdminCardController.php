<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Card;

class AdminCardController extends Controller
{
    public function index()
    {
        $cards = Card::with(['user', 'template'])->latest()->paginate(20);

        return view('admin.cards.index', compact('cards'));
    }

    public function destroy(Card $card)
    {
        $card->delete();

        return back()->with('success', 'کارت حذف شد.');
    }

    public function feature(Card $card)
    {
        $card->update(['is_featured' => !$card->is_featured]);

        $status = $card->is_featured ? 'ویژه شد' : 'از ویژه خارج شد';

        return back()->with('success', "کارت {$status}.");
    }
}
