<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\QrCode;
use chillerlan\QRCode\QRCode as QRCodeGenerator;
use chillerlan\QRCode\QROptions;
use chillerlan\QRCode\Data\QRMatrix;
use chillerlan\QRCode\Output\QRMarkupSVG;
use chillerlan\QRCode\Output\QRGdImagePNG;

/**
 * Single source of truth for all QR code rendering.
 * Every output path (preview, download, thumbnail) goes through here.
 */
class QrRenderer
{
    public static function isGdAvailable(): bool
    {
        return extension_loaded('gd');
    }

    private string $foregroundColor;
    private string $backgroundColor;
    private string $gradientFrom;
    private string $gradientTo;
    private string $eyeColor;
    private string $style;
    private string $shape;
    private string $pattern;
    private int $size;
    private string $errorCorrection;
    private string $eyeStyle;
    private string $frameStyle;
    private string $frameColor;
    private ?string $text;
    private string $textPosition;
    private string $textFont;
    private int $textSize;
    private string $textColor;
    private ?string $logoPath;
    private int $logoSize;
    private int $logoPadding;
    private int $margin;
    private int $resolution;
    private ?string $content;

    public static function fromModel(QrCode $qr): self
    {
        $r = new self();
        $r->foregroundColor = $qr->foreground_color ?? '#000000';
        $r->backgroundColor = $qr->background_color ?? '#FFFFFF';
        $r->gradientFrom = $qr->gradient_from ?? '';
        $r->gradientTo = $qr->gradient_to ?? '';
        $r->eyeColor = $qr->eye_color ?? $qr->foreground_color ?? '#000000';
        $r->style = $qr->style ?? 'square';
        $r->shape = $qr->shape ?? 'square';
        $r->pattern = $qr->pattern ?? 'default';
        $r->size = $qr->size ?? 300;
        $r->errorCorrection = $qr->error_correction ?? 'M';
        $r->eyeStyle = $qr->eye_style ?? 'square';
        $r->frameStyle = $qr->frame_style ?? 'none';
        $r->frameColor = $qr->frame_color ?? '#000000';
        $r->text = $qr->text;
        $r->textPosition = $qr->text_position ?? 'bottom';
        $r->textFont = $qr->text_font ?? 'Vazirmatn';
        $r->textSize = (int) ($qr->text_size ?? 14);
        $r->textColor = $qr->text_color ?? '#000000';
        $r->logoPath = $qr->logo_path ? storage_path('app/public/' . $qr->logo_path) : null;
        $r->logoSize = $qr->logo_size ?? 50;
        $r->logoPadding = $qr->logo_padding ?? 5;
        $r->margin = $qr->margin ?? 10;
        $r->resolution = $qr->resolution ?? 300;
        $r->content = $qr->content;
        return $r;
    }

