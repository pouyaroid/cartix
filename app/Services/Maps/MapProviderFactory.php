<?php

declare(strict_types=1);

namespace App\Services\Maps;

use App\Services\Maps\Providers\GoogleMapsProvider;
use App\Services\Maps\Providers\BaladProvider;
use App\Services\Maps\Providers\NeshanProvider;

class MapProviderFactory
{
    private static array $providers = [];

    private static array $registry = [
        'google' => GoogleMapsProvider::class,
        'balad' => BaladProvider::class,
        'neshan' => NeshanProvider::class,
    ];

    public static function make(string $key): MapProviderInterface
    {
        if (!isset(self::$providers[$key])) {
            if (!isset(self::$registry[$key])) {
                throw new \InvalidArgumentException("Unknown map provider: {$key}");
            }
            self::$providers[$key] = new self::$registry[$key]();
        }

        return self::$providers[$key];
    }

    public static function all(): array
    {
        $providers = [];
        foreach (self::$registry as $key => $class) {
            $providers[$key] = new $class();
        }
        return $providers;
    }

    public static function getChoices(): array
    {
        $choices = [];
        foreach (self::$registry as $key => $class) {
            $provider = new $class();
            $choices[$key] = $provider->getNameLocal();
        }
        return $choices;
    }

    public static function renderMap(array $settings): string
    {
        $providerKey = $settings['provider'] ?? 'google';

        try {
            $provider = self::make($providerKey);
            return $provider->renderEmbed($settings);
        } catch (\Exception $e) {
            return <<<HTML
<div style="width:100%;height:100%;display:flex;flex-direction:column;align-items:center;justify-content:center;background:#f3f4f6;border-radius:inherit;gap:8px">
    <i class="bi bi-geo-alt" style="font-size:36px;color:#dc2626;opacity:.5"></i>
    <p style="color:#9ca3af;font-size:13px">خطا در بارگذاری نقشه</p>
</div>
HTML;
        }
    }
}
