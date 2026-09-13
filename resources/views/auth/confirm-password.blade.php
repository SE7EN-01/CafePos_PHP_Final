<x-guest-layout>
    <div class="mb-6 text-center">
        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-amber-500/20 text-amber-400 border border-amber-500/30">
            <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
            </svg>
        </div>
        <h1 class="mt-4 text-2xl font-bold font-display tracking-tight text-white">Secure Area</h1>
        <p class="mt-1 text-xs text-stone-400">Please confirm your password before continuing.</p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
        @csrf

        <div>
            <label for="password" class="block text-xs font-bold text-stone-300 mb-1">Password</label>
            <input 
                id="password" 
                name="password" 
                type="password" 
                required 
                autocomplete="current-password" 
                class="block w-full rounded-xl border border-stone-800 bg-stone-950/60 px-4 py-3 text-sm text-white placeholder-stone-500 focus:border-amber-500 focus:ring-amber-500" 
                placeholder="Enter your password"
            >
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-rose-400" />
        </div>

        <button 
            type="submit" 
            class="w-full flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 px-4 py-3 text-sm font-semibold text-stone-950 shadow-lg shadow-amber-500/20 hover:from-amber-400 hover:to-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-400 transition"
        >
            Confirm Password
        </button>
    </form>
</x-guest-layout>
