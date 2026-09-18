<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * Default settings dictionary.
     *
     * @return array<string, mixed>
     */
    public static function defaults(): array
    {
        return [
            // Payment settings
            'payment_cash_enabled' => '1',
            'payment_khqr_enabled' => '1',
            'exchange_rate_khr' => '4100',
            'default_currency' => 'khr',

            // Bakong KHQR details
            'bakong_account_id' => config('bakong.account_id', 'bongheng@aba'),
            'bakong_merchant_name' => config('bakong.merchant_name', 'Bong Heng Cafe'),
            'bakong_merchant_city' => config('bakong.merchant_city', 'Phnom Penh'),
            'bakong_qr_expiration_minutes' => '5',

            // Cafe & Receipt details
            'cafe_name' => 'Bong Heng Cafe',
            'cafe_tagline' => 'Specialty Cafe & Roastery',
            'cafe_phone' => '+855 12 345 678',
            'cafe_address' => '#123 Street 214, Daun Penh, Phnom Penh',
            'receipt_footer_text' => 'សូមអរគុណចំពោះការគាំទ្រ! សូមអញ្ជើញមកម្តងទៀត។',
            'tax_rate_percent' => '0',

            // POS defaults
            'pos_default_language' => 'km',
            'low_stock_threshold' => '5',
        ];
    }

    /**
     * Get setting value by key.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $defaults = static::defaults();
        $fallback = $default ?? ($defaults[$key] ?? null);

        return Cache::remember("app_setting_{$key}", 3600, function () use ($key, $fallback) {
            $setting = static::where('key', $key)->first();

            return $setting ? $setting->value : $fallback;
        });
    }

    /**
     * Set setting value.
     */
    public static function set(string $key, mixed $value): void
    {
        $stringValue = is_bool($value) ? ($value ? '1' : '0') : (string) $value;

        static::updateOrCreate(
            ['key' => $key],
            ['value' => $stringValue]
        );

        Cache::forget("app_setting_{$key}");
    }

    /**
     * Get all settings merged with defaults.
     *
     * @return array<string, mixed>
     */
    public static function getAll(): array
    {
        $defaults = static::defaults();
        $dbSettings = static::pluck('value', 'key')->toArray();

        return array_merge($defaults, $dbSettings);
    }
}
