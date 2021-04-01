<?php

namespace App\Http\Controllers;

use App\Abandonedcartcampaign;
use App\Campaign;
use App\Country;
use App\CountryShoppreference;
use App\CountryUser;
use App\Customer;
use App\Jobs\SendSms;
use App\Jobs\WelcomeEmailJob;
use App\Log;
use App\Orderconfirm;
use App\Orderdispatch;
use App\Orderrefund;
use App\Shopdetail;
use App\User;
use App\Welcomecampaign;
use Carbon\Traits\Test;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Osiset\ShopifyApp\Storage\Models\Plan;
use phpDocumentor\Reflection\Types\False_;
use PhpParser\Node\Stmt\DeclareDeclare;

class UserController extends Controller
{
    protected $log_store;

    public function __construct(LogsController $log_store)
    {
        $this->log_store = $log_store;

    }

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
        $countries_data = Country::get();

        $campaign_logs_data = Log::where('user_id', $id)->whereIn('model_type', ['Campaign'])->orderBy('id', 'desc')->paginate(30);
        $welcomeCampaign_logs_data = Log::where('user_id', $id)->whereIn('model_type', ['Welcomecampaign'])->orderBy('id', 'desc')->paginate(30);
//        $abandonedCartCampaign_logs_data = Log::where('user_id', $id)->whereIn('model_type', ['Abandonedcartcampaign'])->orderBy('id', 'desc')->paginate(30);
        $plan_logs_data = Log::where('user_id', $id)->whereIn('model_type', ['Plan'])->orderBy('id', 'desc')->paginate(30);
        $user_shop_detail_logs_data = Log::where('user_id', $id)->whereIn('model_type', ['Shopdetail'])->orderBy('id', 'desc')->paginate(30);
        $country_shoppreference_data = CountryShoppreference::where('user_id', $id)->where('status', 'active')->get();
//        dd($country_shoppreference_data);
        $shop_id = $id;
//        'abandonedCartCampaign_logs_data ',
        return view('adminpanel/module/user/shop_status_detail', compact('shop_data',  'welcomeCampaign_logs_data', 'user_shop_detail_logs_data', 'plan_logs_data', 'campaign_logs_data', 'countries_data', 'shop_id','country_shoppreference_data'));
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
            $this->log_store->log_store(Auth::user()->id, 'Shopdetail', $shop_detail->id, $shop_detail->firstname." ".$shop_detail->surname, 'Shop Details Created by User' , $notes = null);
            $shop_detail = Shopdetail::where('user_id', $shop_detail->user_id)->first();

