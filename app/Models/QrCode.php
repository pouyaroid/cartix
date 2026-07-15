<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasActivityLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class QrCode extends Model
{
    use HasFactory, SoftDeletes, HasActivityLog;

    protected $fillable = [
        'user_id',
        'card_id',
        'type',
        'title',
        'content',
        'unique_code',
        // Basic colors
        'foreground_color',
        'background_color',
        'gradient_from',
        'gradient_to',
        // Logo
        'logo_path',
        'logo_size',
        'logo_padding',
        // Style & shape
        'style',
        'shape',
        'pattern',
        'size',
        'error_correction',
        // Eye/Corner markers
        'eye_style',
        'eye_color',
        // Frame
        'frame_style',
        'frame_color',
        // Text
        'text',
        'text_position',
        'text_font',
        'text_size',
        'text_color',
        // Margin & resolution
        'margin',
        'resolution',
        // Status
        'is_active',
        'scans_count',
        'settings',
    ];

    protected function casts(): array
    {
        return [
            'settings' => 'array',
            'is_active' => 'boolean',
            'scans_count' => 'integer',
            'size' => 'integer',
            'logo_size' => 'integer',
            'logo_padding' => 'integer',
            'margin' => 'integer',
            'resolution' => 'integer',
            'text_size' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (QrCode $qrCode) {
            if (empty($qrCode->unique_code)) {
                $qrCode->unique_code = static::generateUniqueCode();
            }
        });
    }

    public static function generateUniqueCode(): string
    {
        do {
            $code = Str::random(12);
        } while (static::where('unique_code', $code)->exists());

        return $code;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class);
    }

    public function qrScans(): HasMany
    {
        return $this->hasMany(QrScan::class);
    }

    public function scans(): HasMany
    {
        return $this->hasMany(QrScan::class);
    }

    public function getFullUrlAttribute(): string
    {
        return route('qr.redirect', $this->unique_code);
    }
}
