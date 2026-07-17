<?php

declare(strict_types=1);

namespace App\Services\Maps\Providers;

use App\Services\Maps\MapProviderInterface;

class GoogleMapsProvider implements MapProviderInterface
{
    public function getKey(): string
    {
        return 'google';
    }

    public function getName(): string
    {
        return 'Google Maps';
    }

    public function getNameLocal(): string
    {
        return 'گوگل مپ';
    }

    public function getScriptUrl(): ?string
    {
        $key = config('services.maps.google_key', '');
        if (empty($key)) {
            return null;
        }
        return "https://maps.googleapis.com/maps/api/js?key={$key}&callback=initGoogleMap";
    }

    public function renderEmbed(array $settings): string
    {
        $lat = $settings['lat'] ?? '35.699739';
        $lng = $settings['lng'] ?? '51.338097';
        $zoom = $settings['zoom'] ?? 12;
        $mapType = $settings['mapType'] ?? 'roadmap';
        $showMarker = $settings['showMarker'] ?? true;
        $markerTitle = $settings['markerTitle'] ?? '';
        $markerDescription = $settings['markerDescription'] ?? '';
        $markerColor = $settings['markerColor'] ?? '#e74c3c';
        $fullscreen = $settings['fullscreen'] ?? false;
        $zoomControl = $settings['zoomControl'] ?? true;
        $streetView = $settings['streetView'] ?? false;
        $scrollWheel = $settings['scrollWheel'] ?? true;
        $draggable = $settings['draggable'] ?? true;
        $doubleClickZoom = $settings['doubleClickZoom'] ?? true;
        $embedUrl = $settings['embedUrl'] ?? '';

        if (!empty($embedUrl)) {
            return $this->renderEmbedUrl($embedUrl, $settings);
        }

        $mapId = 'map-google-' . substr(md5(json_encode($settings)), 0, 8);

        $mapTypes = $mapType === 'hybrid' ? 'HYBRID' : ($mapType === 'satellite' ? 'SATELLITE' : 'ROADMAP');

        $controls = [];
        if (!$fullscreen) $controls[] = 'FullscreenControl:false';
        if (!$zoomControl) $controls[] = 'zoomControl:false';
        if (!$streetView) $controls[] = 'StreetViewControl:false';
        if (!$scrollWheel) $controls[] = 'scrollwheel:false';
        if (!$draggable) $controls[] = 'draggable:false';
        if (!$doubleClickZoom) $controls[] = 'disableDoubleClickZoom:true';

        $controlsStr = implode(',', $controls);

        $markerCode = '';
        if ($showMarker) {
            $infoContent = '';
            if (!empty($markerTitle) || !empty($markerDescription)) {
                $infoContent = addslashes("<div style='font-family:Vazirmatn,sans-serif;direction:rtl;text-align:right;padding:4px'><strong>{$markerTitle}</strong>" . ($markerDescription ? "<br>{$markerDescription}" : '') . "</div>");
            }
            $markerCode = "
                var marker{$mapId} = new google.maps.Marker({
                    position: pos{$mapId},
                    map: map{$mapId}" . ($markerTitle ? ",\n                    title: " . json_encode($markerTitle) : '') . "
                });" . ($infoContent ? "
                var infoWindow{$mapId} = new google.maps.InfoWindow({content: '{$infoContent}'});
                marker{$mapId}.addListener('click', function() { infoWindow{$mapId}.open(map{$mapId}, marker{$mapId}); });" : '');
        }

        return <<<HTML
<div id="{$mapId}" style="width:100%;height:100%;border-radius:inherit"></div>
<script>
(function(){
    if(typeof google !== 'undefined' && google.maps){
        renderMap_{$mapId}();
    } else {
        var s=document.createElement('script');
        s.src='https://maps.googleapis.com/maps/api/js?key={config("services.maps.google_key","")}&callback=renderMap_{$mapId}';
        s.async=true;s.defer=true;
        window.renderMap_{$mapId}=function(){renderMap_{$mapId}()};
        document.head.appendChild(s);
    }
    function renderMap_{$mapId}(){
        var pos={$mapId}?new google.maps.LatLng({$lat},{$lng}):new google.maps.LatLng({$lat},{$lng});
        var pos{$mapId}=new google.maps.LatLng({$lat},{$lng});
        var map{$mapId}=new google.maps.Map(document.getElementById('{$mapId}'),{
            center:pos{$mapId},zoom:{$zoom},mapTypeId:google.maps.MapTypeId.{$mapTypes},
            {$controlsStr}
        });
        {$markerCode}
    }
})();
</script>
HTML;
    }

    private function renderEmbedUrl(string $url, array $settings): string
    {
        $url = filter_var($url, FILTER_SANITIZE_URL);
        if (!$url) {
            return '<div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:#f3f4f6;border-radius:inherit"><p style="color:#9ca3af;font-size:13px">لینک نقشه نامعتبر است</p></div>';
        }
        return '<iframe src="' . htmlspecialchars($url) . '" style="width:100%;height:100%;border:none;border-radius:inherit" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>';
    }

    public function getSupportedMapTypes(): array
    {
        return [
            'roadmap' => 'نقشه',
            'satellite' => 'ماهواره‌ای',
            'hybrid' => 'ترکیبی',
        ];
    }

    public function supportsStreetView(): bool
    {
        return true;
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
        return 1;
    }

    public function getMaxZoom(): int
    {
        return 21;
    }
}
