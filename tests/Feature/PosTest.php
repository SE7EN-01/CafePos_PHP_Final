<?php

use App\Models\User;
use Database\Seeders\CoffeeShopSeeder;

it('renders the POS register for authenticated staff', function () {
    $this->seed(CoffeeShopSeeder::class);

    $user = User::factory()->create(['email_verified_at' => now()]);

    $response = $this->actingAs($user)->get(route('pos.index'));

    $response->assertOk();
    $response->assertSee('Register');
    $response->assertSee('Cappuccino');
    $response->assertSee('Checkout');
});

it('requires authentication to access the POS register', function () {
    $response = $this->get(route('pos.index'));

    $response->assertRedirect(route('login'));
});

it('loads products from database into POS', function () {
    $this->seed(CoffeeShopSeeder::class);

    $user = User::factory()->create(['email_verified_at' => now()]);

    $response = $this->actingAs($user)->get(route('pos.index'));

    $response->assertOk();
    $response->assertSee('Espresso');
});

it('shows categories from database in POS', function () {
    $this->seed(CoffeeShopSeeder::class);

    $user = User::factory()->create(['email_verified_at' => now()]);

    $response = $this->actingAs($user)->get(route('pos.index'));

    $response->assertOk();
    $response->assertSee('All items');
});

it('supports english and khmer bilingual data in POS register', function () {
    $this->seed(CoffeeShopSeeder::class);

    $user = User::factory()->create(['email_verified_at' => now()]);

    $response = $this->actingAs($user)->get(route('pos.index'));

    $response->assertOk();
    $response->assertSee('English');
    $response->assertSee('ខ្មែរ');
    $response->assertSee('កាពូឈីណូ');
    $response->assertSee('Cappuccino');
});
