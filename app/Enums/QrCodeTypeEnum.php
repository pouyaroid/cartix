<?php

declare(strict_types=1);

namespace App\Enums;

enum QrCodeTypeEnum: string
{
    case STATIC = 'static';
    case DYNAMIC = 'dynamic';

    public function label(): string
    {
        return match ($this) {
            self::STATIC => 'کد QR ثابت',
            self::DYNAMIC => 'کد QR پویا',
        };
    }
}
