<?php

namespace App\Http\Controllers;

use App\Abandonedcartcampaign;
use App\AbandonedCartLog;
use App\Address;
use App\Campaign;
use App\Customer;
use App\Jobs\WelcomeSmsJob;
use App\Jobs\WelcomeSms;
use App\Mail\WelcomeEmail;
use App\Shopdetail;
use App\Test;
use App\User;
use App\Welcomecampaign;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Osiset\ShopifyApp\Storage\Queries\Shop;

class CustomerController extends Controller
{
    public function customer_sync(){

        $customer = Auth::user();
        $customer = $customer->api()->rest('GET', '/admin/customers.json')['body']['customers'];
//        $customer = $customer->api()->rest('GET', '/admin/orders.json');
//        dd($customer);
        //           fetch customer
        foreach ($customer as $customer_check){
            $customer = Customer::where('user_id', Auth::user()->id)->where('shopify_customer_id', $customer_check->id)->first();
            if($customer === null){
                $customer = New Customer();
            }

            $customer->shopify_customer_id = $customer_check->id;
            $customer->user_id = Auth::user()->id;

            $customer->email = $customer_check->email;
            $customer->first_name = $customer_check->first_name;
            $customer->last_name = $customer_check->last_name;
            $customer->phone = $customer_check->default_address->phone;
            $customer->currency = $customer_check->currency;
            $customer->accepts_marketing = $customer_check->accepts_marketing;
            $customer->state = $customer_check->state;
            $customer->note = $customer_check->note;
            $customer->orders_count = $customer_check->orders_count;
            $customer->total_spent = $customer_check->total_spent;
            $customer->last_order_id = $customer_check->last_order_id;
            $customer->addresses = json_encode($customer_check->addresses);
            $customer->verified_email = $customer_check->verified_email;
            $customer->accepts_marketing_updated_at = Carbon::createFromTimeString($customer_check->accepts_marketing_updated_at)->format('Y-m-d H:i:s');
            $customer->created_date = Carbon::createFromTimeString($customer_check->created_at)->format('Y-m-d');
            $customer->created_at = Carbon::createFromTimeString($customer_check->created_at)->format('Y-m-d H:i:s');
            $customer->updated_at = Carbon::createFromTimeString($customer_check->updated_at)->format('Y-m-d H:i:s');
            $customer->marketing_opt_in_level = $customer_check->marketing_opt_in_level;
            $customer->save();
            //           fetch customer end

            //           fetch address
            foreach($customer_check->addresses as $address)
//                dd(  $address->id);
                $address_customer = Address::where('shopify_customer_id', $address->customer_id)->where('shopify_address_id', $address->id)->first();
//            dd($address_customer);
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
        return redirect()->back()->with('success','Customer Sync Successfully !');
    }

    public function test(){
        $username = User::where('name', "saifiqbal.myshopify.com")->first();
        dd($username->shopdetail->user_name);
        $password = Auth::user()->shopdetail->password;
        $auth = "Basic ". base64_encode("$username:$password");
        dd($auth);
//        GET /admin/api/2021-01/checkouts.json
//        $orders = Auth::user()->api()->rest('GET', '/admin/api/2021-01/checkouts.json')['body']['checkouts'];
//        dd($orders);
//        foreach ($orders as $order){
//            $product_id_array = [];
//            foreach ($order->line_items as $line_item){
//                $product_id = $line_item->product_id;
//                array_push($product_id_array, $product_id);
//            }
//            dd(implode(", ",$product_id_array));
//
//        }
////        $length = $orders->refunds;
//        $toEnd = count($orders->refunds);
////        dd($toEnd);
//        foreach($orders->refunds as $key=>$value) {
//            if (0 == --$toEnd) {
//                dd( $value);
//            }
//        }

//        foreach($checkouts as $checkout){
//            dd($checkout->id == $checkout->id);
//            if(AbandonedCartLog::where('user_id', Auth::user()->id)->where('checkout_id', $checkout->id)->exists() == false){
//                $customer_id = $checkout->customer->id;
//                $customer = Auth::user()->api()->rest('GET', '/admin/customers/'.$customer_id.'.json')['body']['customer'];
//                $customer_country = $customer->addresses['0']->country;
//                $customer_phone = $customer->addresses['0']->phone;
//
//                $country_users = Auth::user()->countries;
////                dd($country_users);
//                foreach ($country_users as $country_user){
//                    if($country_user->name == $customer_country){
//                        $abandoned_cart_campaign_status_check = Abandonedcartcampaign::where('status', 'active')->where('user_id', $shop->id)->first();
//                        if(isset($abandoned_cart_campaign_status_check)) {
//                            $welcome_campaign = Welcomecampaign::where('user_id', $shop->id)->first();
//
//                            $messgae_text = str_replace('{CustomerName}',$pushed_cust->first_name." ".$pushed_cust->last_name,$welcome_campaign->message_text);
//                            $test = new Test();
//                            $test->text = "Text Message customer name Variable:" .$messgae_text;
//                            $test->save();
//                            $data = [
//                                "from" => $abandoned_cart_campaign->sender_name,
//                                "to" => $pushed_cust->phone,
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
//                                $this->log_store->log_store( $shop->id, 'Welcomecampaign', $welcome_campaign->id, $welcome_campaign->campaign_name, 'Welcome Sms not Sended');
//                                //
//                                $test->save();
//                            } else {
//                                $test = new Test();
//                                $test->number = 200;
//                                $test->text = "Successful Staus:" .$response;
//                                $test->save();
//                                $this->log_store->log_store($shop->id, 'Welcomecampaign', $welcome_campaign->id, $welcome_campaign->campaign_name, 'Welcome Sms Sended Successfully to new Customer ('.$customer->first_name.')');
//                                //                Detect Credits
//                                $user = User::Where('id', $welcome_campaign->user_id)->first();
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

//        $camp = Campaign::where('id',6)->first();
//        $us = User::where('id',$camp->user_id)->first();
//        $user_select_countries = $us->countries;
//        $user_customer = $us->customers;
//        dd($user_customer);
//        $pushed_users=[];
//
//        foreach ($user_select_countries as $countries){
//            foreach ($user_customer as $uc){
//                foreach ($uc->addressess as $add){
//                    if($add->country == $countries->name){
//                        $users = json_decode(json_encode($uc, TRUE));
//                        array_push($pushed_users , $users);
//                    }
//                }
//            }
//
//        }
////        dd($pushed_users);
//        foreach ($pushed_users as $pu){
//            dd($pu->phone);
//        }

//        $user = User::Where('id', 19)->first();
//        if($user->credit >= 0){
//            $user->credit =  $user->credit - 1;
//        }else{
//            $user->credit_status = "0 credits";
//        }
//        $user->save();

//        Mail::from('tanveerhaseeb1999.com')->to('haseebtanveerbutt1999@gmail.com')->send(new WelcomeEmail($user->id));

//        $to_email = "haseebtanveerbutt1999@gmail.com";
//        $s= Shopdetail::where('id',11)->first();
//        dd($s->email);
//        Mail::to($s->email)->send(new WelcomeEmail($user->id));
//
//        if(Mail::failures() != 0) {
//            return "<p> Success! Your E-mail has been sent.</p>";
//        }
//
//        else {
//            return "<p> Failed! Your E-mail has not sent.</p>";
//        }
//        $pushed_customer = [];
//        $customer = Customer::find(10);
////        dd($customer);
//        $user_select_countries = auth::user()->countries;
//        foreach ($user_select_countries as $countries) {
//            foreach ($customer->addressess as $add){
//                if($add->country == $countries->name){
//                        $customer = json_decode(json_encode($customer));
//                    array_push($pushed_customer , $customer);
//                }
//            }
//        }
////        dd($pushed_customer);
//    foreach ($pushed_customer as $pushed_cust){
//        dd($pushed_cust->phone);
//    }

//        $data = [
//            "from" => "haseeb",
//            "to" => "+442079308181",
//            "text" => "hi this is message text",
//        ];
//        $curl = curl_init();
//        curl_setopt_array($curl, array(
//            CURLOPT_URL => "http://api.messaging-service.com/sms/1/text/single",
//            CURLOPT_RETURNTRANSFER => true,
//            CURLOPT_ENCODING => "",
//            CURLOPT_MAXREDIRS => 10,
//            CURLOPT_TIMEOUT => 30,
//            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//            CURLOPT_CUSTOMREQUEST => "POST",
//            CURLOPT_POSTFIELDS => json_encode($data),
//            CURLOPT_HTTPHEADER => array(
//                "accept: application/json",
//                "authorization: Basic c2hvcGlmeWFwcC50ZXh0Z2xvYmFsOlRHc2hvcGlmeTEh",
//                "cache-control: no-cache",
//                "content-type: application/json",
//                "postman-token: 04d5825f-6285-666b-6d0c-968ce3f6fd25"
//            ),
//        ));
//
//        $response = curl_exec($curl);
//        $err = curl_error($curl);
//
//        curl_close($curl);
//        if ($err) {
//            dd($err);
//        } else {
//            dd($response);
//        }
    }
}
