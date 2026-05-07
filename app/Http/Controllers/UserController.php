<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        Gate::authorize('admin');

        return view('users.index', [
            'users' => User::query()->with('category')->latest()->get(),
            'categories' => Category::query()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('admin');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', Rule::in(array_column(UserRole::cases(), 'value'))],
            'phone' => ['nullable', 'string', 'max:30'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'bio' => ['nullable', 'string', 'max:2000'],
            'profile_photo' => ['nullable', 'image', 'max:2048'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $isProvider = $validated['role'] === UserRole::Provider->value;
        $profilePhotoPath = $isProvider ? $request->file('profile_photo')?->store('provider-profiles', 'public') : null;

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'phone' => $validated['phone'] ?? null,
            'bio' => $isProvider ? ($validated['bio'] ?? null) : null,
            'profile_photo_path' => $profilePhotoPath,
            'category_id' => $isProvider ? ($validated['category_id'] ?? null) : null,
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('users.index')
            ->with('success', 'User created successfully.');
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        Gate::authorize('admin');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', Rule::in(array_column(UserRole::cases(), 'value'))],
            'phone' => ['nullable', 'string', 'max:30'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];
        $user->phone = $validated['phone'] ?? null;
        $user->category_id = $validated['role'] === UserRole::Provider->value ? ($validated['category_id'] ?? null) : null;

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user): RedirectResponse
    {
        Gate::authorize('admin');

        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return back()->with('success', 'User deleted.');
    }
}
