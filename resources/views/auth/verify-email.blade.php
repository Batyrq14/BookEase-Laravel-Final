<x-guest-layout>
    <x-slot name="title">Verify Email — BookEase</x-slot>

    <div class="text-center mb-8">
        <div class="w-14 h-14 mx-auto mb-5 rounded-2xl bg-brand-50 flex items-center justify-center">
            <svg class="w-7 h-7 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
            </svg>
        </div>
        <h3 class="text-surface-900">Verify Your Email</h3>
        <p class="mt-2 text-surface-500 max-w-sm mx-auto" style="font-size: var(--text-body); letter-spacing: var(--tracking-body);">
            Thanks for signing up! Before getting started, please verify your email address by clicking the link we just emailed to you.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="alert-success mb-6">
            A new verification link has been sent to your email address.
        </div>
    @endif

    <div class="space-y-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-primary-button class="w-full justify-center py-3 text-base">
                {{ __('Resend Verification Email') }}
            </x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full py-3 font-medium text-surface-500 hover:text-surface-700 hover:bg-surface-100 rounded-full transition-colors"
                style="font-size: var(--text-body); letter-spacing: var(--tracking-body);">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>
