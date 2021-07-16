<?php namespace App\Jobs;

use App\Http\Controllers\LogsController;
use App\Orderrefund;
use App\Test;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Osiset\ShopifyApp\Objects\Values\ShopDomain;
use stdClass;

class OrdersUpdatedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Shop's myshopify domain
     *
     * @var ShopDomain|string
     */
    public $shopDomain;

    /**
     * The webhook data
     *
     * @var object
     */
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
            $order_refund_data = $this->data;
            $shop = User::where('name', $user_shop)->first();
            $order_refund_campaign_status_check = Orderrefund::where('status', 'active')->where('user_id', $shop->id)->first();
            if(isset($order_refund_campaign_status_check)){
                if($order_refund_data->financial_status  == "refunded" ){
                    if($shop->credit_status != "0 credits"){
                        dispatch(new OrderRefundJob($order_refund_data, $shop));
                    }else{
                        $this->log_store->log_store( $shop->id, 'Orderrefund', null, null, "Order refund SMS not sended to customer because Your Credits is '0'",null);
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
