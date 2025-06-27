<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\SendWelcomeEmail;
use Carbon\Carbon;

class JobController extends Controller
{
    public function processQueue()
    {
        $emailJob = new SendWelcomeEmail();
        dispatch($emailJob);
        return redirect()->back()->with('success', 'Email đã được đưa vào hàng đợi và sẽ được gửi trong vài phút.');
    }
}
