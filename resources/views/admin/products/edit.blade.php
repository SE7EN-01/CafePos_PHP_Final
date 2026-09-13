<x-app-layout>
    <div class="min-h-full bg-[#F9F6F0] p-4 sm:p-6 lg:p-8">
        <div class="w-full space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <a href="{{ route('admin.products.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2 text-xs font-semibold text-stone-700 shadow-sm border border-stone-200/80 hover:bg-stone-50 hover:text-amber-800 transition">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
                    <span>Back to Products</span>
                </a>
                <span class="text-xs text-stone-400">Editing Product #{{ $product->id }}</span>
            </div>

            <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_420px]">
                <!-- Main Product & Variants Details -->
                <div class="space-y-6">
                    <!-- Product Details Form -->
                    <div class="rounded-3xl bg-white p-6 sm:p-8 shadow-sm border border-stone-200/80">
                        <div class="border-b border-stone-100 pb-4 mb-6">
                            <h2 class="text-xl font-bold font-display tracking-tight text-stone-900">Product Details</h2>
                            <p class="text-xs text-stone-500 mt-0.5">Edit category, name, and description.</p>
                        </div>

                        <form method="POST" action="{{ route('admin.products.update', $product) }}" class="space-y-5">
                            @csrf
                            @method('patch')

                            <div>
                                <label class="block text-xs font-bold text-stone-700 mb-1.5">Category <span class="text-rose-500">*</span></label>
                                <select 
                                    name="category_id" 
                                    required 
                                    class="block w-full rounded-2xl border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-900 focus:border-amber-500 focus:bg-white focus:ring-2 focus:ring-amber-500/20 transition"
                                >
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}" @selected($product->category_id == $cat->id)>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                            </div>

                            <div class="grid gap-5 sm:grid-cols-2">
                                <div>
                                    <label class="block text-xs font-bold text-stone-700 mb-1.5">Name (English) <span class="text-rose-500">*</span></label>
                                    <input 
                                        name="name[en]" 
                                        type="text" 
                                        value="{{ old('name.en', $product->name_translations['en'] ?? '') }}" 
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
                                        value="{{ old('name.km', $product->name_translations['km'] ?? '') }}" 
                                        class="block w-full rounded-2xl border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-900 focus:border-amber-500 focus:bg-white focus:ring-2 focus:ring-amber-500/20 transition"
                                    >
                                    @error('name.km')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                                </div>
                            </div>

                            <div class="grid gap-5 sm:grid-cols-2">
                                <div>
                                    <label class="block text-xs font-bold text-stone-700 mb-1.5">Description (English)</label>
                                    <textarea 
                                        name="description[en]" 
                                        rows="3" 
                                        class="block w-full rounded-2xl border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-900 focus:border-amber-500 focus:bg-white focus:ring-2 focus:ring-amber-500/20 transition"
                                    >{{ old('description.en', $product->description_translations['en'] ?? '') }}</textarea>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-stone-700 mb-1.5">Description (Khmer)</label>
                                    <textarea 
                                        name="description[km]" 
                                        rows="3" 
                                        class="block w-full rounded-2xl border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-900 focus:border-amber-500 focus:bg-white focus:ring-2 focus:ring-amber-500/20 transition"
                                    >{{ old('description.km', $product->description_translations['km'] ?? '') }}</textarea>
                                </div>
                            </div>

                            <label class="inline-flex items-center gap-3 cursor-pointer select-none">
                                <input type="hidden" name="is_active" value="0">
                                <input 
                                    type="checkbox" 
                                    name="is_active" 
                                    value="1" 
                                    @checked($product->is_active) 
                                    class="h-5 w-5 rounded-lg border-stone-300 text-amber-600 focus:ring-amber-500"
                                >
                                <span class="text-sm font-bold text-stone-900">Active on Point of Sale (POS)</span>
                            </label>

                            <div class="flex items-center gap-3 pt-4 border-t border-stone-100">
                                <button type="submit" class="rounded-2xl bg-stone-900 px-6 py-3.5 text-xs font-bold text-white hover:bg-stone-800 transition">
                                    Save Product Details
                                </button>
                                <a href="{{ route('admin.products.index') }}" class="rounded-2xl px-5 py-3.5 text-xs font-semibold text-stone-600 hover:bg-stone-100 transition">Cancel</a>
                            </div>
                        </form>
                    </div>

                    <!-- Variants Section -->
                    <div class="rounded-3xl bg-white p-6 sm:p-8 shadow-sm border border-stone-200/80">
                        <div class="border-b border-stone-100 pb-4 mb-6 flex items-center justify-between">
                            <div>
                                <h2 class="text-xl font-bold font-display tracking-tight text-stone-900">Product Variants</h2>
                                <p class="text-xs text-stone-500 mt-0.5">Sizes, roasts, and pricing variations for this item.</p>
                            </div>
                            <span class="rounded-full bg-stone-100 px-3 py-1 text-xs font-bold text-stone-700">{{ $product->variants->count() }} Variants</span>
                        </div>

                        <div class="space-y-4">
                            @forelse ($product->variants as $variant)
                                <div x-data="{ editing: false }" class="rounded-2xl border border-stone-200/90 p-5 bg-stone-50/40 hover:border-amber-400/40 transition">
                                    <!-- Readonly View -->
                                    <div x-show="!editing" class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <h4 class="font-bold text-stone-900 text-sm">{{ $variant->name }}</h4>
                                                @if (!empty($variant->name_translations['km']) && $variant->name_translations['km'] !== $variant->name_translations['en'])
                                                    <span class="text-xs text-stone-400">({{ $variant->name_translations['km'] }})</span>
                                                @endif
                                                <span class="rounded-full px-2 py-0.5 text-[10px] font-bold {{ $variant->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-stone-200 text-stone-600' }}">
                                                    {{ $variant->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </div>
                                            <div class="mt-1 flex flex-wrap items-center gap-3 text-xs text-stone-500">
                                                <span class="font-extrabold text-stone-900 font-display text-sm">${{ number_format($variant->price, 2) }}</span>
                                                <span>&bull;</span>
                                                <span>Cost: ${{ number_format($variant->cost_price, 2) }}</span>
                                                @if ($variant->track_stock)
                                                    <span>&bull;</span>
                                                    <span class="font-semibold {{ $variant->stock_quantity <= 5 ? 'text-amber-700' : 'text-emerald-700' }}">
                                                        Stock: {{ $variant->stock_quantity }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            <button @click="editing = true" class="rounded-xl px-3 py-1.5 text-xs font-semibold text-amber-800 bg-amber-50 hover:bg-amber-100 transition border border-amber-200">
                                                Edit Variant
                                            </button>
                                            <form method="POST" action="{{ route('admin.products.variants.destroy', [$product, $variant]) }}" onsubmit="return confirm('Delete this variant?')">
                                                @csrf @method('delete')
                                                <button type="submit" class="rounded-xl px-3 py-1.5 text-xs font-semibold text-rose-700 bg-rose-50 hover:bg-rose-100 transition border border-rose-200">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- Edit Variant Sub-form -->
                                    <div x-show="editing" x-cloak class="pt-4 mt-4 border-t border-stone-200">
                                        <form method="POST" action="{{ route('admin.products.variants.update', [$product, $variant]) }}" class="space-y-4">
                                            @csrf @method('patch')
                                            <div class="grid gap-4 sm:grid-cols-2">
                                                <div>
                                                    <label class="block text-xs font-bold text-stone-700 mb-1">Name (English)</label>
                                                    <input name="name[en]" type="text" value="{{ $variant->name_translations['en'] ?? '' }}" required class="block w-full rounded-xl border-stone-200 bg-white px-3 py-2 text-xs text-stone-900 focus:border-amber-500 focus:ring-amber-500">
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-bold text-stone-700 mb-1">Name (Khmer)</label>
                                                    <input name="name[km]" type="text" value="{{ $variant->name_translations['km'] ?? '' }}" class="block w-full rounded-xl border-stone-200 bg-white px-3 py-2 text-xs text-stone-900 focus:border-amber-500 focus:ring-amber-500">
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-bold text-stone-700 mb-1">Price ($)</label>
                                                    <input name="price" type="number" step="0.01" min="0" value="{{ $variant->price }}" required class="block w-full rounded-xl border-stone-200 bg-white px-3 py-2 text-xs text-stone-900 focus:border-amber-500 focus:ring-amber-500">
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-bold text-stone-700 mb-1">Cost Price ($)</label>
                                                    <input name="cost_price" type="number" step="0.01" min="0" value="{{ $variant->cost_price }}" required class="block w-full rounded-xl border-stone-200 bg-white px-3 py-2 text-xs text-stone-900 focus:border-amber-500 focus:ring-amber-500">
                                                </div>
                                                <div>
                                                    <label class="flex items-center gap-2 pt-2">
                                                        <input type="hidden" name="track_stock" value="0">
                                                        <input type="checkbox" name="track_stock" value="1" @checked($variant->track_stock) class="rounded border-stone-300 text-amber-600 focus:ring-amber-500">
                                                        <span class="text-xs font-bold text-stone-700">Track Stock Quantity</span>
                                                    </label>
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-bold text-stone-700 mb-1">Stock Quantity</label>
                                                    <input name="stock_quantity" type="number" step="0.01" min="0" value="{{ $variant->stock_quantity }}" class="block w-full rounded-xl border-stone-200 bg-white px-3 py-2 text-xs text-stone-900 focus:border-amber-500 focus:ring-amber-500">
                                                </div>
                                            </div>

                                            <label class="flex items-center gap-2">
                                                <input type="hidden" name="is_active" value="0">
                                                <input type="checkbox" name="is_active" value="1" @checked($variant->is_active) class="rounded border-stone-300 text-amber-600 focus:ring-amber-500">
                                                <span class="text-xs font-bold text-stone-700">Active Variant</span>
                                            </label>

                                            <div class="flex items-center gap-2 pt-2">
                                                <button type="submit" class="rounded-xl bg-stone-900 px-4 py-2 text-xs font-bold text-white hover:bg-stone-800 transition">Save Variant</button>
                                                <button type="button" @click="editing = false" class="rounded-xl px-3 py-2 text-xs font-medium text-stone-600 hover:bg-stone-100 transition">Cancel</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <div class="rounded-2xl border border-dashed border-stone-200 p-8 text-center text-xs text-stone-400">
                                    No variants yet. Add variants such as Regular, Large, or Iced using the form on the right.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Add Variant Sidebar -->
                <aside>
                    <div class="rounded-3xl bg-[#1C1917] p-6 text-white shadow-xl border border-stone-800">
                        <div class="border-b border-stone-800 pb-4">
                            <div class="inline-flex items-center gap-2 rounded-full bg-amber-500/15 px-3 py-1 text-xs font-semibold text-amber-300 border border-amber-500/25 mb-2">
                                Sizing &amp; Pricing
                            </div>
                            <h3 class="text-lg font-bold font-display text-white">Add Variant</h3>
                            <p class="mt-1 text-xs text-stone-400">Add size (e.g. Regular, Large) with price and inventory tracking.</p>
                        </div>

                        <form method="POST" action="{{ route('admin.products.variants.store', $product) }}" class="mt-5 space-y-4">
                            @csrf
                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label class="block text-xs font-bold text-stone-300 mb-1">Name (EN) <span class="text-rose-400">*</span></label>
                                    <input name="name[en]" type="text" required placeholder="e.g. Regular" class="block w-full rounded-2xl border-stone-700 bg-stone-900/80 px-4 py-2.5 text-xs text-white placeholder-stone-500 focus:border-amber-500 focus:ring-amber-500">
                                    @error('name.en')<p class="mt-1 text-xs text-rose-400">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-stone-300 mb-1">Name (KM)</label>
                                    <input name="name[km]" type="text" placeholder="e.g. កែវធម្មតា" class="block w-full rounded-2xl border-stone-700 bg-stone-900/80 px-4 py-2.5 text-xs text-white placeholder-stone-500 focus:border-amber-500 focus:ring-amber-500">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-stone-300 mb-1">Price ($) <span class="text-rose-400">*</span></label>
                                    <input name="price" type="number" step="0.01" min="0" value="3.50" required class="block w-full rounded-2xl border-stone-700 bg-stone-900/80 px-4 py-2.5 text-xs text-white focus:border-amber-500 focus:ring-amber-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-stone-300 mb-1">Cost ($) <span class="text-rose-400">*</span></label>
                                    <input name="cost_price" type="number" step="0.01" min="0" value="1.00" required class="block w-full rounded-2xl border-stone-700 bg-stone-900/80 px-4 py-2.5 text-xs text-white focus:border-amber-500 focus:ring-amber-500">
                                </div>
                            </div>

                            <label class="flex items-center gap-2 select-none cursor-pointer">
                                <input type="hidden" name="track_stock" value="0">
                                <input type="checkbox" name="track_stock" value="1" class="rounded border-stone-700 bg-stone-900 text-amber-500 focus:ring-amber-500">
                                <span class="text-xs text-stone-300 font-medium">Track Stock for this Variant</span>
                            </label>

                            <div>
                                <label class="block text-xs font-bold text-stone-300 mb-1">Initial Stock Quantity</label>
                                <input name="stock_quantity" type="number" step="0.01" min="0" value="50" class="block w-full rounded-2xl border-stone-700 bg-stone-900/80 px-4 py-2.5 text-xs text-white focus:border-amber-500 focus:ring-amber-500">
                            </div>

                            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-amber-400 to-amber-600 px-5 py-3.5 text-xs font-bold text-stone-950 shadow-lg shadow-amber-500/25 hover:brightness-110 active:scale-[0.99] transition">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                <span>Add Variant</span>
                            </button>
                        </form>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</x-app-layout>
