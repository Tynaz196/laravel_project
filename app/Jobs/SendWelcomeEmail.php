<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\WelcomeEmail;
use App\Models\User;
use Exception;

class SendWelcomeEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 30;
    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function handle()
    {
        try {
            Log::info('Starting to send welcome email to: ' . $this->user->email);
            $email = new WelcomeEmail($this->user);
            Mail::to($this->user->email)->send($email);
            Log::info('Welcome email sent successfully to: ' . $this->user->email);
        } catch (Exception $e) {
            Log::error('Failed to send welcome email to ' . $this->user->email . ': ' . $e->getMessage());
            throw $e;
        }
    }

    public function failed(Exception $exception)
    {
        Log::error('Welcome email job failed for user ' . $this->user->email . ': ' . $exception->getMessage());
    }
}
