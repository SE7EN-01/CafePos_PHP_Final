<?php

use App\Models\User;
use Spatie\Permission\Models\Role;

it('allows an admin to view the team access dashboard', function () {
    Role::create(['name' => 'admin', 'guard_name' => 'web']);
    $admin = User::factory()->create(['email_verified_at' => now()]);
    $admin->assignRole('admin');

    $response = $this->actingAs($admin)->get(route('admin.users.index'));

    $response->assertOk();
    $response->assertSee('Add a team member');
    $response->assertSee('Account settings');
});

it('forbids non-admin users from the team access dashboard', function () {
    $user = User::factory()->create(['email_verified_at' => now()]);

    $response = $this->actingAs($user)->get(route('admin.users.index'));

    $response->assertForbidden();
});

it('creates a user with a role and account settings', function () {
    Role::create(['name' => 'admin', 'guard_name' => 'web']);
    Role::create(['name' => 'cashier', 'guard_name' => 'web']);
    $admin = User::factory()->create(['email_verified_at' => now()]);
    $admin->assignRole('admin');

    $response = $this->actingAs($admin)->post(route('admin.users.store'), [
        'name' => 'Dara Sok',
        'email' => 'dara@example.com',
        'phone' => '+85512345678',
        'password' => 'password-123',
        'password_confirmation' => 'password-123',
        'role' => 'cashier',
        'is_active' => '1',
        'email_verified' => '1',
    ]);

    $createdUser = User::where('email', 'dara@example.com')->firstOrFail();

    $response->assertRedirect(route('admin.users.index'));
    expect($createdUser->is_active)
        ->toBeTrue()
        ->and($createdUser->email_verified_at)
        ->not
        ->toBeNull()
        ->and($createdUser->hasRole('cashier'))
        ->toBeTrue();
});

it('requires an admin role when creating a user', function () {
    $user = User::factory()->create(['email_verified_at' => now()]);

    $response = $this->actingAs($user)->post(route('admin.users.store'), []);

    $response->assertForbidden();
});
