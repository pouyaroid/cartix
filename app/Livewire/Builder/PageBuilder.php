<?php

declare(strict_types=1);

namespace App\Livewire\Builder;

use App\Models\LandingPage;
use App\Models\LandingPageBlock;
use App\Services\LandingPageService;
use Livewire\Component;

class PageBuilder extends Component
{
    public LandingPage $page;
    public array $blocks = [];
    public ?string $selectedBlockId = null;
    public bool $isDirty = false;
    public string $responsiveMode = 'desktop';

    protected $listeners = [
        'blockAdded' => 'refreshBlocks',
        'blockUpdated' => 'refreshBlocks',
        'blockDeleted' => 'refreshBlocks',
        'blockSelected' => 'selectBlock',
        'blocksReordered' => 'refreshBlocks',
    ];

    public function mount(LandingPage $page): void
    {
        $this->page = $page;
        $this->refreshBlocks();
    }

    public function refreshBlocks(): void
    {
        $this->page->load('blocks');
        $this->blocks = $this->page->getBlocksTree();
        $this->isDirty = false;
    }

    public function selectBlock(?string $blockId): void
    {
        $this->selectedBlockId = $blockId;
    }

    public function setResponsiveMode(string $mode): void
    {
        $this->responsiveMode = $mode;
    }

    public function addBlock(string $component, string $type, ?int $parentId = null): void
    {
        $service = app(LandingPageService::class);
        $service->addBlock($this->page, [
            'component' => $component,
            'type' => $type,
        ], $parentId);

        $this->refreshBlocks();
        $this->dispatch('blockAdded');
    }

    public function deleteBlock(int $blockId): void
    {
        $block = LandingPageBlock::findOrFail($blockId);
        $service = app(LandingPageService::class);
        $service->deleteBlock($block);

        if ($this->selectedBlockId === (string) $blockId) {
            $this->selectedBlockId = null;
        }

        $this->refreshBlocks();
        $this->dispatch('blockDeleted');
    }

    public function duplicateBlock(int $blockId): void
    {
        $block = LandingPageBlock::findOrFail($blockId);
        $service = app(LandingPageService::class);
        $service->duplicateBlockById($block);

        $this->refreshBlocks();
        $this->dispatch('blockDuplicated');
    }

    public function save(): void
    {
        $service = app(LandingPageService::class);
        $service->createVersion($this->page, 'manual');
        $this->isDirty = false;
        $this->dispatch('saved');
    }

    public function autosave(): void
    {
        $service = app(LandingPageService::class);
        $service->createVersion($this->page, 'autosave');
        $this->isDirty = false;
    }

    public function togglePublish(): void
    {
        $service = app(LandingPageService::class);

        if ($this->page->isPublished()) {
            $service->unpublishPage($this->page);
        } else {
            $service->publishPage($this->page);
        }

        $this->page->refresh();
    }

    public function render()
    {
        return view('livewire.builder.page-builder');
    }
}
