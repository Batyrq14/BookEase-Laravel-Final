<section>
    <div class="mb-6">
        <h2 class="text-base font-semibold text-slate-900 dark:text-white">Profile Information</h2>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Update your name and email address.</p>
    </div>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-5">
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

        @if($user->isStaff())
        <div>
            <x-input-label for="bio" :value="__('Professional bio')" />
            <textarea id="bio" name="bio" rows="4"
                      class="block w-full bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500
                             rounded-xl px-4 py-2.5 text-sm transition-colors resize-none
                             focus:outline-none focus:ring-2 focus:ring-slate-500/30 dark:focus:ring-slate-400/20 focus:border-slate-500 dark:focus:border-slate-400">{{ old('bio', $user->bio) }}</textarea>
            <x-input-error :messages="$errors->get('bio')" />
        </div>

        <div>
            <x-input-label for="profile_photo" :value="__('Profile photo')" />
            @if($user->profile_photo_path)
                <img src="{{ asset('storage/'.$user->profile_photo_path) }}" alt="{{ $user->name }}"
                     class="mb-3 h-16 w-16 rounded-full object-cover border border-slate-200 dark:border-slate-700">
            @endif
            <input id="profile_photo" name="profile_photo" type="file" accept="image/*"
                   class="block w-full rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 px-4 py-2.5 text-sm text-slate-600 dark:text-slate-300 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-slate-100 dark:file:bg-slate-700 file:text-slate-700 dark:file:text-slate-200 hover:file:bg-slate-200 dark:hover:file:bg-slate-600 transition-colors">
            <x-input-error :messages="$errors->get('profile_photo')" />
        </div>
        @endif

        <div>
            <x-input-label for="email" :value="__('Email address')" />
            <x-text-input id="email" name="email" type="email" :value="old('email', $user->email)"
                          required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2 p-3 rounded-xl bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 text-sm text-amber-700 dark:text-amber-400">
                    ⚠️ Your email is unverified.
                    <button form="send-verification"
                            class="underline hover:text-amber-800 dark:hover:text-amber-300 transition-colors ms-1">
                        Resend verification email
                    </button>
                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-1 text-emerald-600 dark:text-emerald-400">✓ Verification link sent!</p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-2 border-t border-slate-100 dark:border-slate-700">
            <x-primary-button>Save Changes</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition
                   x-init="setTimeout(() => show = false, 2500)"
                    class="text-sm text-emerald-600 dark:text-emerald-400 font-medium">
                    Saved successfully.
                </p>
            @endif
        </div>
    </form>
</section>
