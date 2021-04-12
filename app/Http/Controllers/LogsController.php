<?php

namespace App\Http\Controllers;

use App\Log;
use App\UserCamapignLog;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LogsController extends Controller
{
    public function log_store($user_id, $model_type, $model_id, $model_name, $action){
        $log_save = new Log();
        $log_save->user_id = $user_id;
        $log_save->model_type = $model_type;
        $log_save->model_id = $model_id;
        $log_save->model_name = $model_name;
        $log_save->action = $action;
        $log_save->save();

        return true;
    }

    public function user_log($user_id, $model_type, $order_name, $customer_id, $action, $status){
        $log_save = new UserCamapignLog();
        $log_save->user_id = $user_id;
        $log_save->model_type = $model_type;
        $log_save->order_name = $order_name;
        $log_save->customer_id = $customer_id;
        $log_save->action = $action;
        $log_save->status = $status;
        $log_save->created_date = Carbon::now()->format('Y-m-d');
        $log_save->save();

        return true;
    }
}
