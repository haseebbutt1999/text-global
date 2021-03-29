<?php namespace App\Jobs;

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
use phpDocumentor\Reflection\Types\True_;
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
        $data = $this->data;
        try {

            $shop = $this->shopDomain;
            $shop = User::where('name', $shop)->first();
            $data = $this->data;
            $data=json_decode(json_encode($data),FALSE);

            $test = new Test();
            $test->text = "cURL Error #:" .$data;
            $test->save();
            $test = new Test();
            $test->text = "shopdomain #:" .$shop;
            $test->save();
//            $welcome_campaign = Welcomecampaign::where('user_id', Auth::user()->id)->first();
//            $data = [
//                "from" => $welcome_campaign->sender_name,
//                "to" => $this->data->phone,
//                "text" => $welcome_campaign->message_text,
//            ];
//            $data = json_encode($data);
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
//                $test->save();
//            } else {
//                $test = new Test();
//                $test->number = 200;
//                $test->text = "Successful Staus:" .$response;
//                $test->save();
////                Detect Credits
//                $user = User::Where('id', $welcome_campaign->user_id)->first();
//                if($user->credit >= 0){
//                    $user->credit =  $user->credit - 1;
//                }else{
//                    $user->credit_status = "0 credits";
//                }
//                $user->save();
//            }
        } catch (\Exception $exception){
            $new = new Test();
            $new->text = "catch error:".$exception->getMessage();
            $new->save();
        }
        // Convert domain


//        $this->customer->welcome_sms_status = $this->welcome_sms_status;
//        $this->customer->save();

        // Do what you wish with the data
        // Access domain name as $this->shopDomain->toNative()
    }
}
