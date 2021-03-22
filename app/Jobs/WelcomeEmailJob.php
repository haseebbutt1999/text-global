<?php

namespace App\Jobs;

use App\Mail\WelcomeEmail;
use App\Shopdetail;
use App\Test;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class WelcomeEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $shopdetail;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Shopdetail $shopdetail)
    {
        $this->shopdetail = $shopdetail;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->shopdetail->email)->send(new WelcomeEmail());

        if(Mail::failures() != 0) {

            $t = new Test();
            $t->text = "Success! Your E-mail has been sent";
            $t->number = $this->shopdetail->id;
            $t->save();
        }

        else {
            $t = new Test();
            $t->text = "Failed! Your E-mail has not sent";
            $t->number = $this->shopdetail->id;
            $t->save();

        }

    }
}
