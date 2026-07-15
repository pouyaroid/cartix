<?php

declare(strict_types=1);

namespace App\Config;

class WidgetConfig
{
    public static function getCategories(): array
    {
        return [
            'basic' => ['label' => 'ابتدایی', 'icon' => 'bi-type'],
            'layout' => ['label' => 'چیدمان', 'icon' => 'bi-layout-split'],
            'content' => ['label' => 'محتوا', 'icon' => 'bi-file-earmark-text'],
            'business' => ['label' => 'کسب‌وکار', 'icon' => 'bi-briefcase'],
            'advanced' => ['label' => 'پیشرفته', 'icon' => 'bi-stars'],
            'decorative' => ['label' => 'تزئینی', 'icon' => 'bi-brush'],
        ];
    }

    public static function getWidgets(): array
    {
        return [
            // Basic
            'widget-heading' => [
                'name' => 'عنوان',
                'category' => 'basic',
                'icon' => 'bi-type-h1',
                'default_content' => ['text' => 'عنوان جدید', 'tag' => 'h2', 'align' => 'center'],
                'default_styles' => ['desktop' => ['padding' => ['top' => '20px', 'bottom' => '20px']]],
            ],
            'widget-text' => [
                'name' => 'متن',
                'category' => 'basic',
                'icon' => 'bi-text-paragraph',
                'default_content' => ['text' => 'متن خود را اینجا بنویسید...'],
                'default_styles' => ['desktop' => ['padding' => ['top' => '10px', 'bottom' => '10px']]],
            ],
            'widget-button' => [
                'name' => 'دکمه',
                'category' => 'basic',
                'icon' => 'bi-hand-index',
                'default_content' => ['text' => 'کلیک کنید', 'link' => '#', 'variant' => 'primary'],
                'default_styles' => ['desktop' => ['padding' => ['top' => '10px', 'bottom' => '10px']]],
            ],
            'widget-image' => [
                'name' => 'تصویر',
                'category' => 'basic',
                'icon' => 'bi-image',
                'default_content' => ['src' => '', 'alt' => '', 'caption' => ''],
                'default_styles' => ['desktop' => ['width' => '100%']],
            ],
            'widget-video' => [
                'name' => 'ویدیو',
                'category' => 'basic',
                'icon' => 'bi-play-circle',
                'default_content' => ['url' => '', 'type' => 'youtube'],
                'default_styles' => ['desktop' => ['width' => '100%']],
            ],
            'widget-icon' => [
                'name' => 'آیکون',
                'category' => 'basic',
                'icon' => 'bi-emoji-smile',
                'default_content' => ['name' => 'bi-star', 'size' => '2rem', 'color' => '#4f46e5'],
                'default_styles' => ['desktop' => ['text-align' => 'center']],
            ],
            'widget-divider' => [
                'name' => 'جداکننده',
                'category' => 'basic',
                'icon' => 'bi-dash',
                'default_content' => ['style' => 'solid', 'color' => '#e0e0e0', 'width' => '100%'],
                'default_styles' => ['desktop' => ['padding' => ['top' => '15px', 'bottom' => '15px']]],
            ],
            'widget-spacer' => [
                'name' => 'فاصله',
                'category' => 'basic',
                'icon' => 'bi-arrows-expand',
                'default_content' => ['height' => '50px'],
                'default_styles' => [],
            ],
            'widget-html' => [
                'name' => 'HTML',
                'category' => 'basic',
                'icon' => 'bi-code-slash',
                'default_content' => ['code' => '<div>Custom HTML</div>'],
                'default_styles' => [],
            ],
            'widget-code' => [
                'name' => 'کد سفارشی',
                'category' => 'basic',
                'icon' => 'bi-terminal',
                'default_content' => ['code' => '', 'type' => 'html'],
                'default_styles' => [],
            ],
            'widget-map' => [
                'name' => 'نقشه',
                'category' => 'basic',
                'icon' => 'bi-geo-alt',
                'default_content' => ['lat' => '35.6892', 'lng' => '51.3890', 'zoom' => '14'],
                'default_styles' => ['desktop' => ['height' => '400px', 'width' => '100%']],
            ],
            'widget-lottie' => [
                'name' => 'Lottie',
                'category' => 'basic',
                'icon' => 'bi-animation',
                'default_content' => ['url' => '', 'loop' => true, 'autoplay' => true],
                'default_styles' => ['desktop' => ['width' => '300px', 'height' => '300px']],
            ],

            // Layout
            'layout-section' => [
                'name' => 'بخش',
                'category' => 'layout',
                'icon' => 'bi-layout-split',
                'default_content' => ['columns' => 1],
                'default_styles' => ['desktop' => ['padding' => ['top' => '40px', 'right' => '20px', 'bottom' => '40px', 'left' => '20px']]],
            ],
            'layout-column' => [
                'name' => 'ستون',
                'category' => 'layout',
                'icon' => 'bi-column-width',
                'default_content' => ['width' => '100%'],
                'default_styles' => ['desktop' => ['padding' => ['top' => '10px', 'right' => '10px', 'bottom' => '10px', 'left' => '10px']]],
            ],
            'layout-container' => [
                'name' => 'کانتینر',
                'category' => 'layout',
                'icon' => 'bi-box',
                'default_content' => ['maxWidth' => '1200px'],
                'default_styles' => ['desktop' => ['margin' => ['left' => 'auto', 'right' => 'auto']]],
            ],
            'layout-tabs' => [
                'name' => 'تب‌ها',
                'category' => 'layout',
                'icon' => 'bi-folder',
                'default_content' => ['tabs' => [['title' => 'تب ۱', 'content' => ''], ['title' => 'تب ۲', 'content' => '']]],
                'default_styles' => [],
            ],
            'layout-accordion' => [
                'name' => 'آکاردئون',
                'category' => 'layout',
                'icon' => 'bi-list-nested',
                'default_content' => ['items' => [['title' => 'سوال ۱', 'content' => 'پاسخ ۱'], ['title' => 'سوال ۲', 'content' => 'پاسخ ۲']]],
                'default_styles' => [],
            ],

            // Content
            'content-card' => [
                'name' => 'کارت',
                'category' => 'content',
                'icon' => 'bi-card-heading',
                'default_content' => ['title' => 'عنوان کارت', 'text' => 'متن توضیحی کارت', 'image' => '', 'link' => ''],
                'default_styles' => ['desktop' => ['border-radius' => '12px', 'box-shadow' => '0 4px 12px rgba(0,0,0,0.08)']],
            ],
            'content-list' => [
                'name' => 'لیست',
                'category' => 'content',
                'icon' => 'bi-list-ul',
                'default_content' => ['items' => ['آیتم ۱', 'آیتم ۲', 'آیتم ۳'], 'style' => 'bullet'],
                'default_styles' => [],
            ],
            'content-testimonials' => [
                'name' => 'نظرات مشتریان',
                'category' => 'content',
                'icon' => 'bi-chat-quote',
                'default_content' => ['items' => [['text' => 'نظر عالی!', 'author' => 'نام مشتری', 'rating' => 5]]],
                'default_styles' => [],
            ],
            'content-gallery' => [
                'name' => 'گالری',
                'category' => 'content',
                'icon' => 'bi-grid',
                'default_content' => ['images' => [], 'columns' => 3],
                'default_styles' => [],
            ],
            'content-progress' => [
                'name' => 'نوار پیشرفت',
                'category' => 'content',
                'icon' => 'bi-bar-chart',
                'default_content' => ['value' => 70, 'label' => 'پیشرفت', 'color' => '#4f46e5'],
                'default_styles' => ['desktop' => ['height' => '12px']],
            ],
            'content-counter' => [
                'name' => 'شمارنده',
                'category' => 'content',
                'icon' => 'bi-123',
                'default_content' => ['value' => 1000, 'suffix' => '+', 'label' => 'مشتری'],
                'default_styles' => ['desktop' => ['text-align' => 'center']],
            ],
            'content-timeline' => [
                'name' => 'خط زمانی',
                'category' => 'content',
                'icon' => 'bi-signpost',
                'default_content' => ['items' => [['title' => 'رویداد ۱', 'date' => '', 'description' => 'توضیحات']]],
                'default_styles' => [],
            ],
            'content-pricing' => [
                'name' => 'جدول قیمت',
                'category' => 'content',
                'icon' => 'bi-tag',
                'default_content' => ['plans' => [['name' => 'پایه', 'price' => '۰', 'features' => ['ویژگی ۱'], 'highlighted' => false]]],
                'default_styles' => [],
            ],
            'content-team' => [
                'name' => 'تیم',
                'category' => 'content',
                'icon' => 'bi-people',
                'default_content' => ['members' => [['name' => 'نام', 'role' => 'سمت', 'image' => '', 'social' => []]]],
                'default_styles' => [],
            ],
            'content-faq' => [
                'name' => 'سوالات متداول',
                'category' => 'content',
                'icon' => 'bi-question-circle',
                'default_content' => ['items' => [['question' => 'سوال ۱', 'answer' => 'پاسخ ۱']]],
                'default_styles' => [],
            ],

            // Business
            'form-contact' => [
                'name' => 'فر تماس',
                'category' => 'business',
                'icon' => 'bi-envelope',
                'default_content' => ['formId' => 'contact', 'fields' => [['type' => 'text', 'name' => 'name', 'label' => 'نام', 'required' => true], ['type' => 'email', 'name' => 'email', 'label' => 'ایمیل', 'required' => true], ['type' => 'textarea', 'name' => 'message', 'label' => 'پیام', 'required' => true]], 'submitText' => 'ارسال', 'successMessage' => 'پیام شما ارسال شد.'],
                'default_styles' => [],
            ],
            'form-newsletter' => [
                'name' => 'خبرنامه',
                'category' => 'business',
                'icon' => 'bi-mailbox',
                'default_content' => ['formId' => 'newsletter', 'fields' => [['type' => 'email', 'name' => 'email', 'label' => 'ایمیل', 'required' => true]], 'submitText' => 'عضویت'],
                'default_styles' => [],
            ],
            'widget-social' => [
                'name' => 'شبکه‌های اجتماعی',
                'category' => 'business',
                'icon' => 'bi-share',
                'default_content' => ['links' => [['platform' => 'instagram', 'url' => '#'], ['platform' => 'telegram', 'url' => '#']]],
                'default_styles' => ['desktop' => ['text-align' => 'center']],
            ],
            'widget-menu' => [
                'name' => 'منو',
                'category' => 'business',
                'icon' => 'bi-list',
                'default_content' => ['items' => [['label' => 'خانه', 'url' => '#'], ['label' => 'درباره ما', 'url' => '#']]],
                'default_styles' => [],
            ],
            'widget-logo' => [
                'name' => 'لوگو',
                'category' => 'business',
                'icon' => 'bi-badge-ad',
                'default_content' => ['src' => '', 'alt' => 'لوگو', 'width' => '150px'],
                'default_styles' => ['desktop' => ['text-align' => 'center']],
            ],
            'widget-badges' => [
                'name' => 'نشان‌ها',
                'category' => 'business',
                'icon' => 'bi-award',
                'default_content' => ['badges' => [['text' => 'برتر', 'color' => '#4f46e5']]],
                'default_styles' => ['desktop' => ['text-align' => 'center']],
            ],
            'widget-stats' => [
                'name' => 'آمار',
                'category' => 'business',
                'icon' => 'bi-graph-up',
                'default_content' => ['items' => [['value' => '۱۰۰+', 'label' => 'مشتری', 'icon' => 'bi-people']]],
                'default_styles' => [],
            ],
            'widget-charts' => [
                'name' => 'نمودار',
                'category' => 'business',
                'icon' => 'bi-pie-chart',
                'default_content' => ['type' => 'bar', 'data' => [65, 59, 80, 81, 56], 'labels' => ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه']],
                'default_styles' => ['desktop' => ['height' => '300px']],
            ],

            // Advanced
            'advanced-carousel' => [
                'name' => 'کروسل',
                'category' => 'advanced',
                'icon' => 'bi-calendar3',
                'default_content' => ['slides' => [['image' => '', 'title' => '', 'text' => '']]],
                'default_styles' => ['desktop' => ['height' => '500px']],
            ],
            'advanced-slider' => [
                'name' => 'اسلایدر',
                'category' => 'advanced',
                'icon' => 'bi-arrows-angle-expand',
                'default_content' => ['slides' => [['image' => '', 'title' => 'اسلاید ۱', 'text' => 'متن اسلاید', 'buttonText' => 'بیشتر', 'buttonLink' => '#']]],
                'default_styles' => ['desktop' => ['height' => '600px']],
            ],
            'advanced-countdown' => [
                'name' => 'شمارش معکوس',
                'category' => 'advanced',
                'icon' => 'bi-clock-history',
                'default_content' => ['targetDate' => now()->addDays(30)->toIso8601String(), 'label' => 'تا شروع رویداد'],
                'default_styles' => ['desktop' => ['text-align' => 'center']],
            ],
            'advanced-qrcode' => [
                'name' => 'کد QR',
                'category' => 'advanced',
                'icon' => 'bi-qr-code',
                'default_content' => ['url' => '', 'size' => 200, 'color' => '#000000', 'bgColor' => '#ffffff'],
                'default_styles' => ['desktop' => ['text-align' => 'center']],
            ],
            'advanced-popup' => [
                'name' => 'پاپ‌آپ',
                'category' => 'advanced',
                'icon' => 'bi-window',
                'default_content' => ['triggerText' => 'کلیک کنید', 'title' => 'عنوان', 'content' => 'محتوا'],
                'default_styles' => [],
            ],
            'advanced-floating' => [
                'name' => 'دکمه شناور',
                'category' => 'advanced',
                'icon' => 'bi-hand-index-thumb',
                'default_content' => ['text' => 'تماس', 'link' => 'tel:+989121234567', 'icon' => 'bi-telephone', 'position' => 'bottom-right'],
                'default_styles' => ['desktop' => ['position' => 'fixed']],
            ],

            // Decorative
            'deco-shape' => [
                'name' => 'شکل جداکننده',
                'category' => 'decorative',
                'icon' => 'bi-triangle',
                'default_content' => ['shape' => 'wave', 'color' => '#ffffff', 'flip' => false],
                'default_styles' => ['desktop' => ['height' => '80px']],
            ],
            'deco-bgvideo' => [
                'name' => 'ویدیو پس‌زمینه',
                'category' => 'decorative',
                'icon' => 'bi-film',
                'default_content' => ['url' => '', 'type' => 'video/mp4', 'overlay' => 'rgba(0,0,0,0.4)'],
                'default_styles' => ['desktop' => ['height' => '400px']],
            ],
            'deco-gradient' => [
                'name' => 'گرادیان',
                'category' => 'decorative',
                'icon' => 'bi-circle-half',
                'default_content' => ['from' => '#667eea', 'to' => '#764ba2', 'direction' => '135deg'],
                'default_styles' => ['desktop' => ['height' => '200px']],
            ],
            'deco-svg' => [
                'name' => 'شکل SVG',
                'category' => 'decorative',
                'icon' => 'bi-bezier',
                'default_content' => ['svg' => '<circle cx="50" cy="50" r="40" fill="#4f46e5"/>', 'width' => '100px', 'height' => '100px'],
                'default_styles' => [],
            ],
            'deco-bgimage' => [
                'name' => 'تصویر پس‌زمینه',
                'category' => 'decorative',
                'icon' => 'bi-card-image',
                'default_content' => ['src' => '', 'overlay' => 'rgba(0,0,0,0.3)', 'position' => 'center'],
                'default_styles' => ['desktop' => ['height' => '400px', 'background-size' => 'cover']],
            ],
        ];
    }

    public static function getWidget(string $component): ?array
    {
        return static::getWidgets()[$component] ?? null;
    }

    public static function getWidgetsByCategory(string $category): array
    {
        return array_filter(
            static::getWidgets(),
            fn($widget) => $widget['category'] === $category
        );
    }
}
