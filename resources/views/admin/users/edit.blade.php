<x-app-layout>
    <div class="min-h-full bg-[#F9F6F0] p-4 sm:p-6 lg:p-8">
        <div class="mx-auto max-w-3xl space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2 text-xs font-semibold text-stone-700 shadow-sm border border-stone-200/80 hover:bg-stone-50 hover:text-amber-800 transition">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
                    <span>Back to Team</span>
                </a>
                <span class="text-xs text-stone-400">Editing #{{ $user->id }}</span>
            </div>

            <div class="rounded-3xl bg-white p-6 sm:p-8 shadow-sm border border-stone-200/80">
                <div class="border-b border-stone-100 pb-5 mb-6">
                    <h1 class="text-2xl font-bold font-display tracking-tight text-stone-900">Edit Team Member</h1>
                    <p class="text-xs text-stone-500 mt-1">Update staff details, contact info, and permission roles.</p>
                </div>

                <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-6">
                    @csrf
                    @method('patch')

                    <div class="grid gap-6 sm:grid-cols-2">
                        <div>
                            <label class="block text-xs font-bold text-stone-700 mb-1.5">Full Name <span class="text-rose-500">*</span></label>
                            <input name="name" type="text" value="{{ old('name', $user->name) }}" required class="block w-full rounded-2xl border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-900 focus:border-amber-500 focus:bg-white focus:ring-2 focus:ring-amber-500/20 transition">
                            @error('name')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-stone-700 mb-1.5">Email Address <span class="text-rose-500">*</span></label>
                            <input name="email" type="email" value="{{ old('email', $user->email) }}" required class="block w-full rounded-2xl border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-900 focus:border-amber-500 focus:bg-white focus:ring-2 focus:ring-amber-500/20 transition">
                            @error('email')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="grid gap-6 sm:grid-cols-2">
                        <div>
                            <label class="block text-xs font-bold text-stone-700 mb-1.5">Phone Number <span class="text-stone-400 font-normal">(optional)</span></label>
                            <input name="phone" type="tel" value="{{ old('phone', $user->phone) }}" class="block w-full rounded-2xl border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-900 focus:border-amber-500 focus:bg-white focus:ring-2 focus:ring-amber-500/20 transition">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-stone-700 mb-1.5">System Role <span class="text-rose-500">*</span></label>
                            <select name="role" required class="block w-full rounded-2xl border-stone-200 bg-stone-50 px-4 py-3 text-sm capitalize text-stone-900 focus:border-amber-500 focus:bg-white focus:ring-2 focus:ring-amber-500/20 transition">
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}" @selected(old('role', $user->roles->first()?->name) === $role->name)>{{ ucfirst($role->name) }}</option>
                                @endforeach
                            </select>
                            @error('role')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <fieldset class="space-y-3 border-t border-stone-100 pt-5 text-xs">
                        <legend class="text-xs font-bold uppercase tracking-wider text-stone-400 mb-2">Access Status</legend>
                        <label class="flex items-center gap-2.5 cursor-pointer select-none">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $user->is_active)) class="h-4 w-4 rounded border-stone-300 text-amber-600 focus:ring-amber-500">
                            <span class="text-xs font-bold text-stone-800">Active Account (Can log into POS &amp; dashboard)</span>
                        </label>
                        <label class="flex items-center gap-2.5 cursor-pointer select-none">
                            <input type="hidden" name="email_verified" value="0">
                            <input type="checkbox" name="email_verified" value="1" @checked($user->email_verified_at !== null) class="h-4 w-4 rounded border-stone-300 text-amber-600 focus:ring-amber-500">
                            <span class="text-xs font-bold text-stone-800">Email Verified</span>
                        </label>
                    </fieldset>

                    <div class="flex items-center gap-3 pt-6 border-t border-stone-100">
                        <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-stone-900 px-6 py-3.5 text-xs font-bold text-white shadow-md hover:bg-stone-800 transition">
                            Save Changes
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="rounded-2xl px-5 py-3.5 text-xs font-semibold text-stone-600 hover:bg-stone-100 transition">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
