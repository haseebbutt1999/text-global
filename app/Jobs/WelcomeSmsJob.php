<?php

namespace App\Jobs;

use App\Customer;
use App\Test;
use App\User;
use App\Welcomecampaign;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class WelcomeSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $welcome_campaign;
    public $welcome_sms_status="Sended";
    public $customer;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Welcomecampaign $welcome_campaign, Customer $customer)
    {
        $this->welcome_campaign = $welcome_campaign;
        $this->customer = $customer;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $data = [
            "from" => $this->welcome_campaign->sender_name,
            "to" => $this->customer->phone,
            "text" => $this->welcome_campaign->message_text,
        ];
        $data = json_encode($data);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://api.messaging-service.com/sms/1/text/single",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $data,
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
            $test->save();
        } else {
            $test = new Test();
            $test->number = 200;
            $test->text = "Successful Staus:" .$response;
            $test->save();
//                Detect Credits
            $user = User::Where('id', $this->welcome_campaign->user_id)->first();
            if($user->credit >= 0){
                $user->credit =  $user->credit - 1;
            }else{
                $user->credit_status = "0 credits";
            }
            $user->save();
        }

        $this->customer->welcome_sms_status = $this->welcome_sms_status;
        $this->customer->save();
    }
}
