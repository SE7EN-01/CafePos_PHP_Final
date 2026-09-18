<?php

use App\Models\Setting;
use App\Models\User;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'barista', 'guard_name' => 'web']);
});

it('prevents unauthenticated guests from accessing store settings', function () {
    $response = $this->get(route('admin.settings.index'));
    $response->assertRedirect(route('login'));
});

it('prevents barista role from accessing or updating store settings', function () {
    $barista = User::factory()->create(['email_verified_at' => now()]);
    $barista->assignRole('barista');

    $getResponse = $this->actingAs($barista)->get(route('admin.settings.index'));
    $getResponse->assertForbidden();

    $postResponse = $this->actingAs($barista)->post(route('admin.settings.update'), [
        'exchange_rate_khr' => 4150,
    ]);
    $postResponse->assertForbidden();
});

it('allows admin to view the settings page with all customization sections', function () {
    $admin = User::factory()->create(['email_verified_at' => now()]);
    $admin->assignRole('admin');

    $response = $this->actingAs($admin)->get(route('admin.settings.index'));

    $response->assertOk();
    $response->assertSee('Store &amp; Payment Settings', false);
    $response->assertSee('Payment &amp; Currency', false);
    $response->assertSee('Store &amp; Receipt', false);
    $response->assertSee('POS Preferences', false);
    $response->assertSee('exchange_rate_khr');
});

it('allows admin to customize payment settings and exchange rate', function () {
    $admin = User::factory()->create(['email_verified_at' => now()]);
    $admin->assignRole('admin');

    $payload = [
        'payment_cash_enabled' => '1',
        'exchange_rate_khr' => 4150,
        'default_currency' => 'khr',
        'payment_khqr_enabled' => '1',
        'bakong_account_id' => 'custom_merchant@aba',
        'bakong_merchant_name' => 'Specialty Coffee Roastery',
        'bakong_merchant_city' => 'Siem Reap',
        'cafe_name' => 'Bong Heng Roastery',
        'cafe_tagline' => 'Artisanal Coffee & Roastery',
        'cafe_phone' => '+855 12 345 678',
        'cafe_address' => 'Street 240, Phnom Penh',
        'tax_rate_percent' => 10,
        'receipt_footer_text' => 'See you again soon!',
        'pos_default_language' => 'km',
        'low_stock_threshold' => 15,
    ];

    $response = $this->actingAs($admin)->post(route('admin.settings.update'), $payload);

    $response->assertRedirect(route('admin.settings.index'));
    $response->assertSessionHas('status', 'settings-updated');

    expect(Setting::get('exchange_rate_khr'))->toBe('4150');
    expect(Setting::get('default_currency'))->toBe('khr');
    expect(Setting::get('cafe_name'))->toBe('Bong Heng Roastery');
    expect(Setting::get('bakong_account_id'))->toBe('custom_merchant@aba');
    expect(Setting::get('bakong_merchant_city'))->toBe('Siem Reap');
    expect(Setting::get('pos_default_language'))->toBe('km');
    expect(Setting::get('low_stock_threshold'))->toBe('15');
});
