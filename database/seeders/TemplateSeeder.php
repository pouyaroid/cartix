<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Template;
use Illuminate\Database\Seeder;

class TemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            // Business Templates
            ['name' => 'شرکتی', 'slug' => 'corporate', 'category' => 'business', 'blade_view' => 'corporate.show', 'is_premium' => false],
            ['name' => 'لوکس', 'slug' => 'luxury', 'category' => 'business', 'blade_view' => 'luxury.show', 'is_premium' => true],

            // General Templates
            ['name' => 'مینیمال', 'slug' => 'minimal', 'category' => 'general', 'blade_view' => 'minimal.show', 'is_premium' => false],
            ['name' => 'مدرن', 'slug' => 'modern', 'category' => 'general', 'blade_view' => 'modern.show', 'is_premium' => false],
            ['name' => 'کلاسیک', 'slug' => 'classic', 'category' => 'general', 'blade_view' => 'classic.show', 'is_premium' => false],
            ['name' => 'تاریک', 'slug' => 'dark', 'category' => 'general', 'blade_view' => 'dark.show', 'is_premium' => false],

            // Invitation Templates
            ['name' => 'عروسی', 'slug' => 'wedding', 'category' => 'invitation', 'blade_view' => 'wedding.show', 'is_premium' => true],
            ['name' => 'تولد', 'slug' => 'birthday', 'category' => 'invitation', 'blade_view' => 'birthday.show', 'is_premium' => true],
            ['name' => 'رویداد', 'slug' => 'event', 'category' => 'invitation', 'blade_view' => 'event.show', 'is_premium' => false],

            // Business Type Templates
            ['name' => 'کنفرانس', 'slug' => 'conference', 'category' => 'business', 'blade_view' => 'conference.show', 'is_premium' => true],
            ['name' => 'رستوران', 'slug' => 'restaurant', 'category' => 'business', 'blade_view' => 'restaurant.show', 'is_premium' => false],
            ['name' => 'پزشکی', 'slug' => 'medical', 'category' => 'business', 'blade_view' => 'medical.show', 'is_premium' => true],
            ['name' => 'نمونه‌کار', 'slug' => 'portfolio', 'category' => 'general', 'blade_view' => 'portfolio.show', 'is_premium' => false],
            ['name' => 'خلاقانه', 'slug' => 'creative', 'category' => 'general', 'blade_view' => 'creative.show', 'is_premium' => false],

            // Wedding Templates (20 Premium Designs)
            ['name' => 'لوکس عروسی', 'slug' => 'wedding-luxury', 'category' => 'wedding', 'blade_view' => 'wedding.luxury', 'is_premium' => true, 'description' => 'قالب لوکس با طراحی تاریک و طلایی'],
            ['name' => 'مینیمال عروسی', 'slug' => 'wedding-minimal', 'category' => 'wedding', 'blade_view' => 'wedding.minimal', 'is_premium' => false, 'description' => 'قالب مینیمال با فضای سفید و تمیز'],
            ['name' => 'شیک عروسی', 'slug' => 'wedding-elegant', 'category' => 'wedding', 'blade_view' => 'wedding.elegant', 'is_premium' => true, 'description' => 'قالب شیک با رنگ‌های ملایم و تایپوگرافی ظریف'],
            ['name' => 'گلدار عروسی', 'slug' => 'wedding-floral', 'category' => 'wedding', 'blade_view' => 'wedding.floral', 'is_premium' => true, 'description' => 'قالب گلدار با المان‌های گیاهی و ارگانیک'],
            ['name' => 'سلطنتی عروسی', 'slug' => 'wedding-royal', 'category' => 'wedding', 'blade_view' => 'wedding.royal', 'is_premium' => true, 'description' => 'قالب سلطنتی با بنفش تیره و طلایی'],
            ['name' => 'وینتیج عروسی', 'slug' => 'wedding-vintage', 'category' => 'wedding', 'blade_view' => 'wedding.vintage', 'is_premium' => true, 'description' => 'قالب وینتیج با رنگ‌های سپیا و تایپوگرافی قدیمی'],
            ['name' => 'رومانیک عروسی', 'slug' => 'wedding-romantic', 'category' => 'wedding', 'blade_view' => 'wedding.romantic', 'is_premium' => true, 'description' => 'قالب رمانیک با گرادیان صورتی و قلب‌ها'],
            ['name' => 'مدرن عروسی', 'slug' => 'wedding-modern', 'category' => 'wedding', 'blade_view' => 'wedding.modern', 'is_premium' => false, 'description' => 'قالب مدرن با هندسه جسورانه و طراحی معاصر'],
            ['name' => 'سنتی ایرانی', 'slug' => 'wedding-persian', 'category' => 'wedding', 'blade_view' => 'wedding.persian', 'is_premium' => true, 'description' => 'قالب سنتی ایرانی با الگوهای پیچیده و خوشنویسی'],
            ['name' => 'باغ عروسی', 'slug' => 'wedding-garden', 'category' => 'wedding', 'blade_view' => 'wedding.garden', 'is_premium' => true, 'description' => 'قالب باغی با تم سبز و طبیعت'],
            ['name' => 'بوهو عروسی', 'slug' => 'wedding-boho', 'category' => 'wedding', 'blade_view' => 'wedding.boho', 'is_premium' => true, 'description' => 'قالب بوهو با رنگ‌های خاکی و بافت ماکارمه'],
            ['name' => 'لوکس تاریک', 'slug' => 'wedding-dark-luxury', 'category' => 'wedding', 'blade_view' => 'wedding.dark-luxury', 'is_premium' => true, 'description' => 'قالب لوکس تاریک با پس‌زمینه مشکی و لهجه‌های طلایی'],
            ['name' => 'تم طلایی', 'slug' => 'wedding-gold-theme', 'category' => 'wedding', 'blade_view' => 'wedding.gold-theme', 'is_premium' => true, 'description' => 'قالب با پالت تک‌رنگ طلایی'],
            ['name' => 'آبرنگ عروسی', 'slug' => 'wedding-watercolor', 'category' => 'wedding', 'blade_view' => 'wedding.watercolor', 'is_premium' => true, 'description' => 'قالب با شست‌وشوی آبرنگ ملایم'],
            ['name' => 'گلس‌مورفیزم', 'slug' => 'wedding-glassmorphism', 'category' => 'wedding', 'blade_view' => 'wedding.glassmorphism', 'is_premium' => true, 'description' => 'قالب با افکت شیشه مات و اثرات مدرن'],
            ['name' => 'انیمیشنی پریمیوم', 'slug' => 'wedding-premium-animated', 'category' => 'wedding', 'blade_view' => 'wedding.premium-animated', 'is_premium' => true, 'description' => 'قالب با انیمیشن‌های CSS در سراسر صفحه'],
            ['name' => 'ابریشم عروسی', 'slug' => 'wedding-silk', 'category' => 'wedding', 'blade_view' => 'wedding.silk', 'is_premium' => true, 'description' => 'قالب با گرادیان‌های نرم و بافت ابریشمی'],
            ['name' => 'شب پرستاره', 'slug' => 'wedding-starry-night', 'category' => 'wedding', 'blade_view' => 'wedding.starry-night', 'is_premium' => true, 'description' => 'قالب با پس‌زمینه آبی تیره و المان‌های آسمانی'],
            ['name' => 'رزگلد عروسی', 'slug' => 'wedding-rose-gold', 'category' => 'wedding', 'blade_view' => 'wedding.rose-gold', 'is_premium' => true, 'description' => 'قالب با پالت رزگلد و حس زنانه'],
            ['name' => 'آرت دکو', 'slug' => 'wedding-art-deco', 'category' => 'wedding', 'blade_view' => 'wedding.art-deco', 'is_premium' => true, 'description' => 'قالب با الگوهای هندسی و الهام از دهه ۱۹۲۰'],
        ];

        foreach ($templates as $template) {
            Template::firstOrCreate(
                ['slug' => $template['slug']],
                array_merge($template, [
                    'is_active' => true,
                    'description' => $template['description'] ?? "قالب {$template['name']}",
                ])
            );
        }
    }
}
