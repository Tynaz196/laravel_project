<?php

namespace App\Observers;

use App\Models\Post;
use Illuminate\Support\Str;

class PostObserver
{
    /**
     * Handle the Post "creating" event.
     */
    public function creating(Post $post): void
    {
        if (empty($post->slug)) {
            $post->slug = $this->generateUniqueSlug($post->title);
        }
    }

    /**
     * Handle the Post "updating" event.
     */
    public function updating(Post $post): void
    {
        if ($post->isDirty('title')) {
            $post->slug = $this->generateUniqueSlug($post->title, $post->id);
        }
    }

    /**
     * Handle the Post "created" event.
     */
    public function created(Post $post): void
    {
        //
    }

    /**
     * Handle the Post "updated" event.
     */
    public function updated(Post $post): void
    {
        //
    }

    /**
     * Handle the Post "deleted" event.
     */
    public function deleted(Post $post): void
    {
        //
    }

    /**
     * Handle the Post "restored" event.
     */
    public function restored(Post $post): void
    {
        //
    }

    /**
     * Handle the Post "force deleted" event.
     */
    public function forceDeleted(Post $post): void
    {
        //
    }

    /**
     * Generate unique slug for post
     */
    protected function generateUniqueSlug(string $title, $ignoreId = null): string
    {
        $slug = Str::slug($title);

        if (!Post::query()
            ->where('slug', $slug)
            ->when($ignoreId, fn($query) => $query->where('id', '!=', $ignoreId))
            ->exists()) {
            return $slug;
        }

        // Thêm MD5 hash để tạo slug duy nhất
        $uniqueHash = substr(md5($title . time() . uniqid()), 0, 8);
        return $slug . '-' . $uniqueHash;
    }
}
