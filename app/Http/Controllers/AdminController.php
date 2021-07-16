<?php

namespace App\Http\Controllers;

use App\Credit;
use App\Customer;
use App\Exports\CustomersExport;
use App\Package;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Osiset\ShopifyApp\Storage\Models\Plan;

class AdminController extends Controller
{
    public function admin_dashboard(){
        if(auth::user()->role == 'user'){
            return view('adminpanel/module/dashboard/user_dashboard');
        }if(auth::user()->role == 'admin'){
            return view('adminpanel/module/dashboard/dashboard_index');
        }
    }

    public function shops_index(){
        $users_data = User::where('role', 'user')->orderBy('id', 'desc')->paginate(10);

        return view('adminpanel/module/dashboard/shops_index', compact('users_data'));
    }

    public function customers_index(Request $request){
        $shop=Auth::user();
        $countries = [];
        foreach ($shop->customers as $customer){
            if(isset($customer->addressess[0])){
                array_push($countries, $customer->addressess[0]->country);
            }
        }
        $countries = array_values(array_unique($countries));

//        $collections=Product::where('shop', $shop->name)->pluck('collection')->toArray();
        $total_orders = '';
        $total_spents = '';
        $filter_country='';
//        $products = Product::with('trendyolModel')->where('shop', $shop->name);
        $customers_data = Customer::where('user_id', Auth::user()->id);
//        dd($products->get());
        if ($request->input('total_orders'))
        {
            $total_orders=$request->input('total_orders');

            $customers_data=$customers_data->where(function ($query) use($total_orders){
                $query->orWhere('orders_count','LIKE binary', '%'.$total_orders.'%');
            });
        }
        if ($request->input('total_spents')) {
            $total_spents = $request->input('total_spents');

            $customers_data=$customers_data->where(function ($query) use($total_spents){
                $query->orWhere('total_spent','LIKE binary', '%'.number_format($total_spents, 2).'%');
            });
        }
        if ($request->input('country')) {

            $filter_country = $request->input('country');

            $customers_data=$customers_data->whereHas('addressess',function ($query) use($filter_country){
                $query->where('country',$filter_country);
            });
//            $products = $products->where('product_type', $filter_product_type);
        }
        $customers_data = $customers_data->orderBy('created_at', 'desc')->paginate(20);
//        if (count($request->all())) {
//            $products->appends($request->all());
//        }
        return view('adminpanel/module/dashboard/customers_index', compact('customers_data','total_orders', 'total_spents', 'countries', 'filter_country'));


//        return view('adminpanel/module/dashboard/customers_index', compact('customers_data'));
    }

    public function customer_export(){
        return Excel::download(new CustomersExport, 'customers.xlsx');
    }

    public function customer_push(){
        $customers = Customer::where('user_id', Auth::user()->id)->where('data_pushed', null)->get();
        if(count($customers)){
            foreach ($customers as $customer){
                if($customer->data_pushed == null){
                    if(isset($customer->addressess[0]->country)){
                        $country = $customer->addressess[0]->country;
                    }else{
                        $country = null;
                    }
//                         $username = User::find(Auth::user()->id)->shopdetail->user_name;
//                        $password = User::find(Auth::user()->id)->shopdetail->password;
//                        $auth = "Basic ". base64_encode("$username:$password");
                    $data = [
                        "firstName" => $customer->first_name,
                        "lastName" => $customer->last_name,
                        "contactInformation"=>[
                            "email"=>[
                                "address"=>$customer->email,
                            ],
                        ],
                        "country"=>$country,
                        "customAttributes"=>[
                            "orders_count"=>$customer->orders_count,
                            "total_spent"=>$customer->total_spent,
                            "currency"=>$customer->currency,
                        ],
                    ];
//            dd($data);

                    $curl = curl_init();

                    curl_setopt_array($curl, array(
                        CURLOPT_URL => "https://api.messaging-service.com/people/2/persons",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => json_encode($data),
                        CURLOPT_HTTPHEADER => array(
                            "accept: application/json",
                            "authorization: Basic c2hvcGlmeWFwcC50ZXh0Z2xvYmFsOlRHc2hvcGlmeTEh",
                            "cache-control: no-cache",
                            "content-type: application/json",
                            "postman-token: f4774df4-f030-2a0c-bcd1-76e024d4e338"
                        ),
                    ));

                    $response = curl_exec($curl);
                    $err = curl_error($curl);

                    curl_close($curl);

                    if ($err) {

                    } else {
                        $var =json_decode($response);
                        if(isset($var->requestError) && $var->requestError->serviceException->messageId == "BAD_REQUEST"){

                        }elseif(isset($var->errorMessage) && $var->errorMessage == "Duplicate"){
                            $customer->data_pushed = "pushed";
                            $customer->save();
                        }else{
                            $customer->data_pushed = "pushed";
                            $customer->save();
                        }
                    }
                }
            }
            return redirect()->back()->with('success', 'Customers Pushed Successfully');
        }else{
            return redirect()->back()->with('error', 'Customers Already Pushed !');
        }

    }

