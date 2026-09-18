@php
    $receiptCafeName = \App\Models\Setting::get('cafe_name', 'Bong Heng Cafe');
    $receiptTagline = \App\Models\Setting::get('cafe_tagline', 'ហាងកាហ្វេ និងរោងលីង • SPECIALTY CAFE & ROASTERY');
    $receiptPhone = \App\Models\Setting::get('cafe_phone', '');
    $receiptAddress = \App\Models\Setting::get('cafe_address', '');
    $receiptRate = (int) \App\Models\Setting::get('exchange_rate_khr', 4100);
    $receiptFooter = \App\Models\Setting::get('receipt_footer_text', 'សូមអរគុណចំពោះការគាំទ្រ! សូមអញ្ជើញមកម្តងទៀត។');
@endphp

<x-app-layout>
    <div class="min-h-full bg-[#F9F6F0] p-4 sm:p-6 lg:p-8">
        <div class="mx-auto max-w-lg">
            <!-- Success Announcement Banner (no-print) -->
            <div class="no-print text-center mb-6">
                <div class="inline-flex h-16 w-16 items-center justify-center rounded-3xl bg-emerald-500/15 text-emerald-600 ring-8 ring-emerald-500/10 mb-3 shadow-sm">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                </div>
                <h1 class="text-2xl font-bold font-display tracking-tight text-stone-900">ការបញ្ជាទិញបានជោគជ័យ! (Order Confirmed!)</h1>
                <p class="text-xs text-stone-500 mt-1">ប្រតិបត្តិការត្រូវបានកត់ត្រាជោគជ័យ &bull; Transaction recorded successfully.</p>
            </div>

            <!-- Receipt Container (Target of Print) -->
            <div id="printable-receipt" class="rounded-3xl bg-white shadow-xl border border-stone-200/80 overflow-hidden">
                <!-- Receipt Cafe Header -->
                <div class="bg-[#1C1917] px-6 py-5 text-white text-center border-b border-stone-800">
                    <div class="flex items-center justify-center gap-2 mb-1">
                        <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-amber-500 text-stone-950">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.362 5.214A8.252 8.252 0 0 1 12 21 8.25 8.25 0 0 1 6.038 7.047 8.287 8.287 0 0 0 9 9.601a8.983 8.983 0 0 1 3.361-6.867 8.21 8.21 0 0 0 3 2.48Z" /></svg>
                        </div>
                        <span class="font-display font-bold tracking-tight text-lg text-white">{{ $receiptCafeName }}</span>
                    </div>
                    <p class="text-[11px] text-amber-400 font-medium tracking-wide">{{ $receiptTagline }}</p>
                    @if ($receiptPhone || $receiptAddress)
                        <p class="text-[10px] text-stone-300 mt-0.5">{{ implode(' • ', array_filter([$receiptPhone, $receiptAddress])) }}</p>
                    @endif
                    <p class="text-[10px] text-stone-400 mt-0.5 tracking-wider uppercase">វិក្កយបត្រ / OFFICIAL RECEIPT</p>
                    <div class="mt-3 flex items-center justify-between pt-3 border-t border-stone-800 text-xs text-stone-400">
                        <span class="font-mono font-bold text-amber-300/90">{{ $order->order_number }}</span>
                        <span>{{ $order->created_at->format('M j, Y g:i A') }}</span>
                    </div>
                </div>

                <!-- Order Info Meta -->
                <div class="px-6 py-4 bg-stone-50/70 border-b border-stone-100 text-xs space-y-2">
                    <div class="flex justify-between">
                        <span class="text-stone-500">អ្នកគិតលុយ / Cashier:</span>
                        <span class="font-semibold text-stone-900">{{ $order->user->name ?? 'Staff' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-stone-500">សេវាកម្ម / Service:</span>
                        <span class="font-bold text-stone-900">
                            {{ $order->order_type === 'dine_in' ? 'ញ៉ាំនៅហាង (Dine In)' : 'ខ្ចប់ទៅផ្ទះ (Takeaway)' }}
                        </span>
                    </div>
                    @if ($order->cafeTable)
                        <div class="flex justify-between">
                            <span class="text-stone-500">តុ / Table:</span>
                            <span class="font-bold text-amber-800">{{ $order->cafeTable->name }} ({{ $order->cafeTable->location ?? 'Indoor' }})</span>
                        </div>
                    @endif
                    <div class="flex justify-between items-center">
                        <span class="text-stone-500">វិធីទូទាត់ / Payment:</span>
                        <span class="font-bold text-stone-900 uppercase">
                            {{ $order->payment_method === 'khqr' ? 'KHQR / Bakong' : 'សាច់ប្រាក់ (CASH - KHR ៛)' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-stone-500">ស្ថានភាព / Status:</span>
                        <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-[11px] font-bold text-emerald-800 capitalize">
                            {{ $order->status === 'completed' ? 'បានទូទាត់ (Paid)' : ($order->status === 'pending' ? 'រង់ចាំ (Pending)' : $order->status) }}
                        </span>
                    </div>
                </div>

                <!-- Receipt Items -->
                <div class="px-6 py-5">
                    <p class="text-xs font-bold uppercase tracking-wider text-stone-400 mb-3">មុខទំនិញ / Items Ordered</p>
                    <div class="divide-y divide-stone-100">
                        @foreach ($order->items as $item)
                            @php
                                $prodTrans = $item->productVariant->product->name_translations ?? [];
                                $enProd = $prodTrans['en'] ?? ($item->productVariant->product->name ?? 'Specialty Item');
                                $kmProd = $prodTrans['km'] ?? $enProd;

                                $varTrans = $item->productVariant->name_translations ?? [];
                                $rawVar = is_string($item->productVariant->name) ? $item->productVariant->name : ($varTrans['en'] ?? 'Regular');
                                $enVar = match ($rawVar) {
                                    'ធម្មតា' => 'Regular',
                                    'ធំ' => 'Large',
                                    default => $varTrans['en'] ?? $rawVar,
                                };
                                $kmVar = match ($rawVar) {
                                    'Regular' => 'ធម្មតា',
                                    'Large' => 'ធំ',
                                    default => $varTrans['km'] ?? ($varTrans['en'] ?? $rawVar),
                                };
                            @endphp
                            <div class="py-2.5 flex items-start justify-between gap-3 text-xs">
                                <div class="min-w-0 flex-1">
                                    <p class="font-bold text-stone-900 leading-snug">
                                        {{ $kmProd }} <span class="font-normal text-stone-500">({{ $enProd }})</span>
                                    </p>
                                    <p class="text-[11px] text-stone-500 mt-0.5">
                                        <span class="text-amber-700 font-medium">{{ $kmVar }} ({{ $enVar }})</span> &bull; ${{ number_format($item->unit_price, 2) }} &times; {{ $item->quantity }}
                                    </p>
                                </div>
                                <span class="font-bold text-stone-900 font-mono">${{ number_format($item->subtotal, 2) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Totals -->
                <div class="px-6 py-4 bg-stone-50/50 border-t border-stone-200 space-y-2 text-xs">
                    <div class="flex justify-between text-stone-600">
                        <span>សរុបរង / Subtotal:</span>
                        <span class="font-medium text-stone-900 font-mono">${{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    @if ($order->tax_amount > 0)
                        <div class="flex justify-between text-stone-600">
                            <span>ពន្ធ / Tax:</span>
                            <span class="font-medium text-stone-900 font-mono">${{ number_format($order->tax_amount, 2) }}</span>
                        </div>
                    @endif
                    <div class="flex items-baseline justify-between pt-2 border-t border-stone-200 text-sm font-bold text-stone-900">
                        <span class="text-base">សរុប / Total Amount:</span>
                        <div class="text-right">
                            <span class="text-xl font-extrabold text-amber-700 font-display font-mono">${{ number_format($order->total_amount, 2) }}</span>
                            <p class="text-xs font-bold text-stone-800 font-mono mt-0.5">{{ number_format(round(($order->total_amount * $receiptRate) / 100) * 100) }} ៛</p>
                            <p class="text-[9px] text-stone-400 font-normal">អត្រា / Rate: 1 USD = {{ number_format($receiptRate) }} ៛</p>
                        </div>
                    </div>
                </div>

                <!-- Receipt Footer Message -->
                <div class="px-6 py-4 text-center border-t border-dashed border-stone-200 bg-white">
                    <p class="text-[11px] text-stone-700 font-semibold font-khmer">{{ $receiptFooter }}</p>
                    <p class="text-[10px] text-stone-400 mt-0.5">Thank you for visiting {{ $receiptCafeName }} &bull; POS Official</p>
                </div>
            </div>

            <!-- Action Controls (no-print) -->
            <div class="no-print mt-6 flex flex-col gap-3 sm:flex-row">
                <!-- Print Button -->
                <button 
                    type="button" 
                    onclick="window.print()" 
                    class="flex-1 inline-flex items-center justify-center gap-2 rounded-2xl bg-amber-500 px-5 py-3.5 text-sm font-bold text-stone-950 shadow-lg shadow-amber-500/25 hover:bg-amber-400 transition"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24-1.049.034-2.148.74-2.854l5.657-5.657c.706-.706 1.805-.98 2.854-.74m0 0 2.121 2.121c.707.707.707 1.853 0 2.56L12.435 14.92c-.707.707-1.853.707-2.56 0L7.754 12.8m8.485-6.364 2.122-2.121c.707-.707 1.853-.707 2.56 0l2.122 2.121c.707.707.707 1.853 0 2.56l-2.122 2.122m-8.485-6.364L7.754 12.8M6.75 21H18a2.25 2.25 0 0 0 2.25-2.25V12M6.75 21A2.25 2.25 0 0 1 4.5 18.75V12m2.25 9h11.25" />
                    </svg>
                    <span>បោះពុម្ព (Print Receipt)</span>
                </button>

                <!-- New Order Button -->
                <a 
                    href="{{ route('pos.index') }}" 
                    class="flex-1 inline-flex items-center justify-center gap-2 rounded-2xl bg-stone-900 px-5 py-3.5 text-sm font-bold text-white shadow-md hover:bg-stone-800 transition"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    <span>ការបញ្ជាទិញថ្មី (New Order)</span>
                </a>

                @if (!Auth::user()->hasRole('barista'))
                    <a 
                        href="{{ route('admin.orders.show', $order) }}" 
                        class="inline-flex items-center justify-center rounded-2xl bg-white px-4 py-3.5 text-xs font-semibold text-stone-700 border border-stone-200 hover:bg-stone-50 transition"
                    >
                        Orders List
                    </a>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
