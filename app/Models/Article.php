<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'slug', 'title', 'description', 'category', 'body', 'status',
        'is_demo', 'verification_status', 'verified_at', 'source_name',
        'source_url', 'source_type', 'source_note', 'action_label',
        'action_url', 'deadline', 'location', 'image', 'image_alt',
        'author', 'tags', 'is_featured', 'is_trending', 'is_breaking',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'is_demo' => 'boolean',
            'is_featured' => 'boolean',
            'is_trending' => 'boolean',
            'is_breaking' => 'boolean',
            'tags' => 'array',
            'published_at' => 'datetime',
            'verified_at' => 'datetime',
            'deadline' => 'date',
        ];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')
            ->where('is_demo', false)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function getIsClosedAttribute(): bool
    {
        return $this->deadline !== null && $this->deadline->endOfDay()->isPast();
    }
}
