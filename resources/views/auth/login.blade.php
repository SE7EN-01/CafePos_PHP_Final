<x-guest-layout>
    <div x-data="{
        fillCredentials(email, pwd) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = pwd;
        }
    }">
        <div class="mb-6 text-center">
            <h1 class="text-2xl font-bold font-display tracking-tight text-white">Sign In</h1>
            <p class="mt-1 text-xs text-stone-400">Enter your credentials to access the register &amp; dashboard.</p>
        </div>

        <!-- Demo Accounts Helper Pill -->
        <div class="mb-6 rounded-2xl bg-white/5 p-3.5 border border-white/10 text-xs">
            <p class="font-bold text-amber-400 mb-2 flex items-center gap-1.5">
                <span>⚡ Demo Accounts (Click to autofill)</span>
            </p>
            <div class="grid grid-cols-2 gap-2">
                <button 
                    type="button" 
                    @click="fillCredentials('admin@cafe.com', 'admin')" 
                    class="rounded-xl bg-white/10 px-3 py-2 text-left hover:bg-amber-500/20 hover:text-amber-300 transition text-[11px]"
                >
                    <span class="block font-bold text-white">Manager / Admin</span>
                    <span class="block text-stone-400">admin@cafe.com</span>
                </button>
                <button 
                    type="button" 
                    @click="fillCredentials('barista@cafe.com', 'barista')" 
                    class="rounded-xl bg-white/10 px-3 py-2 text-left hover:bg-amber-500/20 hover:text-amber-300 transition text-[11px]"
                >
                    <span class="block font-bold text-white">Barista Staff</span>
                    <span class="block text-stone-400">barista@cafe.com</span>
                </button>
            </div>
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-xs font-bold text-stone-300 mb-1">Email Address</label>
                <input 
                    id="email" 
                    name="email" 
                    type="email" 
                    value="{{ old('email') }}" 
                    required 
                    autofocus 
                    autocomplete="username" 
                    class="block w-full rounded-2xl border-stone-700 bg-stone-900/80 px-4 py-3 text-xs text-white placeholder-stone-500 focus:border-amber-400 focus:ring-amber-400 transition" 
                    placeholder="name@coffee.com"
                >
                <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs text-rose-400" />
            </div>

            <div>
                <label for="password" class="block text-xs font-bold text-stone-300 mb-1">Password</label>
                <input 
                    id="password" 
                    name="password" 
                    type="password" 
                    required 
                    autocomplete="current-password" 
                    class="block w-full rounded-2xl border-stone-700 bg-stone-900/80 px-4 py-3 text-xs text-white placeholder-stone-500 focus:border-amber-400 focus:ring-amber-400 transition" 
                    placeholder="••••••••"
                >
                <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs text-rose-400" />
            </div>

            <div class="flex items-center justify-between text-xs pt-1">
                <label for="remember_me" class="inline-flex items-center gap-2 cursor-pointer select-none">
                    <input id="remember_me" type="checkbox" name="remember" class="rounded border-stone-700 bg-stone-900 text-amber-500 focus:ring-amber-500">
                    <span class="text-stone-400">Remember me</span>
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="font-medium text-amber-400 hover:text-amber-300 transition">
                        Forgot password?
                    </a>
                @endif
            </div>

            <button 
                type="submit" 
                class="w-full inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-amber-400 via-amber-500 to-amber-600 px-5 py-3.5 text-xs font-bold text-stone-950 shadow-lg shadow-amber-500/25 hover:brightness-110 active:scale-[0.99] transition"
            >
                <span>Sign in to POS</span>
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" /></svg>
            </button>
        </form>
    </div>
</x-guest-layout>
