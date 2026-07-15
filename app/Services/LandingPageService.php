<?php

declare(strict_types=1);

namespace App\Services;

use App\Config\WidgetConfig;
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
        $widget = WidgetConfig::getWidget($data['component']);

        $maxOrder = LandingPageBlock::where('landing_page_id', $page->id)
            ->where('parent_id', $parentId)
            ->max('sort_order') ?? -1;

        return LandingPageBlock::create([
            'landing_page_id' => $page->id,
            'parent_id' => $parentId,
            'type' => $data['type'] ?? 'widget',
            'component' => $data['component'],
            'content' => $data['content'] ?? ($widget['default_content'] ?? []),
            'styles' => $data['styles'] ?? ($widget['default_styles'] ?? []),
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
        $tree = $page->getBlocksTree();
        $html = '';
        $hoverRules = [];

        foreach ($tree as $block) {
            $result = $this->renderBlock($block);
            $html .= $result['html'];
            if (!empty($result['hoverCss'])) {
                $hoverRules[] = $result['hoverCss'];
            }
        }

        // Inject hover styles
        if (!empty($hoverRules)) {
            $html = '<style>' . implode("\n", $hoverRules) . '</style>' . $html;
        }

        return $html;
    }

    private function renderBlock(array $block): array
    {
        $default = ['html' => '', 'hoverCss' => ''];

        if (empty($block['is_visible'])) {
            return $default;
        }

        $component = $block['component'] ?? '';
        $content = $block['content'] ?? [];
        $allStyles = $block['styles'] ?? [];
        $desktopStyles = $allStyles['desktop'] ?? [];
        $buttonStyles = $allStyles['button'] ?? [];
        $hoverStyles = $allStyles['hover'] ?? [];

        $styles = $this->buildInlineStyles($desktopStyles);
        $buttonCss = $this->buildInlineStyles($buttonStyles);

        $childrenHtml = '';
        $childHoverRules = [];
        if (!empty($block['children'])) {
            foreach ($block['children'] as $child) {
                $childResult = $this->renderBlock($child);
                $childrenHtml .= $childResult['html'];
                if (!empty($childResult['hoverCss'])) {
                    $childHoverRules[] = $childResult['hoverCss'];
                }
            }
        }

        // Content variables
        $tag = $content['tag'] ?? 'h2';
        $text = $content['text'] ?? '';
        $link = $content['link'] ?? '#';
        $src = $content['src'] ?? '';
        $alt = $content['alt'] ?? '';
        $caption = $content['caption'] ?? '';
        $dividerColor = $content['color'] ?? '#e5e7eb';
        $height = $content['height'] ?? '48px';
        $maxWidth = $content['maxWidth'] ?? '1200px';

        // Build hover CSS
        $hoverCss = '';
        if (!empty($hoverStyles)) {
            $hoverInline = $this->buildInlineStyles($hoverStyles);
            if ($hoverInline) {
                $blockId = $block['id'] ?? 0;
                $sel = $component === 'widget-button'
                    ? ".lp-block-{$blockId} .lp-btn"
                    : ".lp-block-{$blockId}";
                $hoverCss = "{$sel}:hover{{$hoverInline}}";
            }
        }

        // Heading sizes
        $headingSizes = [
            'h1' => '48px', 'h2' => '36px', 'h3' => '28px',
            'h4' => '22px', 'h5' => '18px', 'h6' => '16px',
        ];

        $blockId = $block['id'] ?? 0;

        // Render each component
        $inner = '';
        switch ($component) {
            case 'widget-heading':
                $fontSize = $headingSizes[$tag] ?? '36px';
                $inner = "<{$tag} style=\"font-size:{$fontSize};font-weight:700;line-height:1.2;color:#111827;margin:0\">{$this->e($text)}</{$tag}>";
                break;

            case 'widget-text':
                $inner = "<p style=\"font-size:16px;line-height:1.7;color:#4b5563;margin:0\">{$this->e($text)}</p>";
                break;

            case 'widget-button':
                $btnDefaults = [
                    'background-color' => '#4f46e5',
                    'color' => '#ffffff',
                    'border' => 'none',
                    'border-radius' => '10px',
                    'padding' => '12px 28px',
                    'font-size' => '15px',
                    'font-weight' => '600',
                    'box-shadow' => '0 2px 8px rgba(79,70,229,.25)',
                    'transition' => 'all .2s',
                    'cursor' => 'pointer',
                    'display' => 'inline-flex',
                    'align-items' => 'center',
                    'gap' => '8px',
                    'text-decoration' => 'none',
                ];
                $btnMerged = array_merge($btnDefaults, array_filter($buttonStyles, fn($v) => $v !== '' && $v !== null));
                if (!empty($btnMerged['gradient'])) {
                    $btnMerged['background'] = $btnMerged['gradient'];
                    unset($btnMerged['background-color']);
                    unset($btnMerged['gradient']);
                }
                $btnInline = $this->buildInlineStyles($btnMerged);
                $textAlign = $desktopStyles['text-align'] ?? 'center';
                $wrapPadding = $this->resolveValue($desktopStyles['padding'] ?? '');
                $wrapMargin = $this->resolveValue($desktopStyles['margin'] ?? '');
                $wrapStyle = "text-align:{$textAlign}";
                if ($wrapPadding) $wrapStyle .= ";padding:{$wrapPadding}";
                if ($wrapMargin) $wrapStyle .= ";margin:{$wrapMargin}";
                $inner = "<div style=\"{$wrapStyle}\"><a href=\"{$this->e($link)}\" class=\"lp-btn\" style=\"{$btnInline}\">{$this->e($text)}</a></div>";
                break;

            case 'widget-image':
                if ($src) {
                    $borderRadius = $desktopStyles['border-radius'] ?? '12px';
                    $inner = "<div style=\"border-radius:{$borderRadius};overflow:hidden\"><img src=\"{$this->e($src)}\" alt=\"{$this->e($alt)}\" style=\"width:100%;display:block\"></div>";
                    if ($caption) {
                        $inner .= "<p style=\"text-align:center;font-size:13px;color:#71717a;margin-top:10px\">{$this->e($caption)}</p>";
                    }
                } else {
                    $inner = "<div style=\"padding:40px;background:#f3f4f6;border-radius:12px;text-align:center\"><i class=\"bi bi-image\" style=\"font-size:28px;color:#d1d5db\"></i></div>";
                }
                break;

            case 'widget-divider':
                $inner = "<hr style=\"border:none;border-top:1px solid {$this->e($dividerColor)};margin:0;{$styles}\">";
                break;

            case 'widget-spacer':
                $inner = "<div style=\"height:{$this->e($height)};{$styles}\"></div>";
                break;

            case 'widget-icon':
                $iconName = $content['name'] ?? 'bi-star';
                $iconSize = $content['size'] ?? '24px';
                $iconColor = $content['color'] ?? '#4f46e5';
                $inner = "<div style=\"width:56px;height:56px;display:flex;align-items:center;justify-content:center;background:#eef2ff;border-radius:14px;margin:0 auto;{$styles}\"><i class=\"bi {$this->e($iconName)}\" style=\"font-size:{$this->e($iconSize)};color:{$this->e($iconColor)}\"></i></div>";
                break;

            case 'widget-logo':
                $logoHeight = $content['height'] ?? '40px';
                if ($src) {
                    $inner = "<div style=\"text-align:center;{$styles}\"><img src=\"{$this->e($src)}\" style=\"height:{$this->e($logoHeight)};object-fit:contain\"></div>";
                } else {
                    $inner = "<div style=\"text-align:center;font-size:22px;font-weight:800;color:#4f46e5;{$styles}\">LOGO</div>";
                }
                break;

            case 'widget-social':
                $iconMap = [
                    'instagram' => 'bi-instagram', 'telegram' => 'bi-telegram',
                    'whatsapp' => 'bi-whatsapp', 'twitter' => 'bi-twitter-x',
                    'linkedin' => 'bi-linkedin', 'youtube' => 'bi-youtube',
                    'facebook' => 'bi-facebook',
                ];
                $links = '';
                foreach ($content['links'] ?? [] as $linkItem) {
                    $platform = $linkItem['platform'] ?? 'link';
                    $url = $linkItem['url'] ?? '#';
                    $icon = $iconMap[$platform] ?? 'bi-link';
                    $links .= "<a href=\"{$this->e($url)}\" style=\"width:40px;height:40px;display:inline-flex;align-items:center;justify-content:center;background:#f3f4f6;border-radius:10px;color:#6b7280;text-decoration:none;transition:all .2s\" onmouseover=\"this.style.background='#4f46e5';this.style.color='#fff'\" onmouseout=\"this.style.background='#f3f4f6';this.style.color='#6b7280'\"><i class=\"bi {$this->e($icon)}\"></i></a>";
                }
                $inner = "<div style=\"display:flex;gap:10px;justify-content:center;flex-wrap:wrap;{$styles}\">{$links}</div>";
                break;

            case 'widget-map':
                $mapHeight = $content['height'] ?? '300px';
                $inner = "<div style=\"height:{$this->e($mapHeight)};background:#e0e7ff;border-radius:12px;display:flex;align-items:center;justify-content:center;{$styles}\"><i class=\"bi bi-geo-alt\" style=\"font-size:36px;color:#6366f1\"></i></div>";
                break;

            case 'widget-stats':
                $statsHtml = '';
                foreach ($content['items'] ?? [] as $item) {
                    $statsHtml .= "<div style=\"text-align:center;padding:16px\"><div style=\"font-size:36px;font-weight:800;color:#4f46e5;line-height:1\">{$this->e($item['value'] ?? '0')}</div><div style=\"font-size:13px;color:#6b7280;margin-top:6px\">{$this->e($item['label'] ?? '')}</div></div>";
                }
                $inner = "<div style=\"display:flex;justify-content:center;gap:24px;flex-wrap:wrap;{$styles}\">{$statsHtml}</div>";
                break;

            case 'content-list':
                $listItems = '';
                foreach ($content['items'] ?? [] as $item) {
                    $listItems .= "<li style=\"padding:8px 0;border-bottom:1px solid #f3f4f6;display:flex;align-items:center;gap:10px\"><span style=\"width:6px;height:6px;background:#4f46e5;border-radius:50%;flex-shrink:0\"></span><span style=\"font-size:15px;color:#374151\">{$this->e($item)}</span></li>";
                }
                $inner = "<ul style=\"list-style:none;padding:0;margin:0;{$styles}\">{$listItems}</ul>";
                break;

            case 'content-counter':
                $counterValue = $content['value'] ?? '0';
                $counterSuffix = $content['suffix'] ?? '';
                $counterLabel = $content['label'] ?? '';
                $inner = "<div style=\"text-align:center;padding:12px 0;{$styles}\"><div style=\"font-size:48px;font-weight:800;color:#4f46e5;line-height:1\">{$this->e($counterValue)}{$this->e($counterSuffix)}</div><div style=\"font-size:14px;color:#6b7280;margin-top:6px\">{$this->e($counterLabel)}</div></div>";
                break;

            case 'content-progress':
                $progressValue = $content['value'] ?? 0;
                $progressLabel = $content['label'] ?? '';
                $inner = "<div style=\"margin-bottom:6px;display:flex;justify-content:space-between;{$styles}\"><span style=\"font-size:13px;color:#374151;font-weight:500\">{$this->e($progressLabel)}</span><span style=\"font-size:13px;color:#9ca3af\">{$this->e($progressValue)}%</span></div><div style=\"height:8px;background:#f3f4f6;border-radius:100px;overflow:hidden\"><div style=\"height:100%;width:{$this->e($progressValue)}%;background:linear-gradient(90deg,#4f46e5,#7c3aed);border-radius:100px\"></div></div>";
                break;

            case 'advanced-countdown':
                $countdownLabel = $content['label'] ?? '';
                $inner = "<div style=\"text-align:center;padding:16px 0;{$styles}\"><div style=\"font-size:40px;font-weight:800;color:#111827;font-variant-numeric:tabular-nums\">--:--:--:--</div><div style=\"font-size:13px;color:#6b7280;margin-top:6px\">{$this->e($countdownLabel)}</div></div>";
                break;

            case 'advanced-qrcode':
                $qrSize = $content['size'] ?? 180;
                $inner = "<div style=\"text-align:center;padding:20px 0;{$styles}\"><div style=\"width:{$this->e($qrSize)}px;height:{$this->e($qrSize)}px;background:#fff;border:2px solid #e5e7eb;border-radius:12px;display:inline-flex;align-items:center;justify-content:center\"><i class=\"bi bi-qr-code\" style=\"font-size:40px;color:#d1d5db\"></i></div></div>";
                break;

            case 'deco-gradient':
                $gradHeight = $content['height'] ?? '200px';
                $gradDir = $content['direction'] ?? '135deg';
                $gradFrom = $content['from'] ?? '#4f46e5';
                $gradTo = $content['to'] ?? '#7c3aed';
                $inner = "<div style=\"height:{$this->e($gradHeight)};background:linear-gradient({$this->e($gradDir)},{$this->e($gradFrom)},{$this->e($gradTo)})\"></div>";
                break;

            case 'deco-shape':
                $shapeHeight = $content['height'] ?? '80px';
                $shapeColor = $content['color'] ?? '#ffffff';
                $inner = "<div style=\"height:{$this->e($shapeHeight)};overflow:hidden;line-height:0\"><svg viewBox=\"0 0 1440 100\" preserveAspectRatio=\"none\" style=\"width:100%;height:100%\"><path fill=\"{$this->e($shapeColor)}\" d=\"M0,50 C480,100 960,0 1440,50 L1440,100 L0,100 Z\"></path></svg></div>";
                break;

            default:
                $inner = "<div style=\"padding:24px;background:#f9fafb;border-radius:10px;text-align:center;border:1px dashed #d1d5db;{$styles}\"><i class=\"bi bi-puzzle\" style=\"font-size:20px;color:#d1d5db\"></i><p style=\"font-size:11px;color:#9ca3af;margin-top:6px\">{$this->e($component)}</p></div>";
                break;
        }

        // Apply wrapper styles (for non-button widgets)
        $wrapperStyles = $styles;
        if ($component === 'widget-button') {
            $wrapperStyles = '';
        }

        $wrapperClass = "lp-block-{$blockId}";
        $wrapperStyle = $wrapperStyles ? " style=\"{$wrapperStyles}\"" : '';

        $html = "<div class=\"{$wrapperClass}\"{$wrapperStyle}>{$inner}</div>";

        // Merge hover rules
        $allHoverCss = $hoverCss;
        if (!empty($childHoverRules)) {
            $allHoverCss = implode("\n", $childHoverRules) . ($allHoverCss ? "\n{$allHoverCss}" : '');
        }

        return ['html' => $html, 'hoverCss' => $allHoverCss];
    }

    private function e(?string $value): string
    {
        return e($value ?? '');
    }

    private function resolveValue($value): string
    {
        if (is_array($value)) {
            $top = $value['top'] ?? '0';
            $right = $value['right'] ?? $value['left'] ?? '0';
            $bottom = $value['bottom'] ?? '0';
            $left = $value['left'] ?? $value['right'] ?? '0';
            return "{$top} {$right} {$bottom} {$left}";
        }
        return (string) $value;
    }

    private function buildInlineStyles(array $styles): string
    {
        $css = [];

        foreach ($styles as $property => $value) {
            if ($value === '' || $value === null || $value === false) {
                continue;
            }

            // Handle nested padding/margin objects
            if (in_array($property, ['padding', 'margin']) && is_array($value)) {
                $top = $value['top'] ?? '0';
                $right = $value['right'] ?? $value['left'] ?? '0';
                $bottom = $value['bottom'] ?? '0';
                $left = $value['left'] ?? $value['right'] ?? '0';
                $css[] = "{$property}:{$top} {$right} {$bottom} {$left}";
                continue;
            }

            // Skip nested arrays that aren't padding/margin
            if (is_array($value)) {
                continue;
            }

            $css[] = "{$property}:{$value}";
        }

        return implode(';', $css);
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
