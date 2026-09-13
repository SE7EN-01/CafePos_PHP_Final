<section>
    <header class="mb-6">
        <h2 class="text-lg font-semibold text-stone-900">Update password</h2>
        <p class="mt-1 text-sm text-stone-500">Ensure your account is using a long, random password to stay secure.</p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-5">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="block text-sm font-medium text-stone-700">Current password</label>
            <input id="update_password_current_password" name="current_password" type="password" autocomplete="current-password" class="mt-1.5 block w-full rounded-xl border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-900 placeholder-stone-400 focus:border-amber-500 focus:ring-amber-500" placeholder="Enter current password">
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <label for="update_password_password" class="block text-sm font-medium text-stone-700">New password</label>
            <input id="update_password_password" name="password" type="password" autocomplete="new-password" class="mt-1.5 block w-full rounded-xl border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-900 placeholder-stone-400 focus:border-amber-500 focus:ring-amber-500" placeholder="Enter new password">
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block text-sm font-medium text-stone-700">Confirm password</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" class="mt-1.5 block w-full rounded-xl border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-900 placeholder-stone-400 focus:border-amber-500 focus:ring-amber-500" placeholder="Confirm new password">
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-stone-900 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-stone-800 focus:outline-none focus:ring-2 focus:ring-stone-900 focus:ring-offset-2">
                Save password
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-emerald-600"
                >Saved.</p>
            @endif
        </div>
    </form>
</section>
