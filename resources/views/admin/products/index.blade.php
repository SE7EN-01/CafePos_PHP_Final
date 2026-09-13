<x-app-layout>
    <div class="min-h-full bg-[#F9F6F0] p-4 sm:p-6 lg:p-8">
        <div class="w-full space-y-6">
            <!-- Header -->
            <header class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between bg-white rounded-3xl p-6 shadow-sm border border-stone-200/80">
                <div class="flex items-center gap-3.5">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-500/15 text-amber-700 border border-amber-500/30">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5m6 4.125l2.25 2.25m0 0l2.25-2.25M12 13.875V7.5" />
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="text-2xl font-bold font-display tracking-tight text-stone-900">Products &amp; Menu</h1>
                            <span class="rounded-full bg-stone-100 px-3 py-0.5 text-xs font-bold text-stone-600 border border-stone-200">
                                {{ $products->total() }} Products
                            </span>
                        </div>
                        <p class="text-xs text-stone-500 mt-0.5">Manage beverages, beans, pastries, pricing and sizing variants</p>
                    </div>
                </div>

                <a 
                    href="{{ route('admin.products.create') }}" 
                    class="inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-amber-400 to-amber-600 px-5 py-3 text-xs font-bold text-stone-950 shadow-lg shadow-amber-500/25 hover:brightness-110 active:scale-[0.98] transition"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    <span>New Product</span>
                </a>
            </header>

            <!-- Products List -->
            <div class="overflow-hidden rounded-3xl bg-white shadow-sm border border-stone-200/80">
                <div class="border-b border-stone-100 px-6 py-4 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-stone-900">All Products</h3>
                    <span class="text-xs text-stone-400">Page {{ $products->currentPage() }} of {{ $products->lastPage() }}</span>
                </div>

                <div class="divide-y divide-stone-100">
                    @forelse ($products as $product)
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between px-6 py-4 gap-4 hover:bg-stone-50/50 transition">
                            <div class="flex items-center gap-4">
                                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#1C1917] text-amber-400 font-bold font-display text-sm border border-stone-800">
                                    {{ strtoupper(substr($product->name, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="font-bold text-stone-900 text-sm flex items-center gap-2">
                                        <span>{{ $product->name }}</span>
                                        @if (!empty($product->name_translations['km']) && $product->name_translations['km'] !== $product->name_translations['en'])
                                            <span class="text-xs font-normal text-stone-500">({{ $product->name_translations['km'] }})</span>
                                        @endif
                                    </p>
                                    <p class="text-xs text-stone-500 mt-0.5 flex items-center gap-2">
                                        <span class="inline-flex items-center rounded-md bg-stone-100 px-2 py-0.5 text-[11px] font-semibold text-stone-700">
                                            {{ $product->category->name ?? 'Uncategorized' }}
                                        </span>
                                        <span>&bull;</span>
                                        <span class="text-stone-500 font-medium">{{ $product->variants->count() }} variants</span>
                                        @if ($product->variants->isNotEmpty())
                                            <span>&bull;</span>
                                            <span class="font-bold text-stone-900">
                                                ${{ number_format($product->variants->min('price'), 2) }}
                                                @if ($product->variants->min('price') !== $product->variants->max('price'))
                                                    - ${{ number_format($product->variants->max('price'), 2) }}
                                                @endif
                                            </span>
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 self-end sm:self-center">
                                <span class="rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $product->is_active ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-stone-100 text-stone-600 border border-stone-200' }}">
                                    {{ $product->is_active ? 'Active' : 'Inactive' }}
                                </span>
                                <a href="{{ route('admin.products.edit', $product) }}" class="rounded-xl px-3 py-1.5 text-xs font-semibold text-amber-800 bg-amber-50 hover:bg-amber-100 transition border border-amber-200">
                                    Edit &amp; Variants
                                </a>
                                <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Delete this product and its variants?')">
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
                            <p class="font-bold text-stone-800">No products created yet</p>
                            <p class="mt-1 text-xs text-stone-400">Click "New Product" above to add beverages and cafe items.</p>
                        </div>
                    @endforelse
                </div>

                @if ($products->hasPages())
                    <div class="border-t border-stone-100 px-6 py-4">{{ $products->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
