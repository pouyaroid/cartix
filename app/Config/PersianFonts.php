<?php

declare(strict_types=1);

namespace App\Config;

class PersianFonts
{
    public static function getAllFonts(): array
    {
        return [
            // ── Google Fonts (reliable, batched loading) ──
            'Vazirmatn' => [
                'name' => 'Vazirmatn',
                'label' => 'وزیرمتن',
                'weights' => [100, 200, 300, 400, 500, 600, 700, 800, 900],
                'source' => 'google',
                'category' => 'body',
            ],
            'Shabnam' => [
                'name' => 'Shabnam',
                'label' => 'شبنم',
                'weights' => [400, 500, 600, 700, 800],
                'source' => 'google',
                'category' => 'body',
            ],
            'Sahel' => [
                'name' => 'Sahel',
                'label' => 'ساحل',
                'weights' => [300, 400, 500, 600, 700, 800, 900],
                'source' => 'google',
                'category' => 'body',
            ],
            'Samim' => [
                'name' => 'Samim',
                'label' => 'سمیم',
                'weights' => [400, 700],
                'source' => 'google',
                'category' => 'body',
            ],
            'Tanha' => [
                'name' => 'Tanha',
                'label' => 'تنها',
                'weights' => [400],
                'source' => 'google',
                'category' => 'body',
            ],
            'Lalezar' => [
                'name' => 'Lalezar',
                'label' => 'لاله‌زار',
                'weights' => [400],
                'source' => 'google',
                'category' => 'display',
            ],
            'Parastoo' => [
                'name' => 'Parastoo',
                'label' => 'پراستو',
                'weights' => [400, 700],
                'source' => 'google',
                'category' => 'display',
            ],
            'Markazi Text' => [
                'name' => 'Markazi Text',
                'label' => 'مرکزی',
                'weights' => [400, 500, 600, 700],
                'source' => 'google',
                'category' => 'display',
            ],
            'Mj Nastaliq' => [
                'name' => 'Mj Nastaliq',
                'label' => 'مجله نستعلیق',
                'weights' => [400],
                'source' => 'google',
                'category' => 'display',
            ],

            // ── CDN Fonts (Vazirmatn ecosystem, verified working) ──
            'Vazir' => [
                'name' => 'Vazir',
                'label' => 'وزیر (کلاسیک)',
                'weights' => [100, 200, 300, 400, 500, 600, 700, 800, 900],
                'source' => 'cdn',
                'url' => 'https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css',
                'category' => 'body',
            ],

            // ── System Fonts (always available, no loading needed) ──
            'Tahoma' => [
                'name' => 'Tahoma',
                'label' => 'تاهوما',
                'weights' => [400, 700],
                'source' => 'system',
                'category' => 'body',
            ],
            'Arial' => [
                'name' => 'Arial',
                'label' => 'آریال',
                'weights' => [400, 700],
                'source' => 'system',
                'category' => 'body',
            ],
            'Helvetica' => [
                'name' => 'Helvetica',
                'label' => 'هلوتیکا',
                'weights' => [300, 400, 700],
                'source' => 'system',
                'category' => 'body',
            ],
            'Georgia' => [
                'name' => 'Georgia',
                'label' => 'جورجیا',
                'weights' => [400, 700],
                'source' => 'system',
                'category' => 'display',
            ],
            'Times New Roman' => [
                'name' => 'Times New Roman',
                'label' => 'تایمز',
                'weights' => [400, 700],
                'source' => 'system',
                'category' => 'display',
            ],
        ];
    }

    public static function getGoogleFontsUrl(array $fontNames): string
    {
        $googleFonts = array_filter(
            static::getAllFonts(),
            fn($f) => $f['source'] === 'google'
        );

        $families = [];
        foreach ($fontNames as $name) {
            if (isset($googleFonts[$name])) {
                $weights = $googleFonts[$name]['weights'] ?? [400];
                $families[] = str_replace(' ', '+', $name) . ':wght@' . implode(';', $weights);
            }
        }

        if (empty($families)) {
            return '';
        }

        return 'https://fonts.googleapis.com/css2?family=' . implode('&family=', $families) . '&display=swap';
    }

    public static function getCdnFonts(array $fontNames): array
    {
        $cdnFonts = array_filter(
            static::getAllFonts(),
            fn($f) => $f['source'] === 'cdn'
        );

        $urls = [];
        foreach ($fontNames as $name) {
            if (isset($cdnFonts[$name]) && !empty($cdnFonts[$name]['url'])) {
                $urls[$name] = $cdnFonts[$name]['url'];
            }
        }

        return $urls;
    }

    public static function getFontNames(): array
    {
        return array_keys(static::getAllFonts());
    }
}
