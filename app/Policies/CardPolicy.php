<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Card;
use App\Models\User;

class CardPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Card $card): bool
    {
        return $user->id === $card->user_id || $user->hasRole(['super-admin', 'admin']);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Card $card): bool
    {
        return $user->id === $card->user_id || $user->hasRole(['super-admin', 'admin']);
    }

    public function delete(User $user, Card $card): bool
    {
        return $user->id === $card->user_id || $user->hasRole(['super-admin', 'admin']);
    }

    public function publish(User $user, Card $card): bool
    {
        return $user->id === $card->user_id || $user->hasRole(['super-admin', 'admin']);
    }
}
