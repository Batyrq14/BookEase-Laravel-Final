<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Book;
use App\Models\User;

class BookPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        return $user->isAdmin() ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Book $book): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Book $book): bool
    {
        return false;
    }

    public function delete(User $user, Book $book): bool
    {
        return false;
    }
}
