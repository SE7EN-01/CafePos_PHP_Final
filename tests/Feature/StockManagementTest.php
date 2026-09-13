<?php

use App\Models\Ingredient;
use App\Models\ProductVariant;
use App\Models\Recipe;
use App\Models\User;
use Database\Seeders\CoffeeShopSeeder;
use Database\Seeders\RoleAndPermissionSeeder;

beforeEach(function () {
    $this->seed(RoleAndPermissionSeeder::class);
});

it('allows admin to view stock dashboard', function () {
    $admin = User::factory()->create(['email_verified_at' => now()]);
    $admin->assignRole('admin');

    Ingredient::create([
        'name' => ['en' => 'Arabica Beans', 'km' => 'គ្រាប់កាហ្វេ'],
        'unit' => 'grams',
        'current_stock' => 5000,
        'reorder_level' => 1000,
    ]);

    $response = $this->actingAs($admin)->get(route('admin.stock.index'));

    $response->assertOk();
    $response->assertSee('Stock &amp; Inventory', false);
    $response->assertSee('Arabica Beans');
});

it('prevents barista from accessing stock management', function () {
    $barista = User::factory()->create(['email_verified_at' => now()]);
    $barista->assignRole('barista');

    $response = $this->actingAs($barista)->get(route('admin.stock.index'));

    $response->assertForbidden();
});

it('allows admin to receive stock (stock in) for raw ingredients', function () {
    $admin = User::factory()->create(['email_verified_at' => now()]);
    $admin->assignRole('admin');

    $ingredient = Ingredient::create([
        'name' => ['en' => 'Oat Milk', 'km' => 'ទឹកដោះគោ'],
        'unit' => 'ml',
        'current_stock' => 1000,
        'reorder_level' => 500,
    ]);

    $response = $this->actingAs($admin)->post(route('admin.stock.in'), [
        'item_type' => 'ingredient',
        'item_id' => $ingredient->id,
        'quantity' => 2000,
        'unit_cost' => 3.50,
        'reason' => 'Fresh Delivery',
        'notes' => 'Batch #A1',
    ]);

    $response->assertSessionHas('status', 'stock-received');
    $response->assertRedirect(route('admin.stock.index'));

    expect((float) $ingredient->fresh()->current_stock)->toBe(3000.0);

    $this->assertDatabaseHas('stock_movements', [
        'stockable_type' => Ingredient::class,
        'stockable_id' => $ingredient->id,
        'type' => 'in',
        'quantity' => 2000,
        'reason' => 'Fresh Delivery',
    ]);
});

it('allows admin to record waste and adjusts stock', function () {
    $admin = User::factory()->create(['email_verified_at' => now()]);
    $admin->assignRole('admin');

    $ingredient = Ingredient::create([
        'name' => ['en' => 'Whole Milk', 'km' => 'ទឹកដោះគោស្រស់'],
        'unit' => 'ml',
        'current_stock' => 5000,
        'reorder_level' => 1000,
    ]);

    $response = $this->actingAs($admin)->post(route('admin.stock.adjust'), [
        'item_type' => 'ingredient',
        'item_id' => $ingredient->id,
        'adjustment_type' => 'waste',
        'quantity' => 300,
        'reason' => 'Dropped carton on floor',
    ]);

    $response->assertSessionHas('status', 'stock-adjusted');
    expect((float) $ingredient->fresh()->current_stock)->toBe(4700.0);

    $this->assertDatabaseHas('stock_movements', [
        'stockable_type' => Ingredient::class,
        'stockable_id' => $ingredient->id,
        'type' => 'waste',
        'quantity' => -300,
        'reason' => 'Dropped carton on floor',
    ]);
});

it('allows admin to recount stock and update balance', function () {
    $admin = User::factory()->create(['email_verified_at' => now()]);
    $admin->assignRole('admin');

    $ingredient = Ingredient::create([
        'name' => ['en' => 'Vanilla Syrup', 'km' => 'ស៊ីរ៉ូ'],
        'unit' => 'ml',
        'current_stock' => 1200,
        'reorder_level' => 200,
    ]);

    $response = $this->actingAs($admin)->post(route('admin.stock.adjust'), [
        'item_type' => 'ingredient',
        'item_id' => $ingredient->id,
        'adjustment_type' => 'recount',
        'quantity' => 1150,
        'reason' => 'End of month stocktake',
    ]);

    $response->assertSessionHas('status', 'stock-adjusted');
    expect((float) $ingredient->fresh()->current_stock)->toBe(1150.0);

    $this->assertDatabaseHas('stock_movements', [
        'stockable_type' => Ingredient::class,
        'stockable_id' => $ingredient->id,
        'type' => 'adjustment',
        'quantity' => -50,
        'reason' => 'End of month stocktake',
    ]);
});

it('automatically deducts ingredient stock on pos order with recipe', function () {
    $this->seed(CoffeeShopSeeder::class);

    $staff = User::factory()->create(['email_verified_at' => now()]);
    $staff->assignRole('barista');

    // Find or create variant with recipe
    $variant = ProductVariant::whereHas('recipes')->first();
    expect($variant)->not->toBeNull();

    $recipe = $variant->recipes->first();
    $ingredient = $recipe->ingredient;
    $initialStock = (float) $ingredient->current_stock;
    $qtyOrdered = 2;
    $expectedDeduction = (float) $recipe->quantity_required * $qtyOrdered;

    $response = $this->actingAs($staff)->post(route('pos.checkout.store'), [
        'order_type' => 'takeaway',
        'payment_method' => 'cash',
        'items' => [
            [
                'variant_id' => $variant->id,
                'quantity' => $qtyOrdered,
            ],
        ],
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect();

    expect((float) $ingredient->fresh()->current_stock)->toBe($initialStock - $expectedDeduction);

    $this->assertDatabaseHas('stock_movements', [
        'stockable_type' => Ingredient::class,
        'stockable_id' => $ingredient->id,
        'type' => 'sale',
        'quantity' => -$expectedDeduction,
    ]);
});
