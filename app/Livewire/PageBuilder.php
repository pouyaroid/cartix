<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\LandingPage;
use App\Models\LandingPageBlock;
use App\Services\LandingPageRenderer;
use Livewire\Component;

class PageBuilder extends Component
{
    public LandingPage $page;
    public array $blocks = [];
    public ?int $selectedBlockId = null;
    public string $responsiveMode = 'desktop';
    public string $activeTab = 'properties';
    public string $widgetSearch = '';
    public bool $isDirty = false;

    protected $listeners = [
        'blockUpdated' => 'refreshBlocks',
        'blockDeleted' => 'refreshBlocks',
        'blockReordered' => 'handleReorder',
        'save' => 'save',
        'confirmDeleteBlock' => 'deleteBlock',
    ];

    public function mount(LandingPage $page): void
    {
        $this->page = $page->load('blocks');
        $this->blocks = $this->page->getBlocksTree();
    }

    public function refreshBlocks(): void
    {
        $this->page->load('blocks');
        $this->blocks = $this->page->getBlocksTree();
    }

    public function getSelectedBlock(): ?LandingPageBlock
    {
        if ($this->selectedBlockId === null) return null;
        return LandingPageBlock::find($this->selectedBlockId);
    }

    public function selectBlock(?int $blockId): void
    {
        $this->selectedBlockId = $blockId;
        if ($blockId !== null) {
            $this->activeTab = 'properties';
        }
    }

    public function setResponsiveMode(string $mode): void
    {
        $this->responsiveMode = $mode;
    }

    public function addBlock(string $component, string $type, ?int $parentId = null): void
    {
        $widget = $this->getWidgetDefaults($component);
        $maxOrder = LandingPageBlock::where('landing_page_id', $this->page->id)
            ->where('parent_id', $parentId)
            ->max('sort_order') ?? -1;

        $block = LandingPageBlock::create([
            'landing_page_id' => $this->page->id,
            'parent_id' => $parentId,
            'type' => $type,
            'component' => $component,
            'content' => $widget['content'] ?? [],
            'styles' => $widget['styles'] ?? ['desktop' => [], 'tablet' => [], 'mobile' => []],
            'sort_order' => $maxOrder + 1,
            'depth' => $this->calculateDepth($parentId),
            'is_visible' => true,
        ]);

        $this->refreshBlocks();
        $this->selectedBlockId = $block->id;
        $this->activeTab = 'properties';
        $this->isDirty = true;
    }

    public function updateBlockContent(int $blockId, string $key, mixed $value): void
    {
        $block = LandingPageBlock::find($blockId);
        if (!$block) return;

        $content = $block->content ?? [];
        $content[$key] = $value;
        $block->update(['content' => $content]);

        $this->refreshBlocks();
        $this->isDirty = true;
    }

    public function updateBlockContentArray(int $blockId, string $key, array $value): void
    {
        $block = LandingPageBlock::find($blockId);
        if (!$block) return;

        $content = $block->content ?? [];
        $content[$key] = $value;
        $block->update(['content' => $content]);

        $this->refreshBlocks();
        $this->isDirty = true;
    }

    public function updateListItem(int $blockId, string $key, int $index, string $value): void
    {
        $block = LandingPageBlock::find($blockId);
        if (!$block) return;

        $content = $block->content ?? [];
        $items = $content[$key] ?? [];
        if (isset($items[$index])) {
            $items[$index] = $value;
        }
        $content[$key] = $items;
        $block->update(['content' => $content]);

        $this->refreshBlocks();
        $this->isDirty = true;
    }

    public function addItem(int $blockId, string $key, mixed $item): void
    {
        $block = LandingPageBlock::find($blockId);
        if (!$block) return;

        $content = $block->content ?? [];
        $items = $content[$key] ?? [];
        $items[] = $item;
        $content[$key] = $items;
        $block->update(['content' => $content]);

        $this->refreshBlocks();
        $this->isDirty = true;
    }

