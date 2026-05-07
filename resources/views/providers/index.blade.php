<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold text-slate-900 dark:text-white">Providers</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Create staff accounts or generate invitation links for new providers</p>
            </div>
        </div>
    </x-slot>

    <div class="grid gap-6 lg:grid-cols-[1.2fr,0.8fr]">
        <x-card :padding="false">
            @if($providers->count())
            <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50">
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Provider</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Assigned Services</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @foreach ($providers as $provider)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if($provider->profile_photo_path)
                                    <img src="{{ asset('storage/'.$provider->profile_photo_path) }}" alt="{{ $provider->name }}"
                                         class="h-11 w-11 rounded-full object-cover border border-slate-200 dark:border-slate-600">
                                @else
                                    <div class="flex h-11 w-11 items-center justify-center rounded-full bg-brand-600 dark:bg-brand-500 text-sm font-bold text-white shadow-sm">
                                        {{ strtoupper(substr($provider->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <p class="font-semibold text-slate-900 dark:text-white">{{ $provider->name }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ ucfirst($provider->role->value) }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-slate-700 dark:text-slate-300">{{ $provider->email }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ $provider->phone ?: 'No phone on file' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300">
                                {{ $provider->assigned_services_count }} {{ Str::plural('service', $provider->assigned_services_count) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
            @else
            <div class="flex flex-col items-center justify-center py-20 text-center">
                <div class="w-14 h-14 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center mb-4">
                    <svg class="w-7 h-7 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M17 20h5V4H2v16h5m10 0v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6m10 0H7"/>
                    </svg>
                </div>
                <p class="text-slate-700 dark:text-slate-300 font-semibold mb-1">No providers yet</p>
                <p class="text-slate-500 dark:text-slate-400 text-sm">Create the first provider account to start assigning services.</p>
            </div>
            @endif
        </x-card>

        <x-card>
            <div class="mb-5">
                <h2 class="text-base font-semibold text-slate-900 dark:text-white">Add Provider</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Create an account now or generate a password-reset invitation link to share later.</p>
            </div>

            @if(session('invitation_link'))
            <div class="mb-5 rounded-2xl border border-emerald-200 bg-emerald-50 p-4">
                <p class="text-sm font-semibold text-emerald-800">Invitation link ready for {{ session('invited_provider_email') }}</p>
                <p class="mt-2 break-all text-xs text-emerald-700">{{ session('invitation_link') }}</p>
            </div>
            @endif

            <form method="POST" action="{{ route('providers.store') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div>
                    <x-input-label for="name" :value="__('Provider name')" />
                    <x-text-input id="name" name="name" type="text" :value="old('name')" required autofocus />
                    <x-input-error :messages="$errors->get('name')" />
                </div>

                <div>
                    <x-input-label for="email" :value="__('Email address')" />
                    <x-text-input id="email" name="email" type="email" :value="old('email')" required />
                    <x-input-error :messages="$errors->get('email')" />
                </div>

                <div>
                    <x-input-label for="phone" :value="__('Phone number')" />
                    <x-text-input id="phone" name="phone" type="tel" :value="old('phone')" placeholder="+1 555 000 0000" />
                    <x-input-error :messages="$errors->get('phone')" />
                </div>

                <div>
                    <x-input-label for="bio" :value="__('Bio')" />
                    <textarea id="bio" name="bio" rows="4"
                              class="block w-full rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 px-4 py-2.5 text-sm transition-colors resize-none focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">{{ old('bio') }}</textarea>
                    <x-input-error :messages="$errors->get('bio')" />
                </div>

                <div>
                    <x-input-label for="profile_photo" :value="__('Profile photo')" />
                    <input id="profile_photo" name="profile_photo" type="file" accept="image/*"
                           class="block w-full rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 px-4 py-2.5 text-sm text-slate-600 dark:text-slate-300 file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-700 dark:file:bg-slate-700 dark:file:text-slate-300 hover:file:bg-slate-200 dark:hover:file:bg-slate-600">
                    <x-input-error :messages="$errors->get('profile_photo')" />
                </div>

                <div>
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password" name="password" type="password"
                                  placeholder="Required only when creating the account directly" />
                    <x-input-error :messages="$errors->get('password')" />
                </div>

                <div>
                    <x-input-label for="password_confirmation" :value="__('Confirm password')" />
                    <x-text-input id="password_confirmation" name="password_confirmation" type="password" />
                </div>

                <div class="flex flex-wrap items-center gap-3 pt-2 border-t border-slate-100 dark:border-slate-700">
                    <button type="submit" name="mode" value="create"
                            class="inline-flex items-center rounded-xl bg-slate-900 dark:bg-slate-100 px-4 py-2.5 text-sm font-semibold text-white dark:text-slate-900 transition hover:bg-slate-800 dark:hover:bg-white">
                        Create Provider
                    </button>
                    <button type="submit" name="mode" value="invite"
                            class="inline-flex items-center rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 px-4 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 transition hover:bg-slate-50 dark:hover:bg-slate-700">
                        Generate Invite Link
                    </button>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>
