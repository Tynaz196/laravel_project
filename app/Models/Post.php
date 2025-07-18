<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\PostStatus;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use App\Observers\PostObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy(PostObserver::class)]
class Post extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, SoftDeletes;

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

    public function getThumbnailUrlAttribute(): ?string
    {
        // Return uploaded thumbnail or default from storage/public
        return $this->getFirstMediaUrl('thumbnails')
            ?: asset('storage/default-thumbnail.jpg');
    }
}
