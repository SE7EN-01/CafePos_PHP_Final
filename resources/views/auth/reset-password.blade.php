<x-guest-layout>
    <div class="mb-6 text-center">
        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-amber-500/20 text-amber-400 border border-amber-500/30">
            <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
            </svg>
        </div>
        <h1 class="mt-4 text-2xl font-bold font-display tracking-tight text-white">Reset Password</h1>
        <p class="mt-1 text-xs text-stone-400">Enter your new secure password below.</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <label for="email" class="block text-xs font-bold text-stone-300 mb-1">Email Address</label>
            <input 
                id="email" 
                name="email" 
                type="email" 
                value="{{ old('email', $request->email) }}" 
                required 
                autofocus 
                autocomplete="username" 
                class="block w-full rounded-xl border border-stone-800 bg-stone-950/60 px-4 py-3 text-sm text-white placeholder-stone-500 focus:border-amber-500 focus:ring-amber-500" 
                placeholder="name@coffeecounter.com"
            >
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-rose-400" />
        </div>

        <div>
            <label for="password" class="block text-xs font-bold text-stone-300 mb-1">New Password</label>
            <input 
                id="password" 
                name="password" 
                type="password" 
                required 
                autocomplete="new-password" 
                class="block w-full rounded-xl border border-stone-800 bg-stone-950/60 px-4 py-3 text-sm text-white placeholder-stone-500 focus:border-amber-500 focus:ring-amber-500" 
                placeholder="Create a strong password"
            >
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-rose-400" />
        </div>

        <div>
            <label for="password_confirmation" class="block text-xs font-bold text-stone-300 mb-1">Confirm Password</label>
            <input 
                id="password_confirmation" 
                name="password_confirmation" 
                type="password" 
                required 
                autocomplete="new-password" 
                class="block w-full rounded-xl border border-stone-800 bg-stone-950/60 px-4 py-3 text-sm text-white placeholder-stone-500 focus:border-amber-500 focus:ring-amber-500" 
                placeholder="Confirm your password"
            >
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-rose-400" />
        </div>

        <button 
            type="submit" 
            class="w-full flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 px-4 py-3 text-sm font-semibold text-stone-950 shadow-lg shadow-amber-500/20 hover:from-amber-400 hover:to-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-400 transition"
        >
            Reset Password
        </button>
    </form>
</x-guest-layout>
