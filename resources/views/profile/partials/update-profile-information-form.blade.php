<section>
    <div class="mb-6">
        <h2 class="text-base font-semibold text-slate-900">Profile Information</h2>
        <p class="text-sm text-slate-500 mt-0.5">Update your name and email address.</p>
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
                      class="block w-full bg-white border border-slate-300 text-slate-900 placeholder-slate-400
                             rounded-xl px-4 py-2.5 text-sm transition-colors resize-none
                             focus:outline-none focus:ring-2 focus:ring-slate-500/30 focus:border-slate-500">{{ old('bio', $user->bio) }}</textarea>
            <x-input-error :messages="$errors->get('bio')" />
        </div>

        <div>
            <x-input-label for="profile_photo" :value="__('Profile photo')" />
            @if($user->profile_photo_path)
                <img src="{{ asset('storage/'.$user->profile_photo_path) }}" alt="{{ $user->name }}"
                     class="mb-3 h-16 w-16 rounded-full object-cover border border-slate-200">
            @endif
            <input id="profile_photo" name="profile_photo" type="file" accept="image/*"
                   class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-600">
            <x-input-error :messages="$errors->get('profile_photo')" />
        </div>
        @endif

        <div>
            <x-input-label for="email" :value="__('Email address')" />
            <x-text-input id="email" name="email" type="email" :value="old('email', $user->email)"
                          required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2 p-3 rounded-xl bg-amber-50 border border-amber-200 text-sm text-amber-700">
                    Your email is unverified.
                    <button form="send-verification"
                            class="underline hover:text-amber-800 transition-colors ms-1">
                        Resend verification email
                    </button>
                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-1 text-emerald-600">Verification link sent!</p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-2 border-t border-slate-100">
            <x-primary-button>Save Changes</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition
                   x-init="setTimeout(() => show = false, 2500)"
                   class="text-sm text-emerald-600 font-medium">
                    Saved successfully.
                </p>
            @endif
        </div>
    </form>
</section>
