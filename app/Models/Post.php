<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\PostStatus;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Post extends Model implements HasMedia
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
        'publish_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', PostStatus::PENDING);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', PostStatus::APPROVED);
    }


    public function scopeDennied($query)
    {
        return $query->where('status', PostStatus::DENNIED);
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
        $Slug = Str::slug($title);

        if (!static::query()
            ->where('slug', $Slug)
            ->when($ignoreId, fn($query) => $query->where('id', '!=', $ignoreId))
            ->exists()) {
            return $Slug;
        }

        // Nếu đã tồn tại thì thêm MD5 hash để đảm bảo độc nhất
        $uniqueHash = substr(md5($title . time() . uniqid()), 0, 8);
        return $Slug . '-' . $uniqueHash;
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl('thumbnails');
    }
}