    public function admin_customer_push(){
        $customers = Customer::where('data_pushed', null)->get();
        if(count($customers)){
            foreach ($customers as $customer)
            {
                if($customer->data_pushed == null){
                    if(isset($customer->addressess[0]->country)){
                        $country = $customer->addressess[0]->country;
                    }else{
                        $country = null;
                    }
                    $data = [
                        "firstName" => $customer->first_name,
                        "lastName" => $customer->last_name,
                        "contactInformation"=>[
                            "email"=>[
                                "address"=>$customer->email,
                            ],
                        ],
                        "country"=>$country,
                        "customAttributes"=>[
                            "orders_count"=>$customer->orders_count,
                            "total_spent"=>$customer->total_spent,
                            "currency"=>$customer->currency,
                        ],
                    ];

                    $curl = curl_init();

                    curl_setopt_array($curl, array(
                        CURLOPT_URL => "https://api.messaging-service.com/people/2/persons",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => json_encode($data),
                        CURLOPT_HTTPHEADER => array(
                            "accept: application/json",
                            "authorization: Basic c2hvcGlmeWFwcC50ZXh0Z2xvYmFsOlRHc2hvcGlmeTEh",
                            "cache-control: no-cache",
                            "content-type: application/json",
                            "postman-token: f4774df4-f030-2a0c-bcd1-76e024d4e338"
                        ),
                    ));

                    $response = curl_exec($curl);
                    $err = curl_error($curl);

                    curl_close($curl);

                    if ($err) {

                    } else {
                        $var =json_decode($response);
                        if(isset($var->requestError) && $var->requestError->serviceException->messageId == "BAD_REQUEST"){

                        }elseif(isset($var->errorMessage) && $var->errorMessage == "Duplicate"){
                            $customer->data_pushed = "pushed";
                            $customer->save();
                        }else{
                            $customer->data_pushed = "pushed";
                            $customer->save();
                        }
                    }
                }
            }
            return redirect()->back()->with('success', 'Customers Pushed Successfully');
        }else{
            return redirect()->back()->with('error', 'Customers Already Pushed !');
        }
    }

    public function plans_index(){
        $plans_data = Plan::orderBy('id', 'desc')->paginate(10);
        return view('adminpanel/module/dashboard/plans_index', compact('plans_data'));
    }

    public function plan_save(Request $request){
//        dd($request->id);
        $plan_save = new Plan();

        $plan_save->type = $request->type;
        $plan_save->name = $request->name;
        $plan_save->capped_amount = $request->capped_amount;
        $plan_save->interval = $request->interval;
        $plan_save->terms = $request->terms;
        $plan_save->trial_days = $request->trial_days;
        $plan_save->price = $request->price;
        $plan_save->credit = $request->credit;
        $plan_save->test = 1;
        $plan_save->on_install = 0;
        $plan_save->save();

        return redirect()->back();
    }

    public function edit_plan_save(Request $request, $id){
//        dd($id);
        $plan_update = Plan::where('id',$id)->first();
//        dd($package_update);
        if($plan_update === null){
            $plan_update = new Plan();
        }

        $plan_update->type = $request->type;
        $plan_update->name = $request->name;
        $plan_update->capped_amount = $request->capped_amount;
        $plan_update->interval = $request->interval;
        $plan_update->terms = $request->terms;
        $plan_update->trial_days = $request->trial_days;
        $plan_update->price = $request->price;
        $plan_update->credit = $request->credit;
        $plan_update->test = 1;
        $plan_update->on_install = 0;
//        $plan_update->status = $request->status;
        $plan_update->save();

        return redirect()->back();
    }

    public function plan_delete($id){
        $plan_delete = Plan::where('id',$id)->first();
        if(isset($plan_delete)){
            $plan_delete->delete();
        }

        return redirect()->back();
    }

    public function credits_index(){
        $credits_data = Credit::orderBy('id', 'desc')->paginate(10);
        $plans_data = Plan::get();
        return view('adminpanel/module/dashboard/credits_index', compact('credits_data', 'plans_data'));
    }

    public function credits_save(Request $request){

        $credit_save = new Credit();
        $credit_save->plan_id = $request->plan_id;
        $credit_save->price = $request->price;
        $credit_save->credits = $request->credits;
        if(isset($request->status)){
            $credit_save->status = $request->status;
        }else{
            $credit_save->status = "Inactive";
        }
        $credit_save->save();

        return redirect()->back();
    }

    public function edit_credits_save(Request $request, $id){

        $credit_save = Credit::where('id',$id)->first();

        if($credit_save == null){
            $credit_save = new Credit();
        }
        $credit_save->plan_id = $request->plan_id;
        $credit_save->price = $request->price;
        $credit_save->credits = $request->credits;
        if($request->status){
            $credit_save->status = $request->status;
        }else{
            $credit_save->status = "Inactive";
        }
        $credit_save->save();

        return redirect()->back();
    }

    public function credits_delete($id){
        $credit_delete = Credit::where('id',$id)->first();
        if(isset($credit_delete)){
            $credit_delete->delete();
        }

        return redirect()->back();
    }
}
