<?php

namespace App\Http\Controllers;

use App\Models\CafeTable;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use App\Models\Setting;
use App\Services\InventoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function index(Request $request): View
    {
        $cart = $request->input('cart', []);
        $orderType = $request->input('order_type', 'dine_in');
        $items = [];
        $subtotal = 0;

        $variantIds = array_column($cart, 'id');
        $variants = ProductVariant::with('product')->whereIn('id', $variantIds)->get()->keyBy('id');

        foreach ($cart as $item) {
            $variant = $variants->get($item['id']);
            if (! $variant) {
                continue;
            }

            $lineTotal = $variant->price * $item['quantity'];
            $subtotal += $lineTotal;

            $prodTrans = $variant->product->name_translations ?? [];
            $enProdName = $prodTrans['en'] ?? $variant->product->name;
            $kmProdName = $prodTrans['km'] ?? $enProdName;

            $varTrans = $variant->name_translations ?? [];
            $rawVarName = is_string($variant->name) ? $variant->name : ($varTrans['en'] ?? 'Regular');

            $enVarName = match ($rawVarName) {
                'ធម្មតា' => 'Regular',
                'ធំ' => 'Large',
                default => $varTrans['en'] ?? $rawVarName,
            };
            $kmVarName = match ($rawVarName) {
                'Regular' => 'ធម្មតា',
                'Large' => 'ធំ',
                default => $varTrans['km'] ?? ($varTrans['en'] ?? $rawVarName),
            };

            $items[] = [
                'variant' => $variant,
                'product_name_en' => $enProdName,
                'product_name_km' => $kmProdName,
                'variant_name_en' => $enVarName,
                'variant_name_km' => $kmVarName,
                'quantity' => $item['quantity'],
                'unit_price' => $variant->price,
                'subtotal' => $lineTotal,
            ];
        }

        $total = $subtotal;
        $tables = CafeTable::where('is_active', true)->orderBy('name')->get();

        $exchangeRate = (int) Setting::get('exchange_rate_khr', 4100);
        $cashEnabled = (bool) Setting::get('payment_cash_enabled', true);
        $khqrEnabled = (bool) Setting::get('payment_khqr_enabled', true);
        $cashDefaultCurrency = (string) Setting::get('default_currency', 'khr');

        return view('pos.checkout', [
            'items' => $items,
            'subtotal' => $subtotal,
            'total' => $total,
            'order_type' => $orderType,
            'tables' => $tables,
            'exchangeRate' => $exchangeRate,
            'cashEnabled' => $cashEnabled,
            'khqrEnabled' => $khqrEnabled,
            'cashDefaultCurrency' => $cashDefaultCurrency,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'order_type' => ['required', 'string', 'in:dine_in,takeaway'],
            'cafe_table_id' => ['nullable', 'exists:cafe_tables,id'],
            'payment_method' => ['required', 'string', 'in:cash,khqr'],
            'khqr_md5' => ['required_if:payment_method,khqr', 'nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.variant_id' => ['required', 'exists:product_variants,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ]);

        $subtotal = 0;

        $order = DB::transaction(function () use ($validated, &$subtotal) {
            foreach ($validated['items'] as &$item) {
                $variant = ProductVariant::findOrFail($item['variant_id']);
                $item['unit_price'] = $variant->price;
                $item['subtotal'] = $variant->price * $item['quantity'];
                $subtotal += $item['subtotal'];
            }
            unset($item);

            $total = $subtotal;

            $tableId = ($validated['order_type'] === 'dine_in') ? ($validated['cafe_table_id'] ?? null) : null;

            $paymentMethod = $validated['payment_method'] ?? 'cash';
            $orderStatus = $paymentMethod === 'khqr' ? 'completed' : 'pending';

            $order = Order::create([
                'order_number' => $this->generateOrderNumber(),
                'user_id' => auth()->id(),
                'cafe_table_id' => $tableId,
                'order_type' => $validated['order_type'],
                'status' => $orderStatus,
                'subtotal' => $subtotal,
                'tax_amount' => 0.0,
                'total_amount' => $total,
                'payment_method' => $paymentMethod,
                'khqr_md5' => $validated['khqr_md5'] ?? null,
                'paid_at' => $paymentMethod === 'khqr' ? now() : null,
            ]);

            if ($tableId) {
                CafeTable::where('id', $tableId)->update(['status' => 'occupied']);
            }

            foreach ($validated['items'] as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_variant_id' => $item['variant_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $item['subtotal'],
                ]);
            }

            // Deduct inventory atomically through central InventoryService (Step 8, 9 & 10)
            app(InventoryService::class)->deductForOrder($order);

            return $order;
        });

        return redirect()->route('pos.receipt', $order);
    }

    public function receipt(Order $order): View
    {
        abort_unless(
            auth()->id() === $order->user_id || auth()->user()->hasRole('admin'),
            403
        );

        $order->load('items.productVariant.product', 'user', 'cafeTable');

        return view('pos.receipt', [
            'order' => $order,
        ]);
    }

    private function generateOrderNumber(): string
    {
        $today = now()->format('Ymd');
        $lastOrder = Order::where('order_number', 'like', "ORD-{$today}-%")
            ->orderByDesc('order_number')
            ->first();

        if ($lastOrder) {
            $lastNumber = (int) substr($lastOrder->order_number, -4);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return 'ORD-'.$today.'-'.str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
