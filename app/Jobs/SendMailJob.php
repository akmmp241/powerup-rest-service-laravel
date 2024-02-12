<?php

namespace App\Jobs;

use App\Mail\SendForgetPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public array $data
    )
    {
    }

    public function handle(): void
    {
        Mail::to($this->data['email'])->send(new SendForgetPassword($this->data));
    }
}
