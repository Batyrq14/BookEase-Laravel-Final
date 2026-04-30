<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Http\Requests\StoreProviderRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProviderController extends Controller
{
    public function index(): View
    {
        Gate::authorize('admin');

        return view('providers.index', [
            'providers' => User::query()
                ->providers()
                ->withCount('assignedServices')
                ->latest()
                ->get(),
        ]);
    }

    public function store(StoreProviderRequest $request): RedirectResponse
    {
        Gate::authorize('admin');

        $validated = $request->validated();
        $mode = $validated['mode'];
        $profilePhotoPath = $request->file('profile_photo')?->store('provider-profiles', 'public');

        $provider = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'bio' => $validated['bio'] ?? null,
            'profile_photo_path' => $profilePhotoPath,
            'role' => UserRole::Provider->value,
            'password' => Hash::make($validated['password'] ?? Str::password(24)),
        ]);

        if ($mode === 'invite') {
            $token = Password::broker()->createToken($provider);
            $invitationLink = route('password.reset', [
                'token' => $token,
                'email' => $provider->email,
            ]);

            return redirect()
                ->route('providers.index')
                ->with('success', 'Provider invitation created successfully.')
                ->with('invitation_link', $invitationLink)
                ->with('invited_provider_email', $provider->email);
        }

        return redirect()
            ->route('providers.index')
            ->with('success', 'Provider account created successfully.');
    }
}
