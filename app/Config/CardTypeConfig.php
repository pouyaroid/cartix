<?php

declare(strict_types=1);

namespace App\Config;

class CardTypeConfig
{
    public static function get(string $type): array
    {
        return match($type) {
            'business', 'corporate', 'real_estate' => self::business(),
            'wedding', 'birthday', 'invitation' => self::wedding(),
            'restaurant' => self::restaurant(),
            'doctor', 'lawyer' => self::medical(),
            'event', 'conference' => self::event(),
            'portfolio', 'resume' => self::portfolio(),
            default => self::business(),
        };
    }

    private static function business(): array
    {
        return [
            'label' => 'کارت ویزیت',
            'icon' => 'bi-briefcase',
            'theme_color' => '#1a365d',
            'cover_color' => '#1a365d',
            'allowed_blocks' => ['contact', 'social', 'services', 'products', 'testimonials', 'faq', 'map', 'gallery', 'custom'],
            'required_blocks' => ['contact'],
            'default_blocks' => ['contact', 'social'],
            'meta_fields' => [
                ['key' => 'company_name', 'label' => 'نام شرکت', 'type' => 'text', 'placeholder' => 'شرکت الف'],
                ['key' => 'job_title', 'label' => 'سمت شغلی', 'type' => 'text', 'placeholder' => 'مدیر فنی'],
                ['key' => 'working_hours', 'label' => 'ساعات کاری', 'type' => 'text', 'placeholder' => 'شنبه تا پنجشنبه ۹ تا ۱۷'],
            ],
            'section_templates' => [
                'contact' => [
                    'title' => 'اطلاعات تماس',
                    'icon' => 'bi-telephone',
                ],
                'services' => [
                    'title' => 'خدمات',
                    'icon' => 'bi-briefcase',
                ],
                'products' => [
                    'title' => 'محصولات',
                    'icon' => 'bi-box-seam',
                ],
                'testimonials' => [
                    'title' => 'نظرات مشتریان',
                    'icon' => 'bi-chat-quote',
                ],
                'faq' => [
                    'title' => 'سوالات متداول',
                    'icon' => 'bi-question-circle',
                ],
            ],
        ];
    }

    private static function wedding(): array
    {
        return [
            'label' => 'دعوت نامه عروسی',
            'icon' => 'bi-heart',
            'theme_color' => '#b8860b',
            'cover_color' => '#b8860b',
            'allowed_blocks' => ['contact', 'social', 'gallery', 'timeline', 'countdown', 'rsvp', 'map', 'custom'],
            'required_blocks' => ['contact'],
            'default_blocks' => ['contact', 'social'],
            'meta_fields' => [
                ['key' => 'partner_name', 'label' => 'نام همسر', 'type' => 'text', 'placeholder' => 'زهرا کریمی'],
                ['key' => 'wedding_date', 'label' => 'تاریخ عروسی', 'type' => 'datetime', 'placeholder' => ''],
                ['key' => 'groom_name', 'label' => 'نام داماد', 'type' => 'text', 'placeholder' => 'علی محمدی'],
                ['key' => 'bride_name', 'label' => 'نام عروس', 'type' => 'text', 'placeholder' => 'زهرا کریمی'],
                ['key' => 'ceremony_time', 'label' => 'ساعت مراسم', 'type' => 'text', 'placeholder' => '۱۸:۰۰'],
                ['key' => 'dress_code', 'label' => 'کد لباس', 'type' => 'text', 'placeholder' => 'مجلسی'],
            ],
            'section_templates' => [
                'gallery' => [
                    'title' => 'گالری عکس',
                    'icon' => 'bi-images',
                ],
                'timeline' => [
                    'title' => 'داستان عشق',
                    'icon' => 'bi-heart',
                ],
                'countdown' => [
                    'title' => 'شمارش معکوس',
                    'icon' => 'bi-clock',
                ],
                'rsvp' => [
                    'title' => 'تأیید حضور',
                    'icon' => 'bi-check-circle',
                ],
            ],
        ];
    }

