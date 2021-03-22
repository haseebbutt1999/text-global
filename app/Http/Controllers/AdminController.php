<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Package;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    public function customers_index(){
        $customers_data = Customer::orderBy('id', 'desc')->paginate(10);

        return view('adminpanel/module/dashboard/customers_index', compact('customers_data'));
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
}
