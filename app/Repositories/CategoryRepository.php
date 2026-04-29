<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Exceptions\CategoryHasServicesException;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository
{
    public function delete(Category $category): bool
    {
        if ($category->services()->exists()) {
            throw new CategoryHasServicesException();
        }

        return $category->delete();
    }
    public function listForFilters(): Collection
    {
        return Category::query()
            ->orderBy('name')
            ->get(['id', 'name']);
    }
}