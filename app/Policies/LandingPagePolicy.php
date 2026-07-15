<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\LandingPage;
use App\Models\User;

class LandingPagePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, LandingPage $landingPage): bool
    {
        return $user->id === $landingPage->user_id || $user->hasRole(['super-admin', 'admin']);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, LandingPage $landingPage): bool
    {
        return $user->id === $landingPage->user_id || $user->hasRole(['super-admin', 'admin']);
    }

    public function delete(User $user, LandingPage $landingPage): bool
    {
        return $user->id === $landingPage->user_id || $user->hasRole(['super-admin', 'admin']);
    }

    public function publish(User $user, LandingPage $landingPage): bool
    {
        return $user->id === $landingPage->user_id || $user->hasRole(['super-admin', 'admin']);
    }
}
