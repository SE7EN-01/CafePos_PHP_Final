<x-app-layout>
    <div class="min-h-full bg-[#F9F6F0] p-4 sm:p-6 lg:p-8">
        <div class="w-full space-y-6">
            <!-- Header -->
            <header class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between bg-white rounded-3xl p-6 shadow-sm border border-stone-200/80">
                <div class="flex items-center gap-3.5">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-500/15 text-amber-700 border border-amber-500/30">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="text-2xl font-bold font-display tracking-tight text-stone-900">Cafe Tables</h1>
                            <span class="rounded-full bg-stone-100 px-3 py-0.5 text-xs font-bold text-stone-600 border border-stone-200">
                                {{ $stats['total'] }} Tables
                            </span>
                        </div>
                        <p class="text-xs text-stone-500 mt-0.5">Manage seating layout, live table occupancy, and dining capacity</p>
                    </div>
                </div>

                <!-- Stats summary pills -->
                <div class="flex flex-wrap items-center gap-2.5">
                    <span class="inline-flex items-center gap-1.5 rounded-2xl bg-emerald-50 px-3 py-1.5 text-xs font-bold text-emerald-700 border border-emerald-200">
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                        {{ $stats['available'] }} Available
                    </span>
                    <span class="inline-flex items-center gap-1.5 rounded-2xl bg-amber-50 px-3 py-1.5 text-xs font-bold text-amber-800 border border-amber-200">
                        <span class="h-2 w-2 rounded-full bg-amber-500"></span>
                        {{ $stats['occupied'] }} Occupied
                    </span>
                    @if ($stats['reserved'] > 0)
                        <span class="inline-flex items-center gap-1.5 rounded-2xl bg-stone-100 px-3 py-1.5 text-xs font-bold text-stone-700 border border-stone-200">
                            <span class="h-2 w-2 rounded-full bg-stone-400"></span>
                            {{ $stats['reserved'] }} Reserved
                        </span>
                    @endif
                </div>
            </header>

            @if (session('status'))
                <div class="rounded-2xl bg-emerald-50 border border-emerald-200 p-4 text-xs font-semibold text-emerald-800">
                    Action completed successfully.
                </div>
            @endif

            <div class="grid gap-6 lg:grid-cols-[1fr_380px]">
                <!-- Tables Floor Layout & Grid -->
                <section class="space-y-6">
                    <!-- Filter bar -->
                    <div class="flex flex-wrap items-center justify-between gap-3 bg-white p-4 rounded-3xl border border-stone-200/80 shadow-sm">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.tables.index') }}" class="rounded-xl px-3 py-1.5 text-xs font-bold transition {{ !request('status') ? 'bg-stone-900 text-white' : 'text-stone-600 hover:bg-stone-100' }}">
                                All ({{ $stats['total'] }})
                            </a>
                            <a href="{{ route('admin.tables.index', ['status' => 'available']) }}" class="rounded-xl px-3 py-1.5 text-xs font-bold transition {{ request('status') === 'available' ? 'bg-emerald-600 text-white' : 'text-stone-600 hover:bg-stone-100' }}">
                                Available ({{ $stats['available'] }})
                            </a>
                            <a href="{{ route('admin.tables.index', ['status' => 'occupied']) }}" class="rounded-xl px-3 py-1.5 text-xs font-bold transition {{ request('status') === 'occupied' ? 'bg-amber-600 text-white' : 'text-stone-600 hover:bg-stone-100' }}">
                                Occupied ({{ $stats['occupied'] }})
                            </a>
                        </div>
                    </div>

                    <!-- Tables Grid -->
                    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                        @forelse ($tables as $table)
                            <div class="flex flex-col justify-between rounded-3xl bg-white p-5 shadow-sm border transition-all hover:shadow-md {{ $table->status === 'occupied' ? 'border-amber-300 bg-amber-50/20' : ($table->status === 'available' ? 'border-stone-200/80' : 'border-stone-300') }}">
                                <div>
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <h3 class="text-base font-bold font-display text-stone-900">{{ $table->name }}</h3>
                                            <p class="text-xs text-stone-500 mt-0.5 flex items-center gap-1">
                                                <svg class="h-3.5 w-3.5 text-stone-400" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" /></svg>
                                                <span>{{ $table->location ?? 'Main Dining' }}</span>
                                            </p>
                                        </div>

                                        @if ($table->status === 'available')
                                            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-2.5 py-0.5 text-[11px] font-bold text-emerald-800">
                                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                                Available
                                            </span>
                                        @elseif ($table->status === 'occupied')
                                            <span class="inline-flex items-center gap-1 rounded-full bg-amber-100 px-2.5 py-0.5 text-[11px] font-bold text-amber-800">
                                                <span class="h-1.5 w-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                                Occupied
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 rounded-full bg-stone-100 px-2.5 py-0.5 text-[11px] font-bold text-stone-700">
                                                <span class="h-1.5 w-1.5 rounded-full bg-stone-400"></span>
                                                Reserved
                                            </span>
                                        @endif
                                    </div>

                                    <div class="mt-4 flex items-center gap-2 text-xs text-stone-600">
                                        <span class="inline-flex items-center gap-1 rounded-xl bg-stone-100 px-2.5 py-1 font-semibold text-stone-700">
                                            <svg class="h-3.5 w-3.5 text-stone-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0" /></svg>
                                            {{ $table->capacity }} Seats
                                        </span>
                                        @if ($table->activeOrder)
                                            <span class="inline-flex items-center gap-1 rounded-xl bg-amber-100/70 px-2.5 py-1 text-[11px] font-bold text-amber-900 border border-amber-200">
                                                Order #{{ $table->activeOrder->order_number }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="mt-5 pt-3.5 border-t border-stone-100 flex items-center justify-between text-xs">
                                    <!-- Status quick switcher -->
                                    <form method="POST" action="{{ route('admin.tables.status', $table) }}" class="inline-flex items-center">
                                        @csrf
                                        @method('patch')
                                        @if ($table->status === 'occupied')
                                            <input type="hidden" name="status" value="available">
                                            <button type="submit" class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-700 hover:text-emerald-800 bg-emerald-50 px-2.5 py-1 rounded-xl border border-emerald-200">
                                                Set Available
                                            </button>
                                        @else
                                            <input type="hidden" name="status" value="occupied">
                                            <button type="submit" class="inline-flex items-center gap-1 text-[11px] font-bold text-amber-700 hover:text-amber-800 bg-amber-50 px-2.5 py-1 rounded-xl border border-amber-200">
                                                Set Occupied
                                            </button>
                                        @endif
                                    </form>

                                    <div class="flex items-center gap-1.5">
                                        <a href="{{ route('admin.tables.edit', $table) }}" class="rounded-xl px-2.5 py-1 text-xs font-semibold text-stone-600 hover:bg-stone-100 transition">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('admin.tables.destroy', $table) }}" onsubmit="return confirm('Delete this table?')" class="inline">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="rounded-xl px-2.5 py-1 text-xs font-semibold text-rose-600 hover:bg-rose-50 transition">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full rounded-3xl bg-white p-12 text-center text-stone-400 border border-stone-200/80">
                                <svg class="mx-auto h-12 w-12 text-stone-300 mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25Z" />
                                </svg>
                                <p class="text-sm font-bold text-stone-600">No tables found</p>
                                <p class="text-xs text-stone-400 mt-1">Add your first dining table using the form on the right.</p>
                            </div>
                        @endforelse
                    </div>

                    @if ($tables->hasPages())
                        <div class="mt-4">{{ $tables->links() }}</div>
                    @endif
                </section>

                <!-- Add Table Sidebar Form -->
                <section>
                    <div class="rounded-3xl bg-[#1C1917] p-6 text-white shadow-xl border border-stone-800 sticky top-6">
                        <div class="border-b border-stone-800 pb-4">
                            <div class="inline-flex items-center gap-2 rounded-full bg-amber-500/15 px-3 py-1 text-xs font-semibold text-amber-300 border border-amber-500/25 mb-2">
                                Seating Layout
                            </div>
                            <h3 class="text-lg font-bold font-display text-white">Add New Table</h3>
                            <p class="mt-1 text-xs text-stone-400">Register a dining table or espresso counter bar.</p>
                        </div>

                        <form method="POST" action="{{ route('admin.tables.store') }}" class="mt-5 space-y-4">
                            @csrf
                            <div>
                                <label class="block text-xs font-bold text-stone-300 mb-1.5">Table Name <span class="text-rose-400">*</span></label>
                                <input name="name" type="text" value="{{ old('name') }}" required placeholder="e.g. Table 07, Bar 03" class="block w-full rounded-2xl border-stone-700 bg-stone-900/80 px-4 py-2.5 text-xs text-white placeholder-stone-500 focus:border-amber-500 focus:ring-amber-500">
                                @error('name')<p class="mt-1 text-xs text-rose-400">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-stone-300 mb-1.5">Seat Capacity <span class="text-rose-400">*</span></label>
                                <input name="capacity" type="number" min="1" max="50" value="{{ old('capacity', 2) }}" required class="block w-full rounded-2xl border-stone-700 bg-stone-900/80 px-4 py-2.5 text-xs text-white focus:border-amber-500 focus:ring-amber-500">
                                @error('capacity')<p class="mt-1 text-xs text-rose-400">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-stone-300 mb-1.5">Location / Zone</label>
                                <input name="location" type="text" value="{{ old('location') }}" placeholder="e.g. Indoor Hall, Garden Patio" class="block w-full rounded-2xl border-stone-700 bg-stone-900/80 px-4 py-2.5 text-xs text-white placeholder-stone-500 focus:border-amber-500 focus:ring-amber-500">
                                @error('location')<p class="mt-1 text-xs text-rose-400">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-stone-300 mb-1.5">Initial Status</label>
                                <select name="status" class="block w-full rounded-2xl border-stone-700 bg-stone-900/80 px-4 py-2.5 text-xs text-white focus:border-amber-500 focus:ring-amber-500">
                                    <option value="available" @selected(old('status') === 'available')>Available</option>
                                    <option value="occupied" @selected(old('status') === 'occupied')>Occupied</option>
                                    <option value="reserved" @selected(old('status') === 'reserved')>Reserved</option>
                                </select>
                            </div>

                            <div class="pt-2">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="is_active" value="1" checked class="h-4 w-4 rounded border-stone-700 bg-stone-900 text-amber-500 focus:ring-amber-400">
                                    <span class="text-xs text-stone-300">Active for POS Dine-in orders</span>
                                </label>
                            </div>

                            <button type="submit" class="w-full flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-amber-400 to-amber-600 px-4 py-3 text-xs font-bold text-stone-950 shadow-lg shadow-amber-500/20 hover:brightness-110 active:scale-95 transition">
                                Add Table
                            </button>
                        </form>
                    </div>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
