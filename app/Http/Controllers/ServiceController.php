<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Models\Category;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function browse(Request $request): View
    {
        $filters = $request->only(['search', 'category_id']);

        $query = Service::query()->with(['provider', 'category'])->whereNotNull('provider_id');

        if (! empty($filters['search'])) {
            $query->where('name', 'like', '%'.$filters['search'].'%');
        }
        if (! empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        $services = $query->latest()->get();
        $categories = Category::orderBy('name')->get();

        return view('services.browse', compact('services', 'categories', 'filters'));
    }

    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Service::class);

        $user = auth()->user();
        $filters = $request->only(['search', 'category_id']);

        $query = $user->isAdmin()
            ? Service::query()->with(['provider', 'creator', 'category'])
            : Service::query()->with(['provider', 'creator', 'category'])->where('provider_id', $user->id);

        if (! empty($filters['search'])) {
            $query->where('name', 'like', '%'.$filters['search'].'%');
        }
        if (! empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        $services = $query->latest()->get();
        $categories = Category::orderBy('name')->get();

        return view('services.index', compact('services', 'categories', 'filters'));
    }

    public function create(): View
    {
        Gate::authorize('create', Service::class);

        return view('services.create', [
            'providers' => User::query()->providers()->orderBy('name')->get(),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function store(StoreServiceRequest $request): RedirectResponse
    {
        Gate::authorize('create', Service::class);

        Service::create([
            ...$request->validated(),
            'creator_user_id' => $request->user()->id,
        ]);

        return redirect()->route('services.index')->with('success', 'Service created successfully.');
    }

    public function edit(Service $service): View
    {
        Gate::authorize('update', $service);

        return view('services.edit', [
            'service' => $service,
            'providers' => User::query()->providers()->orderBy('name')->get(),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function update(UpdateServiceRequest $request, Service $service): RedirectResponse
    {
        Gate::authorize('update', $service);

        $service->update($request->validated());

        return redirect()->route('services.index')->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        Gate::authorize('delete', $service);

        $service->delete();

        return redirect()->route('services.index')->with('success', 'Service deleted successfully.');
    }
}
