<?php

namespace App\Http\Controllers;

use App\Campaign;
use App\Country;
use App\Countryuser;
use App\Customer;
use App\Jobs\SendSms;
use App\Jobs\WelcomeEmailJob;
use App\Shopdetail;
use App\User;
use App\Welcomecampaign;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Osiset\ShopifyApp\Storage\Models\Plan;
use phpDocumentor\Reflection\Types\False_;

class UserController extends Controller
{
    public function user_dashboard(){
        if(auth::user()->role == 'user'){
            return view('adminpanel/module/dashboard/user_dashboard');
        }if(auth::user()->role == 'admin'){
            return redirect('admin-dashboard');
        }

    }

    public function shop_detail(Request $request){
        if(Shopdetail::where('user_id', $request->user_id)->exists()){
            $shop_detail = Shopdetail::where('user_id', $request->user_id)->first();
        }else{
            $shop_detail = new Shopdetail();
        }
        $shop_detail->user_id = $request->user_id;
        $shop_detail->firstname = $request->firstname;
        $shop_detail->surname = $request->surname;
        $shop_detail->email = $request->email;
        $shop_detail->mobile_number = $request->mobile_number;
        $shop_detail->company_name = $request->company_name;
        $shop_detail->sender_name = $request->sender_name;
        $shop_detail->save();
        if(Auth::user()->user_status == "inactive"){
            return view('adminpanel/module/user/setup_page');
        }else{
            return redirect('/');
        }

    }

    public function shop_status_detail(Request $request, $id){
        $shop_data = Shopdetail::where('user_id', $id)->first();
//        dd($shop_data);


        return view('adminpanel/module/user/shop_status_detail', compact('shop_data'));
    }

    public function shop_status_detail_save(Request $request){
//        dd($request->all());
        $shop_detail = Shopdetail::where('user_id', $request->user_id)->first();
        if($shop_detail == null ){
            $shop_detail = new Shopdetail();
        }
        $shop_detail->user_id = $request->user_id;
        $shop_detail->firstname = $request->firstname;
        $shop_detail->surname = $request->surname;
        $shop_detail->email = $request->email;
        $shop_detail->mobile_number = $request->mobile_number;
        $shop_detail->company_name = $request->company_name;
        $shop_detail->sender_name = $request->sender_name;

        $shop_detail->user_name = $request->user_name;
        $shop_detail->password = $request->password;
        $shop_detail->save();
        $user_status = User::find($request->user_id);
        if($request->user_status == null){
            $user_status->user_status = "inactive";
        }else{
            $user_status->user_status = $request->user_status;
        }
        $user_status->save();

        return redirect('shops');

    }

    public function user_shop_detail(){
        $user_shop_data = User::where('id', Auth::user()->id)->first();
//        dd($user_shop_data);
        return view('adminpanel/module/user/user_shop_detail', compact('user_shop_data'));

    }

    public function user_shop_detail_save(Request $request){
//        dd($request->all());
        $shop_updated = "";
        $shop_detail = Shopdetail::where('user_id', $request->user_id)->first();
        if($shop_detail == null ){
            $shop_detail = new Shopdetail();
        }
        if($shop_detail != null){
            $shop_updated = "true";
        }
        else{
            $shop_updated = "false";
        }
        $shop_detail->user_id = $request->user_id;
        $shop_detail->firstname = $request->firstname;
        $shop_detail->surname = $request->surname;
        $shop_detail->email = $request->email;
        $shop_detail->mobile_number = $request->mobile_number;
        $shop_detail->company_name = $request->company_name;
        $shop_detail->sender_name = $request->sender_name;
        $shop_detail->save();
        if($shop_updated == "false"){
            $shop_detail = Shopdetail::where('user_id', $shop_detail->user_id)->first();

            $welcomeEmailJob = (new WelcomeEmailJob($shop_detail));
            dispatch($welcomeEmailJob);
        }

        return redirect()->back();

    }

