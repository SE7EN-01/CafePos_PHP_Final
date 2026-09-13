<x-app-layout>
    <div class="min-h-full bg-[#F9F6F0] p-4 sm:p-6 lg:p-8">
        <div class="w-full space-y-6">
            <!-- Header -->
            <header class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between bg-white rounded-3xl p-6 shadow-sm border border-stone-200/80">
                <div class="flex items-center gap-3.5">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-500/15 text-amber-700 border border-amber-500/30">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="text-2xl font-bold font-display tracking-tight text-stone-900">Menu Categories</h1>
                            <span class="rounded-full bg-stone-100 px-3 py-0.5 text-xs font-bold text-stone-600 border border-stone-200">
                                {{ $categories->total() }} Total
                            </span>
                        </div>
                        <p class="text-xs text-stone-500 mt-0.5">Organize coffee, tea, pastries, and food menu groups</p>
                    </div>
                </div>
            </header>

            <div class="grid gap-6 lg:grid-cols-[1fr_400px]">
                <!-- Categories Table/List -->
                <section>
                    <div class="overflow-hidden rounded-3xl bg-white shadow-sm border border-stone-200/80">
                        <div class="border-b border-stone-100 px-6 py-4 flex items-center justify-between">
                            <h3 class="text-sm font-bold text-stone-900">All Categories</h3>
                            <span class="text-xs text-stone-400">Showing page {{ $categories->currentPage() }}</span>
                        </div>
                        <div class="divide-y divide-stone-100">
                            @forelse ($categories as $category)
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between px-6 py-4 gap-4 hover:bg-stone-50/50 transition">
                                    <div class="flex items-center gap-4">
                                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-500/10 text-amber-800 font-bold font-display text-sm border border-amber-500/20">
                                            {{ strtoupper(substr($category->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-stone-900 text-sm flex items-center gap-2">
                                                <span>{{ $category->name }}</span>
                                                @if (!empty($category->name_translations['km']) && $category->name_translations['km'] !== $category->name_translations['en'])
                                                    <span class="text-xs font-normal text-stone-500">({{ $category->name_translations['km'] }})</span>
                                                @endif
                                            </p>
                                            <p class="text-xs text-stone-400 mt-0.5">
                                                <span class="font-mono">{{ $category->slug }}</span> &bull; 
                                                <span class="font-semibold text-stone-600">{{ $category->products_count }} products</span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3 self-end sm:self-center">
                                        <span class="rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $category->is_active ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-stone-100 text-stone-600 border border-stone-200' }}">
                                            {{ $category->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                        <a href="{{ route('admin.categories.edit', $category) }}" class="rounded-xl px-3 py-1.5 text-xs font-semibold text-amber-800 bg-amber-50 hover:bg-amber-100 transition border border-amber-200">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Delete this category? Products inside may be affected.')">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="rounded-xl px-3 py-1.5 text-xs font-semibold text-rose-700 bg-rose-50 hover:bg-rose-100 transition border border-rose-200">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <div class="px-6 py-12 text-center">
                                    <p class="font-bold text-stone-800">No categories found</p>
                                    <p class="mt-1 text-xs text-stone-400">Add your first category using the form on the right.</p>
                                </div>
                            @endforelse
                        </div>
                        @if ($categories->hasPages())
                            <div class="border-t border-stone-100 px-6 py-4">{{ $categories->links() }}</div>
                        @endif
                    </div>
                </section>

                <!-- Add Category Sidebar Card -->
                <section>
                    <div class="rounded-3xl bg-[#1C1917] p-6 text-white shadow-xl border border-stone-800">
                        <div class="border-b border-stone-800 pb-4">
                            <div class="inline-flex items-center gap-2 rounded-full bg-amber-500/15 px-3 py-1 text-xs font-semibold text-amber-300 border border-amber-500/25 mb-2">
                                New Category
                            </div>
                            <h3 class="text-lg font-bold font-display text-white">Create Menu Category</h3>
                            <p class="mt-1 text-xs text-stone-400">Add a bilingual category to organize coffee products.</p>
                        </div>

                        <form method="POST" action="{{ route('admin.categories.store') }}" class="mt-5 space-y-4">
                            @csrf
                            <div>
                                <label class="block text-xs font-bold text-stone-300 mb-1.5">Name (English) <span class="text-rose-400">*</span></label>
                                <input name="name[en]" type="text" value="{{ old('name.en') }}" required placeholder="e.g. Specialty Cold Brew" class="block w-full rounded-2xl border-stone-700 bg-stone-900/80 px-4 py-3 text-xs text-white placeholder-stone-500 focus:border-amber-500 focus:ring-amber-500">
                                @error('name.en')<p class="mt-1 text-xs text-rose-400">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-stone-300 mb-1.5">Name (Khmer) <span class="text-stone-500 font-normal">(optional)</span></label>
                                <input name="name[km]" type="text" value="{{ old('name.km') }}" placeholder="e.g. កាហ្វេត្រជាក់ពិសេស" class="block w-full rounded-2xl border-stone-700 bg-stone-900/80 px-4 py-3 text-xs text-white placeholder-stone-500 focus:border-amber-500 focus:ring-amber-500">
                                @error('name.km')<p class="mt-1 text-xs text-rose-400">{{ $message }}</p>@enderror
                            </div>

                            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-amber-400 to-amber-600 px-5 py-3.5 text-xs font-bold text-stone-950 shadow-lg shadow-amber-500/25 hover:brightness-110 active:scale-[0.99] transition">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                <span>Save Category</span>
                            </button>
                        </form>
                    </div>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
