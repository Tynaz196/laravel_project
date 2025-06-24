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
use Exception;

class SendWelcomeEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 30;

    public function __construct()
    {
        //
    }

    public function handle()
    {
        try {
            Log::info('Starting to send welcome email...');
            $email = new WelcomeEmail();
            Mail::to('test@example.com')->send($email);
            Log::info('Welcome email sent successfully to Mailpit');
        } catch (Exception $e) {
            Log::error('Failed to send welcome email: ' . $e->getMessage());
            throw $e;
        }
    }

    public function failed(Exception $exception)
    {
        Log::error('Welcome email job failed finally: ' . $exception->getMessage());
    }
}