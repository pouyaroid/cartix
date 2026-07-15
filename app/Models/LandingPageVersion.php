<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LandingPageVersion extends Model
{
    use HasFactory;

    protected $fillable = [
        'landing_page_id',
        'created_by',
        'version',
        'label',
        'data',
    ];

    protected function casts(): array
    {
        return [
            'data' => 'array',
            'version' => 'integer',
        ];
    }

    public function landingPage(): BelongsTo
    {
        return $this->belongsTo(LandingPage::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeLatest(Builder $query): Builder
    {
        return $query->orderByDesc('version');
    }

    public function scopeAutosaves(Builder $query): Builder
    {
        return $query->where('label', 'autosave');
    }
}