    private static function restaurant(): array
    {
        return [
            'label' => 'کارت رستوران',
            'icon' => 'bi-cup-hot',
            'theme_color' => '#c0392b',
            'cover_color' => '#c0392b',
            'allowed_blocks' => ['contact', 'social', 'products', 'gallery', 'testimonials', 'faq', 'map', 'custom'],
            'required_blocks' => ['contact', 'products'],
            'default_blocks' => ['contact', 'social', 'products'],
            'meta_fields' => [
                ['key' => 'rating', 'label' => 'امتیاز', 'type' => 'text', 'placeholder' => '4.5'],
                ['key' => 'review_count', 'label' => 'تعداد نظرات', 'type' => 'number', 'placeholder' => '128'],
                ['key' => 'working_hours', 'label' => 'ساعات کاری', 'type' => 'text', 'placeholder' => 'هر روز ۱۲ تا ۲۳'],
                ['key' => 'reservation_phone', 'label' => 'تلفن رزرو', 'type' => 'text', 'placeholder' => '۰۲۱۱۲۳۴۵۶۷۸'],
                ['key' => 'menu_url', 'label' => 'لینک منو', 'type' => 'url', 'placeholder' => 'https://'],
            ],
            'section_templates' => [
                'products' => [
                    'title' => 'منو',
                    'icon' => 'bi-book',
                ],
                'gallery' => [
                    'title' => 'گالری غذا',
                    'icon' => 'bi-camera',
                ],
                'testimonials' => [
                    'title' => 'نظرات مشتریان',
                    'icon' => 'bi-chat-quote',
                ],
            ],
        ];
    }

    private static function medical(): array
    {
        return [
            'label' => 'کارت پزشک',
            'icon' => 'bi-heart-pulse',
            'theme_color' => '#0077b6',
            'cover_color' => '#0077b6',
            'allowed_blocks' => ['contact', 'social', 'services', 'testimonials', 'faq', 'map', 'gallery', 'custom'],
            'required_blocks' => ['contact', 'services'],
            'default_blocks' => ['contact', 'social', 'services'],
            'meta_fields' => [
                ['key' => 'specialty', 'label' => 'تخصص', 'type' => 'text', 'placeholder' => 'متخصص قلب'],
                ['key' => 'clinic_name', 'label' => 'نام کلینیک', 'type' => 'text', 'placeholder' => 'کلینیک قلب تهران'],
                ['key' => 'license_number', 'label' => 'شماره نظام پزشکی', 'type' => 'text', 'placeholder' => '۱۲۳۴۵'],
                ['key' => 'working_hours', 'label' => 'ساعات ویزیت', 'type' => 'text', 'placeholder' => 'شنبه تا چهارشنبه ۱۶ تا ۲۰'],
                ['key' => 'appointment_url', 'label' => 'لینک نوبت‌دهی', 'type' => 'url', 'placeholder' => 'https://'],
            ],
            'section_templates' => [
                'services' => [
                    'title' => 'تخصص‌ها و خدمات',
                    'icon' => 'bi-stethoscope',
                ],
                'testimonials' => [
                    'title' => 'نظرات بیماران',
                    'icon' => 'bi-chat-quote',
                ],
                'faq' => [
                    'title' => 'سوالات متداول',
                    'icon' => 'bi-question-circle',
                ],
            ],
        ];
    }

    private static function event(): array
    {
        return [
            'label' => 'دعوت نامه رویداد',
            'icon' => 'bi-calendar-event',
            'theme_color' => '#7c3aed',
            'cover_color' => '#7c3aed',
            'allowed_blocks' => ['contact', 'social', 'services', 'products', 'gallery', 'countdown', 'map', 'custom'],
            'required_blocks' => ['contact'],
            'default_blocks' => ['contact', 'social'],
            'meta_fields' => [
                ['key' => 'event_date', 'label' => 'تاریخ رویداد', 'type' => 'datetime', 'placeholder' => ''],
                ['key' => 'event_time', 'label' => 'ساعت شروع', 'type' => 'text', 'placeholder' => '۰۹:۰۰'],
                ['key' => 'event_end_time', 'label' => 'ساعت پایان', 'type' => 'text', 'placeholder' => '۱۷:۰۰'],
                ['key' => 'venue', 'label' => 'محل برگزاری', 'type' => 'text', 'placeholder' => 'سالن همایش الف'],
                ['key' => 'ticket_url', 'label' => 'لینک بلیت', 'type' => 'url', 'placeholder' => 'https://'],
                ['key' => 'registration_url', 'label' => 'لینک ثبت‌نام', 'type' => 'url', 'placeholder' => 'https://'],
                ['key' => 'capacity', 'label' => 'ظرفیت', 'type' => 'number', 'placeholder' => '200'],
                ['key' => 'ticket_price', 'label' => 'قیمت بلیت', 'type' => 'text', 'placeholder' => '۵۰۰,۰۰۰ تومان'],
            ],
            'section_templates' => [
                'services' => [
                    'title' => 'سخنرانان',
                    'icon' => 'bi-mic',
                ],
                'products' => [
                    'title' => 'برنامه زمانی',
                    'icon' => 'bi-clock-history',
                ],
                'gallery' => [
                    'title' => 'گالری',
                    'icon' => 'bi-images',
                ],
                'countdown' => [
                    'title' => 'شمارش معکوس',
                    'icon' => 'bi-clock',
                ],
            ],
        ];
    }

