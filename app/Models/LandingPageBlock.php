<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LandingPageBlock extends Model
{
    use HasFactory;

    protected $fillable = [
        'landing_page_id',
        'parent_id',
        'type',
        'component',
        'content',
        'styles',
        'sort_order',
        'depth',
        'is_visible',
    ];

    protected function casts(): array
    {
        return [
            'content' => 'array',
            'styles' => 'array',
            'sort_order' => 'integer',
            'depth' => 'integer',
            'is_visible' => 'boolean',
        ];
    }

    public function landingPage(): BelongsTo
    {
        return $this->belongsTo(LandingPage::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(LandingPageBlock::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(LandingPageBlock::class, 'parent_id')->orderBy('sort_order');
    }

    public function scopeRoot(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }

    public function scopeForPage(Builder $query, int $pageId): Builder
    {
        return $query->where('landing_page_id', $pageId);
    }
}
