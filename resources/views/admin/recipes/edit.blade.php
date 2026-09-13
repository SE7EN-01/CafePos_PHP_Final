<x-app-layout>
    <div class="min-h-full bg-[#F9F6F0] p-4 sm:p-6 lg:p-8">
        <div class="w-full space-y-6">

            <!-- Success Alert -->
            @if (session('status'))
                <div x-data="{ show: true }" x-show="show" class="flex items-center justify-between rounded-2xl bg-emerald-500/10 border border-emerald-500/20 p-4 text-emerald-800">
                    <span class="text-sm font-semibold">Recipe updated successfully!</span>
                    <button @click="show = false" class="text-emerald-600 hover:text-emerald-800">&times;</button>
                </div>
            @endif

            <!-- Header -->
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-stone-200/80">
                <div>
                    <div class="flex items-center gap-2 text-xs font-bold text-amber-700 uppercase tracking-wider">
                        <span>Recipe Configuration</span>
                        <span>&bull;</span>
                        <span>{{ $variant->product->name }}</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-bold font-display tracking-tight text-stone-900 mt-1">
                        {{ $variant->product->name }} ({{ $variant->name }})
                    </h1>
                    <p class="text-xs sm:text-sm text-stone-500 mt-1">Configure ingredient usage per serving for POS inventory deduction and cost calculation</p>
                </div>

                <div class="flex items-center gap-2.5">
                    <a href="{{ route('admin.recipes.index') }}" class="rounded-2xl bg-stone-100 px-4 py-2.5 text-xs font-semibold text-stone-700 hover:bg-stone-200 transition border border-stone-200">
                        &larr; All Recipes
                    </a>
                </div>
            </div>

            <!-- Cost & Margin KPI Cards -->
            <div class="grid gap-4 sm:grid-cols-3">
                <div class="rounded-3xl bg-white p-5 shadow-sm border border-stone-200/80">
                    <span class="text-xs font-bold uppercase tracking-wider text-stone-400">Selling Price</span>
                    <div class="mt-2 text-2xl font-bold font-mono text-stone-900">
                        ${{ number_format($costData['selling_price'], 2) }}
                    </div>
                    <p class="text-[11px] text-stone-400 mt-1">POS customer price per cup</p>
                </div>

                <div class="rounded-3xl bg-white p-5 shadow-sm border border-stone-200/80">
                    <span class="text-xs font-bold uppercase tracking-wider text-stone-400">Recipe Ingredient Cost</span>
                    <div class="mt-2 text-2xl font-bold font-mono text-amber-700">
                        ${{ number_format($costData['recipe_cost'], 2) }}
                    </div>
                    <p class="text-[11px] text-stone-400 mt-1">Raw supplies consumed per drink</p>
                </div>

                <div class="rounded-3xl bg-white p-5 shadow-sm border border-stone-200/80">
                    <span class="text-xs font-bold uppercase tracking-wider text-stone-400">Gross Profit Margin</span>
                    <div class="mt-2 flex items-baseline gap-2">
                        <span class="text-2xl font-bold font-mono text-emerald-700">${{ number_format($costData['gross_margin'], 2) }}</span>
                        <span class="rounded-full bg-emerald-50 px-2 py-0.5 text-xs font-bold text-emerald-700 border border-emerald-200">
                            {{ $costData['margin_percentage'] }}%
                        </span>
                    </div>
                    <p class="text-[11px] text-stone-400 mt-1">Profit margin before overhead</p>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-3">
                <!-- Ingredients in Recipe (2 Cols) -->
                <div class="lg:col-span-2 bg-white rounded-3xl shadow-sm border border-stone-200/80 overflow-hidden">
                    <div class="px-6 py-4 border-b border-stone-200/80 flex items-center justify-between">
                        <h2 class="text-base font-bold font-display text-stone-900">Recipe Formula</h2>
                        <span class="text-xs font-semibold text-stone-400">{{ $variant->recipes->count() }} Ingredients</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-stone-200/80 bg-stone-50/50 text-[11px] font-bold uppercase tracking-wider text-stone-400">
                                    <th class="px-6 py-3.5">Ingredient</th>
                                    <th class="px-6 py-3.5">Quantity Per Serving</th>
                                    <th class="px-6 py-3.5">Unit Cost</th>
                                    <th class="px-6 py-3.5">Subtotal Cost</th>
                                    <th class="px-6 py-3.5 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-stone-100 text-xs">
                                @forelse ($variant->recipes as $r)
                                    @php
                                        $ing = $r->ingredient;
                                        $unitCost = (float) ($ing->average_cost > 0 ? $ing->average_cost : $ing->purchase_cost);
                                        $lineCost = round((float) $r->quantity_required * $unitCost, 4);
                                    @endphp
                                    <tr class="hover:bg-amber-500/[0.02] transition">
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-stone-900">{{ $ing->name }}</div>
                                            <div class="text-[11px] text-stone-400 font-mono">In Stock: {{ $ing->current_stock }} {{ $ing->unit }}</div>
                                        </td>
                                        <td class="px-6 py-4 font-mono font-bold text-stone-900">
                                            {{ number_format($r->quantity_required, 1) }} {{ $ing->unit }}
                                        </td>
                                        <td class="px-6 py-4 font-mono text-stone-600">
                                            ${{ number_format($unitCost, 4) }} / {{ $ing->unit }}
                                        </td>
                                        <td class="px-6 py-4 font-mono font-bold text-amber-700">
                                            ${{ number_format($lineCost, 3) }}
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <form method="POST" action="{{ route('admin.recipes.destroy', $r) }}" onsubmit="return confirm('Remove {{ $ing->name }} from this recipe?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rounded-lg bg-rose-50 px-2.5 py-1 text-xs font-semibold text-rose-700 hover:bg-rose-100 transition border border-rose-200">
                                                    Remove
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-8 text-center text-stone-400">
                                            No ingredients added to this recipe yet. Add an ingredient using the form on the right.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Add Ingredient Form (1 Col) -->
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-stone-200/80 space-y-4">
                    <h3 class="text-base font-bold font-display text-stone-900">+ Add Ingredient</h3>
                    <p class="text-xs text-stone-500">Attach an ingredient and specify the exact quantity needed per drink serving.</p>

                    <form method="POST" action="{{ route('admin.recipes.store', $variant) }}" class="space-y-4">
                        @csrf

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-stone-500 mb-1.5">Raw Ingredient</label>
                            <select 
                                name="ingredient_id" 
                                required 
                                class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3.5 py-2.5 text-xs text-stone-900 focus:border-amber-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-amber-500"
                            >
                                <option value="">-- Choose Ingredient --</option>
                                @foreach ($allIngredients as $ing)
                                    <option value="{{ $ing->id }}">
                                        {{ $ing->name }} (Unit: {{ $ing->unit }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-stone-500 mb-1.5">Quantity Per Serving</label>
                            <input 
                                type="number" 
                                name="quantity_required" 
                                step="0.01" 
                                min="0.01" 
                                required 
                                placeholder="e.g. 18.0 for 18g beans or 150.0 for 150ml milk" 
                                class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3.5 py-2.5 text-xs text-stone-900 font-mono focus:border-amber-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-amber-500"
                            />
                        </div>

                        <button 
                            type="submit" 
                            class="w-full rounded-xl bg-amber-600 py-2.5 text-xs font-bold text-white shadow-sm hover:bg-amber-700 transition"
                        >
                            Save Ingredient to Recipe
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
