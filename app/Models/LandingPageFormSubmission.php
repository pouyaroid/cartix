<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LandingPageFormSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'landing_page_id',
        'form_id',
        'data',
        'ip_address',
        'user_agent',
        'referrer',
        'is_read',
    ];

    protected function casts(): array
    {
        return [
            'data' => 'array',
            'is_read' => 'boolean',
        ];
    }

    public function landingPage(): BelongsTo
    {
        return $this->belongsTo(LandingPage::class);
    }

    public function markAsRead(): void
    {
        $this->update(['is_read' => true]);
    }
}
