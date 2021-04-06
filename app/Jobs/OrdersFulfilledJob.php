<?php namespace App\Jobs;

use App\Http\Controllers\LogsController;
use App\Orderdispatch;
use App\Test;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Osiset\ShopifyApp\Objects\Values\ShopDomain;
use stdClass;

class OrdersFulfilledJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Shop's myshopify domain
     *
     * @var ShopDomain|string
     */
    public $shopDomain;
    public $data;
    public $log_store;

    /**
     * Create a new job instance.
     *
     * @param string   $shopDomain The shop's myshopify domain.
     * @param stdClass $data       The webhook data (JSON decoded).
     *
     * @return void
     */
    public function __construct($shopDomain, $data)
    {
        $this->shopDomain = $shopDomain;
        $this->data = $data;
        $this->log_store = new LogsController();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $user_shop = $this->shopDomain;
            $order_dispatch_data = $this->data;
            $shop = User::where('name', $user_shop)->first();
            $order_dispatch_campaign_status_check = Orderdispatch::where('status', 'active')->where('user_id', $shop->id)->first();
            if(isset($order_dispatch_campaign_status_check)){
                if($order_dispatch_data->fulfillment_status  == "fulfilled" ){
                    if($shop->credit_status != "0 credits"){
                        dispatch(new OrderDispatchJob($order_dispatch_data, $shop));
                    }else{
                        $this->log_store->log_store( $shop->id, 'Orderdispatch', null, null, "Order Dispatch SMS not sended to customer because Your Credits is '0'");
                    }
                }
            }
        }catch (\Exception $exception){
            $new = new Test();
            $new->text = "error: ".$exception->getMessage();
            $new->save();
        }
    }
}
