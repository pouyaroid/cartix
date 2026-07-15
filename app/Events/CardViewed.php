<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Card;
use Illuminate\Foundation\Events\Dispatchable;

class CardViewed
{
    use Dispatchable;

    public function __construct(public Card $card) {}
}
