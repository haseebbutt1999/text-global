<?php

namespace App\Jobs;

use App\Abandonedcartcampaign;
use App\AbandonedCartLog;
use App\Orderconfirm;
use App\Test;
use App\User;
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

            $order_customer_phone_nummber = $order_confirm_data->billing_address->phone;
            $order_customer_country = $order_confirm_data->billing_address->country;

            $country_users = $shop->countries;
            //                dd($country_users);
            foreach ($country_users as $country_user){
                if($country_user->name == $order_customer_country){
                    $order_confirm_campaign = Orderconfirm::where('user_id', $shop->id)->first();

                    $messgae_text = str_replace('{CustomerName}',$order_confirm_data->billing_address->first_name." ".$order_confirm_data->billing_address->last_name,$order_confirm_campaign->message_text);
                    $test = new Test();
                    $test->text = "'Order Confirm' Text msg is" .$messgae_text;
                    $test->save();
                    $data = [
                        "from" => $order_confirm_campaign->sender_name,
                        "to" => $order_customer_phone_nummber,
                        "text" => $messgae_text,
                    ];

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
                        $test->text = "order confirm cURL Error #:" .$err;
                        $this->log_store->log_store( $shop->id, 'Orderconfirm', $order_confirm_campaign->id, $order_confirm_campaign->campaign_name, 'Order Confirm SMS not Sended');
                        //
                        $test->save();
                    } else {
                        $test = new Test();
                        $test->number = 200;
                        $test->text = "order confirm Successful Staus:" .$response;
                        $test->save();
                        $this->log_store->log_store($shop->id, 'Orderconfirm', $order_confirm_campaign->id, $order_confirm_campaign->campaign_name, 'Order Confirm SMS Sended Successfully to Customer ('.$checkout->customer->first_name.')');
                        //                Detect Credits
                        $user = User::Where('id', $order_confirm_campaign->user_id)->first();
                        if($user->credit >= 0){
                            $user->credit =  $user->credit - 1;
                        }else{
                            $user->credit_status = "0 credits";
                        }
                        $user->save();
                    }

                }
            }

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
