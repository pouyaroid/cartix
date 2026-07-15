<?php

declare(strict_types=1);

namespace App\Enums;

enum CardTypeEnum: string
{
    case BUSINESS = 'business';
    case WEDDING = 'wedding';
    case BIRTHDAY = 'birthday';
    case EVENT = 'event';
    case CONFERENCE = 'conference';
    case CORPORATE = 'corporate';
    case RESTAURANT = 'restaurant';
    case DOCTOR = 'doctor';
    case LAWYER = 'lawyer';
    case REAL_ESTATE = 'real_estate';
    case PORTFOLIO = 'portfolio';
    case RESUME = 'resume';

    public function label(): string
    {
        return match ($this) {
            self::BUSINESS => 'کارت ویزیت',
            self::WEDDING => 'دعوت نامه عروسی',
            self::BIRTHDAY => 'دعوت نامه تولد',
            self::EVENT => 'دعوت نامه رویداد',
            self::CONFERENCE => 'کارت کنفرانس',
            self::CORPORATE => 'کارت شرکتی',
            self::RESTAURANT => 'کارت رستوران',
            self::DOCTOR => 'کارت پزشک',
            self::LAWYER => 'کارت وکالت',
            self::REAL_ESTATE => 'کارت املاک',
            self::PORTFOLIO => 'نمونه کار',
            self::RESUME => 'رزومه',
        };
    }

    public static function labels(): array
    {
        return array_map(fn(self $case) => $case->label(), self::cases());
    }
}
