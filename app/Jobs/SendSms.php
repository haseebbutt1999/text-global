<?php

namespace App\Jobs;

use App\Campaign;
use App\Http\Controllers\LogsController;
use App\Test;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mockery\Exception;
use Throwable;

class SendSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $campaign;
    public $send_status="Sended";
    private $log_store;
    private $user_log;

    /**SendSms
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Campaign $campaign)
    {
        $this->campaign = $campaign;
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

//                          $username = User::find($this->campaign->user_id)->shopdetail->user_name;
//                        $password = User::find($this->campaign->user_id)->shopdetail->password;
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
                $this->log_store->log_store($this->campaign->user_id, 'Campaign', $this->campaign->id,$messgae_text, $this->campaign->campaign_name, 'Failed');
            } else {
                $response = json_decode($response);
                if($response->messages[0]->status->name = "PENDING_ENROUTE"){
                    $this->log_store->log_store($this->campaign->user_id, 'Campaign', $this->campaign->id,$messgae_text, $this->campaign->campaign_name, 'Sent' );
//                Detect Credits
                    $this->user_log->user_log( $this->campaign->user_id,$pushed_user->addressess[0]->phone,$pushed_user->addressess[0]->first_name,$pushed_user->addressess[0]->last_name,$messgae_text, 'Campaign', null , $pushed_user->shopify_customer_id, 'Sent', "sended");

                    $user = User::Where('id', $this->campaign->user_id)->first();
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
                    $this->log_store->log_store($this->campaign->user_id, 'Campaign', $this->campaign->id,$messgae_text, $this->campaign->campaign_name, 'Failed');
                    $this->user_log->user_log( $this->campaign->user_id, $pushed_user->addressess[0]->phone,$pushed_user->addressess[0]->first_name,$pushed_user->addressess[0]->last_name,$messgae_text,'Campaign', null , $pushed_user->shopify_customer_id, 'Failed', "not sended");

                }

            }

        }
        $this->campaign->send_status = $this->send_status;
        $this->campaign->save();
    }
}









