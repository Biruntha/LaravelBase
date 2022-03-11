<?php

namespace App\Jobs;

use App\Mail\SignUpEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SignUpEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $details;
    public $receiver;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($receiver,$details) {
        $this->details = $details;
        $this->receiver = $receiver;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email = new SignUpEmail($this->details);        
        Mail::to($this->receiver)->send($email);
    }
}
