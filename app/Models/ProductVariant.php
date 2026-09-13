<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class ProductVariant extends Model
{
    protected $fillable = ['product_id', 'name', 'price', 'cost_price', 'track_stock', 'stock_quantity', 'is_active'];

    protected function casts(): array
    {
        return [
            'name' => 'array',
            'price' => 'decimal:2',
            'cost_price' => 'decimal:2',
            'track_stock' => 'boolean',
            'stock_quantity' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function recipes(): HasMany
    {
        return $this->hasMany(Recipe::class);
    }

    public function stockMovements(): MorphMany
    {
        return $this->morphMany(StockMovement::class, 'stockable');
    }

    public function getNameAttribute(): string
    {
        $name = $this->attributes['name'] ?? null;
        $locale = app()->getLocale();

        if (is_string($name)) {
            $name = json_decode($name, true);
        }

        return $name[$locale] ?? $name['en'] ?? (is_array($name) ? reset($name) : '') ?: '';
    }

    /**
     * @return array<string, string>
     */
    public function getNameTranslationsAttribute(): array
    {
        $name = $this->attributes['name'] ?? [];
        if (is_string($name)) {
            $name = json_decode($name, true) ?? [];
        }

        return is_array($name) ? $name : ['en' => (string) $name];
    }
}
