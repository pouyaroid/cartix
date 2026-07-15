<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Template;
use Illuminate\Http\Request;

class TemplatePreviewController extends Controller
{
    public function preview(Template $template)
    {
        $sampleCard = $this->createSampleCard($template);

        // Try the template's blade_view directly
        if ($template->blade_view && view()->exists($template->blade_view)) {
            return view($template->blade_view, ['card' => $sampleCard]);
        }

        // Try with templates. prefix
        if ($template->blade_view && view()->exists("templates.{$template->blade_view}")) {
            return view("templates.{$template->blade_view}", ['card' => $sampleCard]);
        }

        // Try with templates. prefix and .show suffix
        if ($template->blade_view && view()->exists("templates.{$template->blade_view}.show")) {
            return view("templates.{$template->blade_view}.show", ['card' => $sampleCard]);
        }

        // Fallback to category-based view
        return view($this->resolveView($template->category), ['card' => $sampleCard]);
    }

    public function previewPage(Template $template)
    {
        $sampleCard = $this->createSampleCard($template);

        return view('admin.templates.preview', compact('template', 'sampleCard'));
    }

    private function resolveView(?string $category): string
    {
        $categoryViews = [
            'corporate' => 'cards.business.show',
            'modern' => 'cards.business.show',
            'classic' => 'cards.business.show',
            'luxury' => 'cards.business.show',
            'minimal' => 'cards.portfolio.show',
            'creative' => 'cards.portfolio.show',
            'wedding' => 'cards.wedding.show',
            'birthday' => 'cards.wedding.show',
            'event' => 'cards.event.show',
            'conference' => 'cards.event.show',
            'restaurant' => 'cards.restaurant.show',
            'medical' => 'cards.doctor.show',
            'portfolio' => 'cards.portfolio.show',
            'agency' => 'cards.business.show',
            'invitation' => 'cards.wedding.show',
        ];

        return $categoryViews[$category] ?? 'cards.business.show';
    }

    private function createSampleCard(Template $template)
    {
        $sampleData = $template->settings['sample_data'] ?? [];
        $category = $template->category;

        return (object) [
            'id' => 0,
            'title' => $sampleData['title'] ?? $this->getDefaultTitle($category),
            'slug' => 'sample-' . $template->slug,
            'type' => $this->getTypeForCategory($category),
            'description' => $sampleData['description'] ?? $this->getDefaultDescription($category),
            'phone' => $sampleData['phone'] ?? '۰۹۱۲۱۲۳۴۵۶۷',
            'email' => $sampleData['email'] ?? 'info@example.com',
            'website' => $sampleData['website'] ?? 'https://example.com',
            'address' => $sampleData['address'] ?? 'تهران، خیابان ولیعصر',
            'logo' => $sampleData['logo'] ?? null,
            'profile_image' => $sampleData['profile_image'] ?? null,
            'cover_image' => $sampleData['cover_image'] ?? null,
            'theme_color' => $sampleData['theme_color'] ?? $this->getDefaultColor($category),
            'font_family' => $sampleData['font_family'] ?? 'Vazirmatn',
            'is_published' => true,
            'seo_title' => null,
            'seo_description' => null,
            'og_image' => null,
            'schema_type' => 'Person',
            'map_lat' => null,
            'map_lng' => null,
            'meta' => $sampleData['meta'] ?? $this->getDefaultMeta($category),
            'settings' => $sampleData['settings'] ?? [],
            'socialLinks' => collect($sampleData['social_links'] ?? [
                (object) ['platform' => 'instagram', 'url' => 'https://instagram.com/example', 'sort_order' => 0],
                (object) ['platform' => 'telegram', 'url' => 'https://t.me/example', 'sort_order' => 1],
            ]),
            'sections' => collect($sampleData['sections'] ?? $this->getDefaultSections($category)),
            'galleryItems' => collect($sampleData['gallery'] ?? []),
            'products' => collect($sampleData['products'] ?? $this->getDefaultProducts($category)),
            'services' => collect($sampleData['services'] ?? $this->getDefaultServices($category)),
            'testimonials' => collect($sampleData['testimonials'] ?? []),
            'faqs' => collect($sampleData['faqs'] ?? []),
            'qrCodes' => collect(),
            'template' => $template,
            'user' => (object) ['name' => 'نمونه', 'id' => 0],
        ];
    }

    private function getDefaultTitle(?string $category): string
    {
        return match($category) {
            'wedding', 'invitation' => 'علی & زهرا',
            'restaurant' => 'رستوران سنتی',
            'medical', 'doctor' => 'دکتر محمدی',
            'event', 'conference' => 'کنفرانس فناوری ۱۴۰۵',
            'portfolio', 'creative' => 'سارا احمدی',
            default => 'شرکت نمونه',
        };
    }

