<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Service;
use App\Models\User;

class ServicePolicy
{
    public function before(User $user, string $ability): ?bool
    {
        return $user->isAdmin() ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->isProvider();
    }

    public function view(User $user, Service $service): bool
    {
        return $user->isProvider() && $service->provider_id === $user->id;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Service $service): bool
    {
        return $user->isProvider() && $service->provider_id === $user->id;
    }

    public function delete(User $user, Service $service): bool
    {
        return $user->isProvider()
            && $service->provider_id === $user->id
            && $service->creator_user_id === $user->id;
    }
}
