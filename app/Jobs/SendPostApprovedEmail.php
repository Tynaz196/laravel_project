<?php

namespace App\Jobs;

use App\Mail\PostApprovedMail;
use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendPostApprovedEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $post;
    public $tries = 3; // Số lần retry nếu thất bại
    public $timeout = 60; // Timeout 60 giây

    /**
     * Create a new job instance.
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Kiểm tra post và user vẫn tồn tại
            if (!$this->post || !$this->post->user) {
                Log::warning('Post or user not found when sending approval email', [
                    'post_id' => $this->post?->id
                ]);
                return;
            }

            // Kiểm tra email của user
            if (!$this->post->user->email) {
                Log::warning('User email not found when sending approval email', [
                    'post_id' => $this->post->id,
                    'user_id' => $this->post->user->id
                ]);
                return;
            }

            // Gửi email
            Mail::to($this->post->user->email)->send(new PostApprovedMail($this->post));

            Log::info('Post approval email sent successfully', [
                'post_id' => $this->post->id,
                'user_email' => $this->post->user->email
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send post approval email', [
                'post_id' => $this->post->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Re-throw exception để queue system có thể retry
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Post approval email job failed permanently', [
            'post_id' => $this->post->id,
            'error' => $exception->getMessage(),
            'attempts' => $this->attempts()
        ]);
    }
}
