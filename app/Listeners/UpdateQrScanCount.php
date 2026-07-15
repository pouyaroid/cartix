<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\QrCodeScanned;

class UpdateQrScanCount
{
    public function handle(QrCodeScanned $event): void
    {
        $event->qrCode->increment('scans_count');
    }
}
