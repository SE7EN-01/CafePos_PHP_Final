<?php

use App\Models\CafeTable;
use App\Models\Order;
use App\Models\ProductVariant;
use App\Models\User;
use Database\Seeders\CoffeeShopSeeder;
use Database\Seeders\RoleAndPermissionSeeder;

beforeEach(function () {
    $this->seed(RoleAndPermissionSeeder::class);
});

it('allows admin to view tables list', function () {
    $admin = User::factory()->create(['email_verified_at' => now()]);
    $admin->assignRole('admin');

    CafeTable::create([
        'name' => 'Table VIP 1',
        'capacity' => 4,
        'location' => 'VIP Lounge',
        'status' => 'available',
        'is_active' => true,
    ]);

    $response = $this->actingAs($admin)->get(route('admin.tables.index'));

    $response->assertOk();
    $response->assertSee('Table VIP 1');
    $response->assertSee('VIP Lounge');
});

it('allows admin to create a new cafe table', function () {
    $admin = User::factory()->create(['email_verified_at' => now()]);
    $admin->assignRole('admin');

    $response = $this->actingAs($admin)->post(route('admin.tables.store'), [
        'name' => 'Table Rooftop',
        'capacity' => 6,
        'location' => 'Rooftop Terrace',
        'status' => 'available',
        'is_active' => '1',
    ]);

    $response->assertRedirect(route('admin.tables.index'));
    $this->assertDatabaseHas('cafe_tables', [
        'name' => 'Table Rooftop',
        'capacity' => 6,
        'location' => 'Rooftop Terrace',
    ]);
});

it('allows admin to update a cafe table', function () {
    $admin = User::factory()->create(['email_verified_at' => now()]);
    $admin->assignRole('admin');

    $table = CafeTable::create([
        'name' => 'Table 10',
        'capacity' => 2,
        'location' => 'Corner',
        'status' => 'available',
        'is_active' => true,
    ]);

    $response = $this->actingAs($admin)->patch(route('admin.tables.update', $table), [
        'name' => 'Table 10 Updated',
        'capacity' => 4,
        'location' => 'Window Corner',
        'status' => 'occupied',
        'is_active' => '1',
    ]);

    $response->assertRedirect(route('admin.tables.index'));
    $this->assertDatabaseHas('cafe_tables', [
        'id' => $table->id,
        'name' => 'Table 10 Updated',
        'capacity' => 4,
        'status' => 'occupied',
    ]);
});

it('allows admin to toggle table status', function () {
    $admin = User::factory()->create(['email_verified_at' => now()]);
    $admin->assignRole('admin');

    $table = CafeTable::create([
        'name' => 'Table 20',
        'capacity' => 2,
        'status' => 'available',
        'is_active' => true,
    ]);

    $response = $this->actingAs($admin)->patch(route('admin.tables.status', $table), [
        'status' => 'occupied',
    ]);

    $response->assertSessionHas('status', 'table-status-updated');
    expect($table->fresh()->status)->toBe('occupied');
});

it('assigns table on dine in pos checkout and marks table occupied', function () {
    $this->seed(CoffeeShopSeeder::class);

    $staff = User::factory()->create(['email_verified_at' => now()]);
    $variant = ProductVariant::first();

    $table = CafeTable::create([
        'name' => 'Table Dine Test',
        'capacity' => 4,
        'location' => 'Main Floor',
        'status' => 'available',
        'is_active' => true,
    ]);

    $response = $this->actingAs($staff)->post(route('pos.checkout.store'), [
        'order_type' => 'dine_in',
        'cafe_table_id' => $table->id,
        'payment_method' => 'cash',
        'items' => [
            [
                'variant_id' => $variant->id,
                'quantity' => 1,
            ],
        ],
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect();
    $this->assertDatabaseHas('orders', [
        'order_type' => 'dine_in',
        'cafe_table_id' => $table->id,
    ]);

    expect($table->fresh()->status)->toBe('occupied');
});

it('frees occupied table when order is completed by admin', function () {
    $admin = User::factory()->create(['email_verified_at' => now()]);
    $admin->assignRole('admin');

    $table = CafeTable::create([
        'name' => 'Table Complete Test',
        'capacity' => 4,
        'status' => 'occupied',
        'is_active' => true,
    ]);

    $order = Order::create([
        'order_number' => 'ORD-TEST-99',
        'user_id' => $admin->id,
        'cafe_table_id' => $table->id,
        'order_type' => 'dine_in',
        'status' => 'pending',
        'subtotal' => 10,
        'tax_amount' => 1,
        'total_amount' => 11,
    ]);

    $response = $this->actingAs($admin)->patch(route('admin.orders.update-status', $order), [
        'status' => 'completed',
    ]);

    $response->assertRedirect(route('admin.orders.show', $order));
    expect($order->fresh()->status)->toBe('completed')
        ->and($table->fresh()->status)->toBe('available');
});
