<?php

use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Support\Facades\Hash;

beforeEach(function () {
    $this->seed(RoleAndPermissionSeeder::class);
});

test('user can login via api and receive bearer token', function () {
    $user = User::create([
        'name' => 'API User',
        'email' => 'api@coffeeshop.com',
        'password' => Hash::make('secret123'),
        'is_active' => true,
    ]);
    $user->assignRole('barista');

    $response = $this->postJson('/api/login', [
        'email' => 'api@coffeeshop.com',
        'password' => 'secret123',
    ]);

    $response->assertOk()
        ->assertJsonStructure([
            'status',
            'message',
            'token_type',
            'access_token',
            'user' => ['id', 'name', 'email', 'roles'],
        ]);

    expect($response->json('token_type'))->toBe('Bearer')
        ->and($response->json('access_token'))->not->toBeEmpty();
});

test('inactive user cannot login via api', function () {
    $user = User::create([
        'name' => 'Inactive User',
        'email' => 'inactive@coffeeshop.com',
        'password' => Hash::make('secret123'),
        'is_active' => false,
    ]);

    $response = $this->postJson('/api/login', [
        'email' => 'inactive@coffeeshop.com',
        'password' => 'secret123',
    ]);

    $response->assertStatus(403)
        ->assertJson([
            'status' => 'error',
            'message' => 'Account is inactive. Please contact your manager.',
        ]);
});

test('user cannot login with invalid credentials', function () {
    User::create([
        'name' => 'Valid User',
        'email' => 'valid@coffeeshop.com',
        'password' => Hash::make('secret123'),
        'is_active' => true,
    ]);

    $response = $this->postJson('/api/login', [
        'email' => 'valid@coffeeshop.com',
        'password' => 'wrong-password',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});

test('authenticated user can access me endpoint', function () {
    $user = User::create([
        'name' => 'Token User',
        'email' => 'token@coffeeshop.com',
        'password' => Hash::make('secret123'),
        'is_active' => true,
    ]);
    $user->assignRole('admin');

    $token = $user->createToken('test_token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer '.$token)
        ->getJson('/api/me');

    $response->assertOk()
        ->assertJson([
            'status' => 'success',
            'user' => [
                'id' => $user->id,
                'email' => 'token@coffeeshop.com',
            ],
        ]);
});

test('authenticated user can logout and revoke token', function () {
    $user = User::create([
        'name' => 'Logout User',
        'email' => 'logout@coffeeshop.com',
        'password' => Hash::make('secret123'),
        'is_active' => true,
    ]);

    $token = $user->createToken('logout_test_token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer '.$token)
        ->postJson('/api/logout');

    $response->assertOk()
        ->assertJson([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);

    expect($user->fresh()->tokens)->toHaveCount(0);
});