            $welcomeEmailJob = (new WelcomeEmailJob($shop_detail));
            dispatch($welcomeEmailJob);
        }else{
            $this->log_store->log_store(Auth::user()->id, 'Shopdetail', $shop_detail->id, $shop_detail->firstname." ".$shop_detail->surname, 'Shop Details Updated by User' , $notes = null);
        }

        return redirect()->back();

    }

    public function countries_index(){

        $admin_selected_countries = auth::user()->country_shop_pref;

        $country_user_data = CountryUser::where('user_id', auth::user()->id)->where('status', 'active')->get();

        return view('adminpanel/module/user/countries', compact('admin_selected_countries', 'country_user_data'));

    }

    public function country_shop_preferences_save(Request $request){

//        dd($request->all());
        $countries = $request->country_id;
//        dd($request->all());
        $user = User::find($request->user_id);
//        dd($user->countriesShopPref());

        $user->countries()->detach();
        if($user->countries()->where('name', 'United Kingdom')->exists() == false){
            $user->countries()->attach($countries, ['status'=>'active']);
        }

        $user->country_shop_pref()->detach();
        $user->country_shop_pref()->attach($countries, ['status'=>'active']);
//        $user->country_shop_pref()->attach(230, ['status'=>'active']);
//        $user->countries()->attach(230, ['status'=>'active']);

        return redirect()->back();
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

        $customer_data = Customer::where('user_id', Auth::user()->id)->get();
        $sms_campaign_data = Campaign::where('user_id', Auth::user()->id)->get();

        return view('adminpanel/module/user/sms_campaign', compact('customer_data', 'sms_campaign_data'));
    }

    public function sms_campaign_save(Request $request){
//        dd($request->all());
        $campaign_save = new Campaign();
        $campaign_save->user_id = Auth::user()->id;
        $campaign_save->campaign_name = $request->campaign_name;
        $campaign_save->message_text = $request->message_text;
        $campaign_save->calculated_credit_per_sms = $request->calculated_credit_per_sms;
        $campaign_save->sender_name = $request->sender_name;
        $campaign_save->published_at = \Carbon\Carbon::createFromTimeString($request->published_at)->format('Y-m-d H:i:s');
//        $campaign_save->status = 'active';
        if(isset($request->status)){
            $campaign_save->status= $request->status;
        }
        $campaign_save->save();

        $this->log_store->log_store(Auth::user()->id, 'Campaign', $campaign_save->id, $campaign_save->campaign_name, 'Campaign Saved by User' , $notes = null);

        return redirect()->back();


//        return redirect()->back();
    }

    public function edit_campaign_save(Request $request, $id){
//        dd($id);
//        dd($request->all());
        $campaign_save = Campaign::where('id',$id)->first();
        if($campaign_save == null){
            $campaign_save = new Campaign();
        }
        $campaign_save->user_id = Auth::user()->id;
        $campaign_save->campaign_name = $request->campaign_name;
        $campaign_save->calculated_credit_per_sms = $request->calculated_credit_per_sms;
        $campaign_save->message_text = $request->message_text;
        $campaign_save->sender_name = $request->sender_name;
        $campaign_save->published_at = Carbon::createFromTimeString($request->published_at)->format('Y-m-d H:i:s');
        if(isset($request->status)){
            $campaign_save->status= $request->status;
        }
        $campaign_save->save();
        $this->log_store->log_store(Auth::user()->id, 'Campaign', $campaign_save->id, $campaign_save->campaign_name, 'Campaign Edited by User' , $notes = null);
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
        if($request->status == 'inactive'){
            $this->log_store->log_store(Auth::user()->id, 'Campaign', $campaign_save->id, $campaign_save->campaign_name, 'Campaign Inactive by User' , $notes = null);
        }
        $current_user_campaign = Campaign::where('id', $campaign_save->id)->where('send_status', 'Not Sended')->where('status', 'active')->where('user_id', Auth::user()->id)->first();
        if(isset($current_user_campaign)){
            $this->log_store->log_store(Auth::user()->id, 'Campaign', $current_user_campaign->id, $current_user_campaign->campaign_name, 'Campaign Active by User' , $notes = null);
            dispatch(new SendSms($current_user_campaign))->delay($current_user_campaign->published_at);
        }else{
            $this->log_store->log_store(Auth::user()->id, 'Campaign', $current_user_campaign->id, $current_user_campaign->campaign_name, 'Campaign Published Failed by User' , $notes = null);
        }

        return redirect()->back();

    }

    public function delete_campaign($id){
        $campaign_delete = Campaign::find($id);
        $campaign_delete->delete();
        $this->log_store->log_store(Auth::user()->id, 'Campaign', $id, $campaign_delete->campaign_name, 'Campaign Deleted by User' , $notes = null);
        return redirect()->back();
    }

    public function welcome_campaign(){
        $welcome_campaign = Welcomecampaign::where('user_id', Auth::user()->id)->first();
        $welcome_campaign= json_decode(json_encode($welcome_campaign,True));
        return view('adminpanel/module/user/welcome_campaign', compact('welcome_campaign'));
    }

    public function welcome_sms_campaign_save(Request $request){
//        dd($request->all());
        $welcome_campaign_save = Welcomecampaign::where('user_id', Auth::user()->id)->first();
        if($welcome_campaign_save == null){
            $welcome_campaign_save = new Welcomecampaign();
        }
        $welcome_campaign_save->user_id = Auth::user()->id;
        $welcome_campaign_save->campaign_name = $request->campaign_name;
        $welcome_campaign_save->message_text = $request->message_text;
        $welcome_campaign_save->sender_name = $request->sender_name;
        if(isset($request->status)){
            $welcome_campaign_save->status= $request->status;
        }else{
            $welcome_campaign_save->status= "inactive";
        }
        $welcome_campaign_save->save();
        $this->log_store->log_store(Auth::user()->id, 'Welcomecampaign', $welcome_campaign_save->id, $welcome_campaign_save->campaign_name, 'Welcome Campaign Updated by User' , $notes = null);

        return redirect()->back();
    }



    public function order_confirm_campaign_save(Request $request){
//        dd($request->all());
        $order_confirm_campaign_save = Orderconfirm::where('user_id', Auth::user()->id)->first();
        if($order_confirm_campaign_save == null){
            $order_confirm_campaign_save = new Orderconfirm();
        }
        $order_confirm_campaign_save->user_id = Auth::user()->id;
        $order_confirm_campaign_save->campaign_name = $request->campaign_name;
        $order_confirm_campaign_save->message_text = $request->message_text;
        $order_confirm_campaign_save->sender_name = $request->sender_name;
        if(isset($request->status)){
            $order_confirm_campaign_save->status= $request->status;
        }else{
            $order_confirm_campaign_save->status= "inactive";
        }
        $order_confirm_campaign_save->save();
        $this->log_store->log_store(Auth::user()->id, 'Orderconfirm', $order_confirm_campaign_save->id, $order_confirm_campaign_save->campaign_name, 'Order Confirm Camapign Updated by User' , $notes = null);

        return redirect()->back();
    }

    public function order_refund_campaign_save(Request $request){
//        dd($request->all());
        $order_refund_campaign_save = Orderrefund::where('user_id', Auth::user()->id)->first();
        if($order_refund_campaign_save == null){
            $order_refund_campaign_save = new Orderrefund();
        }
        $order_refund_campaign_save->user_id = Auth::user()->id;
        $order_refund_campaign_save->campaign_name = $request->campaign_name;
        $order_refund_campaign_save->message_text = $request->message_text;
        $order_refund_campaign_save->sender_name = $request->sender_name;
        if(isset($request->status)){
            $order_refund_campaign_save->status= $request->status;
        }else{
            $order_refund_campaign_save->status= "inactive";
        }
        $order_refund_campaign_save->save();
        $this->log_store->log_store(Auth::user()->id, 'Orderrefund', $order_refund_campaign_save->id, $order_refund_campaign_save->campaign_name, 'Order Refund Camapign Updated by User' , $notes = null);

        return redirect()->back();
    }

    public function order_dispatch_campaign_save(Request $request){
//        dd($request->all());
        $order_dispatch_campaign_save = Orderdispatch::where('user_id', Auth::user()->id)->first();
        if($order_dispatch_campaign_save == null){
            $order_dispatch_campaign_save = new Orderdispatch();
        }
        $order_dispatch_campaign_save->user_id = Auth::user()->id;
        $order_dispatch_campaign_save->campaign_name = $request->campaign_name;
        $order_dispatch_campaign_save->message_text = $request->message_text;
        $order_dispatch_campaign_save->sender_name = $request->sender_name;
        if(isset($request->status)){
            $order_dispatch_campaign_save->status= $request->status;
        }else{
            $order_dispatch_campaign_save->status= "inactive";
        }
        $order_dispatch_campaign_save->save();
        $this->log_store->log_store(Auth::user()->id, 'Orderdispatch', $order_dispatch_campaign_save->id, $order_dispatch_campaign_save->campaign_name, 'Order Confirm Camapign Updated by User' , $notes = null);

        return redirect()->back();
    }

    public function abandoned_cart_campaign(){
        $abandoned_cart_campaign = Abandonedcartcampaign::where('user_id', Auth::user()->id)->first();
        $abandoned_cart_campaign= json_decode(json_encode($abandoned_cart_campaign,True));
        return view('adminpanel/module/user/abandonedcart_campaign', compact('abandoned_cart_campaign'));
    }

    public function abandoned_cart_campaign_save(Request $request){
//        dd($request->all());
        $abandoned_cart_campaign = Abandonedcartcampaign::where('user_id', Auth::user()->id)->first();
        if($abandoned_cart_campaign == null){
            $abandoned_cart_campaign = new Abandonedcartcampaign();
        }
        $abandoned_cart_campaign->user_id = Auth::user()->id;
        $abandoned_cart_campaign->campaign_name = $request->campaign_name;
        $abandoned_cart_campaign->message_text = $request->message_text;
        $abandoned_cart_campaign->sender_name = $request->sender_name;
        $abandoned_cart_campaign->delay_time = $request->delay_time;
        if(isset($request->status)){
            $abandoned_cart_campaign->status= $request->status;
        }else{
            $abandoned_cart_campaign->status= "inactive";
        }
        $abandoned_cart_campaign->save();

        return redirect()->back();
    }

    public function sms_trigger_index(){
        $welcome_campaign = Welcomecampaign::where('user_id', Auth::user()->id)->first();
        $welcome_campaign= json_decode(json_encode($welcome_campaign,True));
        $abandoned_cart_campaign = Abandonedcartcampaign::where('user_id', Auth::user()->id)->first();
        $abandoned_cart_campaign= json_decode(json_encode($abandoned_cart_campaign,True));
        $orderconfirm_campaign = Orderconfirm::where('user_id', Auth::user()->id)->first();
        $orderconfirm_campaign= json_decode(json_encode($orderconfirm_campaign,True));
        $orderrefund_campaign = Orderrefund::where('user_id', Auth::user()->id)->first();
        $orderrefund_campaign= json_decode(json_encode($orderrefund_campaign,True));
        $orderdispatch_campaign = Orderdispatch::where('user_id', Auth::user()->id)->first();
        $orderdispatch_campaign= json_decode(json_encode($orderdispatch_campaign,True));

        $welcomeCampaign_logs_data = Log::where('user_id', Auth::user()->id)->whereIn('model_type', ['Welcomecampaign'])->orderBy('id', 'desc')->paginate(30);
        return view('adminpanel/module/user/sms_trigger_index',compact('orderdispatch_campaign','orderrefund_campaign','welcome_campaign', 'orderconfirm_campaign', 'welcomeCampaign_logs_data', 'abandoned_cart_campaign'));
    }

    public function webhooks()
    {
        $webhook=Auth::user()->api()->rest('GET','/admin/webhooks.json');
        dd($webhook);
    }
}
