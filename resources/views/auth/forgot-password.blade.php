<x-guest-layout>
    <div class="mb-6 text-center">
        <h1 class="text-2xl font-bold font-display tracking-tight text-white">Forgot Password?</h1>
        <p class="mt-1 text-xs text-stone-400">Enter your staff email to receive a password reset link.</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
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
                class="block w-full rounded-2xl border-stone-700 bg-stone-900/80 px-4 py-3 text-xs text-white placeholder-stone-500 focus:border-amber-400 focus:ring-amber-400 transition" 
                placeholder="staff@coffeepos.com"
            >
            <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs text-rose-400" />
        </div>

        <button 
            type="submit" 
            class="w-full inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-amber-400 via-amber-500 to-amber-600 px-5 py-3.5 text-xs font-bold text-stone-950 shadow-lg shadow-amber-500/25 hover:brightness-110 active:scale-[0.99] transition mt-2"
        >
            <span>Email Password Reset Link</span>
        </button>

        <p class="text-center text-xs text-stone-400 pt-2">
            <a href="{{ route('login') }}" class="font-bold text-amber-400 hover:text-amber-300 transition">&larr; Back to sign in</a>
        </p>
    </form>
</x-guest-layout>
