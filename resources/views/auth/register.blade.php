<x-guest-layout>
    <div>
        <div class="mb-6 text-center">
            <h1 class="text-2xl font-bold font-display tracking-tight text-white">Register Staff Account</h1>
            <p class="mt-1 text-xs text-stone-400">Join the coffee counter team to start taking orders.</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <div>
                <label for="name" class="block text-xs font-bold text-stone-300 mb-1">Full Name</label>
                <input 
                    id="name" 
                    name="name" 
                    type="text" 
                    value="{{ old('name') }}" 
                    required 
                    autofocus 
                    autocomplete="name" 
                    class="block w-full rounded-2xl border-stone-700 bg-stone-900/80 px-4 py-3 text-xs text-white placeholder-stone-500 focus:border-amber-400 focus:ring-amber-400 transition" 
                    placeholder="e.g. Dara Sok"
                >
                <x-input-error :messages="$errors->get('name')" class="mt-1 text-xs text-rose-400" />
            </div>

            <div>
                <label for="email" class="block text-xs font-bold text-stone-300 mb-1">Email Address</label>
                <input 
                    id="email" 
                    name="email" 
                    type="email" 
                    value="{{ old('email') }}" 
                    required 
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
                    autocomplete="new-password" 
                    class="block w-full rounded-2xl border-stone-700 bg-stone-900/80 px-4 py-3 text-xs text-white placeholder-stone-500 focus:border-amber-400 focus:ring-amber-400 transition" 
                    placeholder="Create password"
                >
                <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs text-rose-400" />
            </div>

            <div>
                <label for="password_confirmation" class="block text-xs font-bold text-stone-300 mb-1">Confirm Password</label>
                <input 
                    id="password_confirmation" 
                    name="password_confirmation" 
                    type="password" 
                    required 
                    autocomplete="new-password" 
                    class="block w-full rounded-2xl border-stone-700 bg-stone-900/80 px-4 py-3 text-xs text-white placeholder-stone-500 focus:border-amber-400 focus:ring-amber-400 transition" 
                    placeholder="Repeat password"
                >
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-xs text-rose-400" />
            </div>

            <button 
                type="submit" 
                class="w-full inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-amber-400 via-amber-500 to-amber-600 px-5 py-3.5 text-xs font-bold text-stone-950 shadow-lg shadow-amber-500/25 hover:brightness-110 active:scale-[0.99] transition mt-2"
            >
                <span>Create Account</span>
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
            </button>

            <p class="text-center text-xs text-stone-400 pt-2">
                Already registered?
                <a href="{{ route('login') }}" class="font-bold text-amber-400 hover:text-amber-300 transition">Sign in</a>
            </p>
        </form>
    </div>
</x-guest-layout>