    private function getDefaultDescription(?string $category): string
    {
        return match($category) {
            'wedding', 'invitation' => 'با کمال میل شما را به مراسم عروسی دعوت می‌کنیم',
            'restaurant' => 'بهترین غذاهای سنتی ایرانی',
            'medical', 'doctor' => 'متخصص قلب و عروق',
            'event', 'conference' => 'بزرگترین رویداد فناوری سال',
            'portfolio', 'creative' => 'طراح UI/UX و گرافیک',
            default => 'ما بهترین خدمات را ارائه می‌دهیم',
        };
    }

    private function getDefaultColor(?string $category): string
    {
        return match($category) {
            'wedding', 'invitation' => '#b8860b',
            'restaurant' => '#c0392b',
            'medical', 'doctor' => '#0077b6',
            'event', 'conference' => '#7c3aed',
            'portfolio', 'creative' => '#6366f1',
            default => '#1a365d',
        };
    }

    private function getTypeForCategory(?string $category): string
    {
        return match($category) {
            'wedding', 'invitation' => 'wedding',
            'birthday' => 'birthday',
            'restaurant' => 'restaurant',
            'medical', 'doctor' => 'doctor',
            'event', 'conference' => 'event',
            'portfolio', 'creative' => 'portfolio',
            default => 'business',
        };
    }

    private function getDefaultMeta(?string $category): array
    {
        return match($category) {
            'wedding', 'invitation' => [
                'partner_name' => 'زهرا کریمی',
                'wedding_date' => now()->addDays(60)->toIso8601String(),
            ],
            'restaurant' => [
                'rating' => '4.5',
                'review_count' => 128,
                'working_hours' => 'هر روز ۱۲ تا ۲۳',
            ],
            'medical', 'doctor' => [
                'clinic_name' => 'کلینیک قلب تهران',
            ],
            'event', 'conference' => [
                'event_date' => now()->addDays(30)->toIso8601String(),
                'registration_url' => '#',
            ],
            'portfolio', 'creative' => [
                'bio' => 'طراح با بیش از ۵ سال تجربه در حوزه UI/UX',
                'skills' => ['UI Design', 'UX Research', 'Figma', 'Photoshop', 'HTML/CSS'],
            ],
            default => [],
        };
    }

    private function getDefaultSections(?string $category): array
    {
        return [
            (object) ['id' => 1, 'type' => 'contact', 'title' => 'تماس با ما', 'content' => null, 'sort_order' => 0, 'is_visible' => true, 'settings' => []],
            (object) ['id' => 2, 'type' => 'social', 'title' => 'شبکه‌های اجتماعی', 'content' => null, 'sort_order' => 1, 'is_visible' => true, 'settings' => []],
        ];
    }

    private function getDefaultProducts(?string $category): array
    {
        if ($category === 'restaurant') {
            return [
                (object) ['id' => 1, 'name' => 'چلو کباب', 'description' => 'غذاهای اصلی', 'price' => 185000, 'image' => null, 'sort_order' => 0],
                (object) ['id' => 2, 'name' => 'زرشک پلو', 'description' => 'غذاهای اصلی', 'price' => 165000, 'image' => null, 'sort_order' => 1],
                (object) ['id' => 3, 'name' => 'بستنی', 'description' => 'دسر', 'price' => 45000, 'image' => null, 'sort_order' => 2],
            ];
        }
        return [];
    }

    private function getDefaultServices(?string $category): array
    {
        if ($category === 'medical' || $category === 'doctor') {
            return [
                (object) ['id' => 1, 'name' => 'ویزیت حضوری', 'description' => 'مشاوره و معاینه', 'icon' => 'clipboard-check', 'sort_order' => 0],
                (object) ['id' => 2, 'name' => 'نوار قلب', 'description' => 'تست ورزش', 'icon' => 'activity', 'sort_order' => 1],
                (object) ['id' => 3, 'name' => 'اکو قلب', 'description' => 'سونوگرافی قلب', 'icon' => 'heart-pulse', 'sort_order' => 2],
            ];
        }
        if ($category === 'event' || $category === 'conference') {
            return [
                (object) ['id' => 1, 'name' => 'دکتر علی محمدی', 'description' => 'مدیرعامل شرکت الف', 'icon' => 'person', 'sort_order' => 0],
                (object) ['id' => 2, 'name' => 'مهندس زهرا کریمی', 'description' => 'مدیر فناوری اطلاعات', 'icon' => 'person', 'sort_order' => 1],
            ];
        }
        return [];
    }
}
