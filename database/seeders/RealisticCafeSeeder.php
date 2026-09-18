<?php

namespace Database\Seeders;

use App\Models\CafeTable;
use App\Models\Ingredient;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\StockMovement;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RealisticCafeSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $adminUser = User::where('email', 'admin@cafe.com')->first() ?? User::first();
            $baristaUser = User::where('email', 'barista@cafe.com')->first() ?? $adminUser;

            if (! $adminUser) {
                return;
            }

            // 1. REALISTIC SPECIALTY CAFE PRICES
            $realisticPrices = [
                'Espresso' => ['Regular' => 1.75],
                'Americano' => ['Regular' => 2.25],
                'Cappuccino' => ['Regular' => 2.75, 'Large' => 3.25],
                'Iced Latte' => ['Regular' => 3.00],
                'Caramel Macchiato' => ['Regular' => 3.75],
                'Vanilla Latte' => ['Regular' => 3.50],
                'Mocha' => ['Regular' => 3.50],
                'Spanish Latte' => ['Regular' => 3.50],
                'Cold Brew' => ['Regular' => 3.00],
                'Brown Coffee' => ['Regular' => 2.25],
                'Coconut Coffee' => ['Regular' => 3.25],
                'Hazelnut Latte' => ['Regular' => 3.50],
                'Green Tea Latte' => ['Regular' => 3.00],
                'Thai Milk Tea' => ['Regular' => 2.50],
                'Matcha Tonic' => ['Regular' => 3.50],
                'Peach Lemon Tea' => ['Regular' => 2.75],
                'Passion Fruit Tea' => ['Regular' => 2.75],
                'Chocolate Frappe' => ['Regular' => 3.50],
                'Butter Croissant' => ['Regular' => 2.50],
                'Blueberry Muffin' => ['Regular' => 2.25],
            ];

            foreach ($realisticPrices as $productName => $variants) {
                $product = Product::whereRaw("name->>'en' = ?", [$productName])->first();
                if (! $product) {
                    continue;
                }

                foreach ($variants as $variantName => $price) {
                    ProductVariant::where('product_id', $product->id)
                        ->whereRaw("name->>'en' = ?", [$variantName])
                        ->update([
                            'price' => $price,
                            'cost_price' => round($price * 0.35, 2),
                        ]);
                }
            }

            // 2. REALISTIC SUPPLIERS
            $suppliersData = [
                [
                    'name' => 'Angkor Coffee Roasters Co., Ltd.',
                    'contact_person' => 'Sokha Pich',
                    'phone' => '012 888 999',
                    'email' => 'sales@angkorcoffee.com',
                    'address' => 'St. 214, Daun Penh, Phnom Penh',
                    'notes' => 'Primary specialty Arabica beans supplier (Mondulkiri & Ratanakiri blend)',
                ],
                [
                    'name' => 'Dairy Fresh Cambodia',
                    'contact_person' => 'Mary Chan',
                    'phone' => '015 333 444',
                    'email' => 'orders@dairyfresh.com.kh',
                    'address' => 'St. 271, Mean Chey, Phnom Penh',
                    'notes' => 'Fresh whole milk and whipping cream delivery daily at 6:00 AM',
                ],
                [
                    'name' => 'Phnom Penh Beverage & Bakery Supplies',
                    'contact_person' => 'Heng Bun',
                    'phone' => '098 777 666',
                    'email' => 'info@ppsyrups.com',
                    'address' => 'St. 1986, Sen Sok, Phnom Penh',
                    'notes' => 'Monin syrups, matcha powder, packaging cups and bakery ingredients',
                ],
            ];

            $supplierMap = [];
            foreach ($suppliersData as $sup) {
                $supplier = Supplier::updateOrCreate(
                    ['name' => $sup['name']],
                    array_merge($sup, ['is_active' => true])
                );
                $supplierMap[$sup['name']] = $supplier->id;
            }

            // 3. ENHANCE INGREDIENTS (TRIGGER LOW STOCK & EXPIRY ALERTS)
            $ingredientsData = [
                [
                    'name' => ['en' => 'Espresso beans', 'km' => 'គ្រាប់កាហ្វេអេសប្រេសសូ'],
                    'sku' => 'ING-ESP-01',
                    'unit' => 'grams',
                    'current_stock' => 3850.00,
                    'reorder_level' => 1000.00,
                    'purchase_cost' => 0.0160,
                    'average_cost' => 0.0160,
                    'supplier_id' => $supplierMap['Angkor Coffee Roasters Co., Ltd.'] ?? null,
                    'expiry_date' => now()->addMonths(8),
                    'batch_number' => 'BATCH-MDK-2026',
                    'notes' => '100% Arabica High-Elevation Medium Roast',
                ],
                [
                    'name' => ['en' => 'Whole milk', 'km' => 'ទឹកដោះគោស្រស់សុទ្ធ'],
                    'sku' => 'ING-MLK-01',
                    'unit' => 'ml',
                    'current_stock' => 2200.00, // < 3000 -> LOW STOCK!
                    'reorder_level' => 3000.00,
                    'purchase_cost' => 0.0018,
                    'average_cost' => 0.0018,
                    'supplier_id' => $supplierMap['Dairy Fresh Cambodia'] ?? null,
                    'expiry_date' => now()->addDays(5),
                    'batch_number' => 'BATCH-DF-0914',
                    'notes' => 'Pasteurized fresh whole milk',
                ],
                [
                    'name' => ['en' => 'Matcha powder', 'km' => 'ម្សៅតែបៃតងម៉ាត់ឆា'],
                    'sku' => 'ING-MAT-01',
                    'unit' => 'grams',
                    'current_stock' => 175.00, // < 200 -> LOW STOCK!
                    'reorder_level' => 200.00,
                    'purchase_cost' => 0.0650,
                    'average_cost' => 0.0650,
                    'supplier_id' => $supplierMap['Phnom Penh Beverage & Bakery Supplies'] ?? null,
                    'expiry_date' => now()->addMonths(6),
                    'batch_number' => 'BATCH-UJI-0822',
                    'notes' => 'Japanese Ceremonial Grade Green Tea',
                ],
                [
                    'name' => ['en' => 'Tonic water', 'km' => 'ទឹកតូនិក'],
                    'sku' => 'ING-TON-01',
                    'unit' => 'ml',
                    'current_stock' => 5400.00,
                    'reorder_level' => 1500.00,
                    'purchase_cost' => 0.0022,
                    'average_cost' => 0.0022,
                    'supplier_id' => $supplierMap['Phnom Penh Beverage & Bakery Supplies'] ?? null,
                    'expiry_date' => now()->addMonths(10),
                    'batch_number' => 'BATCH-TON-0711',
                    'notes' => 'Sparkling botanical tonic water',
                ],
                [
                    'name' => ['en' => 'Caramel syrup', 'km' => 'ស៊ីរ៉ូខារ៉ាមែល'],
                    'sku' => 'ING-CAR-01',
                    'unit' => 'ml',
                    'current_stock' => 320.00, // < 500 -> LOW STOCK!
                    'reorder_level' => 500.00,
                    'purchase_cost' => 0.0125,
                    'average_cost' => 0.0125,
                    'supplier_id' => $supplierMap['Phnom Penh Beverage & Bakery Supplies'] ?? null,
                    'expiry_date' => now()->addMonths(12),
                    'batch_number' => 'BATCH-MON-0512',
                    'notes' => 'Artisanal salted caramel syrup',
                ],
                [
                    'name' => ['en' => 'Coconut milk', 'km' => 'ខ្ទិះដូងស្រស់'],
                    'sku' => 'ING-COC-01',
                    'unit' => 'ml',
                    'current_stock' => 1500.00,
                    'reorder_level' => 800.00,
                    'purchase_cost' => 0.0030,
                    'average_cost' => 0.0030,
                    'supplier_id' => $supplierMap['Dairy Fresh Cambodia'] ?? null,
                    'expiry_date' => now()->addDays(3), // EXPIRING SOON!
                    'batch_number' => 'BATCH-COC-0914',
                    'notes' => 'Fresh cold-pressed coconut milk',
                ],
            ];

            foreach ($ingredientsData as $ingData) {
                $ing = Ingredient::whereRaw("name->>'en' = ?", [$ingData['name']['en']])->first();
                if ($ing) {
                    $ing->update($ingData);
                } else {
                    Ingredient::create($ingData);
                }
            }

            // 4. PURCHASES (GRN)
            if (Purchase::count() === 0 && isset($supplierMap['Angkor Coffee Roasters Co., Ltd.'])) {
                $purchase1 = Purchase::create([
                    'supplier_id' => $supplierMap['Angkor Coffee Roasters Co., Ltd.'],
                    'user_id' => $adminUser->id,
                    'invoice_number' => 'INV-AK-2026-0045',
                    'purchase_date' => now()->subDays(5),
                    'status' => 'received',
                    'total_amount' => 80.00,
                    'notes' => 'Restock 5kg Specialty Espresso Beans',
                ]);

                $beans = Ingredient::where('sku', 'ING-ESP-01')->first();
                if ($beans) {
                    PurchaseItem::create([
                        'purchase_id' => $purchase1->id,
                        'ingredient_id' => $beans->id,
                        'quantity' => 5000,
                        'unit' => 'grams',
                        'unit_cost' => 0.0160,
                        'subtotal' => 80.00,
                        'batch_number' => 'BATCH-MDK-2026',
                        'expiry_date' => now()->addMonths(8),
                    ]);

                    StockMovement::create([
                        'user_id' => $adminUser->id,
                        'stockable_type' => Ingredient::class,
                        'stockable_id' => $beans->id,
                        'type' => 'in',
                        'quantity' => 5000,
                        'unit_cost' => 0.0160,
                        'reason' => 'Purchase Invoice #INV-AK-2026-0045',
                        'notes' => 'Regular coffee beans restocking',
                        'created_at' => now()->subDays(5),
                    ]);
                }
            }

            // 5. CAFE TABLES SEATING STATUS
            $tableStatusPlan = [
                'Table 01' => 'occupied',
                'Table 02' => 'occupied',
                'Bar 01' => 'occupied',
                'Table 06' => 'reserved',
                'Table 03' => 'available',
                'Table 04' => 'available',
                'Table 05' => 'available',
                'Bar 02' => 'available',
            ];

            foreach ($tableStatusPlan as $tableName => $status) {
                CafeTable::where('name', $tableName)->update(['status' => $status]);
            }

            // 6. POPULATE REALISTIC ORDERS & SALES HISTORY
            // Delete previous non-ORD-1001 orders cleanly
            $oldOrderIds = Order::where('order_number', '!=', 'ORD-1001')->pluck('id');
            OrderItem::whereIn('order_id', $oldOrderIds)->delete();
            Order::whereIn('id', $oldOrderIds)->delete();

            $allVariants = ProductVariant::with('product')->get();
            if ($allVariants->isEmpty()) {
                return;
            }

            $table1 = CafeTable::where('name', 'Table 01')->first();
            $table2 = CafeTable::where('name', 'Table 02')->first();
            $tableBar = CafeTable::where('name', 'Bar 01')->first();
            $allTables = CafeTable::all();

            $popularVariantPicks = [
                'Iced Latte',
                'Cappuccino',
                'Caramel Macchiato',
                'Green Tea Latte',
                'Butter Croissant',
                'Americano',
                'Brown Coffee',
                'Chocolate Frappe',
                'Blueberry Muffin',
                'Matcha Tonic',
            ];

            $orderSeq = 1010;

            // Historical orders across the past 6 days
            for ($daysAgo = 6; $daysAgo >= 1; $daysAgo--) {
                $targetDate = now()->subDays($daysAgo)->startOfDay();
                $orderCount = match ($daysAgo) {
                    6 => 14,
                    5 => 18,
                    4 => 16,
                    3 => 24, // Weekend
                    2 => 21,
                    1 => 25, // Yesterday
                };

                for ($i = 0; $i < $orderCount; $i++) {
                    // Spread times between 7:30 AM and 5:00 PM
                    $minutes = (int) ($i * (540 / max($orderCount, 1))) + rand(2, 15);
                    $orderTime = $targetDate->copy()->addHours(7)->addMinutes(30 + $minutes);

                    $orderType = (rand(1, 10) <= 6) ? 'dine_in' : 'takeaway';
                    $table = ($orderType === 'dine_in') ? $allTables->random() : null;

                    $paymentMethod = (rand(1, 10) <= 5) ? 'khqr' : 'cash';
                    $khqrMd5 = ($paymentMethod === 'khqr') ? md5('khqr_'.$orderSeq.'_'.$orderTime->timestamp) : null;

                    $orderNumber = 'ORD-'.$orderTime->format('Ymd').'-'.str_pad((string) $orderSeq, 4, '0', STR_PAD_LEFT);

                    $order = Order::create([
                        'order_number' => $orderNumber,
                        'user_id' => (rand(1, 10) <= 7) ? $baristaUser->id : $adminUser->id,
                        'cafe_table_id' => $table?->id,
                        'order_type' => $orderType,
                        'status' => 'completed',
                        'subtotal' => 0,
                        'tax_amount' => 0.0,
                        'total_amount' => 0,
                        'payment_method' => $paymentMethod,
                        'khqr_md5' => $khqrMd5,
                        'khqr_expires_at' => ($paymentMethod === 'khqr') ? $orderTime->copy()->addMinutes(5) : null,
                        'paid_at' => $orderTime,
                        'created_at' => $orderTime,
                        'updated_at' => $orderTime,
                    ]);

                    $itemsCount = rand(1, 3);
                    $subtotal = 0;

                    for ($k = 0; $k < $itemsCount; $k++) {
                        if (rand(1, 10) <= 7) {
                            $favName = $popularVariantPicks[array_rand($popularVariantPicks)];
                            $variant = $allVariants->first(function ($v) use ($favName) {
                                return ($v->product->name_translations['en'] ?? $v->product->name) === $favName;
                            }) ?? $allVariants->random();
                        } else {
                            $variant = $allVariants->random();
                        }

                        $qty = rand(1, 2);
                        $itemSubtotal = $variant->price * $qty;
                        $subtotal += $itemSubtotal;

                        OrderItem::create([
                            'order_id' => $order->id,
                            'product_variant_id' => $variant->id,
                            'quantity' => $qty,
                            'unit_price' => $variant->price,
                            'subtotal' => $itemSubtotal,
                            'created_at' => $orderTime,
                            'updated_at' => $orderTime,
                        ]);
                    }

                    $order->update([
                        'subtotal' => $subtotal,
                        'total_amount' => $subtotal,
                    ]);

                    $orderSeq++;
                }
            }

            // TODAY'S ORDERS (Up to current time)
            $todayStart = now()->startOfDay()->addHours(6)->addMinutes(30); // 6:30 AM
            $minutesSinceStart = max(now()->diffInMinutes($todayStart), 30);
            $todayOrderCount = max(min((int) ($minutesSinceStart / 4), 22), 8);

            for ($j = 0; $j < $todayOrderCount; $j++) {
                $orderOffset = (int) ($j * ($minutesSinceStart / $todayOrderCount));
                $orderTime = $todayStart->copy()->addMinutes($orderOffset);

                // Ensure it's never in the future
                if ($orderTime->isFuture()) {
                    $orderTime = now()->subMinutes(rand(1, 5));
                }

                $orderType = (rand(1, 10) <= 6) ? 'dine_in' : 'takeaway';
                $table = ($orderType === 'dine_in') ? $allTables->random() : null;

                $paymentMethod = (rand(1, 10) <= 5) ? 'khqr' : 'cash';
                $khqrMd5 = ($paymentMethod === 'khqr') ? md5('khqr_'.$orderSeq.'_'.$orderTime->timestamp) : null;

                $status = 'completed';
                if ($j >= ($todayOrderCount - 3) && $orderType === 'dine_in') {
                    $status = ($j % 2 === 0) ? 'preparing' : 'ready';
                }

                $orderNumber = 'ORD-'.$orderTime->format('Ymd').'-'.str_pad((string) $orderSeq, 4, '0', STR_PAD_LEFT);

                $order = Order::create([
                    'order_number' => $orderNumber,
                    'user_id' => (rand(1, 10) <= 7) ? $baristaUser->id : $adminUser->id,
                    'cafe_table_id' => $table?->id,
                    'order_type' => $orderType,
                    'status' => $status,
                    'subtotal' => 0,
                    'tax_amount' => 0.0,
                    'total_amount' => 0,
                    'payment_method' => $paymentMethod,
                    'khqr_md5' => $khqrMd5,
                    'khqr_expires_at' => ($paymentMethod === 'khqr') ? $orderTime->copy()->addMinutes(5) : null,
                    'paid_at' => ($paymentMethod === 'khqr' || $status === 'completed') ? $orderTime : null,
                    'created_at' => $orderTime,
                    'updated_at' => $orderTime,
                ]);

                $itemsCount = rand(1, 3);
                $subtotal = 0;

                for ($k = 0; $k < $itemsCount; $k++) {
                    if (rand(1, 10) <= 7) {
                        $favName = $popularVariantPicks[array_rand($popularVariantPicks)];
                        $variant = $allVariants->first(function ($v) use ($favName) {
                            return ($v->product->name_translations['en'] ?? $v->product->name) === $favName;
                        }) ?? $allVariants->random();
                    } else {
                        $variant = $allVariants->random();
                    }

                    $qty = rand(1, 2);
                    $itemSubtotal = $variant->price * $qty;
                    $subtotal += $itemSubtotal;

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_variant_id' => $variant->id,
                        'quantity' => $qty,
                        'unit_price' => $variant->price,
                        'subtotal' => $itemSubtotal,
                        'created_at' => $orderTime,
                        'updated_at' => $orderTime,
                    ]);
                }

                $order->update([
                    'subtotal' => $subtotal,
                    'total_amount' => $subtotal,
                ]);

                $orderSeq++;
            }

            // Link occupied tables to today's active dine-in orders
            $activeOrders = Order::where('order_type', 'dine_in')
                ->where('created_at', '>=', now()->startOfDay())
                ->latest()
                ->take(3)
                ->get();

            if ($activeOrders->count() >= 3 && $table1 && $table2 && $tableBar) {
                $activeOrders[0]->update(['cafe_table_id' => $table1->id, 'status' => 'preparing']);
                $activeOrders[1]->update(['cafe_table_id' => $table2->id, 'status' => 'ready']);
                $activeOrders[2]->update(['cafe_table_id' => $tableBar->id, 'status' => 'preparing']);
            }
        });
    }
}