    public function removeItem(int $blockId, string $key, int $index): void
    {
        $block = LandingPageBlock::find($blockId);
        if (!$block) return;

        $content = $block->content ?? [];
        $items = $content[$key] ?? [];
        array_splice($items, $index, 1);
        $content[$key] = $items;
        $block->update(['content' => $content]);

        $this->refreshBlocks();
        $this->isDirty = true;
    }

    public function updateArrayItemField(int $blockId, string $key, int $index, string $field, string $value): void
    {
        $block = LandingPageBlock::find($blockId);
        if (!$block) return;

        $content = $block->content ?? [];
        $items = $content[$key] ?? [];
        if (isset($items[$index]) && is_array($items[$index])) {
            $items[$index][$field] = $value;
        }
        $content[$key] = $items;
        $block->update(['content' => $content]);

        $this->refreshBlocks();
        $this->isDirty = true;
    }

    public function addArrayItem(int $blockId, string $key, array $item): void
    {
        $block = LandingPageBlock::find($blockId);
        if (!$block) return;

        $content = $block->content ?? [];
        $items = $content[$key] ?? [];
        $items[] = $item;
        $content[$key] = $items;
        $block->update(['content' => $content]);

        $this->refreshBlocks();
        $this->isDirty = true;
    }

    public function removeArrayItem(int $blockId, string $key, int $index): void
    {
        $block = LandingPageBlock::find($blockId);
        if (!$block) return;

        $content = $block->content ?? [];
        $items = $content[$key] ?? [];
        array_splice($items, $index, 1);
        $content[$key] = array_values($items);
        $block->update(['content' => $content]);

        $this->refreshBlocks();
        $this->isDirty = true;
    }

    public function updateBlockStyle(int $blockId, string $property, string $value): void
    {
        $block = LandingPageBlock::find($blockId);
        if (!$block) return;

        $styles = $block->styles ?? ['desktop' => [], 'tablet' => [], 'mobile' => []];
        $mode = $this->responsiveMode;
        if (!isset($styles[$mode])) {
            $styles[$mode] = [];
        }
        $styles[$mode][$property] = $value;
        $block->update(['styles' => $styles]);

        $this->refreshBlocks();
        $this->isDirty = true;
    }

    public function updateBlockStylesForMode(int $blockId, string $mode, array $modeStyles): void
    {
        $block = LandingPageBlock::find($blockId);
        if (!$block) return;

        $styles = $block->styles ?? ['desktop' => [], 'tablet' => [], 'mobile' => []];
        $styles[$mode] = $modeStyles;
        $block->update(['styles' => $styles]);

        $this->refreshBlocks();
        $this->isDirty = true;
    }

    public function deleteBlock(int $blockId): void
    {
        $block = LandingPageBlock::find($blockId);
        if (!$block) return;

        $this->deleteBlockRecursive($block);

        if ($this->selectedBlockId === $blockId) {
            $this->selectedBlockId = null;
        }

        $this->refreshBlocks();
        $this->isDirty = true;
    }

    public function duplicateBlock(int $blockId): void
    {
        $block = LandingPageBlock::find($blockId);
        if (!$block) return;

        $maxOrder = LandingPageBlock::where('landing_page_id', $this->page->id)
            ->where('parent_id', $block->parent_id)
            ->max('sort_order') ?? -1;

        $newBlock = $block->replicate();
        $newBlock->sort_order = $maxOrder + 1;
        $newBlock->save();

        foreach ($block->children as $child) {
            $this->duplicateBlockRecursive($child, $newBlock->id);
        }

        $this->refreshBlocks();
        $this->selectedBlockId = $newBlock->id;
        $this->isDirty = true;
    }

    public function handleReorder(string $orderJson): void
    {
        $order = json_decode($orderJson, true);
        if (!is_array($order)) return;

        foreach ($order as $index => $blockId) {
            LandingPageBlock::where('id', $blockId)
                ->where('landing_page_id', $this->page->id)
                ->update(['sort_order' => $index]);
        }

        $this->refreshBlocks();
        $this->isDirty = true;
    }

