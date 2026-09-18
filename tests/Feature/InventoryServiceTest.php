<?php

use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Recipe;
use App\Models\Supplier;
use App\Models\User;
use App\Services\InventoryService;
use Database\Seeders\RoleAndPermissionSeeder;

beforeEach(function () {
    $this->seed(RoleAndPermissionSeeder::class);
});

it('safely converts compatible units', function () {
    $service = app(InventoryService::class);

    expect($service->convertUnits(1.5, 'kg', 'g'))->toBe(1500.0);
    expect($service->convertUnits(500, 'g', 'kg'))->toBe(0.5);
    expect($service->convertUnits(2.0, 'L', 'ml'))->toBe(2000.0);
    expect($service->convertUnits(250, 'ml', 'L'))->toBe(0.25);
    expect($service->convertUnits(10, 'pcs', 'pcs'))->toBe(10.0);
});

it('throws exception on incompatible unit conversions', function () {
    $service = app(InventoryService::class);

    $service->convertUnits(100, 'kg', 'ml');
})->throws(InvalidArgumentException::class);

it('calculates recipe cost, selling price and gross margin percentage', function () {
    $service = app(InventoryService::class);

    $beans = Ingredient::create([
        'name' => ['en' => 'Espresso Beans'],
        'unit' => 'grams',
        'current_stock' => 5000,
        'reorder_level' => 1000,
        'purchase_cost' => 0.02, // $0.02 per gram
        'average_cost' => 0.02,
    ]);

    $milk = Ingredient::create([
        'name' => ['en' => 'Fresh Milk'],
        'unit' => 'ml',
        'current_stock' => 10000,
        'reorder_level' => 2000,
        'purchase_cost' => 0.002, // $0.002 per ml
        'average_cost' => 0.002,
    ]);

    $category = Category::create([
        'name' => ['en' => 'Drinks'],
        'slug' => 'drinks-'.uniqid(),
        'is_active' => true,
    ]);

    $product = Product::create([
        'category_id' => $category->id,
        'name' => ['en' => 'Latte Test'],
        'is_active' => true,
    ]);

    $variant = ProductVariant::create([
        'product_id' => $product->id,
        'name' => 'Regular',
        'price' => 3.00,
        'cost_price' => 0.66,
    ]);

    // 18g beans = $0.36, 150ml milk = $0.30 -> Total Cost = $0.66
    Recipe::create(['product_variant_id' => $variant->id, 'ingredient_id' => $beans->id, 'quantity_required' => 18]);
    Recipe::create(['product_variant_id' => $variant->id, 'ingredient_id' => $milk->id, 'quantity_required' => 150]);

    $costData = $service->calculateRecipeCost($variant);

    expect($costData['recipe_cost'])->toBe(0.66);
    expect($costData['selling_price'])->toBe(3.00);
    expect($costData['gross_margin'])->toBe(2.34);
    expect($costData['margin_percentage'])->toBe(78.0);
});

it('deducts inventory on order with duplicate prevention guarantee', function () {
    $service = app(InventoryService::class);

    $admin = User::factory()->create(['email_verified_at' => now()]);
    $beans = Ingredient::create([
        'name' => ['en' => 'Beans Test'],
        'unit' => 'grams',
        'current_stock' => 1000,
        'reorder_level' => 200,
    ]);

    $category = Category::create([
        'name' => ['en' => 'Coffee'],
        'slug' => 'coffee-'.uniqid(),
        'is_active' => true,
    ]);

    $product = Product::create([
        'category_id' => $category->id,
        'name' => ['en' => 'Espresso Test'],
        'is_active' => true,
    ]);

    $variant = ProductVariant::create([
        'product_id' => $product->id,
        'name' => 'Hot',
        'price' => 2.50,
        'cost_price' => 0.50,
    ]);

    Recipe::create([
        'product_variant_id' => $variant->id,
        'ingredient_id' => $beans->id,
        'quantity_required' => 20,
    ]);

    $order = Order::create([
        'order_number' => 'ORD-TEST-001',
        'user_id' => $admin->id,
        'order_type' => 'takeaway',
        'status' => 'completed',
        'subtotal' => 5.00,
        'total_amount' => 5.00,
    ]);

    OrderItem::create([
        'order_id' => $order->id,
        'product_variant_id' => $variant->id,
        'quantity' => 2, // 2 x 20g = 40g
        'unit_price' => 2.50,
        'subtotal' => 5.00,
    ]);

    // 1st Deduction
    $deductedFirst = $service->deductForOrder($order);
    expect($deductedFirst)->toBeTrue();
    expect((float) $beans->fresh()->current_stock)->toBe(960.0);
    expect($order->fresh()->inventory_deducted_at)->not->toBeNull();

    // 2nd Deduction attempt on same order (Duplicate Protection Step 9)
    $deductedSecond = $service->deductForOrder($order);
    expect($deductedSecond)->toBeFalse();
    expect((float) $beans->fresh()->current_stock)->toBe(960.0); // Remains unchanged!
});

