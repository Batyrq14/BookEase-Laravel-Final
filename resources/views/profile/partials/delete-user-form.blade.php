<section>
    <div class="mb-6">
        <h2 class="text-base font-semibold text-slate-900">Delete Account</h2>
        <p class="text-sm text-slate-400 mt-0.5">
            Once your account is deleted, all data is permanently removed. This cannot be undone.
        </p>
    </div>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">
        Delete Account
    </x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-7">
            @csrf
            @method('delete')

            <div class="flex items-start gap-4 mb-5">
                <div class="w-10 h-10 rounded-xl bg-red-500/15 border border-red-500/25 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-semibold text-white">Are you absolutely sure?</h3>
                    <p class="text-sm text-slate-400 mt-1">
                        Enter your password to permanently delete your account and all associated data.
                    </p>
                </div>
            </div>

            <div class="mb-6">
                <x-input-label for="password" :value="__('Your password')" />
                <x-text-input id="password" name="password" type="password"
                              placeholder="••••••••" class="w-full" />
                <x-input-error :messages="$errors->userDeletion->get('password')" />
            </div>

            <div class="flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">
                    Cancel
                </x-secondary-button>
                <x-danger-button>
                    Yes, delete my account
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
