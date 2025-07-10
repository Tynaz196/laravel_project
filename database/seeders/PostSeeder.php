<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo 5 bài viết
        Post::factory(5)->create()->each(function ($post) {
            $this->addThumbnailToPost($post);
        });
    }

    /**
     * Thêm thumbnail cho bài viết
     */
    private function addThumbnailToPost(Post $post): void
    {
        try {
            // Tạo ảnh dummy từ picsum.photos (640x480)
            $imageUrl = "https://picsum.photos/640/480?random=" . $post->id;

            // Download ảnh
            $imageContent = Http::get($imageUrl)->body();

            // Tạo file tạm thời
            $tempFile = tempnam(sys_get_temp_dir(), 'thumbnail_') . '.jpg';
            file_put_contents($tempFile, $imageContent);

            // Tạo UploadedFile object
            $uploadedFile = new UploadedFile(
                $tempFile,
                'thumbnail_' . $post->id . '.jpg',
                'image/jpeg',
                null,
                true
            );

            // Thêm vào media collection
            $post->addMediaFromRequest('thumbnail')
                ->usingFileName('thumbnail_' . $post->id . '.jpg')
                ->toMediaCollection('thumbnails');

            // Xóa file tạm
            unlink($tempFile);
        } catch (\Exception $e) {
            // Nếu lỗi thì bỏ qua, không có thumbnail
            Log::warning("Could not add thumbnail to post {$post->id}: " . $e->getMessage());
        }
    }
}
