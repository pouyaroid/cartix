<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\LandingPage;
use App\Models\LandingPageBlock;
use App\Models\LandingPageReusableBlock;
use App\Models\LandingPageTemplate;
use App\Models\LandingPageVersion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LandingPageService
{
    public function createPage(array $data, int $userId): LandingPage
    {
        $data['user_id'] = $userId;
        $data['slug'] = $this->generateUniqueSlug($data['title']);

        $page = LandingPage::create($data);

        $this->createVersion($page, 'create');

        return $page;
    }

    public function duplicatePage(LandingPage $page): LandingPage
    {
        $newPage = $page->replicate();
        $newPage->title = $page->title . ' (کپی)';
        $newPage->slug = $this->generateUniqueSlug($newPage->title);
        $newPage->status = 'draft';
        $newPage->views_count = 0;
        $newPage->published_at = null;
        $newPage->save();

        foreach ($page->blocks as $block) {
            $this->duplicateBlock($block, $newPage->id, null);
        }

        return $newPage;
    }

    private function duplicateBlock(LandingPageBlock $block, int $newPageId, ?int $newParentId): LandingPageBlock
    {
        $newBlock = $block->replicate();
        $newBlock->landing_page_id = $newPageId;
        $newBlock->parent_id = $newParentId;
        $newBlock->save();

        foreach ($block->children as $child) {
            $this->duplicateBlock($child, $newPageId, $newBlock->id);
        }

        return $newBlock;
    }

    public function saveBlocks(LandingPage $page, array $blocks, ?int $parentId = null): void
    {
        foreach ($blocks as $index => $blockData) {
            $block = LandingPageBlock::updateOrCreate(
                [
                    'landing_page_id' => $page->id,
                    'id' => $blockData['id'] ?? null,
                ],
                [
                    'parent_id' => $parentId,
                    'type' => $blockData['type'] ?? 'widget',
                    'component' => $blockData['component'] ?? 'widget-text',
                    'content' => $blockData['content'] ?? null,
                    'styles' => $blockData['styles'] ?? null,
                    'sort_order' => $index,
                    'depth' => $blockData['depth'] ?? 0,
                    'is_visible' => $blockData['is_visible'] ?? true,
                ]
            );

            if (!empty($blockData['children'])) {
                $this->saveBlocks($page, $blockData['children'], $block->id);
            }
        }
    }

    public function addBlock(LandingPage $page, array $data, ?int $parentId = null): LandingPageBlock
    {
        $maxOrder = LandingPageBlock::where('landing_page_id', $page->id)
            ->where('parent_id', $parentId)
            ->max('sort_order') ?? -1;

        return LandingPageBlock::create([
            'landing_page_id' => $page->id,
            'parent_id' => $parentId,
            'type' => $data['type'] ?? 'widget',
            'component' => $data['component'],
            'content' => $data['content'] ?? [],
            'styles' => $data['styles'] ?? ['desktop' => []],
            'sort_order' => $maxOrder + 1,
            'depth' => $this->calculateDepth($parentId),
        ]);
    }

    public function updateBlock(LandingPageBlock $block, array $data): LandingPageBlock
    {
        $block->update($data);
        return $block->fresh();
    }

    public function deleteBlock(LandingPageBlock $block): void
    {
        foreach ($block->children as $child) {
            $this->deleteBlock($child);
        }
        $block->delete();
    }

    public function reorderBlocks(LandingPage $page, array $order, ?int $parentId = null): void
    {
        foreach ($order as $index => $blockId) {
            LandingPageBlock::where('id', $blockId)
                ->where('landing_page_id', $page->id)
                ->update(['sort_order' => $index, 'parent_id' => $parentId]);
        }
    }

    public function duplicateBlockById(LandingPageBlock $block): LandingPageBlock
    {
        $maxOrder = LandingPageBlock::where('landing_page_id', $block->landing_page_id)
            ->where('parent_id', $block->parent_id)
            ->max('sort_order') ?? -1;

        $newBlock = $block->replicate();
        $newBlock->sort_order = $maxOrder + 1;
        $newBlock->save();

        foreach ($block->children as $child) {
            $this->duplicateBlock($child, $block->landing_page_id, $newBlock->id);
        }

        return $newBlock;
    }

    public function createVersion(LandingPage $page, string $label = 'autosave'): LandingPageVersion
    {
        $maxVersion = $page->versions()->max('version') ?? 0;

        $blocksData = $page->blocks()->get()->map(fn($block) => [
            'id' => $block->id,
            'parent_id' => $block->parent_id,
            'type' => $block->type,
            'component' => $block->component,
            'content' => $block->content,
            'styles' => $block->styles,
            'sort_order' => $block->sort_order,
            'depth' => $block->depth,
            'is_visible' => $block->is_visible,
        ])->toArray();

        return LandingPageVersion::create([
            'landing_page_id' => $page->id,
            'created_by' => Auth::id(),
            'version' => $maxVersion + 1,
            'label' => $label,
            'data' => [
                'blocks' => $blocksData,
                'settings' => $page->settings,
                'custom_css' => $page->custom_css,
                'custom_js' => $page->custom_js,
            ],
        ]);
    }

    public function restoreVersion(LandingPage $page, LandingPageVersion $version): void
    {
        $data = $version->data;

        $page->update([
            'settings' => $data['settings'] ?? $page->settings,
            'custom_css' => $data['custom_css'] ?? $page->custom_css,
            'custom_js' => $data['custom_js'] ?? $page->custom_js,
        ]);

        $page->blocks()->delete();

        if (!empty($data['blocks'])) {
            foreach ($data['blocks'] as $blockData) {
                LandingPageBlock::create([
                    'landing_page_id' => $page->id,
                    'parent_id' => $blockData['parent_id'],
                    'type' => $blockData['type'],
                    'component' => $blockData['component'],
                    'content' => $blockData['content'],
                    'styles' => $blockData['styles'],
                    'sort_order' => $blockData['sort_order'],
                    'depth' => $blockData['depth'],
                    'is_visible' => $blockData['is_visible'],
                ]);
            }
        }
    }

    public function publishPage(LandingPage $page): void
    {
        $page->update([
            'status' => 'published',
            'published_at' => now(),
        ]);

        $this->createVersion($page, 'publish');
        $this->ensureQrCode($page);
    }

    public function unpublishPage(LandingPage $page): void
    {
        $page->update([
            'status' => 'draft',
            'published_at' => null,
        ]);
    }

    public function ensureQrCode(LandingPage $page): void
    {
        if ($page->qr_code_id) {
            return;
        }

        $qrCode = \App\Models\QrCode::create([
            'user_id' => $page->user_id,
            'type' => 'dynamic',
            'title' => $page->title . ' - QR',
            'content' => $page->getPublicUrlAttribute(),
            'is_active' => true,
        ]);

        $qrCode->update(['content' => url("/qr/{$qrCode->unique_code}")]);
        $page->update(['qr_code_id' => $qrCode->id]);
    }

    public function applyTemplate(LandingPage $page, LandingPageTemplate $template): void
    {
        $page->update(['template_id' => $template->id]);

        $page->blocks()->delete();

        if (!empty($template->data['blocks'])) {
            $this->saveBlocks($page, $template->data['blocks']);
        }

        if (!empty($template->data['settings'])) {
            $page->update(['settings' => $template->data['settings']]);
        }
    }

    public function saveReusableBlock(LandingPageBlock $block, string $name, ?int $userId = null): LandingPageReusableBlock
    {
        return LandingPageReusableBlock::create([
            'user_id' => $userId,
            'name' => $name,
            'slug' => Str::slug($name),
            'component' => $block->component,
            'content' => $block->content,
            'styles' => $block->styles,
        ]);
    }

    public function renderPage(LandingPage $page): string
    {
        return app(LandingPageRenderer::class)->renderPage($page);
    }

    public function getUsedFonts(LandingPage $page): array
    {
        $fonts = [];
        foreach ($page->blocks as $block) {
            $styles = $block->styles ?? [];
            $desktop = $styles['desktop'] ?? [];
            if (!empty($desktop['font-family']) && !in_array($desktop['font-family'], $fonts)) {
                $fonts[] = $desktop['font-family'];
            }
        }
        return $fonts;
    }

    private function calculateDepth(?int $parentId): int
    {
        if ($parentId === null) {
            return 0;
        }

        $parent = LandingPageBlock::find($parentId);
        return $parent ? $parent->depth + 1 : 0;
    }

    private function generateUniqueSlug(string $title): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;

        while (LandingPage::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }
}
