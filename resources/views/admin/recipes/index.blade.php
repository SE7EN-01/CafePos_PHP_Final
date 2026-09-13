<x-app-layout>
    <div class="min-h-full bg-[#F9F6F0] p-4 sm:p-6 lg:p-8">
        <div class="w-full space-y-6">
            <!-- Header -->
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-stone-200/80">
                <div class="flex items-center gap-4">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-amber-500/15 text-amber-700 border border-amber-500/30">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 0 1-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 0 1 4.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0 1 12 15a9.065 9.065 0 0 1-6.23-.693L5 14.5m14.8.8 1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0 1 12 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold font-display tracking-tight text-stone-900">Drink Recipes &amp; Costing</h1>
                        <p class="text-xs sm:text-sm text-stone-500 mt-1">Configure ingredient recipes (Bill of Materials), calculate live recipe cost, and optimize gross margins</p>
                    </div>
                </div>

                <div class="flex items-center gap-2.5">
                    <a href="{{ route('admin.stock.index') }}" class="rounded-2xl bg-stone-100 px-4 py-2.5 text-xs font-semibold text-stone-700 hover:bg-stone-200 transition border border-stone-200">
                        &larr; Stock Hub
                    </a>
                </div>
            </div>

            <!-- Recipe List Table -->
            <div class="bg-white rounded-3xl shadow-sm border border-stone-200/80 overflow-hidden">
                <div class="px-6 py-4 border-b border-stone-200/80 flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-bold font-display text-stone-900">Product Variants &amp; Recipes</h2>
                        <p class="text-xs text-stone-500">Live ingredient cost vs menu selling price</p>
                    </div>
                    <span class="text-xs font-semibold text-stone-400">{{ count($variants) }} Variants</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-stone-200/80 bg-stone-50/50 text-[11px] font-bold uppercase tracking-wider text-stone-400">
                                <th class="px-6 py-3.5">Product &amp; Variant</th>
                                <th class="px-6 py-3.5">Category</th>
                                <th class="px-6 py-3.5">Selling Price</th>
                                <th class="px-6 py-3.5">Recipe Cost</th>
                                <th class="px-6 py-3.5">Gross Margin</th>
                                <th class="px-6 py-3.5">Ingredients</th>
                                <th class="px-6 py-3.5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100 text-xs">
                            @forelse ($variants as $v)
                                <tr class="hover:bg-amber-500/[0.02] transition">
                                    <td class="px-6 py-4 font-bold text-stone-900">
                                        <div class="text-sm">{{ $v->product_name }} - {{ $v->variant_name }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="rounded-lg bg-stone-100 px-2.5 py-1 text-[11px] font-medium text-stone-700">
                                            {{ $v->category }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 font-mono font-bold text-stone-900 text-sm">
                                        ${{ number_format($v->selling_price, 2) }}
                                    </td>
                                    <td class="px-6 py-4 font-mono font-bold text-amber-700 text-sm">
                                        ${{ number_format($v->recipe_cost, 2) }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-1.5">
                                            <span class="font-mono font-bold text-emerald-700 text-sm">${{ number_format($v->gross_margin, 2) }}</span>
                                            <span class="rounded-full bg-emerald-50 px-2 py-0.5 text-[10px] font-bold text-emerald-700 border border-emerald-200">
                                                {{ $v->margin_percentage }}%
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($v->ingredients_count > 0)
                                            <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 px-2.5 py-0.5 text-xs font-semibold text-amber-800 border border-amber-200">
                                                {{ $v->ingredients_count }} ingredients
                                            </span>
                                        @else
                                            <span class="text-stone-400 text-xs italic">No recipe attached</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a 
                                            href="{{ route('admin.recipes.edit', $v->variant) }}"
                                            class="rounded-xl bg-amber-600 px-3.5 py-1.5 text-xs font-bold text-white shadow-sm hover:bg-amber-700 transition"
                                        >
                                            {{ $v->ingredients_count > 0 ? 'Edit Recipe' : '+ Add Recipe' }}
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-8 text-center text-stone-400">
                                        No active products found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
