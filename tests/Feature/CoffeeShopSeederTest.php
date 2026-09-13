<?php

use App\Models\User;
use Database\Seeders\CoffeeShopSeeder;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\DB;

it('seeds the complete coffee shop data model', function () {
    $this->seed(CoffeeShopSeeder::class);

    expect(DB::table('categories')->count())
        ->toBe(3)
        ->and(DB::table('products')->count())
        ->toBe(20)
        ->and(DB::table('product_variants')->count())
        ->toBe(21)
        ->and(DB::table('ingredients')->count())
        ->toBe(4)
        ->and(DB::table('recipes')->count())
        ->toBe(8)
        ->and(DB::table('orders')->count())
        ->toBe(1)
        ->and(DB::table('order_items')->count())
        ->toBe(2);
});

it('can be run repeatedly without duplicating coffee shop data', function () {
    $this->seed(CoffeeShopSeeder::class);
    $this->seed(CoffeeShopSeeder::class);

    expect(DB::table('categories')->count())
        ->toBe(3)
        ->and(DB::table('products')->count())
        ->toBe(20)
        ->and(DB::table('product_variants')->count())
        ->toBe(21)
        ->and(DB::table('ingredients')->count())
        ->toBe(4)
        ->and(DB::table('recipes')->count())
        ->toBe(8)
        ->and(DB::table('orders')->count())
        ->toBe(1)
        ->and(DB::table('order_items')->count())
        ->toBe(2);
});

it('promotes the default user to admin when the database seeder runs', function () {
    $existingUser = User::factory()->create([
        'name' => 'Existing User',
        'email' => 'admin@cafe.com',
        'is_active' => false,
        'email_verified_at' => null,
    ]);

    $this->seed(DatabaseSeeder::class);
    $this->seed(DatabaseSeeder::class);

    $existingUser->refresh();

    expect(User::where('email', 'admin@cafe.com')->count())
        ->toBe(1)
        ->and($existingUser->is_active)
        ->toBeTrue()
        ->and($existingUser->email_verified_at)
        ->not
        ->toBeNull()
        ->and($existingUser->hasRole('admin'))
        ->toBeTrue();
});
