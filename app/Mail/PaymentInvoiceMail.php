<?php

namespace App\Mail;

use App\Test;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    public $paymentDetail;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($users,$paymentDetails)
    {
        $this->user = $users;
        $this->paymentDetail =  $paymentDetails;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $t = new Test();
        $t->text = "markdown".json_encode($this->paymentDetail);
        $t->save();
        return $this->markdown('emails.PaymentInvoiceMail')->with(['user'=> $this->user, 'paymentDetail'=>$this->paymentDetail]);
    }
}
