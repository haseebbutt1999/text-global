<?php namespace App\Jobs;

use App\Address;
use App\Customer;
use App\Http\Controllers\LogsController;
use App\Test;
use App\User;
use App\Welcomecampaign;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Mockery\Exception;
use Osiset\ShopifyApp\Objects\Values\ShopDomain;
use PhpParser\Node\Stmt\TryCatch;
use stdClass;

class CustomerCreateJob implements ShouldQueue
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
        $pushed_customer=[];
        $user_shop = $this->shopDomain;
        $shop = User::where('name', $user_shop)->first();
        $customer_data=$this->data;
        try {
            $customer = Customer::where('user_id', $shop->id)->where('shopify_customer_id', $customer_data->id)->first();
            if($customer === null){
                $customer = New Customer();
            }

            $customer->shopify_customer_id = $customer_data->id;
            $customer->user_id = $shop->id;

            $customer->email = $customer_data->email;
            $customer->first_name = $customer_data->first_name;
            $customer->last_name = $customer_data->last_name;
            $customer->phone = $customer_data->phone;
            $customer->currency = $customer_data->currency;
            $customer->accepts_marketing = $customer_data->accepts_marketing;
            $customer->state = $customer_data->state;
            $customer->note = $customer_data->note;
            $customer->orders_count = $customer_data->orders_count;
            $customer->total_spent = $customer_data->total_spent;
            $customer->last_order_id = $customer_data->last_order_id;
            $customer->addresses = json_encode($customer_data->addresses);
            $customer->verified_email = $customer_data->verified_email;
            $customer->accepts_marketing_updated_at = Carbon::createFromTimeString($customer_data->accepts_marketing_updated_at)->format('Y-m-d H:i:s');
            $customer->marketing_opt_in_level = $customer_data->marketing_opt_in_level;
            $customer->save();
            $this->log_store->log_store( $shop->id, 'Customer', null, $customer->first_name, 'Customer Register Successfully');

            foreach($customer_data->addresses as $address){
                $address_customer = Address::where('shopify_customer_id', $address->customer_id)->where('shopify_address_id', $address->id)->first();
                if($address_customer === null){
                    $address_customer = new Address();
                }
                $address_customer->shopify_address_id = $address->id;
                $address_customer->shopify_customer_id = $address->customer_id;
//            $address_customer->shopify_shop_id = Auth::user()->id;
                $address_customer->first_name = $address->first_name;
                $address_customer->last_name = $address->last_name;
                $address_customer->company = $address->company;
                $address_customer->address1 = $address->address1;
                $address_customer->address2 = $address->address2;
                $address_customer->city = $address->city;
                $address_customer->province = $address->province;
                $address_customer->country = $address->country;
                $address_customer->zip = $address->zip;
                $address_customer->phone = $address->phone;
                $address_customer->name = $address->name;
                $address_customer->province_code = $address->province_code;
                $address_customer->country_code = $address->country_code;
                $address_customer->default = $address->default;
                $address_customer->save();
            }

            $user_select_countries = $shop->countries;
            foreach ($user_select_countries as $countries) {
                foreach ($customer->addressess as $add){
                    if($add->country == $countries->name){
                        $customer = json_decode(json_encode($customer));
                        array_push($pushed_customer , $customer);
                    }
                }
            }
            $welcome_campaign_status_check = Welcomecampaign::where('status', 'active')->where('user_id', $shop->id)->first();
            if(isset($welcome_campaign_status_check)){
                foreach($pushed_customer  as $pushed_cust){
                    $welcome_campaign = Welcomecampaign::where('user_id', $shop->id)->first();
                    $data = [
                        "from" => $welcome_campaign->sender_name,
                        "to" => $pushed_cust->phone,
                        "text" => $welcome_campaign->message_text,
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
                        $test->text = "cURL Error #:" .$err;
                        $this->log_store->log_store( $shop->id, 'Welcomecampaign', $welcome_campaign->id, $welcome_campaign->campaign_name, 'Welcome Sms not Sended');
                        //
                        $test->save();
                    } else {
                        $test = new Test();
                        $test->number = 200;
                        $test->text = "Successful Staus:" .$response;
                        $test->save();
                        $this->log_store->log_store($shop->id, 'Welcomecampaign', $welcome_campaign->id, $welcome_campaign->campaign_name, 'Welcome Sms Sended Successfully to new Customer ('.$customer->first_name.')');
                        //                Detect Credits
                        $user = User::Where('id', $welcome_campaign->user_id)->first();
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
        }




//            save customer end

//            $user_select_countries = $shop->countries;
//            foreach ($user_select_countries as $countries) {
//                foreach ($customer->addressess as $add){
//                    if($add->country == $countries->name){
//                        $users = json_decode(json_encode($customer, TRUE));
//                        array_push($pushed_users , $users);
//                    }
//                }
//
//            }
//
//            foreach ($pushed_users as $user_data){
//            $welcome_campaign = Welcomecampaign::where('user_id', $user_data->id)->first();
//            $data = [
//                "from" => $welcome_campaign->sender_name,
//                "to" => $customer->phone,
//                "text" => $welcome_campaign->message_text,
//            ];
//
//            $curl = curl_init();
//            curl_setopt_array($curl, array(
//                CURLOPT_URL => "http://api.messaging-service.com/sms/1/text/single",
//                CURLOPT_RETURNTRANSFER => true,
//                CURLOPT_ENCODING => "",
//                CURLOPT_MAXREDIRS => 10,
//                CURLOPT_TIMEOUT => 30,
//                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//                CURLOPT_CUSTOMREQUEST => "POST",
//                CURLOPT_POSTFIELDS => $data,
//                CURLOPT_HTTPHEADER => array(
//                    "accept: application/json",
//                    "authorization: Basic c2hvcGlmeWFwcC50ZXh0Z2xvYmFsOlRHc2hvcGlmeTEh",
//                    "cache-control: no-cache",
//                    "content-type: application/json",
//                    "postman-token: 04d5825f-6285-666b-6d0c-968ce3f6fd25"
//                ),
//            ));
//
//            $response = curl_exec($curl);
//            $err = curl_error($curl);
//
//            curl_close($curl);
//
//            if ($err) {
//                $test = new Test();
//                $test->number = 404;
//                $test->text = "cURL Error #:" .$err;
//                $this->log_store->log_store( $user_data->id, 'Welcomecampaign', $welcome_campaign->id, $welcome_campaign->campaign_name, 'Welcome Sms not Sended' , $notes = $err);
//        //
//                $test->save();
//            } else {
//                $test = new Test();
//                $test->number = 200;
//                $test->text = "Successful Staus:" .$response;
//                $test->save();
//                $this->log_store->log_store( $user_data->id, 'Welcomecampaign', $welcome_campaign->id, $welcome_campaign->campaign_name, 'Welcome Sms Sended Successfully to new Customer' , $notes = $response);
//        //                Detect Credits
//                $user = User::Where('id', $welcome_campaign->user_id)->first();
//                if($user->credit >= 0){
//                    $user->credit =  $user->credit - 1;
//                }else{
//                    $user->credit_status = "0 credits";
//                }
//                $user->save();
//            }
//        }
        // Convert domain
    }


}
