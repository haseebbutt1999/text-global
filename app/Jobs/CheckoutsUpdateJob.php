<?php namespace App\Jobs;

use App\Abandonedcartcampaign;
use App\AbandonedCartLog;
use App\Http\Controllers\LogsController;
use App\Test;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Osiset\ShopifyApp\Objects\Values\ShopDomain;
use stdClass;

class CheckoutsUpdateJob implements ShouldQueue
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
        $user_shop = $this->shopDomain;
        $checkout_data = $this->data;

        $test = new Test();
        $test->text = json_encode('checkout:'.$checkout_data);
        $test->save();

        $shop = User::where('name', $user_shop)->first();
        try {
            $abandoned_cart_campaign_status_check = Abandonedcartcampaign::where('status', 'active')->where('user_id', $shop->id)->first();
            if(isset($abandoned_cart_campaign_status_check)) {
                $abandonedCartSmsStatus = AbandonedCartLog::where('user_id', $shop->id)->where('checkout_id', $checkout_data->id)->where('status', 'sended')->first();
                if($abandonedCartSmsStatus == null){
                    if($shop->credit_status != "0 credits"){
                        dispatch(new AbandonedCartSmsJob($checkout_data,$shop))->delay(Carbon::now()->addHours($abandoned_cart_campaign_status_check->delay_time));
                    }else{
                        $this->log_store->log_store( $shop->id, 'Abandonedcartcamapign', null, null, "Abandoned Cart SMS not sended to customer because Your Credits is '0'");
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
