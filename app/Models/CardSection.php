<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CardSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'card_id',
        'type',
        'title',
        'content',
        'sort_order',
        'is_visible',
        'settings',
    ];

    protected function casts(): array
    {
        return [
            'settings' => 'array',
            'is_visible' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class);
    }

    public function items(): HasMany
    {
        $modelMap = [
            'services' => CardService::class,
            'products' => CardProduct::class,
            'testimonials' => CardTestimonial::class,
            'faq' => CardFaq::class,
            'gallery' => CardGalleryItem::class,
            'timeline' => CardService::class,
        ];

        $model = $modelMap[$this->type] ?? CardService::class;

        return $this->hasMany($model, 'card_id')->where('card_id', $this->card_id)->orderBy('sort_order');
    }
}
