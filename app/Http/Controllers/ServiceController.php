<?php
declare(strict_types=1);
namespace App\Http\Controllers;

use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View {
        return view('services.index', ['services' => Service::latest()->get()]);
    }

    public function create(): View {
        return view('services.create');
    }

    public function store(StoreServiceRequest $request): RedirectResponse {
        Service::create($request->validated());
        return redirect()->route('services.index')->with('success', 'Service created successfully.');
    }

    public function edit(Service $service): View {
        return view('services.edit', ['service' => $service]);
    }

    public function update(UpdateServiceRequest $request, Service $service): RedirectResponse {
        $service->update($request->validated());
        return redirect()->route('services.index')->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service): RedirectResponse {
        $service->delete();
        return redirect()->route('services.index')->with('success', 'Service deleted successfully.');
    }
}
