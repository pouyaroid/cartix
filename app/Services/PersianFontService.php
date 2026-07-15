<?php

declare(strict_types=1);

namespace App\Services;

use App\Config\PersianFonts;

class PersianFontService
{
    private array $loadedFonts = [];
    private array $loadedCdnUrls = [];

    public function loadFonts(array $fontNames): string
    {
        $html = '';

        $googleFonts = [];
        $cdnFonts = [];

        foreach ($fontNames as $name) {
            if (in_array($name, $this->loadedFonts)) {
                continue;
            }

            $allFonts = PersianFonts::getAllFonts();
            if (!isset($allFonts[$name])) {
                continue;
            }

            $font = $allFonts[$name];

            if ($font['source'] === 'google') {
                $googleFonts[] = $name;
            } elseif ($font['source'] === 'cdn' && !empty($font['url'])) {
                $cdnFonts[$name] = $font['url'];
            }

            $this->loadedFonts[] = $name;
        }

        // Google Fonts (batched into one request)
        if (!empty($googleFonts)) {
            $url = PersianFonts::getGoogleFontsUrl($googleFonts);
            if ($url) {
                $html .= "<link rel=\"preconnect\" href=\"https://fonts.googleapis.com\">\n";
                $html .= "<link rel=\"preconnect\" href=\"https://fonts.gstatic.com\" crossorigin>\n";
                $html .= "<link href=\"{$url}\" rel=\"stylesheet\">\n";
            }
        }

        // CDN fonts (individual links)
        foreach ($cdnFonts as $name => $url) {
            if (!in_array($url, $this->loadedCdnUrls)) {
                $html .= "<link href=\"{$url}\" rel=\"stylesheet\">\n";
                $this->loadedCdnUrls[] = $url;
            }
        }

        return $html;
    }

    public function getFontFaceCss(string $fontName): string
    {
        $allFonts = PersianFonts::getAllFonts();
        if (!isset($allFonts[$fontName])) {
            return '';
        }

        $font = $allFonts[$fontName];
        $weights = $font['weights'] ?? [400];
        $style = in_array(400, $weights) ? 'normal' : 'normal';

        return "font-family: '{$fontName}', 'Vazirmatn', system-ui, -apple-system, sans-serif;";
    }

    public function getFontOptions(): array
    {
        $fonts = PersianFonts::getAllFonts();
        $options = [];

        foreach ($fonts as $name => $config) {
            $options[] = [
                'value' => $name,
                'label' => $config['label'] ?? $name,
                'category' => $config['category'] ?? 'body',
                'weights' => $config['weights'] ?? [400],
            ];
        }

        return $options;
    }

    public function getFontSelectOptions(): string
    {
        $fonts = PersianFonts::getAllFonts();
        $html = '<option value="">پیش‌فرض</option>';

        // Body fonts
        $bodyFonts = array_filter($fonts, fn($f) => ($f['category'] ?? 'body') === 'body');
        if (!empty($bodyFonts)) {
            $html .= '<optgroup label="فونت‌های متنی">';
            foreach ($bodyFonts as $name => $config) {
                $label = $config['label'] ?? $name;
                $html .= "<option value=\"{$name}\">{$label}</option>";
            }
            $html .= '</optgroup>';
        }

        // Display fonts
        $displayFonts = array_filter($fonts, fn($f) => ($f['category'] ?? 'body') === 'display');
        if (!empty($displayFonts)) {
            $html .= '<optgroup label="فونت‌های عنوان">';
            foreach ($displayFonts as $name => $config) {
                $label = $config['label'] ?? $name;
                $html .= "<option value=\"{$name}\">{$label}</option>";
            }
            $html .= '</optgroup>';
        }

        return $html;
    }

    public function generateFontCss(string $fontName, string $property = 'font-family'): string
    {
        if (empty($fontName)) {
            return '';
        }

        $allFonts = PersianFonts::getAllFonts();
        if (!isset($allFonts[$fontName])) {
            return '';
        }

        $fallback = "'Vazirmatn', system-ui, -apple-system, sans-serif";
        return "{$property}: '{$fontName}', {$fallback};";
    }
}
