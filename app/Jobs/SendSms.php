<?php

namespace App\Jobs;

use App\Campaign;
use App\Http\Controllers\LogsController;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Mockery\Exception;
use Throwable;

class SendSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $campaign;
    public $send_status="Sended";
    private $log_store;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Campaign $campaign)
    {
        $this->campaign = $campaign;
        $this->log_store = new LogsController();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = User::where('id',$this->campaign->user_id)->first();
        $user_select_countries = $user->countries;
        $user_customer = $user->customers;

        $pushed_users=[];

        foreach ($user_select_countries as $countries){
            foreach ($user_customer as $uc){
                foreach ($uc->addressess as $add){
                    if($add->country == $countries->name){
                        $users = json_decode(json_encode($uc, TRUE));
                        array_push($pushed_users , $users);
                    }
                }
            }

        }

        foreach ($pushed_users as $pushed_user) {
            $messgae_text = str_replace('{CustomerName}',$pushed_user->first_name." ".$pushed_user->last_name,$this->campaign->message_text);

            $data = [
                "from" => $this->campaign->sender_name,
                "to" => $pushed_user->phone,
                "text" => $messgae_text,
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
                $this->log_store->log_store(Auth::user()->id, 'Campaign', $this->campaign->id, $this->campaign->campaign_name, 'Campaign Send Failed' , $notes = $err);
            } else {
                $response = json_decode($response);
                if($response->messages[0]->status->name = "PENDING_ENROUTE"){
                    $this->log_store->log_store(Auth::user()->id, 'Campaign', $this->campaign->id, $this->campaign->campaign_name, 'Campaign Sended Successfully' , $notes = $response);
//                Detect Credits
                    $user = User::Where('id', $this->campaign->user_id)->first();
                    if($user->credit >= 0){
                        $user->credit =  $user->credit - $this->campaign->calculated_credit_per_sms;
                    }else{
                        $user->credit_status = "0 credits";
                    }
                    $user->save();
                }else{
                    $this->log_store->log_store(Auth::user()->id, 'Campaign', $this->campaign->id, $this->campaign->campaign_name, 'Campaign Send Failed.' , $notes = $response);
                }

            }

        }


        $this->campaign->send_status = $this->send_status;
        $this->campaign->save();

    }
}









