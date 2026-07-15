<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LandingPageStyle extends Model
{
    use HasFactory;

    protected $fillable = [
        'landing_page_id',
        'scope',
        'selector',
        'properties',
        'breakpoint',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'properties' => 'array',
            'sort_order' => 'integer',
        ];
    }

    public function landingPage(): BelongsTo
    {
        return $this->belongsTo(LandingPage::class);
    }

    public function scopeByBreakpoint(Builder $query, string $breakpoint): Builder
    {
        return $query->where('breakpoint', $breakpoint);
    }
}
