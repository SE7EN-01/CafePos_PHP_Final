<x-app-layout>
    <div class="min-h-full bg-[#F9F6F0] p-4 sm:p-6 lg:p-8">
        <div class="w-full space-y-6">
            <!-- Header -->
            <header class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between bg-white rounded-3xl p-6 shadow-sm border border-stone-200/80">
                <div class="flex items-center gap-3.5">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-500/15 text-amber-700 border border-amber-500/30">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="text-2xl font-bold font-display tracking-tight text-stone-900">Team &amp; Staff Access</h1>
                            <span class="rounded-full bg-stone-100 px-3 py-0.5 text-xs font-bold text-stone-600 border border-stone-200">
                                {{ $users->total() }} Members
                            </span>
                        </div>
                        <p class="text-xs text-stone-500 mt-0.5">Control staff logins, assign barista vs admin roles, and track active accounts</p>
                    </div>
                </div>
            </header>

            <div class="grid gap-6 lg:grid-cols-[1fr_400px]">
                <!-- Team Members Table -->
                <section>
                    <div class="overflow-hidden rounded-3xl bg-white shadow-sm border border-stone-200/80">
                        <div class="border-b border-stone-100 px-6 py-4 flex items-center justify-between">
                            <h3 class="text-sm font-bold text-stone-900">Staff Directory</h3>
                            <span class="text-xs text-stone-400">{{ $users->where('is_active', true)->count() }} active accounts</span>
                        </div>

                        <div class="divide-y divide-stone-100">
                            @forelse ($users as $user)
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between px-6 py-4 gap-4 hover:bg-stone-50/50 transition">
                                    <div class="flex items-center gap-3.5">
                                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-amber-500/20 text-amber-800 font-bold font-display text-sm border border-amber-500/30">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-bold text-stone-900 text-sm leading-tight">{{ $user->name }}</p>
                                            <p class="text-xs text-stone-400 mt-0.5">{{ $user->email }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2.5 self-end sm:self-center">
                                        @foreach ($user->roles as $role)
                                            <span class="rounded-full px-2.5 py-0.5 text-xs font-bold capitalize {{ $role->name === 'admin' ? 'bg-purple-100 text-purple-800 border border-purple-200' : 'bg-amber-100 text-amber-800 border border-amber-200' }}">
                                                {{ $role->name }}
                                            </span>
                                        @endforeach
                                        <span class="rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $user->is_active ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-stone-100 text-stone-600 border border-stone-200' }}">
                                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                        <a href="{{ route('admin.users.edit', $user) }}" class="rounded-xl px-3 py-1.5 text-xs font-semibold text-amber-800 bg-amber-50 hover:bg-amber-100 transition border border-amber-200">
                                            Edit
                                        </a>
                                        @if ($user->id !== auth()->id())
                                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Delete this team member?')">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="rounded-xl px-3 py-1.5 text-xs font-semibold text-rose-700 bg-rose-50 hover:bg-rose-100 transition border border-rose-200">
                                                    Delete
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="px-6 py-12 text-center text-stone-400">
                                    No team members found.
                                </div>
                            @endforelse
                        </div>

                        @if ($users->hasPages())
                            <div class="border-t border-stone-100 px-6 py-4">{{ $users->links() }}</div>
                        @endif
                    </div>
                </section>

                <!-- Add Team Member Form Sidebar -->
                <section>
                    <div class="rounded-3xl bg-[#1C1917] p-6 text-white shadow-xl border border-stone-800">
                        <div class="border-b border-stone-800 pb-4">
                            <div class="inline-flex items-center gap-2 rounded-full bg-amber-500/15 px-3 py-1 text-xs font-semibold text-amber-300 border border-amber-500/25 mb-2">
                                New Staff Account
                            </div>
                            <h3 class="text-lg font-bold font-display text-white">Add a team member</h3>
                            <p class="mt-1 text-xs text-stone-400">Set up credentials and assign barista or admin role.</p>
                        </div>

                        <form method="POST" action="{{ route('admin.users.store') }}" class="mt-5 space-y-4">
                            @csrf
                            <div>
                                <label class="block text-xs font-bold text-stone-300 mb-1">Full Name <span class="text-rose-400">*</span></label>
                                <input name="name" type="text" value="{{ old('name') }}" required placeholder="e.g. Dara Sok" class="block w-full rounded-2xl border-stone-700 bg-stone-900/80 px-4 py-2.5 text-xs text-white placeholder-stone-500 focus:border-amber-500 focus:ring-amber-500">
                                @error('name')<p class="mt-1 text-xs text-rose-400">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-stone-300 mb-1">Email Address <span class="text-rose-400">*</span></label>
                                <input name="email" type="email" value="{{ old('email') }}" required placeholder="staff@coffeepos.com" class="block w-full rounded-2xl border-stone-700 bg-stone-900/80 px-4 py-2.5 text-xs text-white placeholder-stone-500 focus:border-amber-500 focus:ring-amber-500">
                                @error('email')<p class="mt-1 text-xs text-rose-400">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-stone-300 mb-1">Phone Number <span class="text-stone-500 font-normal">(optional)</span></label>
                                <input name="phone" type="tel" value="{{ old('phone') }}" placeholder="+855 12 345 678" class="block w-full rounded-2xl border-stone-700 bg-stone-900/80 px-4 py-2.5 text-xs text-white placeholder-stone-500 focus:border-amber-500 focus:ring-amber-500">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-stone-300 mb-1">System Role <span class="text-rose-400">*</span></label>
                                <select name="role" required class="block w-full rounded-2xl border-stone-700 bg-stone-900/80 px-4 py-2.5 text-xs text-white focus:border-amber-500 focus:ring-amber-500">
                                    <option value="">Select a role</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->name }}" @selected(old('role') === $role->name)>{{ ucfirst($role->name) }}</option>
                                    @endforeach
                                </select>
                                @error('role')<p class="mt-1 text-xs text-rose-400">{{ $message }}</p>@enderror
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-bold text-stone-300 mb-1">Password <span class="text-rose-400">*</span></label>
                                    <input name="password" type="password" required class="block w-full rounded-2xl border-stone-700 bg-stone-900/80 px-4 py-2.5 text-xs text-white focus:border-amber-500 focus:ring-amber-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-stone-300 mb-1">Confirm <span class="text-rose-400">*</span></label>
                                    <input name="password_confirmation" type="password" required class="block w-full rounded-2xl border-stone-700 bg-stone-900/80 px-4 py-2.5 text-xs text-white focus:border-amber-500 focus:ring-amber-500">
                                </div>
                            </div>

                            <fieldset class="space-y-2 pt-2 border-t border-stone-800 text-xs">
                                <legend class="text-xs font-bold uppercase tracking-wider text-stone-300 mb-2">Account settings</legend>
                                <label class="flex items-center gap-2 cursor-pointer select-none">
                                    <input name="is_active" type="checkbox" value="1" @checked(old('is_active', true)) class="rounded border-stone-700 bg-stone-900 text-amber-500 focus:ring-amber-500">
                                    <span class="text-stone-300">Active Account (Allow sign in)</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer select-none">
                                    <input name="email_verified" type="checkbox" value="1" @checked(old('email_verified', true)) class="rounded border-stone-700 bg-stone-900 text-amber-500 focus:ring-amber-500">
                                    <span class="text-stone-300">Mark Email as Verified</span>
                                </label>
                            </fieldset>

                            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-amber-400 to-amber-600 px-5 py-3.5 text-xs font-bold text-stone-950 shadow-lg shadow-amber-500/25 hover:brightness-110 active:scale-[0.99] transition">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                <span>Create Member</span>
                            </button>
                        </form>
                    </div>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
