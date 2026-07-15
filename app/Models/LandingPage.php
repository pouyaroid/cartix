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
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class LandingPage extends Model
{
    use HasFactory, SoftDeletes, HasActivityLog, HasSlug;

    protected $fillable = [
        'user_id',
        'template_id',
        'qr_code_id',
        'title',
        'slug',
        'status',
        'seo_title',
        'seo_description',
        'og_image',
        'favicon',
        'custom_css',
        'custom_js',
        'password',
        'scheduled_publish_at',
        'views_count',
        'settings',
        'metadata',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'settings' => 'array',
            'metadata' => 'array',
            'views_count' => 'integer',
            'scheduled_publish_at' => 'datetime',
            'published_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(LandingPageTemplate::class, 'template_id');
    }

    public function qrCode(): BelongsTo
    {
        return $this->belongsTo(QrCode::class);
    }

    public function blocks(): HasMany
    {
        return $this->hasMany(LandingPageBlock::class)->orderBy('sort_order');
    }

    public function rootBlocks(): HasMany
    {
        return $this->hasMany(LandingPageBlock::class)
            ->whereNull('parent_id')
            ->orderBy('sort_order');
    }

    public function versions(): HasMany
    {
        return $this->hasMany(LandingPageVersion::class);
    }

    public function formSubmissions(): HasMany
    {
        return $this->hasMany(LandingPageFormSubmission::class);
    }

    public function analytics(): HasMany
    {
        return $this->hasMany(LandingPageAnalytics::class);
    }

    public function styles(): HasMany
    {
        return $this->hasMany(LandingPageStyle::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('status', 'draft');
    }

    public function scopeByUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    public function scopeScheduled(Builder $query): Builder
    {
        return $query->where('status', 'draft')
            ->whereNotNull('scheduled_publish_at')
            ->where('scheduled_publish_at', '<=', now());
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    public function isPasswordProtected(): bool
    {
        return !empty($this->password);
    }

    public function getPublicUrlAttribute(): string
    {
        return route('lp.show', $this->slug);
    }

    public function getBlocksTree(): array
    {
        $allBlocks = $this->blocks()->get()->keyBy('id');
        $tree = [];

        foreach ($allBlocks as $block) {
            if ($block->parent_id === null) {
                $tree[] = $this->buildBlockTree($block, $allBlocks);
            }
        }

        return $tree;
    }

    private function buildBlockTree(LandingPageBlock $block, $allBlocks): array
    {
        $data = $block->toArray();
        $data['children'] = [];

        foreach ($allBlocks as $child) {
            if ($child->parent_id === $block->id) {
                $data['children'][] = $this->buildBlockTree($child, $allBlocks);
            }
        }

        return $data;
    }

    public function incrementViews(): void
    {
        $this->increment('views_count');

        $today = now()->toDateString();
        LandingPageAnalytics::updateOrCreate(
            ['landing_page_id' => $this->id, 'date' => $today],
            ['views' => \DB::raw('views + 1')]
        );
    }
}
