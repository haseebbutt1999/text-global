<?php

namespace App\Jobs;

use App\Abandonedcartcampaign;
use App\User;
use App\Welcomecampaign;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class AfterAuthenticateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if(Auth::user()->plan_id == null){
            $shop = Auth::user();
            $shop->plan_id = 1;
            $shop->credit = Auth::user()->plan->credit;
            $shop->save();
        }
        // add country "UK" by default in User
        $countries = Auth::user()->countries()->where('name', 'United Kingdom')->exists();
        $admin = User::where('role', 'admin')->first();
        $countries_pref = $admin->country_shop_pref()->where('name', 'United Kingdom')->exists();
        if($countries == false){
            Auth::user()->countries()->attach(230, ['status'=>'active']);
        }
        if($countries_pref == false){
            Auth::user()->country_shop_pref()->attach(230, ['status'=>'active']);
        }

        $welcome_campaign_save = Welcomecampaign::where('user_id', Auth::user()->id)->first();
        if($welcome_campaign_save == null){
            $welcome_campaign_save = new Welcomecampaign();
            $welcome_campaign_save->user_id = Auth::user()->id;
            $welcome_campaign_save->campaign_name = "Welcome Sms Campagin";
            $welcome_campaign_save->message_text = "{CustomerName}";
            $welcome_campaign_save->sender_name = Auth::user()->name;
            $welcome_campaign_save->status= "active";
            $welcome_campaign_save->save();

        }

        $abandoned_cart_campaign = Abandonedcartcampaign::where('user_id', Auth::user()->id)->first();
        if($abandoned_cart_campaign == null) {
            $abandoned_cart_campaign = new Abandonedcartcampaign();
            $abandoned_cart_campaign->user_id = Auth::user()->id;
            $abandoned_cart_campaign->campaign_name = "Abandoned Cart Campagin";
            $abandoned_cart_campaign->message_text = "{CustomerName} {ProductID} {OrderID} {OrderStatus} Text Message {AbandonedCartURL}";
            $abandoned_cart_campaign->sender_name = Auth::user()->name;
            $abandoned_cart_campaign->delay_time = 1;
            $abandoned_cart_campaign->status = "active";
            $abandoned_cart_campaign->save();
        }
    }
}
