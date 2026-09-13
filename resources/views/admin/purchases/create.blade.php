<x-app-layout>
    <div x-data="{
        rows: [
            { ingredient_id: '', quantity: 1, unit_cost: 0, batch_number: '', expiry_date: '' }
        ],
        addRow() {
            this.rows.push({ ingredient_id: '', quantity: 1, unit_cost: 0, batch_number: '', expiry_date: '' });
        },
        removeRow(index) {
            if (this.rows.length > 1) {
                this.rows.splice(index, 1);
            }
        },
        calculateTotal() {
            return this.rows.reduce((sum, r) => sum + ((parseFloat(r.quantity) || 0) * (parseFloat(r.unit_cost) || 0)), 0).toFixed(2);
        }
    }" class="min-h-full bg-[#F9F6F0] p-4 sm:p-6 lg:p-8">
        <div class="w-full space-y-6">

            <!-- Header -->
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-stone-200/80">
                <div class="flex items-center gap-4">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-500/15 text-emerald-700 border border-emerald-500/30">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold font-display tracking-tight text-stone-900">Receive Goods / Purchase Order</h1>
                        <p class="text-xs sm:text-sm text-stone-500 mt-1">Record supplier delivery, update ingredient stock, and calculate moving average costs</p>
                    </div>
                </div>

                <div class="flex items-center gap-2.5">
                    <a href="{{ route('admin.purchases.index') }}" class="rounded-2xl bg-stone-100 px-4 py-2.5 text-xs font-semibold text-stone-700 hover:bg-stone-200 transition border border-stone-200">
                        &larr; Purchase History
                    </a>
                </div>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('admin.purchases.store') }}" class="space-y-6">
                @csrf

                <!-- Invoice Details Card -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-stone-200/80 space-y-4">
                    <h2 class="text-base font-bold font-display text-stone-900">Invoice Information</h2>

                    <div class="grid gap-4 sm:grid-cols-3">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-stone-500 mb-1.5">Supplier / Vendor</label>
                            <select 
                                name="supplier_id" 
                                class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3.5 py-2.5 text-xs text-stone-900 focus:border-amber-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-amber-500"
                            >
                                <option value="">-- Direct / Walk-in Purchase --</option>
                                @foreach ($suppliers as $s)
                                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-stone-500 mb-1.5">Invoice # / Delivery Note</label>
                            <input 
                                type="text" 
                                name="invoice_number" 
                                placeholder="INV-2026-001" 
                                class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3.5 py-2.5 text-xs text-stone-900 focus:border-amber-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-amber-500"
                            />
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-stone-500 mb-1.5">Purchase Date</label>
                            <input 
                                type="date" 
                                name="purchase_date" 
                                value="{{ date('Y-m-d') }}" 
                                required 
                                class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3.5 py-2.5 text-xs text-stone-900 focus:border-amber-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-amber-500"
                            />
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-stone-500 mb-1.5">Internal Notes (Optional)</label>
                        <input 
                            type="text" 
                            name="notes" 
                            placeholder="e.g. Delivery received in good condition at morning shift" 
                            class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3.5 py-2.5 text-xs text-stone-900 focus:border-amber-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-amber-500"
                        />
                    </div>
                </div>

                <!-- Items Table Card -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-stone-200/80 space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-base font-bold font-display text-stone-900">Received Line Items</h2>
                            <p class="text-xs text-stone-500">List ingredients, received quantities, and unit purchase costs</p>
                        </div>
                        <button 
                            type="button" 
                            @click="addRow()"
                            class="rounded-xl bg-amber-50 px-3 py-1.5 text-xs font-bold text-amber-700 hover:bg-amber-100 transition border border-amber-200"
                        >
                            + Add Line Item
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-stone-200/80 bg-stone-50/50 text-[11px] font-bold uppercase tracking-wider text-stone-400">
                                    <th class="px-4 py-3">Ingredient</th>
                                    <th class="px-4 py-3 w-32">Quantity</th>
                                    <th class="px-4 py-3 w-32">Unit Cost ($)</th>
                                    <th class="px-4 py-3 w-36">Batch #</th>
                                    <th class="px-4 py-3 w-40">Expiry Date</th>
                                    <th class="px-4 py-3 w-28 text-right">Line Total</th>
                                    <th class="px-4 py-3 w-16"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-stone-100 text-xs">
                                <template x-for="(row, index) in rows" :key="index">
                                    <tr>
                                        <!-- Ingredient Select -->
                                        <td class="px-4 py-3">
                                            <select 
                                                :name="'items[' + index + '][ingredient_id]'" 
                                                x-model="row.ingredient_id" 
                                                required 
                                                class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3 py-2 text-xs text-stone-900 focus:border-amber-500 focus:bg-white focus:outline-none"
                                            >
                                                <option value="">-- Select Ingredient --</option>
                                                @foreach ($ingredients as $ing)
                                                    <option value="{{ $ing->id }}">{{ $ing->name }} ({{ $ing->unit }})</option>
                                                @endforeach
                                            </select>
                                        </td>

                                        <!-- Quantity -->
                                        <td class="px-4 py-3">
                                            <input 
                                                type="number" 
                                                :name="'items[' + index + '][quantity]'" 
                                                x-model="row.quantity" 
                                                step="0.01" 
                                                min="0.01" 
                                                required 
                                                placeholder="Qty" 
                                                class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3 py-2 text-xs font-mono text-stone-900 focus:border-amber-500 focus:bg-white focus:outline-none"
                                            />
                                        </td>

                                        <!-- Unit Cost -->
                                        <td class="px-4 py-3">
                                            <input 
                                                type="number" 
                                                :name="'items[' + index + '][unit_cost]'" 
                                                x-model="row.unit_cost" 
                                                step="0.0001" 
                                                min="0" 
                                                required 
                                                placeholder="0.00" 
                                                class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3 py-2 text-xs font-mono text-stone-900 focus:border-amber-500 focus:bg-white focus:outline-none"
                                            />
                                        </td>

                                        <!-- Batch # -->
                                        <td class="px-4 py-3">
                                            <input 
                                                type="text" 
                                                :name="'items[' + index + '][batch_number]'" 
                                                x-model="row.batch_number" 
                                                placeholder="Batch #" 
                                                class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3 py-2 text-xs font-mono text-stone-900 focus:border-amber-500 focus:bg-white focus:outline-none"
                                            />
                                        </td>

                                        <!-- Expiry Date -->
                                        <td class="px-4 py-3">
                                            <input 
                                                type="date" 
                                                :name="'items[' + index + '][expiry_date]'" 
                                                x-model="row.expiry_date" 
                                                class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3 py-2 text-xs text-stone-900 focus:border-amber-500 focus:bg-white focus:outline-none"
                                            />
                                        </td>

                                        <!-- Line Total -->
                                        <td class="px-4 py-3 text-right font-mono font-bold text-stone-900">
                                            $<span x-text="((parseFloat(row.quantity) || 0) * (parseFloat(row.unit_cost) || 0)).toFixed(2)"></span>
                                        </td>

                                        <!-- Remove Row -->
                                        <td class="px-4 py-3 text-center">
                                            <button 
                                                type="button" 
                                                @click="removeRow(index)" 
                                                class="text-stone-400 hover:text-rose-600 font-bold"
                                                x-show="rows.length > 1"
                                            >
                                                &times;
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <!-- Total Cost Footer -->
                    <div class="flex items-center justify-between border-t border-stone-100 pt-4">
                        <div class="text-xs text-stone-500">
                            Subtotal calculated in real-time. On confirmation, stock will be immediately incremented.
                        </div>
                        <div class="flex items-baseline gap-2">
                            <span class="text-xs font-bold uppercase tracking-wider text-stone-500">Grand Total:</span>
                            <span class="text-2xl font-bold font-mono text-emerald-700">$<span x-text="calculateTotal()"></span></span>
                        </div>
                    </div>
                </div>

                <!-- Submit Bar -->
                <div class="flex items-center justify-end gap-3">
                    <a 
                        href="{{ route('admin.purchases.index') }}" 
                        class="rounded-xl px-4 py-2 text-xs font-semibold text-stone-500 hover:bg-stone-100 transition"
                    >
                        Cancel
                    </a>
                    <button 
                        type="submit" 
                        class="rounded-2xl bg-emerald-600 px-6 py-3 text-xs font-bold text-white shadow-sm hover:bg-emerald-700 transition"
                    >
                        Confirm &amp; Receive Purchase (GRN)
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
