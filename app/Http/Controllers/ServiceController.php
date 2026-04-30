<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        Gate::authorize('viewAny', Service::class);

        $user = auth()->user();
        $services = $user->isAdmin()
            ? Service::query()->with(['provider', 'creator'])->latest()->get()
            : Service::query()
                ->with(['provider', 'creator'])
                ->where('provider_id', $user->id)
                ->latest()
                ->get();

        return view('services.index', ['services' => $services]);
    }

    public function create(): View
    {
        Gate::authorize('create', Service::class);

        return view('services.create', [
            'providers' => User::query()->providers()->orderBy('name')->get(),
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
