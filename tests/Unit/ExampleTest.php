<?php

use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('setting defaults contain expected business keys and fallback values', function () {
    $defaults = Setting::defaults();

    expect($defaults)->toBeArray()
        ->and($defaults)->toHaveKeys([
            'payment_cash_enabled',
            'payment_khqr_enabled',
            'exchange_rate_khr',
            'default_currency',
            'cafe_name',
            'pos_default_language',
            'low_stock_threshold',
        ])
        ->and($defaults['exchange_rate_khr'])->toBe('4100')
        ->and($defaults['pos_default_language'])->toBe('km');
});

test('setting get returns fallback default when key is not in database', function () {
    $exchangeRate = Setting::get('exchange_rate_khr');
    expect($exchangeRate)->toBe('4100');

    $customFallback = Setting::get('non_existent_key', 'fallback_val');
    expect($customFallback)->toBe('fallback_val');
});
