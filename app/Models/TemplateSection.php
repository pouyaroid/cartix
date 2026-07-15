<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TemplateSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'template_id',
        'name',
        'type',
        'default_settings',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'default_settings' => 'array',
            'sort_order' => 'integer',
        ];
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }
}
