<?php

namespace App\Jobs;

use App\Abandonedcartcampaign;
use App\AbandonedCartLog;
use App\Http\Controllers\LogsController;
use App\order;
use App\Test;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AbandonedCartSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $checkout_data;
    public $shop;
    public $log_store;
    public $user_log;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($checkout_data, $shop)
    {
        $this->checkout_data = $checkout_data;
        $this->shop = $shop;
        $this->log_store = new LogsController();
        $this->user_log = new LogsController();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $checkout_data = $this->checkout_data;
        $shop = $this->shop;
        $order_checkout_check = order::where('user_id', $shop->id)->where('checkout_id', $checkout_data->id)->first();
        $test = new Test();
        $test->text = "order_checkout:".json_encode($order_checkout_check);
        $test->save();
        if($order_checkout_check == null){
            try {

                $order_customer_phone_nummber = $checkout_data->billing_address->phone;
                $order_customer_country = $checkout_data->billing_address->country;

                $country_users = $shop->countries;
                foreach ($country_users as $country_user){
                    if($country_user->name == $order_customer_country){
                        $abandoned_cart_campaign = Abandonedcartcampaign::where('user_id', $shop->id)->first();
                        $product_id_array = [];
                        foreach ($checkout_data->line_items as $line_item){
                            $product_id = $line_item->product_id;
                            array_push($product_id_array, $product_id);
                        }
                        $product_ids = implode(", ",$product_id_array);
                        $messgae_text = str_replace('{CustomerName}',$checkout_data->billing_address->first_name." ".$checkout_data->billing_address->last_name,$abandoned_cart_campaign->message_text);
                        $messgae_text = str_replace('{ProductId}',$product_ids,$messgae_text);
                        $messgae_text = str_replace('{TotalPrice}',$checkout_data->total_price,$messgae_text);
                        $messgae_text = str_replace('{AbandonedCheckoutUrl}',$checkout_data->abandoned_checkout_url,$messgae_text);
                        $messgae_text = str_replace('{Currency}',$checkout_data->currency,$messgae_text);

                        $data = [
                            "from" => $abandoned_cart_campaign->sender_name,
                            "to" => $order_customer_phone_nummber,
                            "text" => $messgae_text,
                        ];

//                        $username = User::find($shop->id)->shopdetail->user_name;
//                        $password = User::find($shop->id)->shopdetail->password;
//                        $auth = "Basic ". base64_encode("$username:$password");

                        $curl = curl_init();
                        curl_setopt_array($curl, array(
                            CURLOPT_URL => "http://api.messaging-service.com/sms/1/text/single",
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => "",
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 30,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => "POST",
                            CURLOPT_POSTFIELDS => json_encode($data),
                            CURLOPT_HTTPHEADER => array(
                                "accept: application/json",
                                "authorization: Basic c2hvcGlmeWFwcC50ZXh0Z2xvYmFsOlRHc2hvcGlmeTEh",
                                "cache-control: no-cache",
                                "content-type: application/json",
                                "postman-token: 04d5825f-6285-666b-6d0c-968ce3f6fd25"
                            ),
                        ));

                        $response = curl_exec($curl);
                        $err = curl_error($curl);

                        curl_close($curl);

                        if ($err) {
                            $test = new Test();
                            $test->number = 404;
                            $test->text = "Abandonedcartcampaign cURL Error #:" .$err;
                            $this->log_store->log_store( $shop->id, 'Abandonedcartcampaign', $abandoned_cart_campaign->id, $abandoned_cart_campaign->campaign_name, 'Abandonedcartcampaign SMS not Sended');
                            $test->save();

                        } else {
                            $response = json_decode($response);
                            if($response->messages[0]->status->name == "PENDING_ENROUTE"){
                                $test = new Test();
                                $test->text = "abandoned data:" .json_encode($checkout_data);
                                $test->save();
                                $this->log_store->log_store($shop->id, 'Abandonedcartcampaign', $abandoned_cart_campaign->id, $abandoned_cart_campaign->campaign_name, 'Abandonedcartcampaign SMS Sended Successfully to Customer ('.$checkout_data->billing_address->first_name.')');
                                $this->user_log->user_log( $shop->id, 'Abandonedcartcampaign', null , $checkout_data->customer->id, "Abandonedcartcampaign SMS Sended Successfully to Customer (".$checkout_data->billing_address->first_name.")", "sended");

                                //                Detect Credits
                                $user = User::Where('id', $abandoned_cart_campaign->user_id)->first();
                                if($user->credit >= 0){
                                    $user->credit =  $user->credit - $abandoned_cart_campaign->calculated_credit_per_sms;
                                }else{
                                    $user->credit_status = "0 credits";
                                }
                                $user->save();
                                $abandoned_cart_log_status = new AbandonedCartLog();
                                $abandoned_cart_log_status->user_id = $shop->id;
                                $abandoned_cart_log_status->checkout_id = $checkout_data->id;
                                $abandoned_cart_log_status->status = "sended";
                                $abandoned_cart_log_status->save();
                            }else{
                                $test = new Test();
                                $test->text = "rejected msg:" .$response->messages[0]->status->description;
                                $test->save();
                                $this->log_store->log_store($shop->id, 'Abandonedcartcampaign', $abandoned_cart_campaign->id, $abandoned_cart_campaign->campaign_name, 'Abandonedcartcampaign SMS not Sended.');
                                $this->user_log->user_log( $shop->id, 'Abandonedcartcampaign', null , $checkout_data->customer->id, "Abandonedcartcampaign SMS not Sended (".$checkout_data->billing_address->first_name.") because ".$response->messages[0]->status->description, "not sended");

                            }
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

}
