<x-app-layout>
    <div class="min-h-full bg-[#F9F6F0] p-3 sm:p-4 md:p-6 lg:p-8" x-data="checkout()" x-init="init()">
        <div class="mx-auto max-w-5xl">
            <!-- Header Breadcrumb & Language Switcher -->
            <div class="mb-4 sm:mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                <a href="{{ route('pos.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2 text-xs font-semibold text-stone-700 shadow-sm border border-stone-200/80 hover:bg-stone-50 hover:text-amber-800 transition">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
                    <span x-text="lang === 'km' ? 'ត្រឡប់ទៅ POS (Back)' : 'Back to POS'">Back to POS</span>
                </a>

                <div class="flex items-center gap-3">
                    <span class="text-xs font-medium text-stone-500 hidden sm:inline" x-text="lang === 'km' ? 'ជំហានទូទាត់ប្រាក់ &bull; បញ្ចប់ការទូទាត់' : 'Checkout Step &bull; Finalize Payment'">Checkout Step &bull; Finalize Payment</span>
                    
                    <!-- Language Selector (English & Khmer) -->
                    <div class="inline-flex rounded-xl bg-stone-100 p-1 border border-stone-200 shadow-inner">
                        <button 
                            type="button" 
                            @click="setLanguage('en')" 
                            :class="lang === 'en' ? 'bg-white text-stone-950 font-bold shadow-sm' : 'text-stone-600 hover:text-stone-900 font-medium'" 
                            class="flex items-center gap-1.5 rounded-lg px-2.5 py-1 text-xs transition"
                        >
                            <span>🇺🇸 EN</span>
                        </button>
                        <button 
                            type="button" 
                            @click="setLanguage('km')" 
                            :class="lang === 'km' ? 'bg-white text-stone-950 font-bold shadow-sm' : 'text-stone-600 hover:text-stone-900 font-medium'" 
                            class="flex items-center gap-1.5 rounded-lg px-2.5 py-1 text-xs transition"
                        >
                            <span>🇰🇭 ខ្មែរ</span>
                        </button>
                    </div>
                </div>
            </div>

            <form id="checkout-form" method="POST" action="{{ route('pos.checkout.store') }}">
                @csrf
                <input type="hidden" name="order_type" :value="orderType">
                <input type="hidden" name="cafe_table_id" :value="orderType === 'dine_in' ? selectedTable : ''">
                <input type="hidden" name="payment_method" :value="paymentMethod">
                <input type="hidden" name="khqr_md5" :value="khqrMd5">
                @foreach ($items as $index => $item)
                    <input type="hidden" name="items[{{ $index }}][variant_id]" value="{{ $item['variant']->id }}">
                    <input type="hidden" name="items[{{ $index }}][quantity]" value="{{ $item['quantity'] }}">
                @endforeach

                <!-- Mobile: Stack vertically, Desktop: Side by side -->
                <div class="grid gap-4 sm:gap-6 lg:grid-cols-5">
                    <!-- Left: Order Type & Payment Method (3 cols on desktop) -->
                    <div class="lg:col-span-3 space-y-4 sm:space-y-6">
                        <!-- Order Type Card -->
                        <div class="rounded-2xl sm:rounded-3xl bg-white p-4 sm:p-6 shadow-sm border border-stone-200/80">
                            <h2 class="text-sm sm:text-base font-bold font-display text-stone-900 mb-3 flex items-center gap-2">
                                <span class="flex h-5 w-5 sm:h-6 sm:w-6 items-center justify-center rounded-lg bg-amber-100 text-amber-800 text-[10px] sm:text-xs font-bold">1</span>
                                <span x-text="lang === 'km' ? 'ជម្រើសបរិភោគ (Dining Option)' : 'Dining Option'">Dining Option</span>
                            </h2>
                            <div class="grid grid-cols-2 gap-2.5 sm:gap-3.5">
                                <label class="flex cursor-pointer items-center gap-2.5 sm:gap-3.5 rounded-xl sm:rounded-2xl border-2 p-3 sm:p-4 transition-all" :class="orderType === 'dine_in' ? 'border-amber-500 bg-amber-50/50 shadow-sm' : 'border-stone-200 hover:border-stone-300'">
                                    <input type="radio" name="order_type_radio" value="dine_in" x-model="orderType" class="sr-only">
                                    <div class="flex h-9 w-9 sm:h-11 sm:w-11 items-center justify-center rounded-lg sm:rounded-xl" :class="orderType === 'dine_in' ? 'bg-amber-500 text-stone-950 font-bold shadow-md shadow-amber-500/20' : 'bg-stone-100 text-stone-600'">
                                        <svg class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0" /></svg>
                                    </div>
                                    <div>
                                        <p class="font-bold text-xs sm:text-sm text-stone-900 leading-tight" x-text="lang === 'km' ? 'ញ៉ាំនៅហាង' : 'Dine In'">Dine In</p>
                                        <p class="text-[10px] sm:text-xs text-stone-500 mt-0.5" x-text="lang === 'km' ? 'សេវាកម្មតុ (Table)' : 'Table service'">Table service</p>
                                    </div>
                                </label>

                                <label class="flex cursor-pointer items-center gap-2.5 sm:gap-3.5 rounded-xl sm:rounded-2xl border-2 p-3 sm:p-4 transition-all" :class="orderType === 'takeaway' ? 'border-amber-500 bg-amber-50/50 shadow-sm' : 'border-stone-200 hover:border-stone-300'">
                                    <input type="radio" name="order_type_radio" value="takeaway" x-model="orderType" class="sr-only">
                                    <div class="flex h-9 w-9 sm:h-11 sm:w-11 items-center justify-center rounded-lg sm:rounded-xl" :class="orderType === 'takeaway' ? 'bg-amber-500 text-stone-950 font-bold shadow-md shadow-amber-500/20' : 'bg-stone-100 text-stone-600'">
                                        <svg class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 0 1-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 0 1 1.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 0 0-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 0 1-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 0 0-3.375-3.375h-1.5a1.125 1.125 0 0 1-1.125-1.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H9.75" /></svg>
                                    </div>
                                    <div>
                                        <p class="font-bold text-xs sm:text-sm text-stone-900 leading-tight" x-text="lang === 'km' ? 'ខ្ចប់ទៅផ្ទះ' : 'Takeaway'">Takeaway</p>
                                        <p class="text-[10px] sm:text-xs text-stone-500 mt-0.5" x-text="lang === 'km' ? 'កញ្ចប់វេចខ្ចប់ (To-go)' : 'To-go package'">To-go package</p>
                                    </div>
                                </label>
                            </div>

                            <!-- Table Selector -->
                            <div x-show="orderType === 'dine_in'" x-transition class="mt-4 sm:mt-5 pt-3 sm:pt-4 border-t border-stone-100">
                                <div class="flex items-center justify-between mb-2.5 sm:mb-3">
                                    <label class="block text-[11px] sm:text-xs font-bold text-stone-900">
                                        <span x-text="lang === 'km' ? 'ជ្រើសរើសតុ (Assign Table)' : 'Assign Table'">Assign Table</span>
                                        <span class="text-rose-500">*</span>
                                    </label>
                                    <span class="text-[10px] sm:text-[11px] text-stone-400" x-text="lang === 'km' ? 'បៃតង=ទំនេរ ទឹកក្រូច=មានភ្ញៀវ' : 'Green=Available Orange=Occupied'">Green=Available Orange=Occupied</span>
                                </div>

                                @if ($tables->isEmpty())
                                    <p class="text-[11px] sm:text-xs text-stone-400 italic">No tables created yet.</p>
                                @else
                                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 sm:gap-2.5">
                                        @foreach ($tables as $t)
                                            <button 
                                                type="button" 
                                                @click="selectedTable = '{{ $t->id }}'" 
                                                class="relative flex flex-col p-2.5 sm:p-3 rounded-xl sm:rounded-2xl border text-left transition-all"
                                                :class="selectedTable == '{{ $t->id }}' 
                                                    ? 'border-amber-500 bg-amber-50/80 ring-2 ring-amber-500/25 shadow-sm' 
                                                    : 'border-stone-200 bg-stone-50/60 hover:border-stone-300 hover:bg-white'"
                                            >
                                                <div class="flex items-center justify-between">
                                                    <span class="text-[11px] sm:text-xs font-bold text-stone-900">{{ $t->name }}</span>
                                                    @if ($t->status === 'available')
                                                        <span class="h-1.5 w-1.5 sm:h-2 sm:w-2 rounded-full bg-emerald-500" title="Available"></span>
                                                    @elseif ($t->status === 'occupied')
                                                        <span class="h-1.5 w-1.5 sm:h-2 sm:w-2 rounded-full bg-amber-500" title="Occupied"></span>
                                                    @else
                                                        <span class="h-1.5 w-1.5 sm:h-2 sm:w-2 rounded-full bg-stone-400" title="Reserved"></span>
                                                    @endif
                                                </div>
                                                <div class="mt-1 flex items-center justify-between text-[9px] sm:text-[10px] text-stone-500">
                                                    <span>{{ $t->capacity }} <span x-text="lang === 'km' ? 'កៅអី' : 'seats'">seats</span></span>
                                                    <span class="truncate max-w-[50px] sm:max-w-[65px] text-stone-400">{{ $t->location }}</span>
                                                </div>
                                            </button>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Payment Method Card -->
                        <div class="rounded-2xl sm:rounded-3xl bg-white p-4 sm:p-6 shadow-sm border border-stone-200/80">
                            <h2 class="text-sm sm:text-base font-bold font-display text-stone-900 mb-3 flex items-center gap-2">
                                <span class="flex h-5 w-5 sm:h-6 sm:w-6 items-center justify-center rounded-lg bg-amber-100 text-amber-800 text-[10px] sm:text-xs font-bold">2</span>
                                <span x-text="lang === 'km' ? 'វិធីសាស្ត្រទូទាត់ (Payment Method)' : 'Payment Method'">Payment Method</span>
                            </h2>
                            <div class="grid {{ $cashEnabled && $khqrEnabled ? 'grid-cols-2' : 'grid-cols-1' }} gap-2.5 sm:gap-3">
                                @if ($cashEnabled)
                                <button type="button" @click="paymentMethod = 'cash'" :class="paymentMethod === 'cash' ? 'border-amber-500 bg-amber-50 text-amber-900 ring-2 ring-amber-500/20' : 'border-stone-200 text-stone-700 hover:bg-stone-50'" class="flex flex-col items-center justify-center p-3 sm:p-3.5 rounded-xl sm:rounded-2xl border transition text-center">
                                    <svg class="h-5 w-5 sm:h-6 sm:w-6 text-emerald-600 mb-0.5 sm:mb-1" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" /></svg>
                                    <span class="text-[11px] sm:text-xs font-bold" x-text="lang === 'km' ? 'សាច់ប្រាក់ (Cash)' : 'Cash'">Cash</span>
                                </button>
                                @endif
                                @if ($khqrEnabled)
                                <button type="button" @click="paymentMethod = 'khqr'" :class="paymentMethod === 'khqr' ? 'border-amber-500 bg-amber-50 text-amber-900 ring-2 ring-amber-500/20' : 'border-stone-200 text-stone-700 hover:bg-stone-50'" class="flex flex-col items-center justify-center p-3 sm:p-3.5 rounded-xl sm:rounded-2xl border transition text-center">
                                    <svg class="h-5 w-5 sm:h-6 sm:w-6 text-red-600 mb-0.5 sm:mb-1" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0 1 3.75 9.375v-4.5ZM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 0 1-1.125-1.125v-4.5ZM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0 1 13.5 9.375v-4.5Z" /></svg>
                                    <span class="text-[11px] sm:text-xs font-bold" x-text="lang === 'km' ? 'ស្កេន KHQR (បាគង/ABA)' : 'KHQR / ABA'">KHQR / ABA</span>
                                </button>
                                @endif
                            </div>

                            <!-- Cash Calculation Fields (Khmer Riel & USD) -->
                            <div x-show="paymentMethod === 'cash'" class="mt-3 sm:mt-4 pt-3 sm:pt-4 border-t border-stone-100 space-y-3">
                                <!-- Currency Toggle: KHR (default) / USD -->
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                    <label class="text-[11px] sm:text-xs font-bold text-stone-700">
                                        <span x-text="lang === 'km' ? 'រូបិយប័ណ្ណសាច់ប្រាក់ (Cash Currency)' : 'Cash Currency'">Cash Currency</span>
                                    </label>
                                    <div class="inline-flex rounded-xl bg-stone-100 p-1 border border-stone-200">
                                        <button 
                                            type="button" 
                                            @click="cashCurrency = 'khr'" 
                                            :class="cashCurrency === 'khr' ? 'bg-amber-500 text-stone-950 font-bold shadow-sm' : 'text-stone-600 hover:text-stone-900 font-medium'" 
                                            class="flex items-center gap-1 rounded-lg px-2.5 py-1 text-xs transition font-semibold"
                                        >
                                            <span>🇰🇭 លុយរៀល (KHR ៛)</span>
                                        </button>
                                        <button 
                                            type="button" 
                                            @click="cashCurrency = 'usd'" 
                                            :class="cashCurrency === 'usd' ? 'bg-amber-500 text-stone-950 font-bold shadow-sm' : 'text-stone-600 hover:text-stone-900 font-medium'" 
                                            class="flex items-center gap-1 rounded-lg px-2.5 py-1 text-xs transition font-semibold"
                                        >
                                            <span>🇺🇸 ដុល្លារ (USD $)</span>
                                        </button>
                                    </div>
                                </div>

                                <!-- Exchange Rate & Total Due Banner -->
                                <div class="flex flex-wrap items-center justify-between gap-2 rounded-xl bg-amber-50/70 p-3 border border-amber-200/70 text-xs">
                                    <div>
                                        <p class="text-stone-500 text-[11px]" x-text="lang === 'km' ? 'ទឹកប្រាក់ត្រូវទូទាត់' : 'Total Payable'"></p>
                                        <p class="text-base font-extrabold text-amber-800 font-mono">
                                            <span x-text="formatNumber(totalKhr) + ' ៛'"></span>
                                            <span class="text-xs font-normal text-stone-500">($<span x-text="totalAmount.toFixed(2)"></span>)</span>
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-block rounded-md bg-white px-2.5 py-1 text-[10px] font-bold text-amber-900 border border-amber-200 shadow-sm">
                                            1 USD = {{ number_format($exchangeRate) }} ៛
                                        </span>
                                    </div>
                                </div>

                                <!-- Cash Tendered in KHR (លុយរៀល) -->
                                <div x-show="cashCurrency === 'khr'" class="space-y-2">
                                    <div class="flex items-center justify-between">
                                        <label class="block text-[11px] sm:text-xs font-bold text-stone-700">
                                            <span x-text="lang === 'km' ? 'ប្រាក់រៀលទទួល (Cash Tendered - ៛)' : 'Cash Received (KHR ៛)'">Cash Received (KHR ៛)</span>
                                        </label>
                                        <button type="button" @click="setExactKhr()" class="text-[11px] font-bold text-amber-700 hover:underline">
                                            <span x-text="lang === 'km' ? 'គ្រប់ចំនួន (' + formatNumber(totalKhr) + ' ៛)' : 'Exact (' + formatNumber(totalKhr) + ' ៛)'"></span>
                                        </button>
                                    </div>

                                    <div class="relative">
                                        <input 
                                            type="number" 
                                            step="100" 
                                            x-model.number="cashGivenKhr" 
                                            class="block w-full rounded-xl border-stone-200 bg-stone-50 py-2.5 pl-4 pr-14 text-sm sm:text-base font-bold text-stone-900 focus:border-amber-500 focus:ring-amber-500 font-mono"
                                        >
                                        <span class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-xs font-bold text-amber-700">៛ (KHR)</span>
                                    </div>

                                    <!-- Quick Banknote Buttons for Khmer Riel -->
                                    <div class="grid grid-cols-3 sm:grid-cols-6 gap-1.5 pt-1">
                                        <button type="button" @click="cashGivenKhr = 5000" class="rounded-lg bg-stone-100 py-1.5 text-xs font-bold text-stone-700 hover:bg-amber-100 hover:text-amber-900 transition font-mono">5,000 ៛</button>
                                        <button type="button" @click="cashGivenKhr = 10000" class="rounded-lg bg-stone-100 py-1.5 text-xs font-bold text-stone-700 hover:bg-amber-100 hover:text-amber-900 transition font-mono">10,000 ៛</button>
                                        <button type="button" @click="cashGivenKhr = 20000" class="rounded-lg bg-stone-100 py-1.5 text-xs font-bold text-stone-700 hover:bg-amber-100 hover:text-amber-900 transition font-mono">20,000 ៛</button>
                                        <button type="button" @click="cashGivenKhr = 50000" class="rounded-lg bg-stone-100 py-1.5 text-xs font-bold text-stone-700 hover:bg-amber-100 hover:text-amber-900 transition font-mono">50,000 ៛</button>
                                        <button type="button" @click="cashGivenKhr = 100000" class="rounded-lg bg-stone-100 py-1.5 text-xs font-bold text-stone-700 hover:bg-amber-100 hover:text-amber-900 transition font-mono">100,000 ៛</button>
                                        <button type="button" @click="setExactKhr()" class="rounded-lg bg-amber-100 py-1.5 text-xs font-bold text-amber-800 hover:bg-amber-200 transition" x-text="lang === 'km' ? 'គ្រប់ចំនួន' : 'Exact'">Exact</button>
                                    </div>
                                </div>

                                <!-- Cash Tendered in USD (ដុល្លារ) -->
                                <div x-show="cashCurrency === 'usd'" class="space-y-2">
                                    <div class="flex items-center justify-between">
                                        <label class="block text-[11px] sm:text-xs font-bold text-stone-700">
                                            <span x-text="lang === 'km' ? 'ប្រាក់ដុល្លារទទួល (Cash Tendered - $)' : 'Cash Received (USD $)'">Cash Received (USD $)</span>
                                        </label>
                                        <button type="button" @click="setExactUsd()" class="text-[11px] font-bold text-amber-700 hover:underline">
                                            <span x-text="lang === 'km' ? 'គ្រប់ចំនួន ($' + Math.ceil(totalAmount) + ')' : 'Exact ($' + Math.ceil(totalAmount) + ')'"></span>
                                        </button>
                                    </div>

                                    <div class="relative">
                                        <input 
                                            type="number" 
                                            step="0.5" 
                                            x-model.number="cashGivenUsd" 
                                            class="block w-full rounded-xl border-stone-200 bg-stone-50 py-2.5 pl-4 pr-14 text-sm sm:text-base font-bold text-stone-900 focus:border-amber-500 focus:ring-amber-500 font-mono"
                                        >
                                        <span class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-xs font-bold text-amber-700">$ (USD)</span>
                                    </div>

                                    <!-- Quick Bills USD -->
                                    <div class="grid grid-cols-4 gap-1.5 pt-1">
                                        <button type="button" @click="cashGivenUsd = 5" class="rounded-lg bg-stone-100 py-1.5 text-xs font-bold text-stone-700 hover:bg-amber-100 hover:text-amber-900 transition font-mono">$5</button>
                                        <button type="button" @click="cashGivenUsd = 10" class="rounded-lg bg-stone-100 py-1.5 text-xs font-bold text-stone-700 hover:bg-amber-100 hover:text-amber-900 transition font-mono">$10</button>
                                        <button type="button" @click="cashGivenUsd = 20" class="rounded-lg bg-stone-100 py-1.5 text-xs font-bold text-stone-700 hover:bg-amber-100 hover:text-amber-900 transition font-mono">$20</button>
                                        <button type="button" @click="setExactUsd()" class="rounded-lg bg-amber-100 py-1.5 text-xs font-bold text-amber-800 hover:bg-amber-200 transition" x-text="lang === 'km' ? 'គ្រប់ចំនួន' : 'Exact'">Exact</button>
                                    </div>
                                </div>

                                <!-- Change Due or Shortage Alert -->
                                <template x-if="!isUnderpaid">
                                    <div class="flex items-center justify-between rounded-xl bg-emerald-50/80 p-3 text-xs font-bold text-emerald-900 border border-emerald-200 shadow-sm">
                                        <div>
                                            <span class="text-[11px] text-emerald-700 block" x-text="lang === 'km' ? 'ប្រាក់អាប់ជូនភ្ញៀវ (Change Due)' : 'Change Due'">Change Due</span>
                                            <span class="text-lg font-extrabold text-emerald-800 font-mono" x-text="formatNumber(changeDueKhr) + ' ៛'"></span>
                                        </div>
                                        <div class="text-right text-stone-500 font-medium text-xs">
                                            <span>(សមមូល $<span x-text="changeDueUsd.toFixed(2)"></span>)</span>
                                        </div>
                                    </div>
                                </template>

                                <template x-if="isUnderpaid">
                                    <div class="flex items-center justify-between rounded-xl bg-rose-50 p-2.5 text-xs font-bold text-rose-800 border border-rose-200">
                                        <span x-text="lang === 'km' ? 'នៅខ្វះប្រាក់:' : 'Shortage:'">Shortage:</span>
                                        <span class="font-mono text-sm" x-text="formatNumber(shortageKhr) + ' ៛'"></span>
                                    </div>
                                </template>
                            </div>


                            <!-- KHQR QR Code Display -->
                            <div x-show="paymentMethod === 'khqr'" class="mt-3 sm:mt-4 pt-3 sm:pt-4 border-t border-stone-100">
                                <!-- QR Generated & Active -->
                                <div x-show="khqrGenerated && !khqrExpired" class="text-center">
                                    <div class="inline-block p-3 sm:p-4 bg-white rounded-xl sm:rounded-2xl shadow-lg border border-stone-200">
                                        <img :src="khqrQr" alt="KHQR Code" class="w-48 h-48 sm:w-56 sm:h-56 md:w-64 md:h-64">
                                    </div>
                                    <div class="mt-2 sm:mt-3 flex items-center justify-center gap-2">
                                        <span class="text-[11px] sm:text-xs font-bold text-stone-700" x-text="lang === 'km' ? 'ផុតកំណត់ក្នុង:' : 'Expires in:'">Expires in:</span>
                                        <span class="text-xs sm:text-sm font-bold text-amber-600" x-text="qrTimeRemaining"></span>
                                    </div>
                                    <p class="mt-1.5 sm:mt-2 text-[10px] sm:text-[11px] text-stone-500" x-text="lang === 'km' ? 'ស្កេនជាមួយបាគង ឬកម្មវិធីធនាគារនានា' : 'Scan with Bakong or Bank App'">Scan with Bakong or Bank App</p>

                                    <!-- Auto-detect status -->
                                    <div x-show="!khqrPollError" class="mt-2 sm:mt-3 flex items-center justify-center gap-1">
                                        <span class="inline-block h-1.5 w-1.5 sm:h-2 sm:w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                        <span class="text-[11px] sm:text-xs font-medium text-emerald-600" x-text="lang === 'km' ? 'កំពុងរង់ចាំការទូទាត់...' : 'Waiting for payment...'">Waiting for payment...</span>
                                    </div>

                                    <!-- Polling error: API unreachable -->
                                    <div x-show="khqrPollError" class="mt-2 sm:mt-3 rounded-lg bg-amber-50 border border-amber-200 p-2.5 text-center">
                                        <p class="text-[11px] sm:text-xs text-amber-800 font-medium" x-text="lang === 'km' ? 'ការផ្ទៀងផ្ទាត់ស្វ័យប្រវត្តិកំពុងជាប់គាំង។ លោកអ្នកអាចបញ្ជាក់ដោយដៃបន្ទាប់ពីអតិថិជនបានផ្ទេររួចរាល់។' : 'Auto-detect unavailable. Verify payment on your banking app, then confirm below.'"></p>
                                    </div>
                                </div>

                                <!-- QR Expired -->
                                <div x-show="khqrExpired" class="text-center py-4 sm:py-6">
                                    <div class="inline-flex h-10 w-10 sm:h-12 sm:w-12 items-center justify-center rounded-full bg-rose-100 text-rose-600 mb-2 sm:mb-3">
                                        <svg class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" /></svg>
                                    </div>
                                    <p class="text-xs sm:text-sm font-bold text-stone-900" x-text="lang === 'km' ? 'កូដ QR ផុតកំណត់ហើយ' : 'QR Code Expired'">QR Code Expired</p>
                                    <button type="button" @click="regenerateKhqr()" class="mt-2 sm:mt-3 inline-flex items-center gap-1.5 rounded-lg sm:rounded-xl bg-amber-500 px-3 sm:px-4 py-1.5 sm:py-2 text-[11px] sm:text-xs font-bold text-stone-950 hover:bg-amber-400 transition">
                                        <span x-text="lang === 'km' ? 'បង្កើត QR ឡើងវិញ' : 'Regenerate QR'">Regenerate QR</span>
                                    </button>
                                </div>

                                <!-- Initial / Loading State -->
                                <div x-show="!khqrGenerated && !khqrExpired" class="text-center py-6 sm:py-8">
                                    <svg class="h-6 w-6 sm:h-8 sm:w-8 animate-spin mx-auto text-amber-500" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    <p class="text-[11px] sm:text-xs text-stone-500 mt-2" x-text="lang === 'km' ? 'កំពុងបង្កើត KHQR...' : 'Generating KHQR code...'">Generating KHQR code...</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Order Summary (2 cols on desktop) -->
                    <div class="lg:col-span-2">
                        <div class="rounded-2xl sm:rounded-3xl bg-[#1C1917] p-4 sm:p-6 text-white shadow-xl border border-stone-800 sticky top-4 sm:top-6">
                            <div class="flex items-center justify-between pb-3 sm:pb-4 border-b border-stone-800">
                                <div>
                                    <h2 class="text-sm sm:text-base font-bold font-display text-white" x-text="lang === 'km' ? 'សង្ខេបការបញ្ជាទិញ' : 'Order Summary'">Order Summary</h2>
                                    <p class="text-[10px] sm:text-[11px] text-amber-400 capitalize" x-text="orderType === 'dine_in' ? (lang === 'km' ? 'ញ៉ាំនៅហាង (Dine In)' : 'Dine In') : (lang === 'km' ? 'ខ្ចប់ទៅផ្ទះ (Takeaway)' : 'Takeaway')"></p>
                                </div>
                                <span class="rounded-full bg-amber-500/20 px-2 sm:px-2.5 py-0.5 text-[10px] sm:text-xs font-bold text-amber-300 border border-amber-500/30">
                                    {{ count($items) }} <span x-text="lang === 'km' ? 'មុខ' : 'items'">items</span>
                                </span>
                            </div>

                            <!-- Items List -->
                            <div class="mt-3 sm:mt-4 space-y-2.5 sm:space-y-3 max-h-52 sm:max-h-64 overflow-y-auto pr-1">
                                @foreach ($items as $item)
                                    <div class="flex items-start justify-between gap-2 text-xs">
                                        <div class="min-w-0 flex-1">
                                            <!-- Product Title (Bilingual) -->
                                            <p class="font-semibold text-stone-200 truncate">
                                                <span x-show="lang === 'km'">{{ $item['product_name_km'] }}</span>
                                                <span x-show="lang !== 'km'">{{ $item['product_name_en'] }}</span>
                                            </p>
                                            <p class="text-[10px] text-stone-500 truncate">
                                                <span x-show="lang === 'km'">{{ $item['product_name_en'] }}</span>
                                                <span x-show="lang !== 'km'">{{ $item['product_name_km'] }}</span>
                                            </p>
                                            <p class="text-[10px] sm:text-[11px] text-amber-300/80 mt-0.5">
                                                <span x-show="lang === 'km'">{{ $item['variant_name_km'] }}</span>
                                                <span x-show="lang !== 'km'">{{ $item['variant_name_en'] }}</span>
                                                &bull; ${{ number_format($item['unit_price'], 2) }} &times; {{ $item['quantity'] }}
                                            </p>
                                        </div>
                                        <span class="text-[11px] sm:text-xs font-bold text-amber-400">${{ number_format($item['subtotal'], 2) }}</span>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Totals breakdown -->
                            <div class="mt-3 sm:mt-4 pt-3 sm:pt-4 border-t border-stone-800 space-y-1.5 sm:space-y-2 text-[11px] sm:text-xs">
                                <div class="flex justify-between text-stone-400">
                                    <span x-text="lang === 'km' ? 'សរុបរង (Subtotal)' : 'Subtotal'">Subtotal</span>
                                    <span class="font-semibold text-white">${{ number_format($subtotal, 2) }}</span>
                                </div>
                                <div class="flex items-baseline justify-between pt-2.5 sm:pt-3 border-t border-stone-800 text-xs sm:text-sm font-bold text-white">
                                    <span x-text="lang === 'km' ? 'ទឹកប្រាក់សរុប (Total)' : 'Grand Total'">Grand Total</span>
                                    <div class="text-right">
                                        <span class="text-xl sm:text-2xl font-extrabold text-amber-400 font-display">${{ number_format($total, 2) }}</span>
                                        <p class="text-xs text-amber-300 font-mono mt-0.5">({{ number_format(round(($total * $exchangeRate) / 100) * 100) }} ៛)</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Place Order Button -->
                            <button 
                                type="button" 
                                @click="placeOrder()" 
                                :disabled="processing || (paymentMethod === 'cash' && isUnderpaid) || (paymentMethod === 'khqr' && (!khqrGenerated || khqrExpired))" 
                                class="mt-4 sm:mt-6 w-full inline-flex items-center justify-center gap-2 rounded-xl sm:rounded-2xl bg-gradient-to-r from-amber-400 via-amber-500 to-amber-600 px-4 sm:px-5 py-3 sm:py-4 text-xs sm:text-sm font-bold text-stone-950 shadow-lg shadow-amber-500/25 transition duration-150 hover:brightness-110 active:scale-[0.99] disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                <span x-show="!processing">
                                    <template x-if="paymentMethod === 'khqr'">
                                        <span x-text="khqrGenerated && !khqrExpired ? (lang === 'km' ? 'បញ្ជាក់ការទូទាត់ KHQR' : 'Confirm KHQR Payment') : (lang === 'km' ? 'កំពុងបង្កើត KHQR...' : 'Generate KHQR First')"></span>
                                    </template>
                                    <template x-if="paymentMethod === 'cash'">
                                        <span x-text="lang === 'km' ? 'បញ្ជាក់ការទូទាត់សាច់ប្រាក់ (Confirm Cash Payment)' : 'Confirm Cash Payment'">Confirm Cash Payment</span>
                                    </template>
                                </span>
                                <span x-show="processing" class="flex items-center gap-2">
                                    <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    <span x-text="lang === 'km' ? 'កំពុងដំណើរការ...' : 'Processing...'">Processing...</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function checkout() {
            return {
                lang: localStorage.getItem('pos_lang') || 'en',
                orderType: '{{ $order_type }}',
                selectedTable: '{{ $tables->firstWhere('status', 'available')?->id ?? ($tables->first()?->id ?? '') }}',
                paymentMethod: '{{ $cashEnabled ? 'cash' : ($khqrEnabled ? 'khqr' : 'cash') }}',
                totalAmount: {{ $total }},
                exchangeRate: {{ $exchangeRate }},
                cashCurrency: '{{ $cashDefaultCurrency }}',
                cashGivenKhr: {{ round(($total * $exchangeRate) / 100) * 100 }},
                cashGivenUsd: {{ ceil($total) }},
                processing: false,
                khqrGenerated: false,
                khqrQr: '',
                khqrMd5: '',
                khqrExpiresAt: null,
                khqrRemainingSeconds: 0,
                khqrPolling: null,
                khqrTimer: null,
                khqrExpired: false,
                khqrPollError: false,
                khqrPollFailCount: 0,

                setLanguage(newLang) {
                    this.lang = newLang;
                    localStorage.setItem('pos_lang', newLang);
                },

                get totalKhr() {
                    return Math.round((this.totalAmount * this.exchangeRate) / 100) * 100;
                },

                get cashTenderedKhr() {
                    if (this.cashCurrency === 'khr') {
                        return Number(this.cashGivenKhr) || 0;
                    }
                    return Math.round(((Number(this.cashGivenUsd) || 0) * this.exchangeRate) / 100) * 100;
                },

                get isUnderpaid() {
                    return this.cashTenderedKhr < this.totalKhr;
                },

                get shortageKhr() {
                    return Math.max(0, this.totalKhr - this.cashTenderedKhr);
                },

                get changeDueKhr() {
                    return Math.max(0, this.cashTenderedKhr - this.totalKhr);
                },

                get changeDueUsd() {
                    return Math.max(0, Math.round((this.changeDueKhr / this.exchangeRate) * 100) / 100);
                },

                get changeDue() {
                    return this.changeDueUsd;
                },

                setExactKhr() {
                    this.cashGivenKhr = this.totalKhr;
                },

                setExactUsd() {
                    this.cashGivenUsd = Math.ceil(this.totalAmount);
                },

                formatNumber(val) {
                    return Number(val || 0).toLocaleString('en-US');
                },

                get qrTimeRemaining() {
                    if (this.khqrRemainingSeconds <= 0) return '0:00';
                    const mins = Math.floor(this.khqrRemainingSeconds / 60);
                    const secs = this.khqrRemainingSeconds % 60;
                    return mins + ':' + (secs < 10 ? '0' : '') + secs;
                },

                init() {
                    this.$watch('paymentMethod', (value) => {
                        if (value === 'khqr' && !this.khqrGenerated) {
                            this.generateKhqr();
                        }
                    });
                },

                startKhqrTimer() {
                    this.khqrTimer = setInterval(() => {
                        if (this.khqrRemainingSeconds > 0) {
                            this.khqrRemainingSeconds--;
                        } else {
                            this.khqrExpired = true;
                            clearInterval(this.khqrTimer);
                        }
                    }, 1000);
                },

                async generateKhqr() {
                    try {
                        const response = await fetch('{{ route("khqr.generate-for-checkout") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({
                                amount: this.totalAmount,
                                order_number: 'TEMP-' + Date.now(),
                            }),
                        });

                        const data = await response.json();
                        if (data.success) {
                            this.khqrQr = data.data.qr_image;
                            this.khqrMd5 = data.data.md5;
                            this.khqrExpiresAt = data.data.expires_at;
                            this.khqrGenerated = true;
                            this.khqrExpired = false;

                            const expiresAt = new Date(data.data.expires_at);
                            this.khqrRemainingSeconds = Math.max(0, Math.floor((expiresAt - new Date()) / 1000));
                            this.startKhqrTimer();
                            this.startKhqrPolling();
                        }
                    } catch (error) {
                        console.error('KHQR generation failed:', error);
                        alert(this.lang === 'km' ? 'បរាជ័យក្នុងការបង្កើត KHQR។ សូមព្យាយាមម្តងទៀត។' : 'Failed to generate KHQR. Please try again.');
                    }
                },

                startKhqrPolling() {
                    this.khqrPollError = false;
                    this.khqrPollFailCount = 0;
                    this.khqrPolling = setInterval(async () => {
                        if (this.khqrExpired || !this.khqrMd5) {
                            clearInterval(this.khqrPolling);
                            return;
                        }

                        try {
                            const response = await fetch('{{ route("khqr.check-status") }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Accept': 'application/json',
                                },
                                body: JSON.stringify({ md5: this.khqrMd5 }),
                            });

                            const data = await response.json();
                            if (data.data && data.data.paid) {
                                clearInterval(this.khqrPolling);
                                this.khqrPollError = false;
                                document.getElementById('checkout-form').submit();
                            } else {
                                this.khqrPollFailCount++;
                                if (this.khqrPollFailCount >= 3) {
                                    this.khqrPollError = true;
                                }
                            }
                        } catch (error) {
                            console.error('Payment check failed:', error);
                            this.khqrPollFailCount++;
                            if (this.khqrPollFailCount >= 2) {
                                this.khqrPollError = true;
                            }
                        }
                    }, 3000);
                },

                regenerateKhqr() {
                    clearInterval(this.khqrPolling);
                    clearInterval(this.khqrTimer);
                    this.khqrGenerated = false;
                    this.khqrQr = '';
                    this.khqrMd5 = '';
                    this.khqrPollError = false;
                    this.khqrPollFailCount = 0;
                    this.generateKhqr();
                },

                placeOrder() {
                    if (this.paymentMethod === 'cash') {
                        if (this.isUnderpaid) {
                            alert(this.lang === 'km' 
                                ? 'ប្រាក់ទទួលនៅខ្វះ ' + this.formatNumber(this.shortageKhr) + ' ៛ មិនទាន់គ្រប់ចំនួននៅឡើយទេ។'
                                : 'Cash received is short by ' + this.formatNumber(this.shortageKhr) + ' KHR.');
                            return;
                        }
                    }
                    if (this.paymentMethod === 'khqr') {
                        if (!this.khqrGenerated) {
                            alert(this.lang === 'km' ? 'សូមបង្កើតកូដ KHQR ជាមុនសិន។' : 'Please generate KHQR code first.');
                            return;
                        }
                        if (this.khqrExpired) {
                            alert(this.lang === 'km' ? 'កូដ KHQR បានផុតកំណត់ហើយ។ សូមបង្កើតឡើងវិញ។' : 'KHQR code has expired. Please regenerate.');
                            return;
                        }
                        const confirmMsg = this.lang === 'km'
                            ? 'តើលោកអ្នកបញ្ជាក់ថាអតិថិជនបានទូទាត់ប្រាក់ KHQR ចំនួន $' + this.totalAmount.toFixed(2) + ' (' + this.formatNumber(this.totalKhr) + ' ៛) រួចរាល់ហើយមែនទេ?'
                            : 'Confirm that the customer has completed the KHQR payment of $' + this.totalAmount.toFixed(2) + ' (' + this.formatNumber(this.totalKhr) + ' KHR)?';
                        if (!confirm(confirmMsg)) {
                            return;
                        }
                    }
                    this.processing = true;
                    document.getElementById('checkout-form').submit();
                }
            };
        }
    </script>
</x-app-layout>

