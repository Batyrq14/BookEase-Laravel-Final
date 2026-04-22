<section>
    <div class="mb-6">
        <h2 class="text-base font-semibold text-white">Profile Information</h2>
        <p class="text-sm text-slate-400 mt-0.5">Update your name and email address.</p>
    </div>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Full name')" />
            <x-text-input id="name" name="name" type="text" :value="old('name', $user->name)"
                          required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="phone" :value="__('Phone number')" />
            <x-text-input id="phone" name="phone" type="tel" :value="old('phone', $user->phone)"
                          placeholder="+1 555 000 0000" autocomplete="tel" />
            <x-input-error :messages="$errors->get('phone')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email address')" />
            <x-text-input id="email" name="email" type="email" :value="old('email', $user->email)"
                          required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2 p-3 rounded-xl bg-amber-500/10 border border-amber-500/20 text-sm text-amber-400">
                    Your email is unverified.
                    <button form="send-verification"
                            class="underline hover:text-amber-300 transition-colors ms-1">
                        Resend verification email
                    </button>
                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-1 text-emerald-400">Verification link sent!</p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-2 border-t border-slate-700/60">
            <x-primary-button>Save Changes</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition
                   x-init="setTimeout(() => show = false, 2500)"
                   class="text-sm text-emerald-400 font-medium">
                    Saved successfully.
                </p>
            @endif
        </div>
    </form>
</section>
