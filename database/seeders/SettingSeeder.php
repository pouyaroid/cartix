<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['group' => 'general', 'key' => 'site_name', 'value' => 'کارت ایکس', 'type' => 'text'],
            ['group' => 'general', 'key' => 'site_description', 'value' => 'پلتفرم مدیریت کارت دیجیتال و کد QR', 'type' => 'textarea'],
            ['group' => 'general', 'key' => 'site_email', 'value' => 'info@cardx.ir', 'type' => 'email'],
            ['group' => 'general', 'key' => 'site_phone', 'value' => '021-12345678', 'type' => 'text'],
            ['group' => 'general', 'key' => 'site_footer', 'value' => 'تمامی حقوق محفوظ است.', 'type' => 'textarea'],
            ['group' => 'seo', 'key' => 'meta_title', 'value' => 'کارت ایکس - پلتفرم کارت دیجیتال', 'type' => 'text'],
            ['group' => 'seo', 'key' => 'meta_description', 'value' => 'ساخت و مدیریت کارت ویزیت دیجیتال و کد QR', 'type' => 'textarea'],
            ['group' => 'seo', 'key' => 'meta_keywords', 'value' => 'کارت ویزیت, دیجیتال, QR, کد QR', 'type' => 'text'],
            ['group' => 'payment', 'key' => 'gateway', 'value' => 'zarinpal', 'type' => 'text'],
            ['group' => 'payment', 'key' => 'merchant_id', 'value' => '', 'type' => 'text'],
        ];

        foreach ($settings as $setting) {
            Setting::firstOrCreate(
                ['group' => $setting['group'], 'key' => $setting['key']],
                $setting
            );
        }
    }
}