    public function toggleVisibility(int $blockId): void
    {
        $block = LandingPageBlock::find($blockId);
        if (!$block) return;

        $block->update(['is_visible' => !$block->is_visible]);
        $this->refreshBlocks();
        $this->isDirty = true;
    }

    public function save(): void
    {
        $service = app(\App\Services\LandingPageService::class);
        $service->createVersion($this->page, 'manual');
        $this->isDirty = false;
        $this->dispatch('show-toast', ['type' => 'success', 'message' => 'ذخیره شد']);
    }

    public function getUsedFonts(): array
    {
        $fonts = [];
        foreach ($this->page->blocks as $block) {
            $styles = $block->styles ?? [];
            $desktop = $styles['desktop'] ?? [];
            if (!empty($desktop['font-family']) && !in_array($desktop['font-family'], $fonts)) {
                $fonts[] = $desktop['font-family'];
            }
        }
        return $fonts;
    }

    public function getWidgetCategories(): array
    {
        return [
            'basic' => [
                'label' => 'ابتدایی',
                'icon' => 'bi-type',
                'widgets' => [
                    ['component' => 'widget-heading', 'name' => 'عنوان', 'icon' => 'bi-type-h1'],
                    ['component' => 'widget-text', 'name' => 'متن', 'icon' => 'bi-text-paragraph'],
                    ['component' => 'widget-button', 'name' => 'دکمه', 'icon' => 'bi-hand-index'],
                    ['component' => 'widget-image', 'name' => 'تصویر', 'icon' => 'bi-image'],
                    ['component' => 'widget-divider', 'name' => 'جداکننده', 'icon' => 'bi-dash'],
                    ['component' => 'widget-spacer', 'name' => 'فاصله', 'icon' => 'bi-arrows-expand'],
                    ['component' => 'widget-icon', 'name' => 'آیکون', 'icon' => 'bi-emoji-smile'],
                ],
            ],
            'business' => [
                'label' => 'کسب‌وکار',
                'icon' => 'bi-briefcase',
                'widgets' => [
                    ['component' => 'widget-logo', 'name' => 'لوگو', 'icon' => 'bi-badge-ad'],
                    ['component' => 'widget-social', 'name' => 'شبکه اجتماعی', 'icon' => 'bi-share'],
                    ['component' => 'widget-stats', 'name' => 'آمار', 'icon' => 'bi-graph-up'],
                    ['component' => 'widget-map', 'name' => 'نقشه', 'icon' => 'bi-geo-alt'],
                ],
            ],
            'content' => [
                'label' => 'محتوا',
                'icon' => 'bi-file-earmark-text',
                'widgets' => [
                    ['component' => 'content-card', 'name' => 'کارت', 'icon' => 'bi-card-heading'],
                    ['component' => 'content-list', 'name' => 'لیست', 'icon' => 'bi-list-ul'],
                ],
            ],
        ];
    }

    public function getBlockLabel(string $component): string
    {
        $labels = [
            'widget-heading' => 'عنوان',
            'widget-text' => 'متن',
            'widget-button' => 'دکمه',
            'widget-image' => 'تصویر',
            'widget-divider' => 'جداکننده',
            'widget-spacer' => 'فاصله',
            'widget-icon' => 'آیکون',
            'widget-logo' => 'لوگو',
            'widget-social' => 'شبکه اجتماعی',
            'widget-stats' => 'آمار',
            'widget-map' => 'نقشه',
            'content-card' => 'کارت',
            'content-list' => 'لیست',
        ];
        return $labels[$component] ?? $component;
    }

    public function render()
    {
        return view('livewire.page-builder');
    }

