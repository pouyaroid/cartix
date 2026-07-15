<?php

declare(strict_types=1);

namespace App\Enums;

enum SubscriptionStatusEnum: string
{
    case ACTIVE = 'active';
    case CANCELLED = 'cancelled';
    case EXPIRED = 'expired';
    case TRIALING = 'trialing';

    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => 'فعال',
            self::CANCELLED => 'لغو شده',
            self::EXPIRED => 'منقضی شده',
            self::TRIALING => 'آزمایشی',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::ACTIVE => 'success',
            self::CANCELLED => 'danger',
            self::EXPIRED => 'warning',
            self::TRIALING => 'info',
        };
    }
}
