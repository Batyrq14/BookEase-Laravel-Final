<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Service;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ServiceRepository
{
    public function filter(array $filters = []): LengthAwarePaginator
    {
        return Service::query()
            ->with('category')
            ->filter($filters)
            ->paginate(10);
    }
}