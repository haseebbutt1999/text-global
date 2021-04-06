<?php

namespace App\Jobs;

use App\Http\Controllers\LogsController;
use App\Orderdispatch;
use App\Test;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class OrderDispatchJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $order_dispatch_data;
    public $shop;
    Public $log_store;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($order_dispatch_data,$shop)
    {
        $this->order_dispatch_data = $order_dispatch_data;
        $this->shop = $shop;
        $this->log_store = new LogsController();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $order_dispatch_data = $this->order_dispatch_data;
        $shop = $this->shop;
        try {

            $order_customer_phone_nummber = $order_dispatch_data->billing_address->phone;
            $order_customer_country = $order_dispatch_data->billing_address->country;

            $country_users = $shop->countries;
            //                dd($country_users);
            foreach ($country_users as $country_user){
                if($country_user->name == $order_customer_country){
                    $order_dispatch_campaign = Orderdispatch::where('user_id', $shop->id)->first();

                    $messgae_text = str_replace('{CustomerName}',$order_dispatch_data->billing_address->first_name." ".$order_dispatch_data->billing_address->last_name,$order_dispatch_campaign->message_text);
                    $messgae_text = str_replace('{OrderName}',$order_dispatch_data->name,$messgae_text);
                    $messgae_text = str_replace('{FulfillmentStatus}',$order_dispatch_data->fulfillment_status,$messgae_text);
                    $messgae_text = str_replace('{FinancialStatus}',$order_dispatch_data->financial_status,$messgae_text);
                    $messgae_text = str_replace('{OrderStatusUrl}',$order_dispatch_data->order_status_url,$messgae_text);
                    $messgae_text = str_replace('{OrderTotalPrice}',$order_dispatch_data->total_price,$messgae_text);
                    $messgae_text = str_replace('{Currency}',$order_dispatch_data->currency,$messgae_text);

                    $data = [
                        "from" => $order_dispatch_campaign->sender_name,
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
                        $test->text = "order dispatch cURL Error #:" .$err;
                        $this->log_store->log_store( $shop->id, 'Orderdispatch', $order_dispatch_campaign->id, $order_dispatch_campaign->campaign_name, 'Order Dispatch SMS not Sended');
                        //
                        $test->save();
                    } else {
                        $response = json_decode($response);
                        if($response->messages[0]->status->name = "PENDING_ENROUTE"){
                            $this->log_store->log_store($shop->id, 'Orderdispatch', $order_dispatch_campaign->id, $order_dispatch_campaign->campaign_name, 'Order Dispatch SMS Sended Successfully to Customer ('.$order_dispatch_data->billing_address->first_name.')');
                            //                Detect Credits
                            $user = User::Where('id', $order_dispatch_campaign->user_id)->first();
                            if($user->credit >= 0){
                                $user->credit =  $user->credit - $order_dispatch_campaign->calculated_credit_per_sms;
                            }else{
                                $user->credit_status = "0 credits";
                            }
                            $user->save();
                        }else{

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
