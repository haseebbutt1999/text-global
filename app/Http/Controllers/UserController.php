<?php

namespace App\Http\Controllers;

use App\Abandonedcartcampaign;
use App\Campaign;
use App\Country;
use App\CountryShoppreference;
use App\CountryUser;
use App\Credit;
use App\Customer;
use App\Jobs\SendSms;
use App\Jobs\WelcomeEmailJob;
use App\Log;
use App\Orderconfirm;
use App\Orderdispatch;
use App\Orderrefund;
use App\Shopdetail;
use App\User;
use App\UserCamapignLog;
use App\Welcomecampaign;
use Carbon\Traits\Test;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Osiset\ShopifyApp\Storage\Models\Plan;
use phpDocumentor\Reflection\Types\False_;
use PhpParser\Node\Stmt\DeclareDeclare;
use SebastianBergmann\CodeCoverage\Report\Xml\Totals;

class UserController extends Controller
{
    protected $log_store;

    public function __construct(LogsController $log_store)
    {
        $this->log_store = $log_store;

    }

    public function user_dashboard(Request $request){
        if(auth::user()->role == 'user'){
            if($request->query('datefilter')) {
                $query = $request->query('datefilter');
                $dates_array = explode('- ', $query);

                $start_date = date('Y-m-d h:i:s',strtotime($dates_array[0]));
                $end_date = date('Y-m-d h:i:s',strtotime($dates_array[1]));

//                $orders_total_price = WordpressOrder::where('shop_id', session()->get('current_shop_domain'))->whereBetween('date_paid', [$start_date, $end_date])->sum('total');
//
//                $ordersQ = DB::table('wordpress_orders')
//                    ->select(DB::raw('DATE(date_paid) as date'), DB::raw('count(*) as total, sum(total) as total_sum'))
//                    ->whereBetween('created_at', [$start_date, $end_date])
//                    ->groupBy('date')
//                    ->get();
                $total_sms = DB::table('user_camapign_logs')
                    ->select(DB::raw('DATE(created_date) as date'), DB::raw('count(*) as total'))
                    ->where('user_id', Auth::user()->id)->where('status', "sended")
                    ->whereBetween('created_at', [$start_date, $end_date])
                    ->groupBy('date')
                    ->get();

                $total_subscribers = DB::table('customers')
                    ->select(DB::raw('DATE(created_at) as customer_created_dates'), DB::raw('count(*) as total_customers'))
                    ->where('user_id', Auth::user()->id)
                    ->whereBetween('created_at', [$start_date, $end_date])
                    ->groupBy('customer_created_dates')
                    ->get();

                $total_triggered = DB::table('user_camapign_logs')
                    ->select(DB::raw('DATE(created_at) as total_trigger_sms_dates'), DB::raw('count(*) as total_trigger_sms_values'))
                    ->where('user_id', Auth::user()->id)->where('model_type', "Campaign")->where('status', "sended")
                    ->whereBetween('created_at', [$start_date, $end_date])
                    ->groupBy('total_trigger_sms_dates')
                    ->get();

                $abandoned_conversion = DB::table('abandoned_cart_logs')
                    ->select(DB::raw('DATE(created_at) as abandoned_conversion_dates'), DB::raw('count(*) as abandoned_conversion_values'))
                    ->where('user_id', Auth::user()->id)->where('conversion_status', "confirmed")
                    ->whereBetween('created_at', [$start_date, $end_date])
                    ->groupBy('abandoned_conversion_dates')
                    ->get();

                $user_campaign_log = DB::table('user_camapign_logs')->where('user_id', Auth::user()->id)->where('status', "sended")
                    ->whereBetween('created_at', [$start_date, $end_date])->get();
                $abandoned_conversion_logs = DB::table('abandoned_cart_logs')->where('user_id', Auth::user()->id)->where('conversion_status', "confirmed")
                    ->whereBetween('created_at', [$start_date, $end_date])->get();
                $triggered_sms = DB::table('user_camapign_logs')->where('model_type', 'Campaign')->where('status', "sended")
                    ->where('user_id', Auth::user()->id)->whereBetween('created_at', [$start_date, $end_date])->get();
                $customers = DB::table('customers')->where('user_id', Auth::user()->id)->whereBetween('created_at', [$start_date, $end_date])->get();

            }
            else {
                $total_sms = DB::table('user_camapign_logs')
                    ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
                    ->where('user_id', Auth::user()->id)->where('status', "sended")
                    ->groupBy('date')
                    ->get();

                $total_subscribers = DB::table('customers')
                    ->select(DB::raw('DATE(created_at) as customer_created_dates'), DB::raw('count(*) as total_customers'))
                    ->where('user_id', Auth::user()->id)
                    ->groupBy('customer_created_dates')
                    ->get();

                $total_triggered = DB::table('user_camapign_logs')
                    ->select(DB::raw('DATE(created_at) as total_trigger_sms_dates'), DB::raw('count(*) as total_trigger_sms_values'))
                    ->where('user_id', Auth::user()->id)->where('model_type', "Campaign")->where('status', "sended")
                    ->groupBy('total_trigger_sms_dates')
                    ->get();

                $abandoned_conversion = DB::table('abandoned_cart_logs')
                    ->select(DB::raw('DATE(created_at) as abandoned_conversion_dates'), DB::raw('count(*) as abandoned_conversion_values'))
                    ->where('user_id', Auth::user()->id)->where('conversion_status', "confirmed")
                    ->groupBy('abandoned_conversion_dates')
                    ->get();

                $user_campaign_log = DB::table('user_camapign_logs')->where('user_id', Auth::user()->id)->where('status', "sended")->get();
                $abandoned_conversion_logs = DB::table('abandoned_cart_logs')->where('user_id', Auth::user()->id)->where('conversion_status', "confirmed")->get();
                $triggered_sms = DB::table('user_camapign_logs')->where('model_type', 'Campaign')->where('status', "sended")
                    ->where('user_id', Auth::user()->id)->get();
                $customers = DB::table('customers')->where('user_id', Auth::user()->id)->get();

            }

            $total_subscribers_dates = $total_subscribers->pluck('customer_created_dates')->toArray();
            $total_subscribers_values = $total_subscribers->pluck('total_customers')->toArray();

            $total_sms_sended_dates = $total_sms->pluck('date')->toArray();
            $total_sms_sended = $total_sms->pluck('total')->toArray();

            $total_trigered_sms_dates = $total_triggered->pluck('total_trigger_sms_dates')->toArray();
            $total_trigered_sms_values = $total_triggered->pluck('total_trigger_sms_values')->toArray();
//            dd($abandoned_conversion);
            $abandoned_conversion_dates = $abandoned_conversion->pluck('abandoned_conversion_dates')->toArray();
            $abandoned_conversion_values = $abandoned_conversion->pluck('abandoned_conversion_values')->toArray();

            $total_send_sms = $user_campaign_log->count();
            $total_customers = $customers->count();
            $total_triggered_sms = $triggered_sms->count();
            $total_abandoned_conversions = $abandoned_conversion_logs->count();

            return view('adminpanel/module/dashboard/user_dashboard')->with([

                'total_sms_sended_dates' => $total_sms_sended_dates,
                'total_sms_sended' => $total_sms_sended,
                'total_subscribers_dates' => $total_subscribers_dates,
                'total_subscribers_values' => $total_subscribers_values,
                'total_trigered_sms_dates' => $total_trigered_sms_dates,
                'total_trigered_sms_values' => $total_trigered_sms_values,
                'abandoned_conversion_dates' => $abandoned_conversion_dates,
                'abandoned_conversion_values' => $abandoned_conversion_values,

                'total_send_sms' => $total_send_sms,
                'total_customers' => $total_customers,
                'total_triggered_sms' => $total_triggered_sms,
                'total_abandoned_conversions' => $total_abandoned_conversions,
            ]);

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
        $abandonedCartCampaign_logs_data = Log::where('user_id', $id)->whereIn('model_type', ['Abandonedcartcampaign'])->orderBy('id', 'desc')->paginate(30);
        $Orderconfirm_logs_data = Log::where('user_id', $id)->whereIn('model_type', ['Orderconfirm'])->orderBy('id', 'desc')->paginate(30);
        $Orderdispatch_logs_data = Log::where('user_id', $id)->whereIn('model_type', ['Orderdispatch'])->orderBy('id', 'desc')->paginate(30);
        $plan_logs_data = Log::where('user_id', $id)->whereIn('model_type', ['Plan'])->orderBy('id', 'desc')->paginate(30);
        $user_shop_detail_logs_data = Log::where('user_id', $id)->whereIn('model_type', ['Shopdetail'])->orderBy('id', 'desc')->paginate(30);
        $country_shoppreference_data = CountryShoppreference::where('user_id', $id)->where('status', 'active')->get();
//        dd($country_shoppreference_data);
        $shop_id = $id;
//        'abandonedCartCampaign_logs_data ',
        return view('adminpanel/module/user/shop_status_detail', compact('shop_data',
            'abandonedCartCampaign_logs_data', 'Orderconfirm_logs_data', 'Orderdispatch_logs_data',
            'welcomeCampaign_logs_data', 'user_shop_detail_logs_data', 'plan_logs_data', 'campaign_logs_data',
            'countries_data', 'shop_id','country_shoppreference_data'));
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

        $country_user_data = CountryUser::where('user_id', Auth::user()->id)->where('status', 'active')->get();

        return view('adminpanel/module/user/countries', compact('admin_selected_countries', 'country_user_data'));

    }

    public function country_shop_preferences_save(Request $request){

//        dd($request->all());
        $countries = $request->country_id;
//        dd($request->all());
        $user = User::find($request->user_id);
//        dd($user->countriesShopPref());

        $user->countries()->detach();
        if($user->countries()->where('user_id', auth::user()->id)->where('name', 'United Kingdom')->exists() == false){
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
        $credits_data = Credit::get();
        return view('adminpanel/module/user/user_plan_index', compact('plans_data', 'credits_data'));
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

            dispatch(new SendSms($current_user_campaign))->delay(Carbon::parse($current_user_campaign->published_at));
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
        $welcome_campaign_save->calculated_credit_per_sms = $request->calculated_credit_per_sms;
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
        $order_confirm_campaign_save->calculated_credit_per_sms = $request->calculated_credit_per_sms;
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
        $order_refund_campaign_save->calculated_credit_per_sms = $request->calculated_credit_per_sms;
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
        $order_dispatch_campaign_save->calculated_credit_per_sms = $request->calculated_credit_per_sms;
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
        $abandoned_cart_campaign->calculated_credit_per_sms = $request->calculated_credit_per_sms;
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

        $user_welcome_logs_data = UserCamapignLog::where('user_id', Auth::user()->id)->whereIn('model_type', ['Welcomecampaign'])->orderBy('id', 'desc')->paginate(30);
        $user_abandonedcart_logs_data = UserCamapignLog::where('user_id', Auth::user()->id)->whereIn('model_type', ['Abandonedcartcampaign'])->orderBy('id', 'desc')->paginate(30);
        $user_orderconfirm_logs_data = UserCamapignLog::where('user_id', Auth::user()->id)->whereIn('model_type', ['Orderconfirm'])->orderBy('id', 'desc')->paginate(30);
        $user_orderdispatch_logs_data = UserCamapignLog::where('user_id', Auth::user()->id)->whereIn('model_type', ['Orderdispatch'])->orderBy('id', 'desc')->paginate(30);
        $user_orderrefund_logs_data = UserCamapignLog::where('user_id', Auth::user()->id)->whereIn('model_type', ['Orderrefund'])->orderBy('id', 'desc')->paginate(30);

        return view('adminpanel/module/user/sms_trigger_index',compact('orderdispatch_campaign','orderrefund_campaign','welcome_campaign', 'orderconfirm_campaign', 'abandoned_cart_campaign',
        'user_welcome_logs_data', 'user_orderdispatch_logs_data', 'user_orderconfirm_logs_data', 'user_abandonedcart_logs_data', 'user_orderrefund_logs_data'));
    }

    public function webhooks()
    {
        $webhook = [''];
        $users = User::get();

        foreach ($users as $user){
            if($user->id != 1){
                array_push($webhook,$user->api()->rest('GET','/admin/webhooks.json')['body']);
            }
        }

        dd($webhook);
    }

    public  function webhooks_update(){

        $shop = Auth::user();
        $response = $shop->api()->rest('POST', '/admin/webhooks.json', [
            'webhook' => [
                'topic' => 'customers/create',
                'address' => 'https://shopifyapp.textglobal.co.uk/webhook/customer-create',
                "format"=> "json"
            ]
        ]);
        $response = $shop->api()->rest('POST', '/admin/webhooks.json', [
            'webhook' => [
                'topic' => 'orders/create',
                'address' => 'https://shopifyapp.textglobal.co.uk/webhook/orders-create',
                "format"=> "json"
            ]
        ]);
        $response = $shop->api()->rest('POST', '/admin/webhooks.json', [
            'webhook' => [
                'topic' => 'orders/updated',
                'address' => 'https://shopifyapp.textglobal.co.uk/webhook/orders-updated',
                "format"=> "json"
            ]
        ]);
        $response = $shop->api()->rest('POST', '/admin/webhooks.json', [
            'webhook' => [
                'topic' => 'orders/fulfilled',
                'address' => 'https://shopifyapp.textglobal.co.uk/webhook/orders-fulfilled',
                "format"=> "json"
            ]
        ]);
        $response = $shop->api()->rest('POST', '/admin/webhooks.json', [
            'webhook' => [
                'topic' => 'checkouts/update',
                'address' => 'https://shopifyapp.textglobal.co.uk/webhook/checkouts-update',
                "format"=> "json"
            ]
        ]);
        $response = $shop->api()->rest('GET', '/admin/webhooks.json');
        dd($response);

    }




}
