<?php

declare(strict_types=1);

namespace App\Livewire\Builder;

use App\Models\Media;
use Livewire\Component;
use Livewire\WithFileUploads;

class MediaManager extends Component
{
    use WithFileUploads;

    public $mediaItems = [];
    public $selectedUrl = null;
    public bool $showModal = false;

    protected $listeners = ['openMediaPicker' => 'open', 'mediaUploaded' => 'refreshMedia'];

    public function open(): void
    {
        $this->showModal = true;
        $this->refreshMedia();
    }

    public function close(): void
    {
        $this->showModal = false;
    }

    public function refreshMedia(): void
    {
        $this->mediaItems = Media::where('user_id', auth()->id())
            ->latest()
            ->limit(24)
            ->get()
            ->toArray();
    }

    public function select(string $url): void
    {
        $this->selectedUrl = $url;
        $this->dispatch('mediaSelected', url: $url);
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.builder.media-manager');
    }
}
