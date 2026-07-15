<?php

declare(strict_types=1);

namespace App\Livewire\Builder;

use App\Models\LandingPageBlock;
use Livewire\Component;

class PropertiesPanel extends Component
{
    public ?string $blockId = null;
    public array $content = [];
    public array $styles = [];

    protected $listeners = ['blockSelected' => 'loadBlock'];

    public function loadBlock(?string $blockId): void
    {
        $this->blockId = $blockId;

        if (!$blockId) {
            $this->content = [];
            $this->styles = [];
            return;
        }

        $block = LandingPageBlock::find($blockId);
        if ($block) {
            $this->content = $block->content ?? [];
            $this->styles = $block->styles ?? [];
        }
    }

    public function updateContent(string $key, mixed $value): void
    {
        if (!$this->blockId) return;

        $block = LandingPageBlock::find($this->blockId);
        if (!$block) return;

        $content = $block->content ?? [];
        $content[$key] = $value;
        $block->update(['content' => $content]);
        $this->content = $content;

        $this->dispatch('blockUpdated', blockId: $this->blockId);
    }

    public function updateStyle(string $property, string $value): void
    {
        if (!$this->blockId) return;

        $block = LandingPageBlock::find($this->blockId);
        if (!$block) return;

        $styles = $block->styles ?? ['desktop' => []];
        $styles['desktop'][$property] = $value;
        $block->update(['styles' => $styles]);
        $this->styles = $styles;

        $this->dispatch('blockStyleUpdated', blockId: $this->blockId);
    }

    public function render()
    {
        return view('livewire.builder.properties-panel');
    }
}
