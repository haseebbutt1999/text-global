<?php namespace App\Jobs;

use App\Abandonedcartcampaign;
use App\AbandonedCartLog;
use App\Http\Controllers\LogsController;
use App\Test;
use App\User;
use App\Welcomecampaign;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Osiset\ShopifyApp\Objects\Values\ShopDomain;
use stdClass;

class CheckoutsCreateJob implements ShouldQueue
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
        // Convert domain
        $user_shop = $this->shopDomain;
        $checkout_data = $this->data;
        $shop = User::where('name', $user_shop)->first();
        try {

            $abandoned_cart_campaign_status_check = Abandonedcartcampaign::where('status', 'active')->where('user_id', $shop->id)->first();
            if(isset($abandoned_cart_campaign_status_check)) {
                    //                    addHours($abandoned_cart_campaign_status_check->delay_time)
                $new = new Test();
                $new->text = json_encode($checkout_data);
                $new->save();
                if($shop->credit_status != "0 credits"){
                    dispatch(new AbandonedcartSmsDispacthJob($checkout_data,$shop))->delay(Carbon::now()->addSeconds(60));
                }else{
                    $this->log_store->log_store( $shop->id, 'Abandonedcartcampaign', null, null, "Abandoned Cart SMS not sended to customer because Your Credits is '0'");
                }


            }
        }catch (\Exception $exception){
            $new = new Test();
            $new->text = "error: ".$exception->getMessage();
            $new->save();
            $new = new Test();
            $new->text = "error: in webhook abandoned cart data is : ".json_encode($order_dispatch_data);
            $new->save();
            $new = new Test();
            $new->text = "error in webhook shop is : ".json_encode($shop);
            $new->save();
        }


    }
}
