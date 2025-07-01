<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\PostStatus;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Post extends Model
{
    use InteractsWithMedia;

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'slug',
        'description',
        'publish_date',
        'status',
    ];

    protected $casts = [
        'status' => PostStatus::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope: Bài viết đã đăng
    public function scopePosted($query)
    {
        return $query->where('status', PostStatus::POST);
    }

    // Scope: Bài viết mới
    public function scopeNewPost($query)
    {
        return $query->where('status', PostStatus::NEWPOST);
    }

    // Scope: Bài viết được cập nhật
    public function scopeUpdated($query)
    {
        return $query->where('status', PostStatus::UPDATE);
    }

    protected static function booted()
    {
        static::creating(function ($post) {
            $post->slug = static::generateUniqueSlug($post->title);
        });

        static::updating(function ($post) {
            if ($post->isDirty('title')) {
                $post->slug = static::generateUniqueSlug($post->title, $post->id);
            }
        });
    }

    protected static function generateUniqueSlug(string $title, $ignoreId = null): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;

        while (static::query()
            ->where('slug', $slug)
            ->when($ignoreId, fn($query) => $query->where('id', '!=', $ignoreId))
            ->exists()
        ) {
            $slug = $originalSlug . '-' . $count++;
        }

        return $slug;
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl('thumbnails');
    }
}