    public function countries_index(){

        $countries_data = Country::get();
        $country_user_data = CountryUser::where('user_id', auth::user()->id)->where('status', 'active')->get();
//        dd($country_user_data);
        return view('adminpanel/module/user/countries', compact('countries_data', 'country_user_data'));

    }

    public function country_user_save(Request $request){

//        dd( $coutries = $request->country_id);
        $countries = $request->country_id;
        $user = Auth::user();

        $user->countries()->detach();

        $user->countries()->attach($countries, ['status'=>'active']);

        return redirect()->back();
    }

    public function user_plans(){

        $plans_data = Plan::get();
        return view('adminpanel/module/user/user_plan_index', compact('plans_data'));
    }

    public function sms_campaign_index(){

//        return redirect()->back();
        $customer_data = Customer::get();
        $sms_campaign_data = Campaign::get();

        return view('adminpanel/module/user/sms_campaign', compact('customer_data', 'sms_campaign_data'));
    }

    public function sms_campaign_save(Request $request){
//        dd($request->message_text);
        $campaign_save = new Campaign();
        $campaign_save->user_id = Auth::user()->id;
        $campaign_save->campaign_name = $request->campaign_name;
        $campaign_save->message_text = $request->message_text;
        $campaign_save->sender_name = $request->sender_name;
        $campaign_save->published_at = Carbon::createFromTimeString($request->published_at)->format('Y-m-d H:i:s');
//        $campaign_save->status = 'active';
        if(isset($request->status)){
            $campaign_save->status= $request->status;
        }
        $campaign_save->save();

        return redirect()->back();


//        return redirect()->back();
    }

    public function edit_campaign_save(Request $request, $id){
//        dd($id);
        $campaign_save = Campaign::where('id',$id)->first();
        if($campaign_save == null){
            $campaign_save = new Campaign();
        }
        $campaign_save->user_id = Auth::user()->id;
        $campaign_save->campaign_name = $request->campaign_name;
        $campaign_save->message_text = $request->message_text;
        $campaign_save->sender_name = $request->sender_name;
        $campaign_save->published_at = Carbon::createFromTimeString($request->published_at)->format('Y-m-d H:i:s');
        if(isset($request->status)){
            $campaign_save->status= $request->status;
        }
        $campaign_save->save();

        return redirect()->back();
    }

    public function edit_status_campaign_save(Request $request, $id){

        $campaign_save = Campaign::where('id',$id)->first();

//        dd( Carbon::createFromTimeString($campaign_save->published_at)->format('Y-m-d H:i:s'));
        if($campaign_save == null){
            $campaign_save = new Campaign();
        }
        if(isset($request->status)){
            $campaign_save->status= $request->status;
        }
        $campaign_save->save();
        $current_user_campaign = Campaign::where('id', $campaign_save->id)->where('send_status', 'Not Sended')->where('status', 'active')->where('user_id', Auth::user()->id)->first();
        if(isset($current_user_campaign)){
            dispatch(new SendSms($current_user_campaign))->delay($current_user_campaign->published_at);
        }else{
            dd('not found');
        }

        return redirect()->back();

    }

    public function delete_campaign($id){
        $campaign_delete = Campaign::find($id);
        $campaign_delete->delete();
        return redirect()->back();
    }

    public function welcome_campaign(){
        $welcome_campaign = Welcomecampaign::first();
        $welcome_campaign= json_decode(json_encode($welcome_campaign,True));
        return view('adminpanel/module/user/welcome_campaign', compact('welcome_campaign'));
    }

    public function welcome_sms_campaign_save(Request $request){
//        dd($request->all());
        $welcome_campaign_save = Welcomecampaign::first();
        if($welcome_campaign_save == null){
            $welcome_campaign_save = new Welcomecampaign();
        }
        $welcome_campaign_save->user_id = Auth::user()->id;
        $welcome_campaign_save->campaign_name = $request->campaign_name;
        $welcome_campaign_save->message_text = $request->message_text;
        $welcome_campaign_save->sender_name = $request->sender_name;
        if(isset($request->status)){
            $welcome_campaign_save->status= $request->status;
        }
        $welcome_campaign_save->save();

        return redirect()->back();
    }
}
