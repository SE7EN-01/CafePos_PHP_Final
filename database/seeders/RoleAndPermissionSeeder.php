<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (['admin', 'manager', 'cashier', 'barista'] as $roleName) {
            Role::findOrCreate($roleName, 'web');
        }
    }
}
