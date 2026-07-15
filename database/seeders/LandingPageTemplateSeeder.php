<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\LandingPageTemplate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LandingPageTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'name' => 'رستوران',
                'slug' => 'restaurant',
                'description' => 'قالب حرفه‌ای برای رستوران‌ها با منوی غذا، گالری تصاویر و فرم رزرو',
                'category' => 'restaurant',
                'data' => $this->getRestaurantTemplate(),
            ],
            [
                'name' => 'کافه',
                'slug' => 'cafe',
                'description' => 'قالب شیک برای کافه‌ها با منوی نوشیدنی و ساعات کاری',
                'category' => 'cafe',
                'data' => $this->getCafeTemplate(),
            ],
            [
                'name' => 'شرکتی',
                'slug' => 'business',
                'description' => 'قالب حرفه‌ای شرکتی با معرفی خدمات و تیم',
                'category' => 'business',
                'data' => $this->getBusinessTemplate(),
            ],
            [
                'name' => 'آژانس',
                'slug' => 'agency',
                'description' => 'قالب خلاقانه برای آژانس‌های تبلیغاتی و دیجیتال مارکتینگ',
                'category' => 'agency',
                'data' => $this->getAgencyTemplate(),
            ],
            [
                'name' => 'پورتفولیو',
                'slug' => 'portfolio',
                'description' => 'قالب شخصی برای نمایش نمونه کارها',
                'category' => 'portfolio',
                'data' => $this->getPortfolioTemplate(),
            ],
            [
                'name' => 'عروسی',
                'slug' => 'wedding',
                'description' => 'قالب زیبای دعوت‌نامه عروسی',
                'category' => 'wedding',
                'data' => $this->getWeddingTemplate(),
            ],
            [
                'name' => 'رویداد',
                'slug' => 'event',
                'description' => 'قالب رویداد و کنفرانس با شمارش معکوس',
                'category' => 'event',
                'data' => $this->getEventTemplate(),
            ],
            [
                'name' => 'املاک',
                'slug' => 'real-estate',
                'description' => 'قالب املاک با نمایش ملک‌ها و فرم جستجو',
                'category' => 'real-estate',
                'data' => $this->getRealEstateTemplate(),
            ],
            [
                'name' => 'پزشکی',
                'slug' => 'medical',
                'description' => 'قالب کلینیک و مطب با معرفی پزشکان',
                'category' => 'medical',
                'data' => $this->getMedicalTemplate(),
            ],
            [
                'name' => 'آموزشی',
                'slug' => 'education',
                'description' => 'قالب مدرسه و آموزشگاه',
                'category' => 'education',
                'data' => $this->getEducationTemplate(),
            ],
            [
                'name' => 'باشگاه ورزشی',
                'slug' => 'gym',
                'description' => 'قالب باشگاه بدنسازی و فیتنس',
                'category' => 'gym',
                'data' => $this->getGymTemplate(),
            ],
            [
                'name' => 'محصول',
                'slug' => 'product',
                'description' => 'قالب لندینگ محصول با دکمه خرید',
                'category' => 'product',
                'data' => $this->getProductTemplate(),
            ],
            [
                'name' => 'به‌زودی',
                'slug' => 'coming-soon',
                'description' => 'صفحه به‌زودی با شمارش معکوس',
                'category' => 'coming-soon',
                'data' => $this->getComingSoonTemplate(),
            ],
            [
                'name' => 'تعمیر و نگهداری',
                'slug' => 'maintenance',
                'description' => 'صفحه حالت تعمیر و نگهداری',
                'category' => 'maintenance',
                'data' => $this->getMaintenanceTemplate(),
            ],
            [
                'name' => 'منوی دیجیتال',
                'slug' => 'digital-menu',
                'description' => 'منوی دیجیتال فعال‌شده با QR Code',
                'category' => 'digital-menu',
                'data' => $this->getDigitalMenuTemplate(),
            ],
            [
                'name' => 'لندینگ QR',
                'slug' => 'qr-landing',
                'description' => 'صفحه فرود ساده برای اسکن QR',
                'category' => 'qr-landing',
                'data' => $this->getQrLandingTemplate(),
            ],
        ];

        foreach ($templates as $index => $template) {
            LandingPageTemplate::updateOrCreate(
                ['slug' => $template['slug']],
                [
                    'name' => $template['name'],
                    'description' => $template['description'],
                    'category' => $template['category'],
                    'data' => $template['data'],
                    'is_active' => true,
                    'is_premium' => false,
                    'sort_order' => $index,
                ]
            );
        }
    }

    private function getRestaurantTemplate(): array
    {
        return [
            'blocks' => [
                ['type' => 'widget', 'component' => 'widget-logo', 'content' => ['src' => '', 'alt' => 'رستوران', 'width' => '120px'], 'styles' => ['desktop' => ['padding' => ['top' => '20px', 'bottom' => '20px'], 'text-align' => 'center']]],
                ['type' => 'widget', 'component' => 'widget-heading', 'content' => ['text' => 'بهترین غذاهای ایرانی', 'tag' => 'h1', 'align' => 'center'], 'styles' => ['desktop' => ['padding' => ['top' => '60px', 'bottom' => '20px'], 'text-align' => 'center']]],
                ['type' => 'widget', 'component' => 'widget-text', 'content' => ['text' => 'با بیش از ۲۰ سال تجربه، بهترین طعم‌ها را برای شما آماده می‌کنیم'], 'styles' => ['desktop' => ['padding' => ['bottom' => '40px'], 'text-align' => 'center']]],
                ['type' => 'widget', 'component' => 'content-gallery', 'content' => ['images' => [], 'columns' => 3], 'styles' => ['desktop' => ['padding' => ['top' => '40px', 'bottom' => '40px']]]],
                ['type' => 'widget', 'component' => 'content-list', 'content' => ['items' => ['کباب کوبیده', 'جوجه کباب', 'زرشک پلو با مرغ', 'قورمه سبزی', 'فسنجان'], 'style' => 'bullet'], 'styles' => ['desktop' => ['padding' => ['top' => '20px', 'bottom' => '40px']]]],
                ['type' => 'widget', 'component' => 'content-testimonials', 'content' => ['items' => [['text' => 'عالی بود! حتماً دوباره میام', 'author' => 'مشتری راضی', 'rating' => 5]]], 'styles' => []],
                ['type' => 'widget', 'component' => 'form-contact', 'content' => ['formId' => 'reservation', 'fields' => [['type' => 'text', 'name' => 'name', 'label' => 'نام', 'required' => true], ['type' => 'text', 'name' => 'phone', 'label' => 'تلفن', 'required' => true], ['type' => 'text', 'name' => 'date', 'label' => 'تاریخ رزرو', 'required' => true], ['type' => 'number', 'name' => 'guests', 'label' => 'تعداد نفرات', 'required' => true]], 'submitText' => 'رزرو کنید'], 'styles' => ['desktop' => ['padding' => ['top' => '40px', 'bottom' => '40px']]]],
                ['type' => 'widget', 'component' => 'widget-social', 'content' => ['links' => [['platform' => 'instagram', 'url' => '#'], ['platform' => 'telegram', 'url' => '#']]], 'styles' => ['desktop' => ['padding' => ['top' => '20px', 'bottom' => '40px'], 'text-align' => 'center']]],
            ],
            'settings' => ['primary_color' => '#c0392b', 'font_family' => 'Vazirmatn'],
        ];
    }

    private function getCafeTemplate(): array
    {
        return [
            'blocks' => [
                ['type' => 'widget', 'component' => 'widget-logo', 'content' => ['src' => '', 'alt' => 'کافه', 'width' => '120px'], 'styles' => ['desktop' => ['padding' => ['top' => '20px', 'bottom' => '20px'], 'text-align' => 'center']]],
                ['type' => 'widget', 'component' => 'widget-heading', 'content' => ['text' => 'کافه قهوه', 'tag' => 'h1', 'align' => 'center'], 'styles' => ['desktop' => ['padding' => ['top' => '60px', 'bottom' => '20px'], 'text-align' => 'center']]],
                ['type' => 'widget', 'component' => 'widget-text', 'content' => ['text' => 'بهترین قهوه‌ها با بهترین کیفیت'], 'styles' => ['desktop' => ['padding' => ['bottom' => '40px'], 'text-align' => 'center']]],
                ['type' => 'widget', 'component' => 'content-list', 'content' => ['items' => ['اسپرسو', 'کاپوچینو', 'لاته', 'آمریکانو', 'موکا'], 'style' => 'bullet'], 'styles' => ['desktop' => ['padding' => ['top' => '20px', 'bottom' => '40px']]]],
                ['type' => 'widget', 'component' => 'widget-heading', 'content' => ['text' => 'ساعات کاری', 'tag' => 'h2'], 'styles' => ['desktop' => ['padding' => ['top' => '20px', 'bottom' => '10px']]]],
                ['type' => 'widget', 'component' => 'widget-text', 'content' => ['text' => 'شنبه تا پنجشنبه: ۸ صبح تا ۱۰ شب\nجمعه: ۹ صبح تا ۱۱ شب'], 'styles' => ['desktop' => ['padding' => ['bottom' => '40px']]]],
            ],
            'settings' => ['primary_color' => '#6F4E37', 'font_family' => 'Vazirmatn'],
        ];
    }

    private function getBusinessTemplate(): array
    {
        return [
            'blocks' => [
                ['type' => 'widget', 'component' => 'widget-heading', 'content' => ['text' => 'شرکت ما', 'tag' => 'h1', 'align' => 'center'], 'styles' => ['desktop' => ['padding' => ['top' => '80px', 'bottom' => '20px'], 'text-align' => 'center']]],
                ['type' => 'widget', 'component' => 'widget-text', 'content' => ['text' => 'ما راه‌حل‌های نوآورانه برای کسب‌وکار شما ارائه می‌دهیم'], 'styles' => ['desktop' => ['padding' => ['bottom' => '40px'], 'text-align' => 'center']]],
                ['type' => 'widget', 'component' => 'widget-stats', 'content' => ['items' => [['value' => '۱۰۰+', 'label' => 'مشتری', 'icon' => 'bi-people'], ['value' => '۵۰+', 'label' => 'پروژه', 'icon' => 'bi-briefcase'], ['value' => '۱۰+', 'label' => 'سال تجربه', 'icon' => 'bi-clock']]], 'styles' => ['desktop' => ['padding' => ['top' => '40px', 'bottom' => '40px']]]],
                ['type' => 'widget', 'component' => 'content-team', 'content' => ['members' => [['name' => 'مدیرعامل', 'role' => 'مدیرعامل', 'image' => '', 'social' => []]]], 'styles' => ['desktop' => ['padding' => ['top' => '40px', 'bottom' => '40px']]]],
                ['type' => 'widget', 'component' => 'form-contact', 'content' => ['formId' => 'contact', 'fields' => [['type' => 'text', 'name' => 'name', 'label' => 'نام', 'required' => true], ['type' => 'email', 'name' => 'email', 'label' => 'ایمیل', 'required' => true], ['type' => 'textarea', 'name' => 'message', 'label' => 'پیام', 'required' => true]], 'submitText' => 'ارسال'], 'styles' => ['desktop' => ['padding' => ['top' => '40px', 'bottom' => '40px']]]],
            ],
            'settings' => ['primary_color' => '#1a365d', 'font_family' => 'Vazirmatn'],
        ];
    }

    private function getAgencyTemplate(): array
    {
        return [
            'blocks' => [
                ['type' => 'widget', 'component' => 'widget-heading', 'content' => ['text' => 'آژانس خلاق ما', 'tag' => 'h1', 'align' => 'center'], 'styles' => ['desktop' => ['padding' => ['top' => '80px', 'bottom' => '20px'], 'text-align' => 'center']]],
                ['type' => 'widget', 'component' => 'widget-text', 'content' => ['text' => 'ایده‌های خلاقانه را به واقعیت تبدیل می‌کنیم'], 'styles' => ['desktop' => ['padding' => ['bottom' => '40px'], 'text-align' => 'center']]],
                ['type' => 'widget', 'component' => 'content-gallery', 'content' => ['images' => [], 'columns' => 3], 'styles' => ['desktop' => ['padding' => ['top' => '40px', 'bottom' => '40px']]]],
            ],
            'settings' => ['primary_color' => '#7c3aed', 'font_family' => 'Vazirmatn'],
        ];
    }

    private function getPortfolioTemplate(): array
    {
        return [
            'blocks' => [
                ['type' => 'widget', 'component' => 'widget-heading', 'content' => ['text' => 'نام شما', 'tag' => 'h1', 'align' => 'center'], 'styles' => ['desktop' => ['padding' => ['top' => '80px', 'bottom' => '20px'], 'text-align' => 'center']]],
                ['type' => 'widget', 'component' => 'widget-text', 'content' => ['text' => 'طراح گرافیک / توسعه‌دهنده وب'], 'styles' => ['desktop' => ['padding' => ['bottom' => '40px'], 'text-align' => 'center']]],
                ['type' => 'widget', 'component' => 'content-gallery', 'content' => ['images' => [], 'columns' => 3], 'styles' => ['desktop' => ['padding' => ['top' => '40px', 'bottom' => '40px']]]],
                ['type' => 'widget', 'component' => 'widget-social', 'content' => ['links' => [['platform' => 'instagram', 'url' => '#'], ['platform' => 'telegram', 'url' => '#']]], 'styles' => ['desktop' => ['padding' => ['top' => '20px', 'bottom' => '40px'], 'text-align' => 'center']]],
            ],
            'settings' => ['primary_color' => '#4f46e5', 'font_family' => 'Vazirmatn'],
        ];
    }

    private function getWeddingTemplate(): array
    {
        return [
            'blocks' => [
                ['type' => 'widget', 'component' => 'widget-heading', 'content' => ['text' => 'علی و سارا', 'tag' => 'h1', 'align' => 'center'], 'styles' => ['desktop' => ['padding' => ['top' => '80px', 'bottom' => '20px'], 'text-align' => 'center']]],
                ['type' => 'widget', 'component' => 'widget-text', 'content' => ['text' => 'با کمال میل شما را به مراسم عروسی دعوت می‌کنیم'], 'styles' => ['desktop' => ['padding' => ['bottom' => '40px'], 'text-align' => 'center']]],
                ['type' => 'widget', 'component' => 'widget-heading', 'content' => ['text' => '۱۵ شهریور ۱۴۰۵', 'tag' => 'h2', 'align' => 'center'], 'styles' => ['desktop' => ['padding' => ['top' => '20px', 'bottom' => '40px'], 'text-align' => 'center']]],
                ['type' => 'widget', 'component' => 'content-timeline', 'content' => ['items' => [['title' => 'آشنایی', 'date' => '۱۴۰۰', 'description' => 'در یک روز بهاری آشنا شدیم'], ['title' => 'نامزدی', 'date' => '۱۴۰۳', 'description' => 'نامزد کردیم'], ['title' => 'عروسی', 'date' => '۱۴۰۵', 'description' => 'مراسم عروسی']]], 'styles' => ['desktop' => ['padding' => ['top' => '40px', 'bottom' => '40px']]]],
                ['type' => 'widget', 'component' => 'content-gallery', 'content' => ['images' => [], 'columns' => 3], 'styles' => ['desktop' => ['padding' => ['top' => '40px', 'bottom' => '40px']]]],
            ],
            'settings' => ['primary_color' => '#b8860b', 'font_family' => 'Vazirmatn'],
        ];
    }

    private function getEventTemplate(): array
    {
        return [
            'blocks' => [
                ['type' => 'widget', 'component' => 'widget-heading', 'content' => ['text' => 'کنفرانس فناوری ۱۴۰۵', 'tag' => 'h1', 'align' => 'center'], 'styles' => ['desktop' => ['padding' => ['top' => '80px', 'bottom' => '20px'], 'text-align' => 'center']]],
                ['type' => 'widget', 'component' => 'advanced-countdown', 'content' => ['targetDate' => now()->addDays(45)->toIso8601String(), 'label' => 'تا شروع کنفرانس'], 'styles' => ['desktop' => ['padding' => ['bottom' => '40px'], 'text-align' => 'center']]],
                ['type' => 'widget', 'component' => 'widget-stats', 'content' => ['items' => [['value' => '۲۰+', 'label' => 'سخنران', 'icon' => 'bi-mic'], ['value' => '۵۰۰+', 'label' => 'شرکت‌کننده', 'icon' => 'bi-people'], ['value' => '۲', 'label' => 'روز', 'icon' => 'bi-calendar']]], 'styles' => ['desktop' => ['padding' => ['top' => '40px', 'bottom' => '40px']]]],
            ],
            'settings' => ['primary_color' => '#7c3aed', 'font_family' => 'Vazirmatn'],
        ];
    }

    private function getRealEstateTemplate(): array
    {
        return [
            'blocks' => [
                ['type' => 'widget', 'component' => 'widget-heading', 'content' => ['text' => 'مشاور املاک', 'tag' => 'h1', 'align' => 'center'], 'styles' => ['desktop' => ['padding' => ['top' => '80px', 'bottom' => '20px'], 'text-align' => 'center']]],
                ['type' => 'widget', 'component' => 'widget-text', 'content' => ['text' => 'بهترین انتخاب برای خانه رویایی شما'], 'styles' => ['desktop' => ['padding' => ['bottom' => '40px'], 'text-align' => 'center']]],
                ['type' => 'widget', 'component' => 'content-gallery', 'content' => ['images' => [], 'columns' => 3], 'styles' => ['desktop' => ['padding' => ['top' => '40px', 'bottom' => '40px']]]],
                ['type' => 'widget', 'component' => 'widget-map', 'content' => ['lat' => '35.6892', 'lng' => '51.3890', 'zoom' => '14'], 'styles' => ['desktop' => ['height' => '400px', 'width' => '100%']]],
            ],
            'settings' => ['primary_color' => '#059669', 'font_family' => 'Vazirmatn'],
        ];
    }

    private function getMedicalTemplate(): array
    {
        return [
            'blocks' => [
                ['type' => 'widget', 'component' => 'widget-heading', 'content' => ['text' => 'کلینیک سلامت', 'tag' => 'h1', 'align' => 'center'], 'styles' => ['desktop' => ['padding' => ['top' => '80px', 'bottom' => '20px'], 'text-align' => 'center']]],
                ['type' => 'widget', 'component' => 'widget-text', 'content' => ['text' => 'سلامتی شما اولویت ماست'], 'styles' => ['desktop' => ['padding' => ['bottom' => '40px'], 'text-align' => 'center']]],
                ['type' => 'widget', 'component' => 'content-team', 'content' => ['members' => [['name' => 'دکتر احمدی', 'role' => 'متخصص قلب', 'image' => '', 'social' => []]]], 'styles' => ['desktop' => ['padding' => ['top' => '40px', 'bottom' => '40px']]]],
                ['type' => 'widget', 'component' => 'form-contact', 'content' => ['formId' => 'appointment', 'fields' => [['type' => 'text', 'name' => 'name', 'label' => 'نام', 'required' => true], ['type' => 'text', 'name' => 'phone', 'label' => 'تلفن', 'required' => true], ['type' => 'text', 'name' => 'date', 'label' => 'تاریخ نوبت', 'required' => true]], 'submitText' => 'نوبت بگیرید'], 'styles' => ['desktop' => ['padding' => ['top' => '40px', 'bottom' => '40px']]]],
            ],
            'settings' => ['primary_color' => '#0891b2', 'font_family' => 'Vazirmatn'],
        ];
    }

    private function getEducationTemplate(): array
    {
        return [
            'blocks' => [
                ['type' => 'widget', 'component' => 'widget-heading', 'content' => ['text' => 'آموزشگاه', 'tag' => 'h1', 'align' => 'center'], 'styles' => ['desktop' => ['padding' => ['top' => '80px', 'bottom' => '20px'], 'text-align' => 'center']]],
                ['type' => 'widget', 'component' => 'widget-text', 'content' => ['text' => 'یادگیری مسیر موفقیت است'], 'styles' => ['desktop' => ['padding' => ['bottom' => '40px'], 'text-align' => 'center']]],
                ['type' => 'widget', 'component' => 'content-list', 'content' => ['items' => ['برنامه‌نویسی', 'طراحی گرافیک', 'زبان انگلیسی', 'حسابداری'], 'style' => 'bullet'], 'styles' => ['desktop' => ['padding' => ['top' => '20px', 'bottom' => '40px']]]],
            ],
            'settings' => ['primary_color' => '#d97706', 'font_family' => 'Vazirmatn'],
        ];
    }

    private function getGymTemplate(): array
    {
        return [
            'blocks' => [
                ['type' => 'widget', 'component' => 'widget-heading', 'content' => ['text' => 'باشگاه فیتنس', 'tag' => 'h1', 'align' => 'center'], 'styles' => ['desktop' => ['padding' => ['top' => '80px', 'bottom' => '20px'], 'text-align' => 'center']]],
                ['type' => 'widget', 'component' => 'widget-text', 'content' => ['text' => 'بدن سالم، ذهن سالم'], 'styles' => ['desktop' => ['padding' => ['bottom' => '40px'], 'text-align' => 'center']]],
                ['type' => 'widget', 'component' => 'content-list', 'content' => ['items' => ['بدنسازی', 'کاردیو', 'یوگا', 'بوکس', 'شنا'], 'style' => 'bullet'], 'styles' => ['desktop' => ['padding' => ['top' => '20px', 'bottom' => '40px']]]],
                ['type' => 'widget', 'component' => 'content-pricing', 'content' => ['plans' => [['name' => 'ماهانه', 'price' => '۵۰۰,۰۰۰', 'features' => ['تمام امکانات'], 'highlighted' => false], ['name' => 'سالانه', 'price' => '۴,۰۰۰,۰۰۰', 'features' => ['تمام امکانات', '۲ ماه هدیه'], 'highlighted' => true]]], 'styles' => ['desktop' => ['padding' => ['top' => '40px', 'bottom' => '40px']]]],
            ],
            'settings' => ['primary_color' => '#dc2626', 'font_family' => 'Vazirmatn'],
        ];
    }

    private function getProductTemplate(): array
    {
        return [
            'blocks' => [
                ['type' => 'widget', 'component' => 'widget-heading', 'content' => ['text' => 'نام محصول', 'tag' => 'h1', 'align' => 'center'], 'styles' => ['desktop' => ['padding' => ['top' => '80px', 'bottom' => '20px'], 'text-align' => 'center']]],
                ['type' => 'widget', 'component' => 'widget-text', 'content' => ['text' => 'توضیحات محصول خود را اینجا بنویسید'], 'styles' => ['desktop' => ['padding' => ['bottom' => '20px'], 'text-align' => 'center']]],
                ['type' => 'widget', 'component' => 'widget-image', 'content' => ['src' => '', 'alt' => 'محصول', 'caption' => ''], 'styles' => ['desktop' => ['width' => '100%', 'max-width' => '600px', 'margin' => ['left' => 'auto', 'right' => 'auto']]]],
                ['type' => 'widget', 'component' => 'widget-button', 'content' => ['text' => 'همین الان بخرید', 'link' => '#', 'variant' => 'primary'], 'styles' => ['desktop' => ['padding' => ['top' => '20px', 'bottom' => '40px'], 'text-align' => 'center']]],
            ],
            'settings' => ['primary_color' => '#059669', 'font_family' => 'Vazirmatn'],
        ];
    }

    private function getComingSoonTemplate(): array
    {
        return [
            'blocks' => [
                ['type' => 'widget', 'component' => 'widget-heading', 'content' => ['text' => 'به‌زودی...', 'tag' => 'h1', 'align' => 'center'], 'styles' => ['desktop' => ['padding' => ['top' => '120px', 'bottom' => '20px'], 'text-align' => 'center']]],
                ['type' => 'widget', 'component' => 'widget-text', 'content' => ['text' => 'ما در حال کار روی چیزهای جدید هستیم'], 'styles' => ['desktop' => ['padding' => ['bottom' => '40px'], 'text-align' => 'center']]],
                ['type' => 'widget', 'component' => 'advanced-countdown', 'content' => ['targetDate' => now()->addDays(30)->toIso8601String(), 'label' => 'تا راه‌اندازی'], 'styles' => ['desktop' => ['padding' => ['bottom' => '40px'], 'text-align' => 'center']]],
                ['type' => 'widget', 'component' => 'form-newsletter', 'content' => ['formId' => 'newsletter', 'fields' => [['type' => 'email', 'name' => 'email', 'label' => 'ایمیل خود را وارد کنید', 'required' => true]], 'submitText' => 'اطلاع‌رسانی'], 'styles' => ['desktop' => ['padding' => ['bottom' => '40px'], 'text-align' => 'center']]],
            ],
            'settings' => ['primary_color' => '#4f46e5', 'font_family' => 'Vazirmatn'],
        ];
    }

    private function getMaintenanceTemplate(): array
    {
        return [
            'blocks' => [
                ['type' => 'widget', 'component' => 'widget-heading', 'content' => ['text' => 'در حال تعمیر و نگهداری', 'tag' => 'h1', 'align' => 'center'], 'styles' => ['desktop' => ['padding' => ['top' => '120px', 'bottom' => '20px'], 'text-align' => 'center']]],
                ['type' => 'widget', 'component' => 'widget-text', 'content' => ['text' => 'سایت موقتاً در دسترس نیست. لطفاً بعداً مراجعه کنید.'], 'styles' => ['desktop' => ['padding' => ['bottom' => '40px'], 'text-align' => 'center']]],
            ],
            'settings' => ['primary_color' => '#6366f1', 'font_family' => 'Vazirmatn'],
        ];
    }

    private function getDigitalMenuTemplate(): array
    {
        return [
            'blocks' => [
                ['type' => 'widget', 'component' => 'widget-logo', 'content' => ['src' => '', 'alt' => 'رستوران', 'width' => '120px'], 'styles' => ['desktop' => ['padding' => ['top' => '20px', 'bottom' => '20px'], 'text-align' => 'center']]],
                ['type' => 'widget', 'component' => 'widget-heading', 'content' => ['text' => 'منوی رستوران', 'tag' => 'h1', 'align' => 'center'], 'styles' => ['desktop' => ['padding' => ['top' => '20px', 'bottom' => '20px'], 'text-align' => 'center']]],
                ['type' => 'widget', 'component' => 'widget-heading', 'content' => ['text' => 'پیش‌غذا', 'tag' => 'h2'], 'styles' => ['desktop' => ['padding' => ['top' => '20px', 'bottom' => '10px']]]],
                ['type' => 'widget', 'component' => 'content-list', 'content' => ['items' => ['سالاد فصل - ۸۰,۰۰۰ تومان', 'سوسیس بندری - ۱۲۰,۰۰۰ تومان', 'سوپ جو - ۶۰,۰۰۰ تومان'], 'style' => 'bullet'], 'styles' => []],
                ['type' => 'widget', 'component' => 'widget-heading', 'content' => ['text' => 'غذاهای اصلی', 'tag' => 'h2'], 'styles' => ['desktop' => ['padding' => ['top' => '20px', 'bottom' => '10px']]]],
                ['type' => 'widget', 'component' => 'content-list', 'content' => ['items' => ['کباب کوبیده - ۲۵۰,۰۰۰ تومان', 'جوجه کباب - ۲۸۰,۰۰۰ تومان', 'زرشک پلو با مرغ - ۲۲۰,۰۰۰ تومان'], 'style' => 'bullet'], 'styles' => []],
            ],
            'settings' => ['primary_color' => '#c0392b', 'font_family' => 'Vazirmatn'],
        ];
    }

    private function getQrLandingTemplate(): array
    {
        return [
            'blocks' => [
                ['type' => 'widget', 'component' => 'widget-heading', 'content' => ['text' => 'خوش آمدید', 'tag' => 'h1', 'align' => 'center'], 'styles' => ['desktop' => ['padding' => ['top' => '80px', 'bottom' => '20px'], 'text-align' => 'center']]],
                ['type' => 'widget', 'component' => 'widget-text', 'content' => ['text' => 'با اسکن کد QR به این صفحه رسیدید'], 'styles' => ['desktop' => ['padding' => ['bottom' => '40px'], 'text-align' => 'center']]],
                ['type' => 'widget', 'component' => 'widget-social', 'content' => ['links' => [['platform' => 'instagram', 'url' => '#'], ['platform' => 'telegram', 'url' => '#']]], 'styles' => ['desktop' => ['padding' => ['top' => '20px', 'bottom' => '40px'], 'text-align' => 'center']]],
            ],
            'settings' => ['primary_color' => '#4f46e5', 'font_family' => 'Vazirmatn'],
        ];
    }
}
