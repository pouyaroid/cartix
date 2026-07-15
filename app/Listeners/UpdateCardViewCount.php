<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\CardViewed;

class UpdateCardViewCount
{
    public function handle(CardViewed $event): void
    {
        $event->card->increment('views_count');
    }
}
