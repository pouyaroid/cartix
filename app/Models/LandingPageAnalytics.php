<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LandingPageAnalytics extends Model
{
    use HasFactory;

    protected $fillable = [
        'landing_page_id',
        'date',
        'views',
        'unique_views',
        'referrers',
        'devices',
        'locations',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'views' => 'integer',
            'unique_views' => 'integer',
            'referrers' => 'array',
            'devices' => 'array',
            'locations' => 'array',
        ];
    }

    public function landingPage(): BelongsTo
    {
        return $this->belongsTo(LandingPage::class);
    }
}
