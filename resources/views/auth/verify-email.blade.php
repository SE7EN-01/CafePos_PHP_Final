<x-guest-layout>
    <div class="mb-6 text-center">
        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-amber-500/20 text-amber-400 border border-amber-500/30">
            <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
            </svg>
        </div>
        <h1 class="mt-4 text-2xl font-bold font-display tracking-tight text-white">Verify Your Email</h1>
        <p class="mt-1 text-xs text-stone-400">Thanks for joining our team! Please verify your email to get started.</p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 p-3 text-center text-xs font-medium text-emerald-400">
            A new verification link has been sent to your email address.
        </div>
    @endif

    <div class="space-y-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button 
                type="submit" 
                class="w-full flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 px-4 py-3 text-sm font-semibold text-stone-950 shadow-lg shadow-amber-500/20 hover:from-amber-400 hover:to-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-400 transition"
            >
                Resend Verification Email
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button 
                type="submit" 
                class="w-full flex items-center justify-center gap-2 rounded-xl border border-stone-800 bg-white/5 px-4 py-3 text-sm font-medium text-stone-300 hover:bg-white/10 hover:text-white transition"
            >
                Log Out
            </button>
        </form>
    </div>
</x-guest-layout>
