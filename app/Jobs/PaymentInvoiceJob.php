<?php

namespace App\Jobs;

use App\Mail\PaymentInvoiceMail;
use App\Test;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class PaymentInvoiceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $user;
    public $paymentDetail;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $users, $paymentDetails)
    {
        $this->user =$users;
        $this->paymentDetail = $paymentDetails;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
//        $test= new test()
        Mail::to($this->user->email)->send(new PaymentInvoiceMail($this->user,  $this->paymentDetail));

        if(Mail::failures() != 0) {

            $t = new Test();
            $t->text = "Success! Your E-mail has been sent";
            $t->save();
        }

        else {
            $t = new Test();
            $t->text = "Failed! Your E-mail has not sent";
            $t->save();

        }
    }
}
