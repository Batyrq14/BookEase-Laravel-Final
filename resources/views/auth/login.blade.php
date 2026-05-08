<x-guest-layout>
    <x-slot name="title">Sign In — BookEase</x-slot>

    {{-- Heading --}}
    <div class="text-center mb-8">
        <h3 class="text-surface-900">Welcome Back</h3>
        <p class="mt-1.5 text-surface-500" style="font-size: var(--text-body); letter-spacing: var(--tracking-body);">Sign in to access your BookEase account</p>
    </div>

    {{-- Session Status --}}
    <x-auth-session-status class="mb-5" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        {{-- Email --}}
        <div>
            <x-input-label for="email" :value="__('Email address')" required />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="you@example.com"
                icon='<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>'
                iconPosition="left" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        {{-- Password --}}
        <div x-data="{ showPassword: false }">
            <div class="flex items-center justify-between mb-1.5">
                <x-input-label for="password" :value="__('Password')" required />
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="font-semibold text-brand-500 hover:text-brand-600 transition-colors" style="font-size: var(--text-caption); letter-spacing: var(--tracking-caption);">
                        Forgot Password?
                    </a>
                @endif
            </div>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-surface-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                </span>
                <input id="password" :type="showPassword ? 'text' : 'password'" name="password" required autocomplete="current-password" placeholder="••••••••"
                    class="block w-full rounded-none border-0 outline-none bg-white text-surface-900 placeholder-surface-400 shadow-input focus:shadow-input-focus transition-shadow duration-150"
                    style="padding: 15px 44px 15px 48px; font-size: var(--text-body); letter-spacing: var(--tracking-body); color: var(--color-midnight-navy);" />
                <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-surface-400 hover:text-surface-600 transition-colors">
                    <svg x-show="!showPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <svg x-show="showPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" />
        </div>

        {{-- Remember Me --}}
        <div class="flex items-center">
            <label for="remember_me" class="flex items-center gap-2.5 cursor-pointer select-none">
                <input id="remember_me" type="checkbox" name="remember"
                    class="w-4 h-4 rounded-none border-surface-300 text-brand-600 focus:ring-brand-500 cursor-pointer" />
                <span class="text-surface-600" style="font-size: var(--text-body); letter-spacing: var(--tracking-body);">{{ __('Remember me') }}</span>
            </label>
        </div>

        {{-- Submit --}}
        <x-primary-button class="w-full justify-center py-3 text-base">
            {{ __('Sign In') }}
        </x-primary-button>
    </form>

    {{-- Register link --}}
    @if (Route::has('register'))
        <p class="text-center text-surface-500 mt-6" style="font-size: var(--text-body); letter-spacing: var(--tracking-body);">
            Don't have an account?
            <a href="{{ route('register') }}" class="font-semibold text-brand-500 hover:text-brand-600 transition-colors">
                Create one
            </a>
        </p>
    @endif
</x-guest-layout>
