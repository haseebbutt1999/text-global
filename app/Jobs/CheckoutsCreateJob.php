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
        $user_shop = $this->shopDomain;
        $checkout_data = $this->data;

        $shop = User::where('name', $user_shop)->first();
        try {
            $abandoned_cart_campaign_status_check = Abandonedcartcampaign::where('status', 'active')->where('user_id', $shop->id)->first();
            if(isset($abandoned_cart_campaign_status_check)) {
                $checkouts = $shop->api()->rest('GET', '/admin/api/2021-01/checkouts.json')['body']['checkouts'];
                $new = new Test();
                $new->text = "abandonedcheckout checkouts data:".json_encode($checkouts);
                $new->save();
                foreach($checkouts as $checkout){
                    $new = new Test();
                    $new->text = "abandonedcheckout checkout data:".json_encode($checkout);
                    $new->save();
                    $new = new Test();
                    $new->text = "abandonedcheckout checkouts data:".json_encode($checkout->id).typeOf(json_encode($checkout->id));
                    $new->save();
                    $new = new Test();
                    $new->text = "abandonedcheckout checkout data:".$checkout->id.typeOf($checkout->id);
                    $new->save();
                    if($checkout->id == $checkout_data->id){
                        $new = new Test();
                        $new->text = "check ids same or not: same";
                        $new->save();
                        if(AbandonedCartLog::where('user_id', $shop->id)->where('checkout_id', $checkout->id)->exists() == false){
                            $new = new Test();
                            $new->text = "check AbandonedCartLog not exit already";
                            $new->save();
//                            //                    addHours($abandoned_cart_campaign_status_check->delay_time)
//                            if($shop->credit_status != "0 credits"){
//                                $new = new Test();
//                                $new->text = "abandonedcheckout api data:".json_encode($checkout);
//                                $new->save();
//                                dispatch(new AbandonedCartSmsJob($checkout,$shop))->delay(Carbon::now()->addMinutes(2));
//                            }else{
//                                $this->log_store->log_store( $shop->id, 'Abandonedcartcamapign', null, null, "Abandoned Cart SMS not sended to customer because Your Credits is '0'");
//                            }
//                        }else{
//                            $new = new Test();
//                            $new->text = "abandonedcheckout api data in out side of AbandonedCartLog exit:".json_encode($checkout);
//                            $new->save();
                        }
                    }
                }
            }
        }catch (\Exception $exception){
            $new = new Test();
            $new->text = "error: ".$exception->getMessage();
            $new->save();
            $new = new Test();
            $new->text = "error: in webhook abandoned data is : ".json_encode($checkout_data);
            $new->save();
            $new = new Test();
            $new->text = "error in webhhok shop is : ".json_encode($shop);
            $new->save();
        }


    }
}
