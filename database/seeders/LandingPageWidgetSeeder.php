<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Config\WidgetConfig;
use App\Models\LandingPageWidget;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LandingPageWidgetSeeder extends Seeder
{
    public function run(): void
    {
        $widgets = WidgetConfig::getWidgets();

        foreach ($widgets as $component => $config) {
            LandingPageWidget::updateOrCreate(
                ['slug' => Str::slug($component)],
                [
                    'name' => $config['name'],
                    'category' => $config['category'],
                    'icon' => $config['icon'],
                    'component' => $component,
                    'default_content' => $config['default_content'],
                    'default_styles' => $config['default_styles'],
                    'is_active' => true,
                    'sort_order' => 0,
                ]
            );
        }
    }
}
