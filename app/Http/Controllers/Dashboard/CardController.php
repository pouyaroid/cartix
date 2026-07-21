<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Card;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function index()
    {
        $cards = Card::where('user_id', auth()->id())
            ->latest()
            ->paginate(12);

        return view('dashboard.cards.index', compact('cards'));
    }

    public function create()
    {
        return view('dashboard.cards.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $card = Card::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'design_data' => ['objects' => []],
            'settings' => [],
        ]);

        return redirect()->route('dashboard.cards.edit', $card)
            ->with('success', 'کارت با موفقیت ایجاد شد.');
    }

    public function show(Card $card)
    {
        if ($card->user_id !== auth()->id()) {
            abort(403);
        }

        $card->load('media');

        return view('dashboard.cards.show', compact('card'));
    }

    public function edit(Card $card)
    {
        if ($card->user_id !== auth()->id()) {
            abort(403);
        }

        return view('dashboard.cards.edit', compact('card'));
    }

    public function update(Request $request, Card $card)
    {
        if ($card->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $card->update($validated);

        return back()->with('success', 'کارت با موفقیت بروزرسانی شد.');
    }

    public function destroy(Card $card)
    {
        if ($card->user_id !== auth()->id()) {
            abort(403);
        }

        $card->delete();

        return redirect()->route('dashboard.cards.index')
            ->with('success', 'کارت حذف شد.');
    }
}
