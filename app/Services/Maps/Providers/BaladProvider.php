<?php

declare(strict_types=1);

namespace App\Services\Maps\Providers;

use App\Services\Maps\MapProviderInterface;

class BaladProvider implements MapProviderInterface
{
    public function getKey(): string
    {
        return 'balad';
    }

    public function getName(): string
    {
        return 'Balad';
    }

    public function getNameLocal(): string
    {
        return 'بلد';
    }

    public function getScriptUrl(): ?string
    {
        return null;
    }

    public function renderEmbed(array $settings): string
    {
        $lat = $settings['lat'] ?? '35.699739';
        $lng = $settings['lng'] ?? '51.338097';
        $zoom = $settings['zoom'] ?? 12;
        $showMarker = $settings['showMarker'] ?? true;
        $markerTitle = $settings['markerTitle'] ?? '';
        $markerDescription = $settings['markerDescription'] ?? '';
        $embedUrl = $settings['embedUrl'] ?? '';

        if (!empty($embedUrl)) {
            return $this->renderEmbedUrl($embedUrl);
        }

        $mapId = 'map-balad-' . substr(md5(json_encode($settings)), 0, 8);

        $markerParam = $showMarker ? "&marker=true" : "";

        return <<<HTML
<div id="{$mapId}" style="width:100%;height:100%;border-radius:inherit;overflow:hidden">
    <iframe
        src="https://balad.ir/embed?lat={$lat}&lng={$lng}&zoom={$zoom}{$markerParam}"
        style="width:100%;height:100%;border:none"
        allowfullscreen
        loading="lazy"
        referrerpolicy="no-referrer-when-downgrade"
        title="نقشه بلد"
    ></iframe>
</div>
HTML;
    }

    private function renderEmbedUrl(string $url): string
    {
        $url = filter_var($url, FILTER_SANITIZE_URL);
        if (!$url) {
            return '<div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:#f3f4f6;border-radius:inherit"><p style="color:#9ca3af;font-size:13px">لینک نقشه نامعتبر است</p></div>';
        }
        return '<iframe src="' . htmlspecialchars($url) . '" style="width:100%;height:100%;border:none;border-radius:inherit" allowfullscreen loading="lazy"></iframe>';
    }

    public function getSupportedMapTypes(): array
    {
        return [
            'roadmap' => 'نقشه',
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
