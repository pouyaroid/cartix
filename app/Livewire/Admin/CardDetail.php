<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\Card;
use Livewire\Component;

class CardDetail extends Component
{
    public ?int $cardId = null;

    public function mount(int $id): void
    {
        $this->cardId = $id;
    }

    public function delete(): void
    {
        $card = Card::findOrFail($this->cardId);
        $title = $card->title;
        $card->delete();
        $this->dispatch('show-toast', type: 'info', message: "کارت «{$title}» حذف شد.");
        $this->redirect(route('admin.cards.index'), navigate: true);
    }

    public function render()
    {
        $card = Card::with('user', 'media')->findOrFail($this->cardId);

        return view('livewire.admin.card-detail', compact('card'));
    }
}
