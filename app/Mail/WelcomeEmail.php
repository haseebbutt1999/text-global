<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $shop_detail;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($shopdetail)
    {
        $this->shop_detail = $shopdetail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('email.welcome_email')->with('shopdetail', $this->shop_detail);
    }
}
