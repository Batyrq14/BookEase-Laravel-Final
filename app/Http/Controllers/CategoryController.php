<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Exceptions\CategoryHasServicesException;
use App\Models\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function __construct(private readonly CategoryRepository $repository) {}

    public function index(): View
    {
        Gate::authorize('admin');

        return view('categories.index', [
            'categories' => Category::withCount('services')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('admin');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
        ]);

        Category::create($validated);

        return redirect()->route('categories.index')->with('success', 'Category created.');
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        Gate::authorize('admin');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('categories', 'name')->ignore($category)],
        ]);

        $category->update($validated);

        return redirect()->route('categories.index')->with('success', 'Category updated.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        Gate::authorize('admin');

        try {
            $this->repository->delete($category);
        } catch (CategoryHasServicesException) {
            return back()->with('error', 'Cannot delete a category that has services assigned to it.');
        }

        return redirect()->route('categories.index')->with('success', 'Category deleted.');
    }
}
