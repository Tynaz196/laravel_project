<?php

namespace App\Mail;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PostApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $post;

    /**
     * Create a new message instance.
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Bài viết của bạn đã được duyệt!')
                    ->view('emails.post-approved')
                    ->with([
                        'post' => $this->post,
                        'userName' => $this->post->user->name,
                        'postTitle' => $this->post->title,
                        'postUrl' => route('posts.show', $this->post->slug)
                    ]);
    }
}
