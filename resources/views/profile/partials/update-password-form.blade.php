<section>
    <div class="mb-6">
        <h2 class="text-base font-semibold text-white">Update Password</h2>
        <p class="text-sm text-slate-400 mt-0.5">Use a long, random password to keep your account secure.</p>
    </div>

    <form method="post" action="{{ route('password.update') }}" class="space-y-5">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" :value="__('Current password')" />
            <x-text-input id="update_password_current_password" name="current_password"
                          type="password" placeholder="••••••••" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" />
        </div>

        <div>
            <x-input-label for="update_password_password" :value="__('New password')" />
            <x-text-input id="update_password_password" name="password"
                          type="password" placeholder="Min. 8 characters" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Confirm new password')" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation"
                          type="password" placeholder="Repeat new password" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" />
        </div>

        <div class="flex items-center gap-4 pt-2 border-t border-slate-700/60">
            <x-primary-button>Update Password</x-primary-button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition
                   x-init="setTimeout(() => show = false, 2500)"
                   class="text-sm text-emerald-400 font-medium">
                    Password updated.
                </p>
            @endif
        </div>
    </form>
</section>