    private static function portfolio(): array
    {
        return [
            'label' => 'نمونه کار',
            'icon' => 'bi-person-workspace',
            'theme_color' => '#6366f1',
            'cover_color' => '#6366f1',
            'allowed_blocks' => ['contact', 'social', 'gallery', 'services', 'testimonials', 'faq', 'map', 'custom'],
            'required_blocks' => ['contact'],
            'default_blocks' => ['contact', 'social'],
            'meta_fields' => [
                ['key' => 'job_title', 'label' => 'عنوان شغلی', 'type' => 'text', 'placeholder' => 'طراح UI/UX'],
                ['key' => 'bio', 'label' => 'بیوگرافی', 'type' => 'textarea', 'placeholder' => 'طراح با بیش از ۵ سال تجربه...'],
                ['key' => 'skills', 'label' => 'مهارت‌ها', 'type' => 'tags', 'placeholder' => 'UI Design, Figma, Photoshop'],
                ['key' => 'experience_years', 'label' => 'سال‌های تجربه', 'type' => 'number', 'placeholder' => '5'],
                ['key' => 'resume_url', 'label' => 'لینک رزومه', 'type' => 'url', 'placeholder' => 'https://'],
                ['key' => 'linkedin', 'label' => 'لینکدین', 'type' => 'url', 'placeholder' => 'https://linkedin.com/in/'],
                ['key' => 'github', 'label' => 'گیت‌هاب', 'type' => 'url', 'placeholder' => 'https://github.com/'],
            ],
            'section_templates' => [
                'gallery' => [
                    'title' => 'نمونه کارها',
                    'icon' => 'bi-grid',
                ],
                'services' => [
                    'title' => 'خدمات',
                    'icon' => 'bi-briefcase',
                ],
                'testimonials' => [
                    'title' => 'نظرات',
                    'icon' => 'bi-chat-quote',
                ],
            ],
        ];
    }

    public static function getAllTypes(): array
    {
        return [
            'business' => self::business(),
            'wedding' => self::wedding(),
            'birthday' => self::wedding(),
            'restaurant' => self::restaurant(),
            'doctor' => self::medical(),
            'lawyer' => self::medical(),
            'event' => self::event(),
            'conference' => self::event(),
            'corporate' => self::business(),
            'real_estate' => self::business(),
            'portfolio' => self::portfolio(),
            'resume' => self::portfolio(),
        ];
    }

    public static function getBlockTypes(): array
    {
        return [
            'contact' => ['label' => 'اطلاعات تماس', 'icon' => 'bi-telephone', 'has_items' => false],
            'social' => ['label' => 'شبکه‌های اجتماعی', 'icon' => 'bi-share', 'has_items' => false],
            'gallery' => ['label' => 'گالری تصاویر', 'icon' => 'bi-images', 'has_items' => true, 'item_model' => 'CardGalleryItem'],
            'services' => ['label' => 'خدمات', 'icon' => 'bi-briefcase', 'has_items' => true, 'item_model' => 'CardService'],
            'products' => ['label' => 'محصولات / منو', 'icon' => 'bi-box-seam', 'has_items' => true, 'item_model' => 'CardProduct'],
            'testimonials' => ['label' => 'نظرات مشتریان', 'icon' => 'bi-chat-quote', 'has_items' => true, 'item_model' => 'CardTestimonial'],
            'faq' => ['label' => 'سوالات متداول', 'icon' => 'bi-question-circle', 'has_items' => true, 'item_model' => 'CardFaq'],
            'map' => ['label' => 'نقشه', 'icon' => 'bi-geo-alt', 'has_items' => false],
            'video' => ['label' => 'ویدیو', 'icon' => 'bi-play-circle', 'has_items' => false],
            'audio' => ['label' => 'صدا', 'icon' => 'bi-music-note', 'has_items' => false],
            'document' => ['label' => 'اسناد', 'icon' => 'bi-file-earmark', 'has_items' => false],
            'timeline' => ['label' => 'خط زمانی', 'icon' => 'bi-clock-history', 'has_items' => true, 'item_model' => 'CardService'],
            'countdown' => ['label' => 'شمارش معکوس', 'icon' => 'bi-hourglass', 'has_items' => false],
            'rsvp' => ['label' => 'تأیید حضور', 'icon' => 'bi-check-circle', 'has_items' => false],
            'custom' => ['label' => 'محتوای دلخواه', 'icon' => 'bi-pencil-square', 'has_items' => false],
        ];
    }
}
