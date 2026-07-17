<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\LandingPage;

class LandingPageRenderer
{
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

        $blockId = $block['id'] ?? 0;
        $inner = $this->renderComponent($component, $content, $desktopStyles, $buttonStyles, $childrenHtml, $styles);

        $wrapperClass = "lp-block-{$blockId}";
        $wrapperStyle = ($component !== 'widget-button' && $styles) ? " style=\"{$styles}\"" : '';
        $html = "<div class=\"{$wrapperClass}\"{$wrapperStyle}>{$inner}</div>";

        $allHoverCss = $hoverCss;
        if (!empty($childHoverRules)) {
            $allHoverCss = implode("\n", $childHoverRules) . ($allHoverCss ? "\n{$allHoverCss}" : '');
        }

        return ['html' => $html, 'hoverCss' => $allHoverCss];
    }

    private function renderComponent(string $component, array $content, array $ds, array $bs, string $childrenHtml, string $styles): string
    {
        $headingSizes = ['h1' => '48px', 'h2' => '36px', 'h3' => '28px', 'h4' => '22px', 'h5' => '18px', 'h6' => '16px'];

        switch ($component) {
            case 'widget-heading':
                $tag = $content['tag'] ?? 'h2';
                $fontSize = $headingSizes[$tag] ?? '36px';
                $textColor = $ds['color'] ?? '#111827';
                $textAlign = $ds['text-align'] ?? 'center';
                return "<{$tag} style=\"font-size:{$fontSize};font-weight:700;line-height:1.2;color:{$this->e($textColor)};margin:0;text-align:{$textAlign}\">{$this->e($content['text'] ?? '')}</{$tag}>";

            case 'widget-text':
                $textColor = $ds['color'] ?? '#4b5563';
                $textAlign = $ds['text-align'] ?? 'right';
                return "<p style=\"font-size:16px;line-height:1.7;color:{$this->e($textColor)};margin:0;text-align:{$textAlign}\">{$this->e($content['text'] ?? '')}</p>";

            case 'widget-button':
                return $this->renderButton($content, $bs, $ds);

            case 'widget-image':
                return $this->renderImage($content, $ds);

            case 'widget-divider':
                return "<hr style=\"border:none;border-top:1px solid {$this->e($content['color'] ?? '#e5e7eb')};margin:0\">";

            case 'widget-spacer':
                return "<div style=\"height:{$this->e($content['height'] ?? '48px')}\"></div>";

            case 'widget-icon':
                $iconName = $content['name'] ?? 'bi-star';
                $iconSize = $content['size'] ?? '24px';
                $iconColor = $content['color'] ?? '#4f46e5';
                $textAlign = $ds['text-align'] ?? 'center';
                return "<div style=\"text-align:{$textAlign}\"><div style=\"width:56px;height:56px;display:inline-flex;align-items:center;justify-content:center;background:#eef2ff;border-radius:14px\"><i class=\"bi {$this->e($iconName)}\" style=\"font-size:{$this->e($iconSize)};color:{$this->e($iconColor)}\"></i></div></div>";

            case 'widget-logo':
                return $this->renderLogo($content, $ds);

            case 'widget-social':
                return $this->renderSocial($content, $ds);

            case 'widget-map':
                $mapHeight = $content['height'] ?? '300px';
                return "<div style=\"height:{$this->e($mapHeight)};background:#e0e7ff;border-radius:12px;display:flex;align-items:center;justify-content:center;{$styles}\"><i class=\"bi bi-geo-alt\" style=\"font-size:36px;color:#6366f1\"></i></div>";

            case 'widget-stats':
                $statsHtml = '';
                foreach ($content['items'] ?? [] as $item) {
                    $statsHtml .= "<div style=\"text-align:center;padding:16px\"><div style=\"font-size:36px;font-weight:800;color:#4f46e5;line-height:1\">{$this->e($item['value'] ?? '0')}</div><div style=\"font-size:13px;color:#6b7280;margin-top:6px\">{$this->e($item['label'] ?? '')}</div></div>";
                }
                return "<div style=\"display:flex;justify-content:center;gap:24px;flex-wrap:wrap;{$styles}\">{$statsHtml}</div>";

            case 'content-card':
                return $this->renderCardGrid($content, $ds);

            case 'content-list':
                return $this->renderList($content, $ds);

            case 'content-counter':
                $counterValue = $content['value'] ?? '0';
                $counterSuffix = $content['suffix'] ?? '';
                $counterLabel = $content['label'] ?? '';
                return "<div style=\"text-align:center;padding:12px 0;{$styles}\"><div style=\"font-size:48px;font-weight:800;color:#4f46e5;line-height:1\">{$this->e($counterValue)}{$this->e($counterSuffix)}</div><div style=\"font-size:14px;color:#6b7280;margin-top:6px\">{$this->e($counterLabel)}</div></div>";

            case 'content-progress':
                $progressValue = $content['value'] ?? 0;
                $progressLabel = $content['label'] ?? '';
                return "<div style=\"margin-bottom:6px;display:flex;justify-content:space-between;{$styles}\"><span style=\"font-size:13px;color:#374151;font-weight:500\">{$this->e($progressLabel)}</span><span style=\"font-size:13px;color:#9ca3af\">{$this->e($progressValue)}%</span></div><div style=\"height:8px;background:#f3f4f6;border-radius:100px;overflow:hidden\"><div style=\"height:100%;width:{$this->e($progressValue)}%;background:linear-gradient(90deg,#4f46e5,#7c3aed);border-radius:100px\"></div></div>";

            case 'advanced-countdown':
                $countdownLabel = $content['label'] ?? '';
                return "<div style=\"text-align:center;padding:16px 0;{$styles}\"><div style=\"font-size:40px;font-weight:800;color:#111827;font-variant-numeric:tabular-nums\">--:--:--:--</div><div style=\"font-size:13px;color:#6b7280;margin-top:6px\">{$this->e($countdownLabel)}</div></div>";

            case 'advanced-qrcode':
                $qrSize = $content['size'] ?? 180;
                return "<div style=\"text-align:center;padding:20px 0;{$styles}\"><div style=\"width:{$this->e($qrSize)}px;height:{$this->e($qrSize)}px;background:#fff;border:2px solid #e5e7eb;border-radius:12px;display:inline-flex;align-items:center;justify-content:center\"><i class=\"bi bi-qr-code\" style=\"font-size:40px;color:#d1d5db\"></i></div></div>";

            case 'deco-gradient':
                $gradHeight = $content['height'] ?? '200px';
                $gradDir = $content['direction'] ?? '135deg';
                $gradFrom = $content['from'] ?? '#4f46e5';
                $gradTo = $content['to'] ?? '#7c3aed';
                return "<div style=\"height:{$this->e($gradHeight)};background:linear-gradient({$this->e($gradDir)},{$this->e($gradFrom)},{$this->e($gradTo)})\"></div>";

            case 'deco-shape':
                $shapeHeight = $content['height'] ?? '80px';
                $shapeColor = $content['color'] ?? '#ffffff';
                return "<div style=\"height:{$this->e($shapeHeight)};overflow:hidden;line-height:0\"><svg viewBox=\"0 0 1440 100\" preserveAspectRatio=\"none\" style=\"width:100%;height:100%\"><path fill=\"{$this->e($shapeColor)}\" d=\"M0,50 C480,100 960,0 1440,50 L1440,100 L0,100 Z\"></path></svg></div>";

            default:
                return "<div style=\"padding:24px;background:#f9fafb;border-radius:10px;text-align:center;border:1px dashed #d1d5db;{$styles}\"><i class=\"bi bi-puzzle\" style=\"font-size:20px;color:#d1d5db\"></i><p style=\"font-size:11px;color:#9ca3af;margin-top:6px\">{$this->e($component)}</p></div>";
        }
    }

    private function renderButton(array $content, array $bs, array $ds): string
    {
        $text = $content['text'] ?? 'کلیک کنید';
        $link = $content['link'] ?? '#';
        $btnDefaults = [
            'background-color' => '#4f46e5', 'color' => '#ffffff', 'border' => 'none',
            'border-radius' => '10px', 'padding' => '12px 28px', 'font-size' => '15px',
            'font-weight' => '600', 'cursor' => 'pointer', 'text-decoration' => 'none',
        ];
        $btnMerged = array_merge($btnDefaults, array_filter($bs, fn($v) => $v !== '' && $v !== null));
        if (!empty($btnMerged['gradient'])) {
            $btnMerged['background'] = $btnMerged['gradient'];
            unset($btnMerged['background-color'], $btnMerged['gradient']);
        }
        $btnInline = $this->buildInlineStyles($btnMerged);
        $textAlign = $ds['text-align'] ?? 'center';
        $wrapPadding = $this->resolveValue($ds['padding'] ?? '');
        $wrapStyle = "text-align:{$textAlign}";
        if ($wrapPadding) $wrapStyle .= ";padding:{$wrapPadding}";
        return "<div style=\"{$wrapStyle}\"><a href=\"{$this->e($link)}\" class=\"lp-btn\" style=\"{$btnInline}\">{$this->e($text)}</a></div>";
    }

    private function renderImage(array $content, array $ds): string
    {
        $src = $content['src'] ?? '';
        $alt = $content['alt'] ?? '';
        $caption = $content['caption'] ?? '';
        if ($src) {
            $borderRadius = $ds['border-radius'] ?? '12px';
            $html = "<div style=\"border-radius:{$borderRadius};overflow:hidden\"><img src=\"{$this->e($src)}\" alt=\"{$this->e($alt)}\" style=\"width:100%;display:block\"></div>";
            if ($caption) {
                $html .= "<p style=\"text-align:center;font-size:13px;color:#71717a;margin-top:10px\">{$this->e($caption)}</p>";
            }
            return $html;
        }
        return "<div style=\"padding:40px;background:#f3f4f6;border-radius:12px;text-align:center\"><i class=\"bi bi-image\" style=\"font-size:28px;color:#d1d5db\"></i></div>";
    }

    private function renderLogo(array $content, array $ds): string
    {
        $mode = $content['mode'] ?? 'icon';
        $iconClass = $content['icon'] ?? 'bi-badge-ad';
        $iconColor = $ds['color'] ?? '#4f46e5';
        $iconSize = $ds['font-size'] ?? '48px';
        $imgW = $ds['width'] ?? '';
        $imgH = $ds['height'] ?? '';
        $src = $content['src'] ?? '';
        $alt = $content['alt'] ?? '';
        $title = !empty($content['title']) ? ' title="' . $this->e($content['title']) . '"' : '';
        $aria = !empty($content['ariaLabel']) ? ' aria-label="' . $this->e($content['ariaLabel']) . '"' : '';
        $link = $content['link'] ?? '';
        $hasLink = !empty(trim($link));
        $target = ($content['openNewTab'] ?? false) ? ' target="_blank"' : '';
        $relParts = [];
        if ($content['nofollow'] ?? false) $relParts[] = 'nofollow';
        if ($content['noopener'] ?? false) $relParts[] = 'noopener';
        if ($content['noreferrer'] ?? false) $relParts[] = 'noreferrer';
        $rel = !empty($relParts) ? ' rel="' . implode(' ', $relParts) . '"' : '';
        $linkOpen = $hasLink ? "<a href=\"{$this->e($link)}\"{$target}{$rel} style=\"text-decoration:none;display:inline-flex;align-items:center;justify-content:center\">" : '';
        $linkClose = $hasLink ? '</a>' : '';

        if ($mode === 'image' && !empty($src)) {
            $imgMaxW = $imgW ?: '100%';
            $imgStyle = "max-width:{$imgMaxW};";
            if ($imgH) $imgStyle .= "height:{$imgH};";
            $imgStyle .= 'object-fit:contain;display:block;margin:0 auto';
            return $linkOpen . "<img src=\"{$this->e($src)}\" alt=\"{$this->e($alt)}\"{$title}{$aria} style=\"{$imgStyle}\">" . $linkClose;
        }
        return $linkOpen . "<i class=\"bi {$this->e($iconClass)}\"{$title}{$aria} style=\"font-size:{$this->e($iconSize)};color:{$this->e($iconColor)}\"></i>" . $linkClose;
    }

    private function renderSocial(array $content, array $ds): string
    {
        $iconMap = [
            'instagram' => 'bi-instagram', 'telegram' => 'bi-telegram', 'whatsapp' => 'bi-whatsapp',
            'twitter' => 'bi-twitter-x', 'linkedin' => 'bi-linkedin', 'youtube' => 'bi-youtube',
            'facebook' => 'bi-facebook',
        ];
        $links = '';
        foreach ($content['links'] ?? [] as $item) {
            $platform = $item['platform'] ?? 'link';
            $url = $item['url'] ?? '#';
            $icon = $iconMap[$platform] ?? 'bi-link';
            $links .= "<a href=\"{$this->e($url)}\" style=\"width:40px;height:40px;display:inline-flex;align-items:center;justify-content:center;background:#f3f4f6;border-radius:10px;color:#6b7280;text-decoration:none;transition:all .2s\" onmouseover=\"this.style.background='#4f46e5';this.style.color='#fff'\" onmouseout=\"this.style.background='#f3f4f6';this.style.color='#6b7280'\"><i class=\"bi {$this->e($icon)}\"></i></a>";
        }
        return "<div style=\"display:flex;gap:10px;justify-content:center;flex-wrap:wrap\">{$links}</div>";
    }

    private function renderCardGrid(array $content, array $ds): string
    {
        $layout = $content['layout'] ?? 'vertical';
        $grid = $content['grid'] ?? ['desktop' => 3, 'tablet' => 2, 'mobile' => 1];
        $cards = $content['cards'] ?? [];
        $cols = $grid['desktop'] ?? 3;
        $colsTablet = $grid['tablet'] ?? 2;
        $colsMobile = $grid['mobile'] ?? 1;
        $gap = $ds['gap'] ?? '24px';
        $bg = $ds['background'] ?? '#ffffff';
        $border = $ds['border'] ?? '1px solid #e5e7eb';
        $radius = $ds['border-radius'] ?? '16px';
        $shadow = $ds['box-shadow'] ?? '0 4px 6px -1px rgba(0,0,0,0.1)';
        $hoverLift = $ds['hover-lift'] ?? '-6px';
        $hoverShadow = $ds['hover-shadow'] ?? '0 20px 25px -5px rgba(0,0,0,0.1)';
        $hoverScale = $ds['hover-scale'] ?? '1';

        if (empty($cards)) {
            return '<div style="padding:40px;text-align:center;color:#9ca3af;border:2px dashed #e5e7eb;border-radius:12px"><i class="bi bi-plus-circle" style="font-size:24px;display:block;margin-bottom:8px"></i>کارتی اضافه نکرده‌اید</div>';
        }

        $hoverCSS = ".cw-hover{transition:transform .3s,box-shadow .3s}.cw-hover:hover{transform:translateY({$this->e($hoverLift)}) scale({$this->e($hoverScale)});box-shadow:{$this->e($hoverShadow)}}";
        $gridCSS = "display:grid;grid-template-columns:repeat({$cols},1fr);gap:{$this->e($gap)}";

        $cardsHTML = '';
        foreach ($cards as $card) {
            $cardInner = $this->renderCardItem($card, $ds, $layout);
            $cardsHTML .= "<div class=\"cw-hover\" style=\"background:{$this->e($bg)};border:{$this->e($border)};border-radius:{$this->e($radius)};box-shadow:{$this->e($shadow)};overflow:hidden;display:flex;flex-direction:column\">{$cardInner}</div>";
        }

        return "<style>{$hoverCSS}</style><div style=\"{$gridCSS};@media(max-width:991px){grid-template-columns:repeat({$colsTablet},1fr)}@media(max-width:767px){grid-template-columns:repeat({$colsMobile},1fr)}\">{$cardsHTML}</div>";
    }

    private function renderCardItem(array $card, array $ds, string $layout): string
    {
        $showImage = $card['showImage'] ?? true;
        $showBadge = $card['showBadge'] ?? false;
        $showCategory = $card['showCategory'] ?? false;
        $showTitle = $card['showTitle'] ?? true;
        $showDescription = $card['showDescription'] ?? true;
        $showButton = $card['showButton'] ?? true;
        $image = $card['image'] ?? '';
        $badge = $card['badge'] ?? '';
        $badgeColor = $card['badgeColor'] ?? ($ds['badge-bg'] ?? '#4f46e5');
        $category = $card['category'] ?? '';
        $title = $card['title'] ?? '';
        $description = $card['description'] ?? '';
        $buttonText = $card['buttonText'] ?? '';
        $buttonLink = $card['buttonLink'] ?? '#';
        $buttonNewTab = $card['buttonNewTab'] ?? false;
        $cardLink = $card['cardLink'] ?? '';
        $isHorizontal = $layout === 'horizontal';
        $isOverlay = $layout === 'overlay';

        $imageHTML = '';
        if ($showImage) {
            $imgObjFit = $ds['image-object-fit'] ?? 'cover';
            $imgBorderRadius = $ds['image-border-radius'] ?? '16px 0 0 16px';
            $imgBorderRadiusVertical = $ds['image-border-radius'] ?? '16px 16px 0 0';
            $imgAspectRatio = $ds['image-aspect-ratio'] ?? '16/9';
            $imgTag = $image ? "<img src=\"{$this->e($image)}\" alt=\"\" style=\"width:100%;height:100%;object-fit:{$imgObjFit};display:block\">" : '<div style="width:100%;height:100%;background:#f3f4f6;display:flex;align-items:center;justify-content:center"><i class="bi bi-image" style="font-size:24px;color:#d1d5db"></i></div>';
            $imgWrapperStyle = $isHorizontal ? "width:200px;flex-shrink:0;border-radius:{$imgBorderRadius}" : "aspect-ratio:{$imgAspectRatio};overflow:hidden;border-radius:{$imgBorderRadiusVertical}";
            $imageHTML = "<div style=\"{$imgWrapperStyle}\">{$imgTag}</div>";
        }

        $badgeColorVal = $ds['badge-color'] ?? '#fff';
        $badgeSizeVal = $ds['badge-size'] ?? '11px';
        $badgePaddingVal = $ds['badge-padding'] ?? '4px 12px';
        $badgeRadiusVal = $ds['badge-radius'] ?? '20px';
        $catColorVal = $ds['category-color'] ?? '#6b7280';
        $catSizeVal = $ds['category-size'] ?? '12px';
        $catTransformVal = $ds['category-text-transform'] ?? 'uppercase';
        $catSpacingVal = $ds['category-letter-spacing'] ?? '0.05em';
        $titleColorVal = $ds['title-color'] ?? '#111827';
        $titleSizeVal = $ds['title-size'] ?? '18px';
        $titleWeightVal = $ds['title-weight'] ?? '600';
        $descColorVal = $ds['description-color'] ?? '#6b7280';
        $descSizeVal = $ds['description-size'] ?? '14px';
        $descLhVal = $ds['description-line-height'] ?? '1.6';
        $btnBgVal = $ds['btn-bg'] ?? '#4f46e5';
        $btnColorVal = $ds['btn-color'] ?? '#fff';
        $btnPaddingVal = $ds['btn-padding'] ?? '10px 20px';
        $btnRadiusVal = $ds['btn-radius'] ?? '10px';
        $btnFontSizeVal = $ds['btn-font-size'] ?? '14px';
        $btnFontWeightVal = $ds['btn-font-weight'] ?? '600';

        $badgeHTML = ($showBadge && $badge) ? "<span style=\"background:{$this->e($badgeColor)};color:{$badgeColorVal};font-size:{$badgeSizeVal};font-weight:600;padding:{$badgePaddingVal};border-radius:{$badgeRadiusVal}\">{$this->e($badge)}</span>" : '';
        $catHTML = ($showCategory && $category) ? "<div style=\"color:{$catColorVal};font-size:{$catSizeVal};text-transform:{$catTransformVal};letter-spacing:{$catSpacingVal};font-weight:500;margin-bottom:6px\">{$this->e($category)}</div>" : '';
        $titleHTML = ($showTitle && $title) ? "<h3 style=\"color:{$titleColorVal};font-size:{$titleSizeVal};font-weight:{$titleWeightVal};line-height:1.4;margin:0 0 8px 0\">{$this->e($title)}</h3>" : '';
        $descHTML = ($showDescription && $description) ? "<p style=\"color:{$descColorVal};font-size:{$descSizeVal};line-height:{$descLhVal};margin:0 0 16px 0\">{$this->e($description)}</p>" : '';
        $btnHTML = ($showButton && $buttonText) ? "<div><a href=\"{$this->e($buttonLink)}\"" . ($buttonNewTab ? ' target="_blank"' : '') . " style=\"display:inline-block;background:{$btnBgVal};color:{$btnColorVal};padding:{$btnPaddingVal};border-radius:{$btnRadiusVal};font-size:{$btnFontSizeVal};font-weight:{$btnFontWeightVal};text-decoration:none\">{$this->e($buttonText)}</a></div>" : '';

        $contentPadding = $isHorizontal ? '20px' : '20px 24px 24px';
        $contentHTML = "<div style=\"padding:{$contentPadding};display:flex;flex-direction:column;flex:1\">{$catHTML}" . ($badgeHTML ? "<div style=\"margin-bottom:8px\">{$badgeHTML}</div>" : '') . "{$titleHTML}{$descHTML}<div style=\"margin-top:auto\">{$btnHTML}</div></div>";

        if ($isHorizontal) {
            $cardInner = "<div style=\"display:flex;flex-direction:row;min-height:200px\">{$imageHTML}{$contentHTML}</div>";
        } elseif ($isOverlay) {
            $cardInner = "<div style=\"position:relative;min-height:300px;display:flex;align-items:flex-end\">" . ($imageHTML ? "<div style=\"position:absolute;inset:0\">{$imageHTML}</div>" : '') . "<div style=\"position:relative;z-index:1;width:100%;background:linear-gradient(transparent,rgba(0,0,0,0.7));padding:24px\">{$contentHTML}</div></div>";
        } else {
            $cardInner = "<div style=\"display:flex;flex-direction:column\">{$imageHTML}{$contentHTML}</div>";
        }

        if (!empty(trim($cardLink))) {
            $cardInner = "<a href=\"{$this->e($cardLink)}\" style=\"text-decoration:none;color:inherit;display:block;height:100%\">{$cardInner}</a>";
        }

        return $cardInner;
    }

    private function renderList(array $content, array $ds): string
    {
        $listItems = '';
        foreach ($content['items'] ?? [] as $item) {
            $listItems .= "<li style=\"padding:8px 0;border-bottom:1px solid #f3f4f6;display:flex;align-items:center;gap:10px\"><span style=\"width:6px;height:6px;background:#4f46e5;border-radius:50%;flex-shrink:0\"></span><span style=\"font-size:15px;color:#374151\">{$this->e($item)}</span></li>";
        }
        return "<ul style=\"list-style:none;padding:0;margin:0\">{$listItems}</ul>";
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
            if ($value === '' || $value === null || $value === false) continue;
            if (in_array($property, ['padding', 'margin']) && is_array($value)) {
                $top = $value['top'] ?? '0';
                $right = $value['right'] ?? $value['left'] ?? '0';
                $bottom = $value['bottom'] ?? '0';
                $left = $value['left'] ?? $value['right'] ?? '0';
                $css[] = "{$property}:{$top} {$right} {$bottom} {$left}";
                continue;
            }
            if (is_array($value)) continue;
            $css[] = "{$property}:{$value}";
        }
        return implode(';', $css);
    }
}
