<x-app-layout>
    <div class="min-h-full bg-[#F9F6F0] p-4 sm:p-6 lg:p-8">
        <div class="mx-auto max-w-5xl space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2 text-xs font-semibold text-stone-700 shadow-sm border border-stone-200/80 hover:bg-stone-50 hover:text-amber-800 transition">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
                    <span>Back to Orders</span>
                </a>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="window.print()" class="inline-flex items-center gap-1.5 rounded-xl bg-white px-3.5 py-2 text-xs font-semibold text-stone-700 border border-stone-200 hover:bg-stone-50 transition">
                        <svg class="h-4 w-4 text-stone-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24-1.049.034-2.148.74-2.854l5.657-5.657c.706-.706 1.805-.98 2.854-.74m0 0 2.121 2.121c.707.707.707 1.853 0 2.56L12.435 14.92c-.707.707-1.853.707-2.56 0L7.754 12.8m8.485-6.364 2.122-2.121c.707-.707 1.853-.707 2.56 0l2.122 2.121c.707.707.707 1.853 0 2.56l-2.122 2.122m-8.485-6.364L7.754 12.8M6.75 21H18a2.25 2.25 0 0 0 2.25-2.25V12M6.75 21A2.25 2.25 0 0 1 4.5 18.75V12m2.25 9h11.25" /></svg>
                        Print
                    </button>
                    <span class="rounded-full bg-stone-100 px-3 py-1 text-xs font-bold text-stone-700 border border-stone-200">
                        {{ $order->order_number }}
                    </span>
                </div>
            </div>


            <!-- Order Items & Invoice Details -->
            <div class="space-y-6">
                <!-- Items Details -->
                <div class="rounded-3xl bg-white p-6 sm:p-8 shadow-sm border border-stone-200/80">
                    <div class="border-b border-stone-100 pb-4 mb-4 flex items-center justify-between">
                        <h2 class="text-lg font-bold font-display tracking-tight text-stone-900">Items Ordered</h2>
                        <span class="text-xs font-semibold text-stone-500">{{ $order->items->count() }} line items</span>
                    </div>

                    <div class="divide-y divide-stone-100">
                        @foreach ($order->items as $item)
                            <div class="flex items-center justify-between py-3.5 text-xs">
                                <div>
                                    <p class="font-bold text-stone-900 text-sm">{{ $item->productVariant->product->name ?? 'N/A' }}</p>
                                    <p class="text-stone-400 mt-0.5">{{ $item->productVariant->name ?? '' }} &bull; ${{ number_format($item->unit_price, 2) }} &times; {{ $item->quantity }}</p>
                                </div>
                                <span class="font-extrabold text-stone-900 text-sm font-display">${{ number_format($item->subtotal, 2) }}</span>
                            </div>
                        @endforeach
                    </div>

                    <!-- Totals Breakdown -->
                    <div class="mt-5 space-y-2 border-t border-stone-100 pt-4 text-xs">
                        <div class="flex justify-between text-stone-500">
                            <span>Subtotal</span>
                            <span class="font-semibold text-stone-900">${{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        @if ($order->tax_amount > 0)
                            <div class="flex justify-between text-stone-500">
                                <span>Tax</span>
                                <span class="font-semibold text-stone-900">${{ number_format($order->tax_amount, 2) }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between text-base font-extrabold text-stone-900 pt-3 border-t border-stone-200 font-display">
                            <span>Grand Total</span>
                            <span class="text-amber-700">${{ number_format($order->total_amount, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Meta Information -->
                <div class="rounded-3xl bg-white p-6 shadow-sm border border-stone-200/80">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-stone-400 mb-4">Metadata &amp; Staff</h3>
                    <dl class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs">
                        <div>
                            <dt class="text-stone-400">Order Number</dt>
                            <dd class="font-bold text-stone-900 mt-0.5">{{ $order->order_number }}</dd>
                        </div>
                        <div>
                            <dt class="text-stone-400">Cashier</dt>
                            <dd class="font-bold text-stone-900 mt-0.5">{{ $order->user->name ?? 'Walk-in Cashier' }}</dd>
                        </div>
                        <div>
                            <dt class="text-stone-400">Dining Option</dt>
                            <dd class="font-bold text-stone-900 mt-0.5 capitalize">
                                {{ str_replace('_', ' ', $order->order_type) }}
                                @if ($order->cafeTable)
                                    <span class="ml-1 text-xs font-semibold text-amber-800 bg-amber-50 px-2 py-0.5 rounded-lg border border-amber-200">
                                        {{ $order->cafeTable->name }} ({{ $order->cafeTable->location ?? 'Indoor' }})
                                    </span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-stone-400">Created Timestamp</dt>
                            <dd class="font-bold text-stone-900 mt-0.5">{{ $order->created_at->format('M j, Y g:i A') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