    private function getWidgetDefaults(string $component): array
    {
        $defaults = [
            'widget-heading' => [
                'content' => ['text' => 'عنوان', 'tag' => 'h2'],
                'styles' => ['desktop' => ['text-align' => 'center'], 'tablet' => [], 'mobile' => []],
            ],
            'widget-text' => [
                'content' => ['text' => 'متن خود را اینجا بنویسید.'],
                'styles' => ['desktop' => ['color' => '#4b5563', 'text-align' => 'right'], 'tablet' => [], 'mobile' => []],
            ],
            'widget-button' => [
                'content' => ['text' => 'کلیک کنید', 'link' => '#'],
                'styles' => [
                    'desktop' => ['text-align' => 'center'],
                    'button' => ['background-color' => '#4f46e5', 'color' => '#ffffff', 'padding' => '12px 28px', 'border-radius' => '10px', 'font-size' => '15px', 'font-weight' => '600'],
                    'tablet' => [],
                    'mobile' => [],
                ],
            ],
            'widget-image' => [
                'content' => ['src' => '', 'alt' => ''],
                'styles' => ['desktop' => ['border-radius' => '12px'], 'tablet' => [], 'mobile' => []],
            ],
            'widget-divider' => [
                'content' => ['color' => '#e5e7eb'],
                'styles' => ['desktop' => [], 'tablet' => [], 'mobile' => []],
            ],
            'widget-spacer' => [
                'content' => ['height' => '48px'],
                'styles' => ['desktop' => [], 'tablet' => [], 'mobile' => []],
            ],
            'widget-icon' => [
                'content' => ['name' => 'bi-star', 'size' => '24px', 'color' => '#4f46e5'],
                'styles' => ['desktop' => ['text-align' => 'center'], 'tablet' => [], 'mobile' => []],
            ],
            'widget-logo' => [
                'content' => ['mode' => 'icon', 'icon' => 'bi-badge-ad', 'src' => '', 'alt' => '', 'link' => ''],
                'styles' => ['desktop' => ['text-align' => 'center', 'color' => '#4f46e5', 'font-size' => '48px'], 'tablet' => [], 'mobile' => []],
            ],
            'widget-social' => [
                'content' => ['links' => [
                    ['platform' => 'instagram', 'url' => '#'],
                    ['platform' => 'telegram', 'url' => '#'],
                ]],
                'styles' => ['desktop' => [], 'tablet' => [], 'mobile' => []],
            ],
            'widget-stats' => [
                'content' => ['items' => [
                    ['value' => '100+', 'label' => 'مشتری'],
                    ['value' => '50+', 'label' => 'پروژه'],
                ]],
                'styles' => ['desktop' => [], 'tablet' => [], 'mobile' => []],
            ],
            'widget-map' => [
                'content' => ['height' => '300px'],
                'styles' => ['desktop' => [], 'tablet' => [], 'mobile' => []],
            ],
            'content-card' => [
                'content' => [
                    'layout' => 'vertical',
                    'grid' => ['desktop' => 3, 'tablet' => 2, 'mobile' => 1],
                    'cards' => [],
                ],
                'styles' => ['desktop' => ['gap' => '24px'], 'tablet' => [], 'mobile' => []],
            ],
            'content-list' => [
                'content' => ['items' => ['آیتم اول', 'آیتم دوم', 'آیتم سوم']],
                'styles' => ['desktop' => [], 'tablet' => [], 'mobile' => []],
            ],
        ];

        return $defaults[$component] ?? [
            'content' => [],
            'styles' => ['desktop' => [], 'tablet' => [], 'mobile' => []],
        ];
    }

    private function calculateDepth(?int $parentId): int
    {
        if ($parentId === null) return 0;
        $parent = LandingPageBlock::find($parentId);
        return $parent ? $parent->depth + 1 : 0;
    }

    private function deleteBlockRecursive(LandingPageBlock $block): void
    {
        foreach ($block->children as $child) {
            $this->deleteBlockRecursive($child);
        }
        $block->delete();
    }

    private function duplicateBlockRecursive(LandingPageBlock $block, ?int $newParentId): void
    {
        $newBlock = $block->replicate();
        $newBlock->parent_id = $newParentId;
        $newBlock->save();

        foreach ($block->children as $child) {
            $this->duplicateBlockRecursive($child, $newBlock->id);
        }
    }
}
