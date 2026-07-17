<?php

declare(strict_types=1);

namespace App\Services\Maps;

interface MapProviderInterface
{
    public function getKey(): string;

    public function getName(): string;

    public function getNameLocal(): string;

    public function getScriptUrl(): ?string;

    public function renderEmbed(array $settings): string;

    public function getSupportedMapTypes(): array;

    public function supportsStreetView(): bool;

    public function supportsFullscreen(): bool;

    public function supportsScrollWheelZoom(): bool;

    public function supportsMarkerColor(): bool;

    public function getDefaultZoom(): int;

    public function getMinZoom(): int;

    public function getMaxZoom(): int;
}
