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
        $user_shop = $this->shopDomain;
        $checkouts = $this->data;
        $shop = User::where('name', $user_shop)->first();

        $test = new Test();
        $test->text = json_encode($checkouts);
        $test->save();
//        $checkouts = $shop->api()->rest('GET', '/admin/api/2021-01/checkouts.json')['body']['checkouts'];
//        dd($checkouts);
//        foreach($checkouts as $checkout){
//            if(AbandonedCartLog::where('user_id', $shop->id)->where('checkout_id', $checkout->id)->exists() == false){
//                $customer_id = $checkout->customer->id;
//                $customer = $shop->api()->rest('GET', '/admin/customers/'.$customer_id.'.json')['body']['customer'];
//                $customer_country = $customer->addresses['0']->country;
//                $customer_phone = $customer->addresses['0']->phone;
//
//                $country_users = $shop->countries;
////                dd($country_users);
//                foreach ($country_users as $country_user){
//                    if($country_user->name == $customer_country){
//                        $abandoned_cart_campaign_status_check = Abandonedcartcampaign::where('status', 'active')->where('user_id', $shop->id)->first();
//                        if(isset($abandoned_cart_campaign_status_check)) {
//                            $abandoned_cart_campaign = Abandonedcartcampaign::where('user_id', $shop->id)->first();
//
//                            $messgae_text = str_replace('{CustomerName}',$checkout->customer->first_name." ".$checkout->customer->last_name,$abandoned_cart_campaign->message_text);
//                            $test = new Test();
//                            $test->text = "Text Message customer name Variable:" .$messgae_text;
//                            $test->save();
//                            $data = [
//                                "from" => $abandoned_cart_campaign->sender_name,
//                                "to" => $customer_phone,
//                                "text" => $messgae_text,
//                            ];
//
//                            $curl = curl_init();
//                            curl_setopt_array($curl, array(
//                                CURLOPT_URL => "http://api.messaging-service.com/sms/1/text/single",
//                                CURLOPT_RETURNTRANSFER => true,
//                                CURLOPT_ENCODING => "",
//                                CURLOPT_MAXREDIRS => 10,
//                                CURLOPT_TIMEOUT => 30,
//                                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//                                CURLOPT_CUSTOMREQUEST => "POST",
//                                CURLOPT_POSTFIELDS => json_encode($data),
//                                CURLOPT_HTTPHEADER => array(
//                                    "accept: application/json",
//                                    "authorization: Basic c2hvcGlmeWFwcC50ZXh0Z2xvYmFsOlRHc2hvcGlmeTEh",
//                                    "cache-control: no-cache",
//                                    "content-type: application/json",
//                                    "postman-token: 04d5825f-6285-666b-6d0c-968ce3f6fd25"
//                                ),
//                            ));
//
//                            $response = curl_exec($curl);
//                            $err = curl_error($curl);
//
//                            curl_close($curl);
//
//                            if ($err) {
//                                $test = new Test();
//                                $test->number = 404;
//                                $test->text = "cURL Error #:" .$err;
//                                $this->log_store->log_store( $shop->id, 'Abandonedcartcampaign', $abandoned_cart_campaign->id, $abandoned_cart_campaign->campaign_name, 'Abandoned Cart SMS not Sended');
//                                //
//                                $test->save();
//                            } else {
//                                $test = new Test();
//                                $test->number = 200;
//                                $test->text = "Successful Staus:" .$response;
//                                $test->save();
//                                $this->log_store->log_store($shop->id, 'Abandonedcartcampaign', $abandoned_cart_campaign->id, $abandoned_cart_campaign->campaign_name, 'Abandoned Cart SMS Sended Successfully to Customer ('.$checkout->customer->first_name.')');
//                                //                Detect Credits
//                                $user = User::Where('id', $abandoned_cart_campaign->user_id)->first();
//                                if($user->credit >= 0){
//                                    $user->credit =  $user->credit - 1;
//                                }else{
//                                    $user->credit_status = "0 credits";
//                                }
//                                $user->save();
//                            }
//                            dd('pakistan');
//                        }
//                    }
//                }
//
//                $abandoned_cart = new AbandonedCartLog();
//                $abandoned_cart->checkout_id = $checkout->id;
//                $abandoned_cart->user_id = Auth::user()->id;
//                $abandoned_cart->save();
//            }
//        }

    }
}
