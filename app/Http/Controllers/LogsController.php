<?php

namespace App\Http\Controllers;

use App\Log;
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
}
