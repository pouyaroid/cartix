<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\QrCode;
use Illuminate\Foundation\Events\Dispatchable;

class QrCodeCreated
{
    use Dispatchable;

    public function __construct(public QrCode $qrCode) {}
}
