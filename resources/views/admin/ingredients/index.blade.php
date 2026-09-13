<x-app-layout>
    <div class="min-h-full bg-[#F9F6F0] p-4 sm:p-6 lg:p-8">
        <div class="w-full space-y-6">
            <!-- Header -->
            <header class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between bg-white rounded-3xl p-6 shadow-sm border border-stone-200/80">
                <div class="flex items-center gap-3.5">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-500/15 text-amber-700 border border-amber-500/30">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="text-2xl font-bold font-display tracking-tight text-stone-900">Inventory</h1>
                            <span class="rounded-full bg-stone-100 px-3 py-0.5 text-xs font-bold text-stone-600 border border-stone-200">
                                {{ $ingredients->total() }} Items
                            </span>
                        </div>
                        <p class="text-xs text-stone-500 mt-0.5">Track coffee beans, milk, syrups, and raw materials stock levels</p>
                    </div>
                </div>
            </header>

            <div class="grid gap-6 lg:grid-cols-[1fr_400px]">
                <!-- Ingredients Table -->
                <section>
                    <div class="overflow-hidden rounded-3xl bg-white shadow-sm border border-stone-200/80">
                        <div class="border-b border-stone-100 px-6 py-4 flex items-center justify-between">
                            <h3 class="text-sm font-bold text-stone-900">All Inventory Items</h3>
                            <span class="text-xs text-stone-400">Page {{ $ingredients->currentPage() }}</span>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs">
                                <thead>
                                    <tr class="border-b border-stone-100 bg-stone-50/50 text-stone-400 uppercase tracking-wider font-bold">
                                        <th class="px-6 py-3.5">Material</th>
                                        <th class="px-6 py-3.5">Current Stock</th>
                                        <th class="px-6 py-3.5">Stock Meter</th>
                                        <th class="px-6 py-3.5">Status</th>
                                        <th class="px-6 py-3.5 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-stone-100">
                                    @forelse ($ingredients as $ingredient)
                                        <tr class="hover:bg-stone-50/50 transition">
                                            <td class="px-6 py-4">
                                                <p class="font-bold text-stone-900 text-sm flex items-center gap-1.5">
                                                    <span>{{ $ingredient->name }}</span>
                                                    @if (!empty($ingredient->name_translations['km']) && $ingredient->name_translations['km'] !== $ingredient->name_translations['en'])
                                                        <span class="text-xs font-normal text-stone-400">({{ $ingredient->name_translations['km'] }})</span>
                                                    @endif
                                                </p>
                                                <p class="text-xs text-stone-400 mt-0.5">Reorder at {{ $ingredient->reorder_level }} {{ $ingredient->unit }}</p>
                                            </td>

                                            <td class="px-6 py-4 font-extrabold text-stone-900 text-sm font-display">
                                                {{ $ingredient->current_stock }} <span class="text-xs font-medium text-stone-400">{{ $ingredient->unit }}</span>
                                            </td>

                                            <!-- Visual Stock Level Meter -->
                                            <td class="px-6 py-4 min-w-[140px]">
                                                @php
                                                    $ratio = $ingredient->reorder_level > 0 ? ($ingredient->current_stock / ($ingredient->reorder_level * 2)) * 100 : 100;
                                                    $percentage = min(100, max(5, round($ratio)));
                                                    $isLow = $ingredient->isLowStock();
                                                @endphp
                                                <div class="space-y-1">
                                                    <div class="h-2 w-full rounded-full bg-stone-100 overflow-hidden">
                                                        <div 
                                                            class="h-full rounded-full {{ $isLow ? 'bg-rose-500' : 'bg-emerald-500' }}" 
                                                            style="width: {{ $percentage }}%"
                                                        ></div>
                                                    </div>
                                                    <span class="text-[10px] text-stone-400 font-medium">
                                                        {{ $isLow ? 'Below threshold' : 'Sufficient' }}
                                                    </span>
                                                </div>
                                            </td>

                                            <td class="px-6 py-4">
                                                @if ($ingredient->isLowStock())
                                                    <span class="inline-flex items-center gap-1 rounded-full bg-rose-100 px-2.5 py-0.5 text-xs font-bold text-rose-800 border border-rose-200">
                                                        <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span>
                                                        Low stock
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-bold text-emerald-800 border border-emerald-200">
                                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                                        In stock
                                                    </span>
                                                @endif
                                            </td>

                                            <td class="px-6 py-4 text-right space-x-2">
                                                <a href="{{ route('admin.ingredients.edit', $ingredient) }}" class="inline-block rounded-xl px-3 py-1.5 text-xs font-semibold text-amber-800 bg-amber-50 hover:bg-amber-100 transition border border-amber-200">
                                                    Edit
                                                </a>
                                                <form method="POST" action="{{ route('admin.ingredients.destroy', $ingredient) }}" onsubmit="return confirm('Delete this ingredient?')" class="inline">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" class="rounded-xl px-3 py-1.5 text-xs font-semibold text-rose-700 bg-rose-50 hover:bg-rose-100 transition border border-rose-200">
                                                        Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-12 text-center text-stone-400">
                                                No ingredients tracked yet. Use the form on the right to add raw supplies.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if ($ingredients->hasPages())
                            <div class="border-t border-stone-100 px-6 py-4">{{ $ingredients->links() }}</div>
                        @endif
                    </div>
                </section>

                <!-- Add Ingredient Sidebar Form -->
                <section>
                    <div class="rounded-3xl bg-[#1C1917] p-6 text-white shadow-xl border border-stone-800">
                        <div class="border-b border-stone-800 pb-4">
                            <div class="inline-flex items-center gap-2 rounded-full bg-amber-500/15 px-3 py-1 text-xs font-semibold text-amber-300 border border-amber-500/25 mb-2">
                                Inventory Supply
                            </div>
                            <h3 class="text-lg font-bold font-display text-white">Add Raw Material</h3>
                            <p class="mt-1 text-xs text-stone-400">Register new coffee beans, dairy, or syrups.</p>
                        </div>

                        <form method="POST" action="{{ route('admin.ingredients.store') }}" class="mt-5 space-y-4">
                            @csrf
                            <div>
                                <label class="block text-xs font-bold text-stone-300 mb-1.5">Name (English) <span class="text-rose-400">*</span></label>
                                <input name="name[en]" type="text" value="{{ old('name.en') }}" required placeholder="e.g. Arabica Blend Beans" class="block w-full rounded-2xl border-stone-700 bg-stone-900/80 px-4 py-2.5 text-xs text-white placeholder-stone-500 focus:border-amber-500 focus:ring-amber-500">
                                @error('name.en')<p class="mt-1 text-xs text-rose-400">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-stone-300 mb-1.5">Name (Khmer)</label>
                                <input name="name[km]" type="text" value="{{ old('name.km') }}" placeholder="e.g. គ្រាប់កាហ្វេអារ៉ាប៊ីកា" class="block w-full rounded-2xl border-stone-700 bg-stone-900/80 px-4 py-2.5 text-xs text-white placeholder-stone-500 focus:border-amber-500 focus:ring-amber-500">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-stone-300 mb-1.5">Measurement Unit <span class="text-rose-400">*</span></label>
                                <select name="unit" required class="block w-full rounded-2xl border-stone-700 bg-stone-900/80 px-4 py-2.5 text-xs text-white focus:border-amber-500 focus:ring-amber-500">
                                    <option value="grams">Grams (g)</option>
                                    <option value="ml">Milliliters (ml)</option>
                                    <option value="pcs">Pieces (pcs)</option>
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-bold text-stone-300 mb-1.5">Initial Stock</label>
                                    <input name="current_stock" type="number" step="0.01" min="0" value="{{ old('current_stock', 1000) }}" required class="block w-full rounded-2xl border-stone-700 bg-stone-900/80 px-4 py-2.5 text-xs text-white focus:border-amber-500 focus:ring-amber-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-stone-300 mb-1.5">Reorder Level</label>
                                    <input name="reorder_level" type="number" step="0.01" min="0" value="{{ old('reorder_level', 200) }}" required class="block w-full rounded-2xl border-stone-700 bg-stone-900/80 px-4 py-2.5 text-xs text-white focus:border-amber-500 focus:ring-amber-500">
                                </div>
                            </div>

                            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-amber-400 to-amber-600 px-5 py-3.5 text-xs font-bold text-stone-950 shadow-lg shadow-amber-500/25 hover:brightness-110 active:scale-[0.99] transition">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                <span>Save Material</span>
                            </button>
                        </form>
                    </div>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
