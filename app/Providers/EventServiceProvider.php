<?php

declare(strict_types=1);

namespace App\Providers;

use App\Events\CardPublished;
use App\Events\CardViewed;
use App\Events\PaymentCompleted;
use App\Events\QrCodeScanned;
use App\Events\UserRegistered;
use App\Listeners\SendWelcomeEmail;
use App\Listeners\UpdateCardViewCount;
use App\Listeners\UpdateQrScanCount;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        UserRegistered::class => [
            SendWelcomeEmail::class,
        ],
        CardViewed::class => [
            UpdateCardViewCount::class,
        ],
        QrCodeScanned::class => [
            UpdateQrScanCount::class,
        ],
    ];

    public function boot(): void
    {
        //
    }
}
