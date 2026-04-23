<x-app-layout>
    <x-slot name="header">
        <h1 class="text-xl font-bold text-slate-900">Profile Settings</h1>
        <p class="text-sm text-slate-500 mt-0.5">Manage your account details and security</p>
    </x-slot>

    <div class="max-w-2xl space-y-6">
        <x-card>
            @include('profile.partials.update-profile-information-form')
        </x-card>

        <x-card>
            @include('profile.partials.update-password-form')
        </x-card>

        <x-card>
            @include('profile.partials.delete-user-form')
        </x-card>
    </div>
</x-app-layout>
