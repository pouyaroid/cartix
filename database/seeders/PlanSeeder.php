<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'رایگان',
                'slug' => 'free',
                'description' => 'برای شروع کار',
                'price_monthly' => 0,
                'price_yearly' => 0,
                'max_cards' => 3,
                'max_qr_codes' => 5,
                'max_storage_mb' => 50,
                'features' => ['۳ کارت', '۵ کد QR', '۵۰ مگابایت فضا'],
                'is_active' => true,
                'sort_order' => 0,
            ],
            [
                'name' => 'حرفه‌ای',
                'slug' => 'professional',
                'description' => 'برای کسب‌وکارهای کوچک',
                'price_monthly' => 99000,
                'price_yearly' => 990000,
                'max_cards' => 25,
                'max_qr_codes' => 50,
                'max_storage_mb' => 1024,
                'features' => ['۲۵ کارت', '۵۰ کد QR', '۱ گیگابایت فضا', 'قالب‌های پریمیوم', 'آمار پیشرفته'],
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'کسب‌وکار',
                'slug' => 'business',
                'description' => 'برای کسب‌وکارهای متوسط',
                'price_monthly' => 249000,
                'price_yearly' => 2490000,
                'max_cards' => 100,
                'max_qr_codes' => 200,
                'max_storage_mb' => 5120,
                'features' => ['۱۰۰ کارت', '۲۰۰ کد QR', '۵ گیگابایت فضا', 'همه قالب‌ها', 'آمار پیشرفته', 'پشتیبانی اولویت‌دار'],
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'سازمانی',
                'slug' => 'enterprise',
                'description' => 'برای سازمان‌ها',
                'price_monthly' => 499000,
                'price_yearly' => 4990000,
                'max_cards' => -1,
                'max_qr_codes' => -1,
                'max_storage_mb' => 20480,
                'features' => ['کارت نامحدود', 'QR نامحدود', '۲۰ گیگابایت فضا', 'دامنه اختصاصی', 'API', 'پشتیبانی اختصاصی'],
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($plans as $plan) {
            Plan::firstOrCreate(['slug' => $plan['slug']], $plan);
        }
    }
}
