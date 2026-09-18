<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;

/**
 * @property Carbon|null $expiry_date
 */
class Ingredient extends Model
{
    protected $fillable = [
        'name',
        'sku',
        'unit',
        'current_stock',
        'reorder_level',
        'purchase_cost',
        'average_cost',
        'supplier_id',
        'expiry_date',
        'batch_number',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'name' => 'array',
            'current_stock' => 'decimal:2',
            'reorder_level' => 'decimal:2',
            'purchase_cost' => 'decimal:4',
            'average_cost' => 'decimal:4',
            'expiry_date' => 'datetime',
        ];
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function purchaseItems(): HasMany
    {
        return $this->hasMany(PurchaseItem::class);
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

    public function isLowStock(): bool
    {
        return $this->current_stock <= $this->reorder_level;
    }

    public function isExpired(): bool
    {
        return $this->expiry_date !== null && $this->expiry_date->isPast();
    }

    public function isExpiringSoon(int $days = 7): bool
    {
        return $this->expiry_date !== null && ! $this->isExpired() && $this->expiry_date->lte(now()->addDays($days));
    }
}
