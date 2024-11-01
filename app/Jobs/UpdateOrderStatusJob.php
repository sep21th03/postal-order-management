<?php

namespace App\Jobs;

use App\Mail\UpdateOrderStatusEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class UpdateOrderStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $email;
    public $content;
    public $customerName;
    /**
     * Create a new job instance.
     */
    public function __construct($email, $customerName, $content = '')
    {
        $this->email = $email;
        $this->content = $content;
        $this->customerName = $customerName;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        print_r($this->content);
        Mail::to($this->email)->send(new UpdateOrderStatusEmail($this->customerName, $this->content));
    }
}
