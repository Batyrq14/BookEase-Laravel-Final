<x-guest-layout>
    <x-slot name="title">Reset Password — BookEase</x-slot>

    {{-- Heading --}}
    <div class="text-center mb-8">
        <div class="w-14 h-14 mx-auto mb-5 rounded-2xl bg-brand-50 flex items-center justify-center">
            <svg class="w-7 h-7 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
            </svg>
        </div>
        <h3 class="text-surface-900">Forgot Password?</h3>
        <p class="mt-1.5 text-surface-500" style="font-size: var(--text-body); letter-spacing: var(--tracking-body);">Enter your email and we'll send you a reset link</p>
    </div>

    {{-- Session Status --}}
    <x-auth-session-status class="mb-5" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        {{-- Email --}}
        <div>
            <x-input-label for="email" :value="__('Email address')" required />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="you@example.com"
                icon='<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>'
                iconPosition="left" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <x-primary-button class="w-full justify-center py-3 text-base">
            {{ __('Send Reset Link') }}
        </x-primary-button>
    </form>

    {{-- Back to login --}}
    <p class="text-center text-surface-500 mt-6" style="font-size: var(--text-body); letter-spacing: var(--tracking-body);">
        <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 font-semibold text-brand-500 hover:text-brand-600 transition-colors">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
            Back to Sign In
        </a>
    </p>
</x-guest-layout>
