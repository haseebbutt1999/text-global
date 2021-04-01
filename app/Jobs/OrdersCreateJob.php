<?php namespace App\Jobs;

use App\Abandonedcartcampaign;
use App\AbandonedCartLog;
use App\Orderconfirm;
use App\Orderdispatch;
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

class OrdersCreateJob implements ShouldQueue
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
            $order_confirm_data = $this->data;
            $shop = User::where('name', $user_shop)->first();
            $order_confirm_campaign_status_check = Orderconfirm::where('status', 'active')->where('user_id', $shop->id)->first();
            if(isset($order_confirm_campaign_status_check)){
                $new = new Test();
                $new->text = json_encode($order_confirm_data);
                $new->save();
                dispatch(new OrderConfirmJob($order_confirm_data,$shop));
            }
        }catch (\Exception $exception){
            $new = new Test();
            $new->text = "error: ".$exception->getMessage();
            $new->save();
            $new = new Test();
            $new->text = "error: in webhook order data is : ".json_encode($order_confirm_data);
            $new->save();
            $new = new Test();
            $new->text = "error in webhhok shop is : ".json_encode($shop);
            $new->save();
        }
    }
}
