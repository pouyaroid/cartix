<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasActivityLog;
use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Card extends Model
{
    use HasFactory, SoftDeletes, HasActivityLog, HasSlug;

    protected $fillable = [
        'user_id',
        'card_type_id',
        'type',
        'template_id',
        'title',
        'slug',
        'description',
        'profile_image',
        'cover_image',
        'logo',
        'phone',
        'email',
        'website',
        'address',
        'theme_color',
        'font_family',
        'map_lat',
        'map_lng',
        'is_published',
        'is_featured',
        'views_count',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'og_image',
        'schema_type',
        'meta',
        'settings',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'meta' => 'array',
            'settings' => 'array',
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
            'published_at' => 'datetime',
            'map_lat' => 'decimal:7',
            'map_lng' => 'decimal:7',
            'views_count' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cardType(): BelongsTo
    {
        return $this->belongsTo(CardType::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }

    public function sections(): HasMany
    {
        return $this->hasMany(CardSection::class);
    }

    public function socialLinks(): HasMany
    {
        return $this->hasMany(CardSocialLink::class);
    }

    public function galleryItems(): HasMany
    {
        return $this->hasMany(CardGalleryItem::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(CardProduct::class);
    }

    public function services(): HasMany
    {
        return $this->hasMany(CardService::class);
    }

    public function testimonials(): HasMany
    {
        return $this->hasMany(CardTestimonial::class);
    }

    public function faqs(): HasMany
    {
        return $this->hasMany(CardFaq::class);
    }

    public function qrCodes(): HasMany
    {
        return $this->hasMany(QrCode::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeByType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    public function scopeByUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    public function getPublicUrlAttribute(): string
    {
        return route('card.show', $this->slug);
    }
}