    public static function fromLivewire(array $props): self
    {
        $r = new self();
        $r->foregroundColor = $props['foregroundColor'] ?? '#000000';
        $r->backgroundColor = $props['backgroundColor'] ?? '#FFFFFF';
        $r->gradientFrom = $props['gradientFrom'] ?? '';
        $r->gradientTo = $props['gradientTo'] ?? '';
        $r->eyeColor = $props['eyeColor'] ?? '#000000';
        $r->style = $props['style'] ?? 'square';
        $r->shape = $props['shape'] ?? 'square';
        $r->pattern = $props['pattern'] ?? 'default';
        $r->size = (int) ($props['size'] ?? 300);
        $r->errorCorrection = $props['errorCorrection'] ?? 'M';
        $r->eyeStyle = $props['eyeStyle'] ?? 'square';
        $r->frameStyle = $props['frameStyle'] ?? 'none';
        $r->frameColor = $props['frameColor'] ?? '#000000';
        $r->text = $props['text'] ?? null;
        $r->textPosition = $props['textPosition'] ?? 'bottom';
        $r->textFont = $props['textFont'] ?? 'Vazirmatn';
        $r->textSize = (int) ($props['textSize'] ?? 14);
        $r->textColor = $props['textColor'] ?? '#000000';
        $r->logoPath = null; // Livewire uses temporary upload, handled separately
        $r->logoSize = (int) ($props['logoSize'] ?? 50);
        $r->logoPadding = (int) ($props['logoPadding'] ?? 5);
        $r->margin = (int) ($props['margin'] ?? 10);
        $r->resolution = (int) ($props['resolution'] ?? 300);
        $r->content = $props['content'] ?? null;
        return $r;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function setLogoPath(?string $path): self
    {
        $this->logoPath = $path;
        return $this;
    }

    // ------------------------------------------------------------------
    // QR library options (shared by SVG and PNG)
    // ------------------------------------------------------------------

    private function buildModuleValues(): array
    {
        $foreground = $this->foregroundColor;

        if (!empty($this->gradientFrom) && !empty($this->gradientTo)) {
            // For PNG, gradient is not supported by the library — use foreground
            // For SVG, gradient is injected via svgDefs
            $foreground = 'url(#qrGrad)';
        }

        return [
            QRMatrix::M_DATA_DARK     => $foreground,
            QRMatrix::M_FINDER_DARK   => $this->eyeColor,
            QRMatrix::M_FINDER_DOT    => $this->eyeColor,
            QRMatrix::M_ALIGNMENT_DARK => $foreground,
        ];
    }

    private function getDrawCircular(): bool
    {
        return in_array($this->style, ['dots', 'rounded'], true);
    }

    private function getCircleRadius(): float
    {
        return match ($this->style) {
            'dots'    => 0.45,
            'rounded' => 0.38,
            default   => 0.45,
        };
    }

    private function getKeepAsSquare(): array
    {
        if (!$this->getDrawCircular() || in_array($this->eyeStyle, ['dots', 'circle'], true)) {
            return [];
        }
        return [QRMatrix::M_FINDER_DARK, QRMatrix::M_FINDER_DOT];
    }

    private function buildSvgDefs(): string
    {
        if (empty($this->gradientFrom) || empty($this->gradientTo)) {
            return '';
        }
        return '<linearGradient id="qrGrad" x1="0%" y1="0%" x2="100%" y2="100%">'
            . '<stop offset="0%" stop-color="' . $this->gradientFrom . '"/>'
            . '<stop offset="100%" stop-color="' . $this->gradientTo . '"/>'
            . '</linearGradient>';
    }

    // ------------------------------------------------------------------
    // SVG output (used for preview and SVG download)
    // ------------------------------------------------------------------

    public function toSvg(): string
    {
        if (empty($this->content)) {
            return '';
        }

        $options = new QROptions([
            'outputInterface'      => QRMarkupSVG::class,
            'eccLevel'             => $this->errorCorrection,
            'scale'                => max(1, (int) ($this->size / 50)),
            'bgColor'              => $this->backgroundColor,
            'drawLightModules'     => true,
            'moduleValues'         => $this->buildModuleValues(),
            'quietzoneSize'        => $this->margin,
            'svgDefs'              => $this->buildSvgDefs(),
            'svgUseFillAttributes' => true,
            'outputBase64'         => false,
            'drawCircularModules'  => $this->getDrawCircular(),
            'circleRadius'         => $this->getCircleRadius(),
            'keepAsSquare'         => $this->getKeepAsSquare(),
        ]);

        $qr = new QRCodeGenerator($options);
        $baseSvg = $qr->render($this->content);

        return $this->composeFullSvg($baseSvg);
    }

    public function toSvgDataUri(): string
    {
        $svg = $this->toSvg();
        if (empty($svg)) {
            return '';
        }
        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    // ------------------------------------------------------------------
    // PNG output (used for download)
    // ------------------------------------------------------------------

    public function toPng(): ?string
    {
        if (empty($this->content)) {
            return null;
        }

        if (!self::isGdAvailable()) {
            return null;
        }

        try {
            $options = new QROptions([
                'outputInterface'     => QRGdImagePNG::class,
                'eccLevel'            => $this->errorCorrection,
                'scale'               => max(1, (int) ($this->size / 50)),
                'imageBase64'         => false,
                'outputBase64'        => false,
                'bgColor'             => $this->hexToRgb($this->backgroundColor),
                'drawLightModules'    => true,
                'moduleValues'        => $this->buildModuleValuesForPng(),
                'quietzoneSize'       => $this->margin,
                'drawCircularModules' => $this->getDrawCircular(),
                'circleRadius'        => $this->getCircleRadius(),
                'keepAsSquare'        => $this->getKeepAsSquare(),
            ]);

            $qr = new QRCodeGenerator($options);
            $imageData = $qr->render($this->content);

            $baseImage = @imagecreatefromstring($imageData);
            if (!$baseImage) {
                return null;
            }

            $baseW = imagesx($baseImage);
            $baseH = imagesy($baseImage);

            $frameExtra = $this->frameStyle !== 'none' ? 30 : 0;
            $textExtra = !empty($this->text) ? 40 : 0;
            $textOffset = $this->textPosition === 'top' ? $textExtra : 0;

            $totalW = $baseW + ($frameExtra * 2);
            $totalH = $baseH + ($frameExtra * 2) + $textExtra;

            $canvas = imagecreatetruecolor($totalW, $totalH);
            $bg = $this->hexToRgb($this->backgroundColor);
            $bgc = imagecolorallocate($canvas, $bg[0], $bg[1], $bg[2]);
            imagefill($canvas, 0, 0, $bgc);

            if ($this->frameStyle !== 'none') {
                $fc = $this->hexToRgb($this->frameColor);
                $fcr = imagecolorallocate($canvas, $fc[0], $fc[1], $fc[2]);
                imagesetthickness($canvas, 3);
                imagerectangle($canvas, 4, 4 + $textOffset, $totalW - 4, $totalH - 4, $fcr);
                imagesetthickness($canvas, 1);
            }

            imagecopy($canvas, $baseImage, $frameExtra, $frameExtra + $textOffset, 0, 0, $baseW, $baseH);
            imagedestroy($baseImage);

            // Logo
            if ($this->logoPath && file_exists($this->logoPath)) {
                try {
                    $li = @imagecreatefromstring(file_get_contents($this->logoPath));
                    if ($li) {
                        $lw = imagesx($li);
                        $lh = imagesy($li);
                        $max = (int) (($this->logoSize / 100) * min($baseW, $baseH) * 0.3);
                        $r = min($max / max($lw, 1), $max / max($lh, 1));
                        $nw = max(1, (int) ($lw * $r));
                        $nh = max(1, (int) ($lh * $r));
                        $sl = imagecreatetruecolor($nw, $nh);
                        imagecopyresampled($sl, $li, 0, 0, 0, 0, $nw, $nh, $lw, $lh);
                        $lx = $frameExtra + (int) (($baseW - $nw) / 2);
                        $ly = $frameExtra + $textOffset + (int) (($baseH - $nh) / 2);
                        $wb = imagecolorallocate($canvas, 255, 255, 255);
                        imagefilledrectangle($canvas, $lx - $this->logoPadding, $ly - $this->logoPadding, $lx + $nw + $this->logoPadding, $ly + $nh + $this->logoPadding, $wb);
                        imagecopy($canvas, $sl, $lx, $ly, 0, 0, $nw, $nh);
                        imagedestroy($sl);
                        imagedestroy($li);
                    }
                } catch (\Throwable) {
                }
            }

            // Text
            if (!empty($this->text)) {
                $tc = $this->hexToRgb($this->textColor);
                $tcr = imagecolorallocate($canvas, $tc[0], $tc[1], $tc[2]);
                $tw = imagefontwidth(5) * mb_strlen($this->text);
                $tx = (int) (($totalW - $tw) / 2);
                $ty = $this->textPosition === 'top' ? 10 : ($totalH - 20);
                imagestring($canvas, 5, $tx, $ty, $this->text, $tcr);
            }

            ob_start();
            imagepng($canvas);
            $png = ob_get_clean();
            imagedestroy($canvas);

            return $png;
        } catch (\Throwable $e) {
            report($e);
            return null;
        }
    }

    public function toPngDataUri(): string
    {
        $png = $this->toPng();
        if (!$png) {
            return '';
        }
        return 'data:image/png;base64,' . base64_encode($png);
    }

    /**
     * GD output requires [R, G, B] arrays, not CSS hex strings.
     */
    private function buildModuleValuesForPng(): array
    {
        $fg = $this->hexToRgb($this->foregroundColor);
        if (!empty($this->gradientFrom) && !empty($this->gradientTo)) {
            $fg = $this->hexToRgb($this->gradientFrom);
        }
        $eye = $this->hexToRgb($this->eyeColor);
        return [
            QRMatrix::M_DATA_DARK     => $fg,
            QRMatrix::M_FINDER_DARK   => $eye,
            QRMatrix::M_FINDER_DOT    => $eye,
            QRMatrix::M_ALIGNMENT_DARK => $fg,
        ];
    }

    // ------------------------------------------------------------------
    // SVG composition (frame, logo, text) — shared by preview and export
    // ------------------------------------------------------------------

    private function composeFullSvg(string $baseSvg): string
    {
        $qrContent = $this->extractSvgContent($baseSvg);
        [$vbW, $vbH] = $this->extractViewBox($baseSvg);

        $framePad = $this->frameStyle !== 'none' ? 3.0 : 0;
        $textSpace = !empty($this->text) ? 6.0 : 0;
        $textOffset = $this->textPosition === 'top' ? $textSpace : 0;

        $totalW = $vbW + ($framePad * 2);
        $totalH = $vbH + ($framePad * 2) + $textSpace;

        $svg = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"'
            . ' viewBox="0 0 ' . $totalW . ' ' . $totalH . '"'
            . ' style="max-width:100%;height:auto;"'
            . ' preserveAspectRatio="xMidYMid meet">';

        $svg .= '<rect width="' . $totalW . '" height="' . $totalH . '" fill="' . $this->backgroundColor . '" rx="2"/>';

        // Frame
        if ($this->frameStyle !== 'none') {
            $sw = match ($this->frameStyle) { 'bubble' => 1.2, default => 0.8 };
            $rx = match ($this->frameStyle) { 'circle' => min($totalW, $totalH) / 2, 'bubble' => 3, default => 1.5 };
            $svg .= '<rect x="0.5" y="0.5" width="' . ($totalW - 1) . '" height="' . ($totalH - 1)
                . '" fill="none" stroke="' . $this->frameColor . '" stroke-width="' . $sw . '" rx="' . $rx . '"/>';
        }

        $svg .= '<g transform="translate(' . $framePad . ',' . ($framePad + $textOffset) . ')">';
        $svg .= $qrContent;
        $svg .= '</g>';

        // Logo
        $svg .= $this->buildLogoSvg($vbW, $vbH, $framePad, $textOffset);

        // Text
        $svg .= $this->buildTextSvg($totalW, $totalH);

        $svg .= '</svg>';

        return $svg;
    }

    private function buildLogoSvg(float $vbW, float $vbH, float $framePad, float $textOffset): string
    {
        if (!$this->logoPath || !file_exists($this->logoPath)) {
            return '';
        }

        try {
            $mime = mime_content_type($this->logoPath) ?: 'image/png';
            $dataUri = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($this->logoPath));
            $logoSize = ($this->logoSize / 100) * min($vbW, $vbH) * 0.3;
            $pad = $this->logoPadding / 5;
            $cx = ($vbW + $framePad * 2) / 2;
            $cy = $framePad + $textOffset + ($vbH / 2);
            $half = $logoSize / 2;
            $bg = $logoSize + $pad * 2;

            return '<rect x="' . ($cx - $half - $pad) . '" y="' . ($cy - $half - $pad)
                . '" width="' . $bg . '" height="' . $bg . '" fill="' . $this->backgroundColor . '" rx="1"/>'
                . '<image x="' . ($cx - $half) . '" y="' . ($cy - $half)
                . '" width="' . $logoSize . '" height="' . $logoSize
                . '" href="' . $dataUri . '" preserveAspectRatio="xMidYMid meet"/>';
        } catch (\Throwable) {
            return '';
        }
    }

    private function buildTextSvg(float $totalW, float $totalH): string
    {
        if (empty($this->text)) {
            return '';
        }
        $y = $this->textPosition === 'top' ? 4 : ($totalH - 1.5);
        return '<text x="' . ($totalW / 2) . '" y="' . $y
            . '" text-anchor="middle" dominant-baseline="central"'
            . ' font-family="' . $this->textFont . ',sans-serif"'
            . ' font-size="' . max(2, $this->textSize / 4) . '"'
            . ' fill="' . $this->textColor . '">'
            . htmlspecialchars($this->text, ENT_XML1 | ENT_QUOTES, 'UTF-8')
            . '</text>';
    }

    private function extractSvgContent(string $svg): string
    {
        $svg = preg_replace('/<\?xml[^?]*\?>\s*/', '', $svg);
        if (preg_match('/<svg[^>]*>(.*)<\/svg>/s', $svg, $m)) {
            return trim($m[1]);
        }
        return trim($svg);
    }

    private function extractViewBox(string $svg): array
    {
        if (preg_match('/viewBox="([^"]*)"/', $svg, $m)) {
            $p = preg_split('/\s+/', trim($m[1]));
            if (count($p) >= 4) {
                return [(float) $p[2], (float) $p[3]];
            }
        }
        return [100.0, 100.0];
    }

    private function hexToRgb(string $hex): array
    {
        $hex = ltrim($hex, '#');
        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }
        return [(int) hexdec(substr($hex, 0, 2)), (int) hexdec(substr($hex, 2, 2)), (int) hexdec(substr($hex, 4, 2))];
    }
}
