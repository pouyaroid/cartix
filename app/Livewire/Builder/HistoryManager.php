<?php

declare(strict_types=1);

namespace App\Livewire\Builder;

use App\Models\LandingPage;
use App\Models\LandingPageVersion;
use App\Services\LandingPageService;
use Livewire\Component;

class HistoryManager extends Component
{
    public LandingPage $page;
    public $versions = [];
    public $currentVersionId = null;

    public function mount(LandingPage $page): void
    {
        $this->page = $page;
        $this->loadVersions();
    }

    public function loadVersions(): void
    {
        $this->versions = $this->page->versions()
            ->with('creator')
            ->latest()
            ->limit(30)
            ->get()
            ->toArray();
    }

    public function restore(int $versionId): void
    {
        $version = LandingPageVersion::findOrFail($versionId);
        $service = app(LandingPageService::class);
        $service->restoreVersion($this->page, $version);

        $this->loadVersions();
        $this->dispatch('versionRestored');
    }

    public function render()
    {
        return view('livewire.builder.history-manager');
    }
}
