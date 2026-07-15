<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandingPageTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'category',
        'thumbnail',
        'preview_image',
        'data',
        'is_active',
        'is_premium',
        'settings',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'data' => 'array',
            'settings' => 'array',
            'is_active' => 'boolean',
            'is_premium' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory(Builder $query, string $category): Builder
    {
        return $query->where('category', $category);
    }

    public function getBlocks(): array
    {
        return $this->data['blocks'] ?? [];
    }
}
