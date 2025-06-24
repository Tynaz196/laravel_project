<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct()
    {
    }

    public function build()
    {
        return $this->view('emails.welcome')
                    ->subject('Chào mừng từ Laravel App')
                    ->with([
                        'timestamp' => now()->format('d/m/Y H:i:s')
                    ]);
    }
}