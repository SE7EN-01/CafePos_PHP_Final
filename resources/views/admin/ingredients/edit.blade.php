<x-app-layout>
    <div class="min-h-full bg-[#F9F6F0] p-4 sm:p-6 lg:p-8">
        <div class="mx-auto max-w-3xl space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <a href="{{ route('admin.ingredients.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2 text-xs font-semibold text-stone-700 shadow-sm border border-stone-200/80 hover:bg-stone-50 hover:text-amber-800 transition">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
                    <span>Back to Inventory</span>
                </a>
                <span class="text-xs text-stone-400">Editing #{{ $ingredient->id }}</span>
            </div>

            <div class="rounded-3xl bg-white p-6 sm:p-8 shadow-sm border border-stone-200/80">
                <div class="border-b border-stone-100 pb-5 mb-6">
                    <h1 class="text-2xl font-bold font-display tracking-tight text-stone-900">Edit Inventory Item</h1>
                    <p class="text-xs text-stone-500 mt-1">Update stock levels, units, and bilingual naming.</p>
                </div>

                <form method="POST" action="{{ route('admin.ingredients.update', $ingredient) }}" class="space-y-6">
                    @csrf
                    @method('patch')

                    <div class="grid gap-6 sm:grid-cols-2">
                        <div>
                            <label class="block text-xs font-bold text-stone-700 mb-1.5">Name (English) <span class="text-rose-500">*</span></label>
                            <input 
                                name="name[en]" 
                                type="text" 
                                value="{{ old('name.en', $ingredient->name_translations['en'] ?? '') }}" 
                                required 
                                class="block w-full rounded-2xl border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-900 focus:border-amber-500 focus:bg-white focus:ring-2 focus:ring-amber-500/20 transition"
                            >
                            @error('name.en')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-stone-700 mb-1.5">Name (Khmer) <span class="text-stone-400 font-normal">(optional)</span></label>
                            <input 
                                name="name[km]" 
                                type="text" 
                                value="{{ old('name.km', $ingredient->name_translations['km'] ?? '') }}" 
                                class="block w-full rounded-2xl border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-900 focus:border-amber-500 focus:bg-white focus:ring-2 focus:ring-amber-500/20 transition"
                            >
                            @error('name.km')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-stone-700 mb-1.5">Unit <span class="text-rose-500">*</span></label>
                        <select name="unit" required class="block w-full rounded-2xl border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-900 focus:border-amber-500 focus:bg-white focus:ring-2 focus:ring-amber-500/20 transition">
                            @foreach (['grams' => 'Grams (g)', 'ml' => 'Milliliters (ml)', 'pcs' => 'Pieces (pcs)'] as $val => $lbl)
                                <option value="{{ $val }}" @selected(old('unit', $ingredient->unit) === $val)>{{ $lbl }}</option>
                            @endforeach
                        </select>
                        @error('unit')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid gap-6 sm:grid-cols-2">
                        <div>
                            <label class="block text-xs font-bold text-stone-700 mb-1.5">Current Stock Level</label>
                            <input 
                                name="current_stock" 
                                type="number" 
                                step="0.01" 
                                min="0" 
                                value="{{ old('current_stock', $ingredient->current_stock) }}" 
                                required 
                                class="block w-full rounded-2xl border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-900 focus:border-amber-500 focus:bg-white focus:ring-2 focus:ring-amber-500/20 transition"
                            >
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-stone-700 mb-1.5">Reorder Level Threshold</label>
                            <input 
                                name="reorder_level" 
                                type="number" 
                                step="0.01" 
                                min="0" 
                                value="{{ old('reorder_level', $ingredient->reorder_level) }}" 
                                required 
                                class="block w-full rounded-2xl border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-900 focus:border-amber-500 focus:bg-white focus:ring-2 focus:ring-amber-500/20 transition"
                            >
                        </div>
                    </div>

                    <div class="flex items-center gap-3 pt-6 border-t border-stone-100">
                        <button 
                            type="submit" 
                            class="inline-flex items-center justify-center gap-2 rounded-2xl bg-stone-900 px-6 py-3.5 text-xs font-bold text-white shadow-md hover:bg-stone-800 transition"
                        >
                            Save Changes
                        </button>
                        <a href="{{ route('admin.ingredients.index') }}" class="rounded-2xl px-5 py-3.5 text-xs font-semibold text-stone-600 hover:bg-stone-100 transition">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
