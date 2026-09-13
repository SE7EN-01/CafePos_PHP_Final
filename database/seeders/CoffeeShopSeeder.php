<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CoffeeShopSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $timestamp = now();
            $categoryIds = [];

            foreach ([
                ['name' => ['en' => 'Coffee', 'km' => 'កាហ្វេ'], 'slug' => 'coffee'],
                ['name' => ['en' => 'Tea', 'km' => 'តែ'], 'slug' => 'tea'],
                ['name' => ['en' => 'Pastries', 'km' => 'នំ'], 'slug' => 'pastries'],
            ] as $category) {
                DB::table('categories')->updateOrInsert(
                    ['slug' => $category['slug']],
                    ['name' => $this->localizedName($category['name']), 'is_active' => true, 'updated_at' => $timestamp, 'created_at' => $timestamp],
                );

                $categoryIds[$category['slug']] = (int) DB::table('categories')->where('slug', $category['slug'])->value('id');
            }

            $productIds = [];
            $products = [
                ['name' => ['en' => 'Cappuccino', 'km' => 'កាពូឈីណូ'], 'category' => 'coffee', 'description' => ['en' => 'Double espresso with steamed milk and foam', 'km' => 'កាហ្វេអេសប្រេសសូជាមួយពពុះទឹកដោះគោ']],
                ['name' => ['en' => 'Americano', 'km' => 'អាមេរិកាណូ'], 'category' => 'coffee', 'description' => ['en' => 'Rich espresso diluted with hot or iced water', 'km' => 'កាហ្វេអេសប្រេសសូទឹកក្តៅ ឬទឹកកក']],
                ['name' => ['en' => 'Espresso', 'km' => 'អេសប្រេសសូ'], 'category' => 'coffee', 'description' => ['en' => 'Pure concentrated intense espresso shot', 'km' => 'កាហ្វេអេសប្រេសសូសុទ្ធឈ្ងុយឆ្ងាញ់']],
                ['name' => ['en' => 'Iced Latte', 'km' => 'ឡាតេទឹកកក'], 'category' => 'coffee', 'description' => ['en' => 'Smooth espresso with chilled milk and house syrup', 'km' => 'កាហ្វេឡាតេទឹកកកជាមួយទឹកដោះគោស្រស់']],
                ['name' => ['en' => 'Caramel Macchiato', 'km' => 'ខារ៉ាមែល ម៉ាគីអាតូ'], 'category' => 'coffee', 'description' => ['en' => 'Fresh milk, espresso and creamy caramel drizzle', 'km' => 'កាហ្វេទឹកដោះគោជាមួយស្រទាប់ខារ៉ាមែល']],
                ['name' => ['en' => 'Vanilla Latte', 'km' => 'វ៉ានីឡា ឡាតេ'], 'category' => 'coffee', 'description' => ['en' => 'Espresso with steamed milk and vanilla flavor', 'km' => 'កាហ្វេឡាតេក្លិនវ៉ានីឡាឈ្ងុយឆ្ងាញ់']],
                ['name' => ['en' => 'Mocha', 'km' => 'កាហ្វេម៉ូកា'], 'category' => 'coffee', 'description' => ['en' => 'Espresso blended with rich chocolate and milk', 'km' => 'កាហ្វេរសជាតិសូកូឡានិងទឹកដោះគោ']],
                ['name' => ['en' => 'Spanish Latte', 'km' => 'ស្ប៉ានីស ឡាតេ'], 'category' => 'coffee', 'description' => ['en' => 'Sweet espresso made with condensed milk', 'km' => 'កាហ្វេឡាតេទឹកដោះគោខាប់ផ្អែមឈ្ងុយ']],
                ['name' => ['en' => 'Cold Brew', 'km' => 'កាហ្វេត្រជាក់ Cold Brew'], 'category' => 'coffee', 'description' => ['en' => 'Slow-steeped smooth and low-acid cold coffee', 'km' => 'កាហ្វេត្រាំត្រជាក់រសជាតិស្រទន់']],
                ['name' => ['en' => 'Brown Coffee', 'km' => 'កាហ្វេទឹកដោះគោខាប់'], 'category' => 'coffee', 'description' => ['en' => 'Traditional strong coffee with sweet condensed milk', 'km' => 'កាហ្វេប្រពៃណីទឹកដោះគោខាប់ខ្មែរ']],
                ['name' => ['en' => 'Coconut Coffee', 'km' => 'កាហ្វេដូង'], 'category' => 'coffee', 'description' => ['en' => 'Espresso poured over rich sweet coconut milk', 'km' => 'កាហ្វេរសជាតិខ្ទិះដូងក្រអូបឈ្ងុយ']],
                ['name' => ['en' => 'Hazelnut Latte', 'km' => 'ហេហ្សែលណាត់ ឡាតេ'], 'category' => 'coffee', 'description' => ['en' => 'Espresso with toasted hazelnut flavor', 'km' => 'កាហ្វេឡាតេរសជាតិគ្រាប់ធញ្ញជាតិហេហ្សែលណាត់']],
                ['name' => ['en' => 'Green Tea Latte', 'km' => 'តែបៃតងទឹកដោះគោ'], 'category' => 'tea', 'description' => ['en' => 'Japanese matcha whisked with sweetened milk', 'km' => 'តែបៃតងម៉ាត់ឆាជាមួយទឹកដោះគោផ្អែមស្រទន់']],
                ['name' => ['en' => 'Thai Milk Tea', 'km' => 'តែទឹកដោះគោថៃ'], 'category' => 'tea', 'description' => ['en' => 'Aromatic spiced Ceylon black tea with cream', 'km' => 'តែក្រហមទឹកដោះគោថៃរសជាតិដើម']],
                ['name' => ['en' => 'Matcha Tonic', 'km' => 'ម៉ាត់ឆា តូនិក'], 'category' => 'tea', 'description' => ['en' => 'Ceremonial matcha, sparkling tonic, and citrus', 'km' => 'តែម៉ាត់ឆាជាមួយទឹកតូនិកនិងក្រូចឆ្មារ']],
                ['name' => ['en' => 'Peach Lemon Tea', 'km' => 'តែក្រូចឆ្មារផ្លែប៉េស'], 'category' => 'tea', 'description' => ['en' => 'Refreshing black tea with peach slices and lemon', 'km' => 'តែផ្លែប៉េសក្រូចឆ្មារស្រស់ស្រាយ']],
                ['name' => ['en' => 'Passion Fruit Tea', 'km' => 'តែក្រូចវល្លិ៍'], 'category' => 'tea', 'description' => ['en' => 'Tangy fresh passion fruit brewed with green tea', 'km' => 'តែបៃតងក្រូចវល្លិ៍ជូអែមឆ្ងាញ់ពិសា']],
                ['name' => ['en' => 'Chocolate Frappe', 'km' => 'សូកូឡាក្រឡុក'], 'category' => 'tea', 'description' => ['en' => 'Blended rich cocoa with chilled milk and cream', 'km' => 'សូកូឡាក្រឡុកទឹកកកឈ្ងុយឆ្ងាញ់']],
                ['name' => ['en' => 'Butter Croissant', 'km' => 'នំប៉័ងក្រូសង់ប៊័រ'], 'category' => 'pastries', 'description' => ['en' => 'Flaky French butter pastry baked fresh daily', 'km' => 'នំប៉័ងក្រូសង់ស្រួយឆ្ងាញ់ដុតថ្មីៗ']],
                ['name' => ['en' => 'Blueberry Muffin', 'km' => 'នំម៉ាហ្វ៊ីនប្លូប៊ែរី'], 'category' => 'pastries', 'description' => ['en' => 'Moist bakery muffin bursting with fresh blueberries', 'km' => 'នំម៉ាហ្វ៊ីនផ្លែប្លូប៊ែរីផ្អែមឆ្ងាញ់']],
            ];

            foreach ($products as $product) {
                $productName = $this->localizedName($product['name']);
                $productDescription = $this->localizedName($product['description']);

                $categoryId = $categoryIds[$product['category']];
                $existing = DB::table('products')
                    ->where('category_id', $categoryId)
                    ->whereRaw("name->>'en' = ?", [$product['name']['en']])
                    ->first();

                if ($existing) {
                    DB::table('products')->where('id', $existing->id)->update([
                        'name' => $productName,
                        'description' => $productDescription,
                        'is_active' => true,
                        'updated_at' => $timestamp,
                    ]);
                    $productIds[$product['name']['en']] = (int) $existing->id;
                } else {
                    DB::table('products')->insert([
                        'category_id' => $categoryId,
                        'name' => $productName,
                        'description' => $productDescription,
                        'is_active' => true,
                        'updated_at' => $timestamp,
                        'created_at' => $timestamp,
                    ]);
                    $productIds[$product['name']['en']] = (int) DB::getPdo()->lastInsertId();
                }
            }

            $variantIds = [];
            $variants = [];

            foreach ($products as $p) {
                $pName = $p['name']['en'];
                $variants[] = [
                    'product' => $pName,
                    'name' => 'Regular',
                    'price' => 1.50,
                    'cost_price' => 0.50,
                    'track_stock' => false,
                    'stock_quantity' => 0,
                ];
            }

            // Keep large variant for Cappuccino so recipes stay compatible
            $variants[] = [
                'product' => 'Cappuccino',
                'name' => 'Large',
                'price' => 1.50,
                'cost_price' => 0.60,
                'track_stock' => false,
                'stock_quantity' => 0,
            ];

            foreach ($variants as $variant) {
                $variantName = $this->localizedName(['en' => $variant['name'], 'km' => $variant['name'] === 'Regular' ? 'ធម្មតា' : 'ធំ']);

                $productId = $productIds[$variant['product']];
                $existing = DB::table('product_variants')
                    ->where('product_id', $productId)
                    ->whereRaw("name->>'en' = ?", [$variant['name']])
                    ->first();

                if ($existing) {
                    DB::table('product_variants')->where('id', $existing->id)->update([
                        'price' => $variant['price'],
                        'cost_price' => $variant['cost_price'],
                        'track_stock' => $variant['track_stock'] ?? false,
                        'stock_quantity' => $variant['stock_quantity'] ?? 0,
                        'is_active' => true,
                        'updated_at' => $timestamp,
                    ]);
                    $variantIds[$variant['product'].'-'.$variant['name']] = (int) $existing->id;
                } else {
                    DB::table('product_variants')->insert([
                        'product_id' => $productId,
                        'name' => $variantName,
                        'price' => $variant['price'],
                        'cost_price' => $variant['cost_price'],
                        'track_stock' => $variant['track_stock'] ?? false,
                        'stock_quantity' => $variant['stock_quantity'] ?? 0,
                        'is_active' => true,
                        'updated_at' => $timestamp,
                        'created_at' => $timestamp,
                    ]);
                    $variantIds[$variant['product'].'-'.$variant['name']] = (int) DB::getPdo()->lastInsertId();
                }
            }

            $ingredientIds = [];
            foreach ([
                ['name' => 'Espresso beans', 'unit' => 'grams', 'current_stock' => 5000, 'reorder_level' => 1000],
                ['name' => 'Whole milk', 'unit' => 'ml', 'current_stock' => 12000, 'reorder_level' => 3000],
                ['name' => 'Matcha powder', 'unit' => 'grams', 'current_stock' => 800, 'reorder_level' => 200],
                ['name' => 'Tonic water', 'unit' => 'ml', 'current_stock' => 6000, 'reorder_level' => 1500],
            ] as $ingredient) {
                $ingredientName = $this->localizedName(['en' => $ingredient['name'], 'km' => $ingredient['name']]);

                $existing = DB::table('ingredients')
                    ->whereRaw("name->>'en' = ?", [$ingredient['name']])
                    ->where('unit', $ingredient['unit'])
                    ->first();

                if ($existing) {
                    DB::table('ingredients')->where('id', $existing->id)->update([
                        'current_stock' => $ingredient['current_stock'],
                        'reorder_level' => $ingredient['reorder_level'],
                        'updated_at' => $timestamp,
                    ]);
                    $ingredientIds[$ingredient['name']] = (int) $existing->id;
                } else {
                    DB::table('ingredients')->insert([
                        'name' => $ingredientName,
                        'unit' => $ingredient['unit'],
                        'current_stock' => $ingredient['current_stock'],
                        'reorder_level' => $ingredient['reorder_level'],
                        'updated_at' => $timestamp,
                        'created_at' => $timestamp,
                    ]);
                    $ingredientIds[$ingredient['name']] = (int) DB::getPdo()->lastInsertId();
                }
            }

            foreach ([
                ['variant' => 'Cappuccino-Regular', 'ingredient' => 'Espresso beans', 'quantity' => 18],
                ['variant' => 'Cappuccino-Regular', 'ingredient' => 'Whole milk', 'quantity' => 180],
                ['variant' => 'Cappuccino-Large', 'ingredient' => 'Espresso beans', 'quantity' => 24],
                ['variant' => 'Cappuccino-Large', 'ingredient' => 'Whole milk', 'quantity' => 240],
                ['variant' => 'Iced Latte-Regular', 'ingredient' => 'Espresso beans', 'quantity' => 18],
                ['variant' => 'Iced Latte-Regular', 'ingredient' => 'Whole milk', 'quantity' => 220],
                ['variant' => 'Matcha Tonic-Regular', 'ingredient' => 'Matcha powder', 'quantity' => 5],
                ['variant' => 'Matcha Tonic-Regular', 'ingredient' => 'Tonic water', 'quantity' => 250],
            ] as $recipe) {
                DB::table('recipes')->updateOrInsert(
                    ['product_variant_id' => $variantIds[$recipe['variant']], 'ingredient_id' => $ingredientIds[$recipe['ingredient']]],
                    ['quantity_required' => $recipe['quantity'], 'updated_at' => $timestamp, 'created_at' => $timestamp],
                );
            }

            $userId = DB::table('users')->orderBy('id')->value('id');
            $orderNumber = 'ORD-1001';
            DB::table('orders')->updateOrInsert(
                ['order_number' => $orderNumber],
                [
                    'user_id' => $userId,
                    'order_type' => 'takeaway',
                    'status' => 'completed',
                    'subtotal' => 9.5,
                    'tax_amount' => 0.0,
                    'total_amount' => 9.5,
                    'updated_at' => $timestamp,
                    'created_at' => $timestamp,
                ],
            );

            $orderId = (int) DB::table('orders')->where('order_number', $orderNumber)->value('id');
            foreach ([
                ['variant' => 'Cappuccino-Regular', 'quantity' => 1, 'unit_price' => 4.5],
                ['variant' => 'Iced Latte-Regular', 'quantity' => 1, 'unit_price' => 5.0],
            ] as $item) {
                DB::table('order_items')->updateOrInsert(
                    ['order_id' => $orderId, 'product_variant_id' => $variantIds[$item['variant']]],
                    [
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'subtotal' => $item['quantity'] * $item['unit_price'],
                        'updated_at' => $timestamp,
                        'created_at' => $timestamp,
                    ],
                );
            }

            foreach ([
                ['name' => 'Table 01', 'capacity' => 2, 'location' => 'Indoor Hall', 'status' => 'available'],
                ['name' => 'Table 02', 'capacity' => 4, 'location' => 'Indoor Hall', 'status' => 'available'],
                ['name' => 'Table 03', 'capacity' => 4, 'location' => 'Indoor Hall', 'status' => 'available'],
                ['name' => 'Table 04', 'capacity' => 2, 'location' => 'Window Side', 'status' => 'available'],
                ['name' => 'Table 05', 'capacity' => 4, 'location' => 'Garden Patio', 'status' => 'available'],
                ['name' => 'Table 06', 'capacity' => 6, 'location' => 'Garden Patio', 'status' => 'available'],
                ['name' => 'Bar 01', 'capacity' => 1, 'location' => 'Espresso Bar', 'status' => 'available'],
                ['name' => 'Bar 02', 'capacity' => 1, 'location' => 'Espresso Bar', 'status' => 'available'],
            ] as $table) {
                DB::table('cafe_tables')->updateOrInsert(
                    ['name' => $table['name']],
                    [
                        'capacity' => $table['capacity'],
                        'location' => $table['location'],
                        'status' => $table['status'],
                        'is_active' => true,
                        'updated_at' => $timestamp,
                        'created_at' => $timestamp,
                    ],
                );
            }
        });
    }

    /**
     * @param  array{en: string, km: string}  $value
     */
    private function localizedName(array $value): string
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
    }
}