it('receives purchase orders and updates moving average cost and stock', function () {
    $service = app(InventoryService::class);
    $admin = User::factory()->create(['email_verified_at' => now()]);

    $supplier = Supplier::create([
        'name' => 'Angkor Beans Co',
        'phone' => '012999888',
    ]);

    $ingredient = Ingredient::create([
        'name' => ['en' => 'Colombian Beans'],
        'unit' => 'grams',
        'current_stock' => 1000,
        'purchase_cost' => 0.01,
        'average_cost' => 0.01, // 1000g @ $0.01 = $10.00
    ]);

    $purchase = Purchase::create([
        'supplier_id' => $supplier->id,
        'user_id' => $admin->id,
        'invoice_number' => 'INV-TEST-99',
        'purchase_date' => now(),
        'status' => 'pending',
        'total_amount' => 30.00,
    ]);

    // Receive 1000g @ $0.02 ($20.00)
    // New Stock = 2000g. Total Value = $10 + $20 = $30 -> New Avg Cost = $0.015 / g
    PurchaseItem::create([
        'purchase_id' => $purchase->id,
        'ingredient_id' => $ingredient->id,
        'quantity' => 1000,
        'unit' => 'grams',
        'unit_cost' => 0.02,
        'subtotal' => 20.00,
        'batch_number' => 'BATCH-COL-1',
        'expiry_date' => now()->addMonths(6)->toDateString(),
    ]);

    $service->receivePurchase($purchase);

    $freshIngredient = $ingredient->fresh();
    expect((float) $freshIngredient->current_stock)->toBe(2000.0);
    expect((float) $freshIngredient->average_cost)->toBe(0.02); // 0.015 rounded to 0.02
    expect((float) $freshIngredient->purchase_cost)->toBe(0.02);
    expect($freshIngredient->batch_number)->toBe('BATCH-COL-1');
    expect($purchase->fresh()->status)->toBe('received');
});

it('allows admin to manage recipes, suppliers, purchases and view reports', function () {
    $admin = User::factory()->create(['email_verified_at' => now()]);
    $admin->assignRole('admin');

    $response = $this->actingAs($admin)->get(route('admin.recipes.index'));
    $response->assertOk();

    $response = $this->actingAs($admin)->get(route('admin.suppliers.index'));
    $response->assertOk();

    $response = $this->actingAs($admin)->get(route('admin.purchases.index'));
    $response->assertOk();

    $response = $this->actingAs($admin)->get(route('admin.stock.reports'));
    $response->assertOk();
    $response->assertSee('Inventory Reports');
});

it('correctly detects expired and expiring soon ingredients', function () {
    $expired = Ingredient::create([
        'name' => ['en' => 'Expired Milk'],
        'unit' => 'ml',
        'current_stock' => 500,
        'reorder_level' => 100,
        'purchase_cost' => 1.50,
        'average_cost' => 1.50,
        'expiry_date' => now()->subDay(),
    ]);

    $expiringSoon = Ingredient::create([
        'name' => ['en' => 'Expiring Milk'],
        'unit' => 'ml',
        'current_stock' => 500,
        'reorder_level' => 100,
        'purchase_cost' => 1.50,
        'average_cost' => 1.50,
        'expiry_date' => now()->addDays(3),
    ]);

    $fresh = Ingredient::create([
        'name' => ['en' => 'Fresh Milk'],
        'unit' => 'ml',
        'current_stock' => 500,
        'reorder_level' => 100,
        'purchase_cost' => 1.50,
        'average_cost' => 1.50,
        'expiry_date' => now()->addMonth(),
    ]);

    expect($expired->isExpired())->toBeTrue();
    expect($expired->isExpiringSoon())->toBeFalse();

    expect($expiringSoon->isExpired())->toBeFalse();
    expect($expiringSoon->isExpiringSoon())->toBeTrue();

    expect($fresh->isExpired())->toBeFalse();
    expect($fresh->isExpiringSoon())->toBeFalse();
});
