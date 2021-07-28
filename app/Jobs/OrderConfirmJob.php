<?php

namespace App\Jobs;

use App\AbandonedCartLog;
use App\Customer;
use App\Http\Controllers\LogsController;
use App\order;
use App\Orderconfirm;
use App\Test;
use App\User;
use App\UserCamapignLog;
use Carbon\Carbon;
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
    public $log_store;
    public $user_log;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($order_confirm_data, $shop)
    {
        $this->order_confirm_data = $order_confirm_data;
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
        $order_confirm_data = $this->order_confirm_data;
        $shop = $this->shop;
        $test = new Test();
        $test->text = json_encode($order_confirm_data);
        $test->save();

        try {

            $order_checkout_save = order::where('user_id', $shop->id)->where('order_id', $order_confirm_data->id)->where('checkout_id', $order_confirm_data->checkout_id)->first();
            if($order_checkout_save == null){
                $order_checkout_save = new order();
            }
            $order_checkout_save->user_id = $shop->id;
            $order_checkout_save->order_id = $order_confirm_data->id;
            $order_checkout_save->checkout_id = $order_confirm_data->checkout_id;
            $order_checkout_save->customer_id = $order_confirm_data->customer->id;
            $customer = Customer::where('user_id', $shop->id)->where('shopify_customer_id',$order_confirm_data->customer->id)->first();
            if( $customer != null){
                $customer->last_order_date =  Carbon::createFromTimeString($order_confirm_data->created_at)->format('Y-m-d H:i:s');
                $customer->orders_count = $order_confirm_data->customer->orders_count;
                $customer->save();
            }
            $order_checkout_save->save();

            $abandoned_conversion = AbandonedCartLog::where('user_id', $shop->id)->where('checkout_id', $order_confirm_data->checkout_id)->first();
            if($abandoned_conversion != null){
                $abandoned_conversion->conversion_status = "confirmed";
                $abandoned_conversion->save();
            }

            $order_customer_phone_nummber = $order_confirm_data->billing_address->phone;
            $order_customer_country = $order_confirm_data->billing_address->country;

            $country_users = $shop->countries;
            //                dd($country_users);
            foreach ($country_users as $country_user){
                if($country_user->name == $order_customer_country){
                    $order_confirm_campaign = Orderconfirm::where('user_id', $shop->id)->first();

                    $messgae_text = str_replace('{CustomerName}',$order_confirm_data->billing_address->first_name." ".$order_confirm_data->billing_address->last_name,$order_confirm_campaign->message_text);
                    $messgae_text = str_replace('{OrderName}',$order_confirm_data->name,$messgae_text);
                    $messgae_text = str_replace('{FinancialStatus}',$order_confirm_data->financial_status,$messgae_text);
                    $messgae_text = str_replace('{OrderStatusUrl}',$order_confirm_data->order_status_url,$messgae_text);
                    $messgae_text = str_replace('{OrderTotalPrice}',$order_confirm_data->total_price,$messgae_text);
                    $messgae_text = str_replace('{Currency}',$order_confirm_data->currency,$messgae_text);

                    $data = [
                        "from" => $order_confirm_campaign->sender_name,
                        "to" => $order_customer_phone_nummber,
                        "text" => $messgae_text,
                    ];

//                          $username = User::find($shop->id)->shopdetail->user_name;
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
                        $test->text = "order confirm cURL Error #:" .$err;
                        $this->log_store->log_store( $shop->id, 'Orderconfirm', $order_confirm_campaign->id,$messgae_text, $order_confirm_campaign->campaign_name, 'Failed');
                        $test->save();
                    } else {

                        $response = json_decode($response);
                        if($response->messages[0]->status->name == "PENDING_ENROUTE"){
                            $this->log_store->log_store($shop->id, 'Orderconfirm', $order_confirm_campaign->id,$messgae_text, $order_confirm_campaign->campaign_name, 'Sent');
                            $this->user_log->user_log( $shop->id, $order_confirm_data->billing_address->phone,$order_confirm_data->billing_address->first_name,$order_confirm_data->billing_address->last_name,$messgae_text,'Orderconfirm', $order_confirm_data->name , $order_confirm_data->customer->id, 'Sent', "sended");

                            //                Detect Credits
                            $user = User::Where('id', $shop->id)->first();
                            $messgae_text_count = strlen($messgae_text);
                            if($messgae_text_count >= 0){
                                $credit = 0;
                                if ($messgae_text_count <= 0) {
                                    $credit = 0;
                                } else if ($messgae_text_count <= 160) {
                                    $credit = 1;
                                } else if ($messgae_text_count <= 306) {
                                    $credit = 2;
                                } else if ($messgae_text_count <= 460) {
                                    $credit = 3;
                                } else if ($messgae_text_count <= 612) {
                                    $credit = 4;
                                }

                                $user->credit =  $user->credit - $credit;
                            }else{
                                $user->credit_status = "0 credits";
                            }
                            $user->save();
                        }else{
                            $test = new Test();
                            $test->text = "rejected msg:" .$response->messages[0]->status->description;
                            $test->save();
                            $this->user_log->user_log( $shop->id, $order_confirm_data->billing_address->phone,$order_confirm_data->billing_address->first_name,$order_confirm_data->billing_address->last_name,$messgae_text,'Orderconfirm', $order_confirm_data->name , $order_confirm_data->customer->id, 'Failed', "not sended");
                            $this->log_store->log_store($shop->id, 'Orderconfirm', $order_confirm_campaign->id,$messgae_text, $order_confirm_campaign->campaign_name, 'Failed');
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
