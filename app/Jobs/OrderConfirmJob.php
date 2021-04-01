<?php

namespace App\Jobs;

use App\Test;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class OrderConfirmJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $order_confirm_data;
    public $shop;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($order_confirm_data, $shop)
    {
        $this->order_confirm_data = $order_confirm_data;
        $this->shop = $shop;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $order_confirm_data = $this->order_confirm_data;
        $shop = $this->shop;
        try {
            $new = new Test();
            $new->text = "in Job order data is : ".json_encode($order_confirm_data);
            $new->save();
            $new = new Test();
            $new->text = "in Job shop is : ".json_encode($shop);
            $new->save();
        }catch (\Exception $exception){
            $new = new Test();
            $new->text = "error: ".$exception->getMessage();
            $new->save();
            $new = new Test();
            $new->text = "error :in Job order data is : ".json_encode($order_confirm_data);
            $new->save();
            $new = new Test();
            $new->text = "error :in Job shop is : ".json_encode($shop);
            $new->save();
        }
    }
}
