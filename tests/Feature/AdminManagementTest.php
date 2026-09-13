<?php

use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Product;
use App\Models\User;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
});

it('correctly loads bilingual translations on category edit form', function () {
    $admin = User::factory()->create(['email_verified_at' => now()]);
    $admin->assignRole('admin');

    $category = Category::create([
        'name' => ['en' => 'Specialty Coffee', 'km' => 'កាហ្វេពិសេស'],
        'slug' => 'specialty-coffee',
        'is_active' => true,
    ]);

    $response = $this->actingAs($admin)->get(route('admin.categories.edit', $category));

    $response->assertOk();
    $response->assertSee('Specialty Coffee');
    $response->assertSee('កាហ្វេពិសេស');
});

it('correctly loads bilingual translations on product edit form', function () {
    $admin = User::factory()->create(['email_verified_at' => now()]);
    $admin->assignRole('admin');

    $category = Category::create([
        'name' => ['en' => 'Coffee', 'km' => 'កាហ្វេ'],
        'slug' => 'coffee',
        'is_active' => true,
    ]);

    $product = Product::create([
        'category_id' => $category->id,
        'name' => ['en' => 'Caramel Macchiato', 'km' => 'ខារ៉ាមែល ម៉ាគីអាតូ'],
        'description' => ['en' => 'Rich espresso with vanilla syrup', 'km' => 'កាហ្វេរសជាតិទឹកដោះគោ និងខារ៉ាមែល'],
        'is_active' => true,
    ]);

    $response = $this->actingAs($admin)->get(route('admin.products.edit', $product));

    $response->assertOk();
    $response->assertSee('Caramel Macchiato');
    $response->assertSee('ខារ៉ាមែល ម៉ាគីអាតូ');
    $response->assertSee('Rich espresso with vanilla syrup');
});

it('correctly loads bilingual translations on ingredient edit form', function () {
    $admin = User::factory()->create(['email_verified_at' => now()]);
    $admin->assignRole('admin');

    $ingredient = Ingredient::create([
        'name' => ['en' => 'Caramel Syrup', 'km' => 'ស៊ីរ៉ូខារ៉ាមែល'],
        'unit' => 'ml',
        'current_stock' => 500,
        'reorder_level' => 100,
    ]);

    $response = $this->actingAs($admin)->get(route('admin.ingredients.edit', $ingredient));

    $response->assertOk();
    $response->assertSee('Caramel Syrup');
    $response->assertSee('ស៊ីរ៉ូខារ៉ាមែល');
});
