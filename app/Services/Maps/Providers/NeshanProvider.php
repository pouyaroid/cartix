<?php

declare(strict_types=1);

namespace App\Services\Maps\Providers;

use App\Services\Maps\MapProviderInterface;

class NeshanProvider implements MapProviderInterface
{
    public function getKey(): string
    {
        return 'neshan';
    }

    public function getName(): string
    {
        return 'Neshan';
    }

    public function getNameLocal(): string
    {
        return 'نشان';
    }

    public function getScriptUrl(): ?string
    {
        $key = config('services.maps.neshan_key', '');
        if (empty($key)) {
            return null;
        }
        return "https://static.neshan.org/sdk/ol/v5.5.0/neshan-sdk.min.js";
    }

    public function renderEmbed(array $settings): string
    {
        $lat = $settings['lat'] ?? '35.699739';
        $lng = $settings['lng'] ?? '51.338097';
        $zoom = $settings['zoom'] ?? 12;
        $mapType = $settings['mapType'] ?? 'neshan';
        $showMarker = $settings['showMarker'] ?? true;
        $markerTitle = $settings['markerTitle'] ?? '';
        $markerDescription = $settings['markerDescription'] ?? '';
        $markerColor = $settings['markerColor'] ?? '#e74c3c';
        $fullscreen = $settings['fullscreen'] ?? false;
        $zoomControl = $settings['zoomControl'] ?? true;
        $scrollWheel = $settings['scrollWheel'] ?? true;
        $draggable = $settings['draggable'] ?? true;
        $doubleClickZoom = $settings['doubleClickZoom'] ?? true;
        $embedUrl = $settings['embedUrl'] ?? '';

        if (!empty($embedUrl)) {
            return $this->renderEmbedUrl($embedUrl);
        }

        $mapId = 'map-neshan-' . substr(md5(json_encode($settings)), 0, 8);
        $key = config('services.maps.neshan_key', '');

        if (empty($key)) {
            return $this->renderPlaceholder('کلید API نشان تنظیم نشده است');
        }

        $neshanType = $mapType === 'satellite' ? 'neshanSatellite' : 'neshan';
        $fullscreenJs = $fullscreen ? 'true' : 'false';
        $showMarkerJs = $showMarker ? 'true' : 'false';

        $jsOptions = json_encode([
            'scrollWheelZoom' => $scrollWheel,
            'dragging' => $draggable,
            'enableDefault_UI' => $zoomControl,
        ]);

        return <<<HTML
<div id="{$mapId}" style="width:100%;height:100%;border-radius:inherit"></div>
<link rel="stylesheet" href="https://static.neshan.org/sdk/ol/v5.5.0/ol.css">
<script src="https://static.neshan.org/sdk/ol/v5.5.0/neshan-sdk.min.js"></script>
<script>
(function(){
    try {
        var map=new neshan.Map('{$mapId}',{
            key:'{$key}',
            center:[{$lng},{$lat}],
            zoom:{$zoom},
            mapType:'{$neshanType}'
        });
        map.ui.add(new neshan.control.Zoom(), 'top-right');
        if({$fullscreenJs}){map.ui.add(new neshan.control.FullScreen(), 'top-right');}
        if({$showMarkerJs}){
            var marker=new neshan.Marker({
                position:[{$lng},{$lat}],
                map:map
            });
        }
    } catch(e) {
        document.getElementById('{$mapId}').innerHTML='<div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:#f3f4f6"><p style="color:#9ca3af;font-size:13px">خطا در بارگذاری نقشه</p></div>';
    }
})();
</script>
HTML;
    }

    private function renderEmbedUrl(string $url): string
    {
        $url = filter_var($url, FILTER_SANITIZE_URL);
        if (!$url) {
            return $this->renderPlaceholder('لینک نقشه نامعتبر است');
        }
        return '<iframe src="' . htmlspecialchars($url) . '" style="width:100%;height:100%;border:none;border-radius:inherit" allowfullscreen loading="lazy"></iframe>';
    }

    private function renderPlaceholder(string $message): string
    {
        return <<<HTML
<div style="width:100%;height:100%;display:flex;flex-direction:column;align-items:center;justify-content:center;background:#f3f4f6;border-radius:inherit;gap:8px">
    <i class="bi bi-geo-alt" style="font-size:36px;color:#6366f1;opacity:.5"></i>
    <p style="color:#9ca3af;font-size:13px">{$message}</p>
</div>
HTML;
    }

    public function getSupportedMapTypes(): array
    {
        return [
            'neshan' => 'نقشه',
            'satellite' => 'ماهواره‌ای',
        ];
    }

    public function supportsStreetView(): bool
    {
        return false;
    }

    public function supportsFullscreen(): bool
    {
        return true;
    }

    public function supportsScrollWheelZoom(): bool
    {
        return true;
    }

    public function supportsMarkerColor(): bool
    {
        return false;
    }

    public function getDefaultZoom(): int
    {
        return 12;
    }

    public function getMinZoom(): int
    {
        return 5;
    }

    public function getMaxZoom(): int
    {
        return 18;
    }
}
