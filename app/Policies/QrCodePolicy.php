<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\QrCode;
use App\Models\User;

class QrCodePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, QrCode $qrCode): bool
    {
        return $user->id === $qrCode->user_id || $user->hasRole(['super-admin', 'admin']);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, QrCode $qrCode): bool
    {
        return $user->id === $qrCode->user_id || $user->hasRole(['super-admin', 'admin']);
    }

    public function delete(User $user, QrCode $qrCode): bool
    {
        return $user->id === $qrCode->user_id || $user->hasRole(['super-admin', 'admin']);
    }
}
