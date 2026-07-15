<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\QrCode;
use App\Models\QrScan;
use Illuminate\Foundation\Events\Dispatchable;

class QrCodeScanned
{
    use Dispatchable;

    public function __construct(public QrScan $scan, public QrCode $qrCode) {}
}
