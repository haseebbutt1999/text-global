<?php

namespace App\Console\Commands;

use App\Customer;
use App\Http\Controllers\LogsController;
use App\LapsedCustomer;
use App\Test;
use App\User;
use Illuminate\Console\Command;
use Matrix\Exception;

class LapsedCustomerCronJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lapsed:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send SMS to lapsed customers';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->log_store = new LogsController();
        $this->user_log = new LogsController();
        try {
            $shops = User::where('role', 'user')->get();

            foreach ($shops as $shop) {
                $customers = Customer::where('user_id', $shop->id)->get();
                foreach ($customers as $customer) {

                    $customer_country = $customer->addressess[0]->country;
                    $customer_phone = $customer->addressess[0]->phone;

                    $country_users = $shop->countries;
                    //                dd($country_users);
                    foreach ($country_users as $country_user) {
                        if ($country_user->name == $customer_country ) {
                            if ($customer->orders->count() > 1) {
                                if($shop->credit != 0 && $shop->credit > 0){
                                    $to = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', date("Y-m-d H:s:i"));
                                    $from = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $customer->last_order_date);
                                    $diff_in_days = $to->diffInDays($from);
                                    $lapsed_campaign = LapsedCustomer::where('user_id', $shop->id)->first();
//
                                    if ($diff_in_days >= $lapsed_campaign->days) {
                                        // send sms to lapsed customer
                                        $messgae_text = str_replace('{CustomerName}', $customer->first_name . " " . $customer->last_name, $lapsed_campaign->message_text);
//
                                        $data = [
                                            "from" => $lapsed_campaign->sender_name,
                                            "to" => $customer_phone,
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
                                            $test->text = "Lapsed Customers cURL Error #:" . json_encode($err);
                                            $this->log_store->log_store($shop->id, 'LapsedCustomer', $lapsed_campaign->id, $messgae_text, $lapsed_campaign->campaign_name, 'Failed');
                                            //
                                            $test->save();
                                        } else {
                                            $response = json_decode($response);
                                            if ($response->messages[0]->status->name == "PENDING_ENROUTE") {
                                                $this->log_store->log_store($shop->id, 'LapsedCustomer', $lapsed_campaign->id, $messgae_text, $lapsed_campaign->campaign_name, 'Sent');
                                                $this->user_log->user_log($shop->id, $customer->phone, $customer->first_name, $customer->last_name, $messgae_text, 'LapsedCustomer',  null, $customer->shopify_customer_id,null, "Send");

                                                //                Detect Credits
                                                $user = User::Where('id', $lapsed_campaign->user_id)->first();
                                                $messgae_text_count = strlen($messgae_text);
                                                if ($messgae_text_count >= 0) {
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

                                                    $user->credit = $user->credit - $credit;
                                                } else {
                                                    $user->credit_status = "0 credits";
                                                }
                                                $user->save();
                                            } else {
                                                $test = new Test();
                                                $test->text = "lapsed customer rejected msg:" . $response->messages[0]->status->description;
                                                $test->save();
                                                $this->user_log->user_log($shop->id, $customer->phone, $customer->first_name, $customer->last_name, $messgae_text, 'LapsedCustomer', null, null, 'Failed', "not sended");
                                                $this->log_store->log_store($shop->id, 'LapsedCustomer', $lapsed_campaign->id, $messgae_text, $lapsed_campaign->campaign_name, 'Failed');

                                            }

                                        }
                                    }
                                }else{
                                    $this->user_log->user_log($shop->id, $customer->phone, $customer->first_name, $customer->last_name, '', 'LapsedCustomer', null, null, 'Failed due to 0 credit !', "not sended");
                                }
                            }
                        }
                    }
                }
            }
            return 0;
        }catch (Exception $exception){
            $test = new Test();
            $test->text = "Lapsed Customers Exception error" . json_encode($exception->getMessage());
            $test->save();
        }

    }
}
