<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = ['category_id', 'name', 'description', 'image', 'is_active'];

    protected function casts(): array
    {
        return [
            'name' => 'array',
            'description' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
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

    public function getDescriptionAttribute(): string
    {
        $description = $this->attributes['description'] ?? null;
        $locale = app()->getLocale();

        if (is_string($description)) {
            $description = json_decode($description, true);
        }

        return $description[$locale] ?? $description['en'] ?? (is_array($description) ? reset($description) : '') ?: '';
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

    /**
     * @return array<string, string>
     */
    public function getDescriptionTranslationsAttribute(): array
    {
        $description = $this->attributes['description'] ?? [];
        if (is_string($description)) {
            $description = json_decode($description, true) ?? [];
        }

        return is_array($description) ? $description : ['en' => (string) $description];
    }
}
