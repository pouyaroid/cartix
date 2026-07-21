<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\Card;
use Livewire\Component;
use Livewire\WithPagination;

class CardList extends Component
{
    use WithPagination;

    public string $search = '';
    public int $perPage = 15;

    protected array $queryString = [
        'search' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function delete(int $cardId): void
    {
        $card = Card::find($cardId);
        if ($card) {
            $title = $card->title;
            $card->delete();
            $this->dispatch('show-toast', type: 'info', message: "کارت «{$title}» حذف شد.");
        }
    }

    public function render()
    {
        $cards = Card::with('user')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('title', 'like', "%{$this->search}%")
                      ->orWhere('description', 'like', "%{$this->search}%")
                      ->orWhereHas('user', function ($q2) {
                          $q2->where('name', 'like', "%{$this->search}%")
                             ->orWhere('email', 'like', "%{$this->search}%");
                      });
                });
            })
            ->latest()
            ->paginate($this->perPage);

        $stats = [
            'total' => Card::count(),
        ];

        return view('livewire.admin.card-list', compact('cards', 'stats'));
    }
}
