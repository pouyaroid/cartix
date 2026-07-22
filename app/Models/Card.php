<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasActivityLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Card extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, HasActivityLog, InteractsWithMedia;

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Card $card) {
            if (empty($card->slug)) {
                $card->slug = $card->generateUniqueSlug();
            }
        });
    }

    public function generateUniqueSlug(): string
    {
        $slug = \Str::slug($this->title ?? 'card');
        $originalSlug = $slug;
        $counter = 1;

        while (static::withoutGlobalScopes()->where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    public function getShareableUrlAttribute(): string
    {
        return route('card.show', $this->slug);
    }

    public function getMediaModel(): string
    {
        return SpatieMedia::class;
    }

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'description',
        'status',
        'canvas_width',
        'canvas_height',
        'design_data',
        'settings',
    ];

    protected function casts(): array
    {
        return [
            'design_data' => 'array',
            'settings' => 'array',
            'canvas_width' => 'integer',
            'canvas_height' => 'integer',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('final-image')
            ->singleFile()
            ->acceptsMimeTypes(['image/png']);
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumbnail')
            ->width(400)
            ->height(300)
            ->sharpen(10);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function getFinalImageUrlAttribute(): ?string
    {
        return $this->getFirstMedia('final-image')?->getUrl();
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        $media = $this->getFirstMedia('final-image');
        return $media?->getUrl('thumbnail') ?? $media?->getUrl();
    }
}
