<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Project extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'category',
        'description',
        'client',
        'year',
        'tools',
        'cover_image',
        'images',
        'link',
        'order',
        'is_published',
    ];

    protected $casts = [
        'tools' => 'array',
        'images' => 'array',
        'is_published' => 'boolean',
    ];

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function getCoverImageUrlAttribute(): string
    {
        return $this->imageUrl($this->cover_image);
    }

    public function galleryImageUrls(): array
    {
        return collect($this->images ?? [])
            ->map(fn (string $image) => $this->imageUrl($image))
            ->all();
    }

    public function imageUrl(?string $path): string
    {
        if (! $path) {
            return asset('images/portfolio_photography_gallery.png');
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        if (str_starts_with($path, 'images/')) {
            return asset($path);
        }

        return Storage::url($path);
    }
}
