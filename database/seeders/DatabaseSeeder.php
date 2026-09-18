<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $adminUser = User::query()->firstOrCreate(
            ['email' => 'admin@cafe.com'],
            [
                'name' => 'Test admin',
                'password' => Hash::make('admin'),
                'email_verified_at' => now(),
                'is_active' => true,
            ],
        );

        $adminUser->forceFill([
            'email_verified_at' => $adminUser->email_verified_at ?? now(),
            'is_active' => true,
        ])->save();

        $this->call([
            RoleAndPermissionSeeder::class,
            CoffeeShopSeeder::class,
            RealisticCafeSeeder::class,
        ]);

        $adminUser->assignRole('admin');

        $baristaUser = User::query()->firstOrCreate(
            ['email' => 'barista@cafe.com'],
            [
                'name' => 'Test barista',
                'password' => Hash::make('barista'),
                'email_verified_at' => now(),
                'is_active' => true,
            ],
        );

        $baristaUser->forceFill([
            'email_verified_at' => $baristaUser->email_verified_at ?? now(),
            'is_active' => true,
        ])->save();

        $baristaUser->assignRole('barista');
    }
}
