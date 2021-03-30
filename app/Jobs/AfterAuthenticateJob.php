<?php

namespace App\Jobs;

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
            $admin->country_shop_pref()->attach($countries, ['status'=>'active']);
        }

    }
}
