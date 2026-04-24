<x-guest-layout>
    <h2 class="text-xl font-bold text-slate-900 mb-1">Welcome back</h2>
    <p class="text-sm text-slate-500 mb-7">Sign in to your BookEase account</p>

    <x-auth-session-status class="mb-5" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email address')" />
            <x-text-input id="email" type="email" name="email" :value="old('email')"
                          placeholder="you@example.com" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div>
            <div class="flex items-center justify-between mb-1.5">
                <x-input-label for="password" :value="__('Password')" class="mb-0" />
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                       class="text-xs text-slate-600 hover:text-slate-700 transition-colors font-medium">
                        Forgot password?
                    </a>
                @endif
            </div>
            <x-text-input id="password" type="password" name="password"
                          placeholder="••••••••" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div class="flex items-center gap-2.5">
            <input id="remember_me" type="checkbox" name="remember"
                   class="w-4 h-4 rounded bg-white border-slate-300 text-slate-600 focus:ring-slate-500/30 focus:ring-offset-0">
            <label for="remember_me" class="text-sm text-slate-600">Remember me</label>
        </div>

        <x-primary-button class="w-full">Sign in</x-primary-button>

        <p class="text-center text-sm text-slate-500">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-slate-600 hover:text-slate-700 font-medium transition-colors">
                Create one free
            </a>
        </p>
    </form>
</x-guest-layout>
