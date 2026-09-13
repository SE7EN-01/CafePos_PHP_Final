<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'is_active'];

    protected function casts(): array
    {
        return [
            'name' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
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
     * Get raw array of name translations.
     *
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
