<x-app-layout>
    <div class="min-h-full bg-[#F9F6F0] p-4 sm:p-6 lg:p-8">
        <div class="mx-auto max-w-3xl space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <a href="{{ route('admin.products.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2 text-xs font-semibold text-stone-700 shadow-sm border border-stone-200/80 hover:bg-stone-50 hover:text-amber-800 transition">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
                    <span>Back to Products</span>
                </a>
                <span class="text-xs text-stone-400">Step 1: Product Details</span>
            </div>

            <div class="rounded-3xl bg-white p-6 sm:p-8 shadow-sm border border-stone-200/80">
                <div class="border-b border-stone-100 pb-5 mb-6">
                    <div class="inline-flex items-center gap-2 rounded-full bg-amber-500/15 px-3 py-1 text-xs font-semibold text-amber-800 border border-amber-500/25 mb-2">
                        New Menu Item
                    </div>
                    <h1 class="text-2xl font-bold font-display tracking-tight text-stone-900">Create Product</h1>
                    <p class="text-xs text-stone-500 mt-1">Fill in bilingual product information. You can add pricing variants on the next screen.</p>
                </div>

                <form method="POST" action="{{ route('admin.products.store') }}" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-stone-700 mb-1.5">Category <span class="text-rose-500">*</span></label>
                        <select 
                            name="category_id" 
                            required 
                            class="block w-full rounded-2xl border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-900 focus:border-amber-500 focus:bg-white focus:ring-2 focus:ring-amber-500/20 transition"
                        >
                            <option value="">Select a category</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" @selected(old('category_id') == $cat->id)>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid gap-6 sm:grid-cols-2">
                        <div>
                            <label class="block text-xs font-bold text-stone-700 mb-1.5">Name (English) <span class="text-rose-500">*</span></label>
                            <input 
                                name="name[en]" 
                                type="text" 
                                value="{{ old('name.en') }}" 
                                required 
                                placeholder="e.g. Vanilla Oat Latte" 
                                class="block w-full rounded-2xl border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-900 placeholder-stone-400 focus:border-amber-500 focus:bg-white focus:ring-2 focus:ring-amber-500/20 transition"
                            >
                            @error('name.en')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-stone-700 mb-1.5">Name (Khmer) <span class="text-stone-400 font-normal">(optional)</span></label>
                            <input 
                                name="name[km]" 
                                type="text" 
                                value="{{ old('name.km') }}" 
                                placeholder="e.g. ឡាតេទឹកដោះគោអូត" 
                                class="block w-full rounded-2xl border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-900 placeholder-stone-400 focus:border-amber-500 focus:bg-white focus:ring-2 focus:ring-amber-500/20 transition"
                            >
                            @error('name.km')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="grid gap-6 sm:grid-cols-2">
                        <div>
                            <label class="block text-xs font-bold text-stone-700 mb-1.5">Description (English)</label>
                            <textarea 
                                name="description[en]" 
                                rows="3" 
                                placeholder="Tasting notes, espresso roast, or details" 
                                class="block w-full rounded-2xl border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-900 placeholder-stone-400 focus:border-amber-500 focus:bg-white focus:ring-2 focus:ring-amber-500/20 transition"
                            >{{ old('description.en') }}</textarea>
                            @error('description.en')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-stone-700 mb-1.5">Description (Khmer) <span class="text-stone-400 font-normal">(optional)</span></label>
                            <textarea 
                                name="description[km]" 
                                rows="3" 
                                placeholder="ការពិពណ៌នាអំពីរសជាតិ និងគ្រឿងផ្សំ" 
                                class="block w-full rounded-2xl border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-900 placeholder-stone-400 focus:border-amber-500 focus:bg-white focus:ring-2 focus:ring-amber-500/20 transition"
                            >{{ old('description.km') }}</textarea>
                            @error('description.km')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="flex items-center gap-3 pt-6 border-t border-stone-100">
                        <button 
                            type="submit" 
                            class="inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-amber-400 via-amber-500 to-amber-600 px-6 py-3.5 text-xs font-bold text-stone-950 shadow-lg shadow-amber-500/25 hover:brightness-110 active:scale-[0.99] transition"
                        >
                            Save &amp; Continue
                        </button>
                        <a href="{{ route('admin.products.index') }}" class="rounded-2xl px-5 py-3.5 text-xs font-semibold text-stone-600 hover:bg-stone-100 transition">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
