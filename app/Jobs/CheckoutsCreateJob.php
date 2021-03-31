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
use Illuminate\Support\Facades\Auth;
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
        try {
            $user_shop = $this->shopDomain;
            $checkouts = $this->data;
            $shop = User::where('name', $user_shop)->first();
            $abandoned_cart_campaign_status_check = Abandonedcartcampaign::where('status', 'active')->where('user_id', $shop->id)->first();
            if(isset($abandoned_cart_campaign_status_check)) {
                foreach($checkouts as $checkout){
                    $new = new Test();
                    $new->text = "error: ".$checkout->id;
                    $new->save();
                    if(AbandonedCartLog::where('user_id', $shop->id)->where('checkout_id', $checkout->id)->exists() == false){
//                    addHours($abandoned_cart_campaign_status_check->delay_time)
                        dispatch(new AbandonedcartSmsDispacthJob($checkout,$shop))->delay(Carbon::now()->addSeconds(60));
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
