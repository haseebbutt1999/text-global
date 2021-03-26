@extends('adminpanel.layout.default')
@section('content')
    <div class="col-lg-12 col-md-12 p-4">
        <!-- start info box -->
        <div class="row ">
            <div class="col-md-12 col-lg-12 m-auto">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="shopdetail-tab" data-toggle="tab" href="#shopdetail" role="tab" aria-controls="shopdetail"
                           aria-selected="true">Shop Details</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="countries-tab" data-toggle="tab" href="#countries" role="tab" aria-controls="countries"
                           aria-selected="false">Countries</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="campaign-tab" data-toggle="tab" href="#campaign" role="tab" aria-controls="campaign"
                           aria-selected="false">Campaigns</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="plan-tab" data-toggle="tab" href="#plan" role="tab" aria-controls="plan"
                           aria-selected="false">User Plans</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="usershopdetail-tab" data-toggle="tab" href="#usershopdetail" role="tab" aria-controls="usershopdetail"
                           aria-selected="false">User Shop Details</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="shopdetail" role="tabpanel" aria-labelledby="shopdetail-tab">
                        <div class="card col-md-12">
                            <form action="{{route('shop-status-detail-save')}}" method="post">
                                @csrf
                            <div class="card-header d-flex justify-content-between align-items-center bg-white pb-1">
                                <h5>User Status Detail</h5>
                                <div class="form-group">
                                    <input type="submit" class="btn btn-lg btn-primary" value="Save">
                                </div>
                            </div>
                            <div class="card-body col-md-12">
                                {{--                    @dd($shop_data)--}}

                                    {{--                            @dd($shop_data)--}}
                                    <input hidden type="number" name="user_id" value="{{$shop_data->user_id}}">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="#">Firstname</label>
                                                <input placeholder="Enter your firstname" value="{{ $shop_data->firstname }}" name="firstname" type="text" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label for="#">Surname</label>
                                                <input placeholder="Enter your surname" value="{{ $shop_data->surname }}" name="surname" type="text" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label for="#">Email</label>
                                                <input placeholder="Enter email" value="{{ $shop_data-> email}}" name="email" type="email" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label for="#">Mobile#</label>
                                                <input placeholder="Enter mobile number" value="{{ $shop_data-> mobile_number}}" name="mobile_number" type="number" class="form-control">
                                                <small class="text-muted">Mobile format must be 447</small>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="#">Sender Name</label>
                                                <input placeholder="Enter your sendername" value="{{ $shop_data-> sender_name}}" name="sender_name" type="text" class="form-control">
                                                <small class="text-muted">where the SMS is being sent from</small>
                                            </div>
                                            <div class="form-group">
                                                <label for="#">Company Name</label>
                                                <input placeholder="Enter your company name" value="{{ $shop_data-> company_name}}" name="company_name" type="text" class="form-control">
                                            </div>
                                            <div class="mb-2">
                                                Shop Status
                                            </div>
                                            <label class="switch" style="">
                                                {{--                                    @dd($shop_data->user)--}}
                                                <input @if($shop_data->user->user_status == "active")checked="" @endif name="user_status" type="checkbox" value="active" class="custom-control-input  status-switch">
                                                <span class="slider round"></span>
                                            </label>

                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="#">Username</label>
                                                <input placeholder="Enter your username" name="user_name" value="{{$shop_data->user_name}}" type="text" class="form-control">
                                                <small class="text-muted">username format must be xxxxxxxx.textglobal</small>
                                            </div>
                                            <div class="form-group">
                                                <label for="#">Password</label>
                                                <input placeholder="Enter your password" name="password" type="text" value="{{$shop_data->password}}" class="form-control">
                                            </div>
                                        </div>
                                    </div>

                            </div>
                            </form>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="countries" role="tabpanel" aria-labelledby="countries-tab">
                        <div class="card col-md-12">
                            <form action="{{route('country-shop-preferences-save')}}" method="post">
                                @csrf
                                <div class="card-header col-md-12 d-flex justify-content-between align-items-center bg-white pb-1">
                                    <h5>Countries</h5>
                                    <div class="form-group">
                                        <input type="submit" class="btn btn-lg btn-primary" value="Save">
                                    </div>
                                </div>
                                <div class="card-body col-md-12">
                                    {{--                        @dd($user_shop_data)--}}
                                    {{--                        {{route('user-country-save')}}--}}

                                    <input hidden type="number" name="user_id" value="{{$shop_id}}">
                                    <div class="row">
                                        <div class="col-md-12">
{{--                                                                                @dd($countries_data)--}}

                                            <div class="form-group">
                                                <div>
                                                    @foreach($countries_data as $key=>$countries)
                                        {{--                                                <input hidden type="number" name="country_id" value="{{$countries->id}}">--}}
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div >{{$countries->name}}</div>

{{--                                                            @dd($countries->id)--}}
                                                             <label class="switch">
{{--                                                                 @dd($country_shoppreference_data)--}}
                                                                <input @foreach($country_shoppreference_data as $country_user) @if( ($countries->id == $country_user->country_id)  ) checked @endif @endforeach value="{{$countries->id}}"  name="country_id[]" type="checkbox" >
                                                                <span class="slider round"></span>
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <br>


                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="campaign" role="tabpanel" aria-labelledby="campaign-tab">
                        <div class="card col-md-12">
                            <div class="card-header" style="background: white;">
                                <div class="row ">
                                    <div class="col-md-12 px-3 pt-2">
                                        <div class="d-flex justify-content-between">
                                            <h5>Campaigns</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">

                                <ul class="nav nav-tabs" id="myTab" role="tablist">

                                    <li class="nav-item">
                                        <a class="nav-link" id="bundulCampaign-tab" data-toggle="tab" href="#bundulCampaign" role="tab" aria-controls="bundulCampaign"
                                           aria-selected="false">Bundul Campaigns</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="welcomeCampaign-tab" data-toggle="tab" href="#welcomeCampaign" role="tab" aria-controls="welcomeCampaign"
                                           aria-selected="false">Welcome Campaigns</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="abandonedCartCamapign-tab" data-toggle="tab" href="#abandonedCartCamapign" role="tab" aria-controls="abandonedCartCamapign"
                                           aria-selected="false">Abandoned Cart Camapigns</a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="myTabContent">

                                    <div class="tab-pane fade show active" id="bundulCampaign" role="tabpanel" aria-labelledby="bundulCampaign-tab">
                                        <div class="card col-md-12">
                                            <div class="card-header" style="background: white;">
                                                <div class="row ">
                                                    <div class="col-md-12 px-3 pt-2">
                                                        <div class="d-flex justify-content-between">
                                                            <h5>Bundul Sms Campains</h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div id="product_append">
                                                    <div class="row px-3" style="overflow-x:auto;">

                                                        <table id="datatabled" class="table table-borderless  table-hover  table-class ">
                                                            <thead class="border-0 ">

                                                            <tr class="th-tr table-tr text-white text-center">
                                                                <th class="font-weight-bold " style="width: 50%">Action</th>
                                                                <th class="font-weight-bold " style="width: 50%">Created_at</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            {{--                                        @dd($users_data)--}}
                                                            @foreach($campaign_logs_data as $key=>$campaign_log)

                                                                <tr class="td-text-center">
                                                                    <td>
                                                                        {{$campaign_log->action}}
                                                                    </td>
                                                                    <td >
                                                                        {{$campaign_log->created_at}}
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                        {!!  $campaign_logs_data->links() !!}
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade " id="welcomeCampaign" role="tabpanel" aria-labelledby="welcomeCampaign-tab">
                                        <div class="card col-md-12">
                                            <div class="card-header" style="background: white;">
                                                <div class="row ">
                                                    <div class="col-md-12 px-3 pt-2">
                                                        <div class="d-flex justify-content-between">
                                                            <h5>Welcome Sms Campaigns</h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div id="product_append">
                                                    <div class="row px-3" style="overflow-x:auto;">

                                                        <table id="datatabled" class="table table-borderless  table-hover  table-class ">
                                                            <thead class="border-0 ">

                                                            <tr class="th-tr table-tr text-white text-center">
                                                                <th class="font-weight-bold " style="width: 50%">Action</th>
                                                                <th class="font-weight-bold " style="width: 50%">Created_at</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            {{--                                        @dd($users_data)--}}
                                                            @foreach($welcomeCampaign_logs_data as $key=>$welcomeCampaign_log)

                                                                <tr class="td-text-center">
                                                                    <td>
                                                                        {{$welcomeCampaign_log->action}}
                                                                    </td>
                                                                    <td >
                                                                        {{$welcomeCampaign_log->created_at}}
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                        {!!  $welcomeCampaign_logs_data->links() !!}
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade " id="abandonedCartCamapign" role="tabpanel" aria-labelledby="abandonedCartCamapign-tab">
                                        <div class="card col-md-12">
                                            <div class="card-header" style="background: white;">
                                                <div class="row ">
                                                    <div class="col-md-12 px-3 pt-2">
                                                        <div class="d-flex justify-content-between">
                                                            <h5>Abandoned Cart Camapign</h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div id="product_append">
                                                    <div class="row px-3" style="overflow-x:auto;">

                                                        <table id="datatabled" class="table table-borderless  table-hover  table-class ">
                                                            <thead class="border-0 ">

                                                            <tr class="th-tr table-tr text-white text-center">
                                                                <th class="font-weight-bold " style="width: 50%">Action</th>
                                                                <th class="font-weight-bold " style="width: 50%">Created_at</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            {{--                                        @dd($users_data)--}}
{{--                                                            @if(isset($abandonedCartCampaign_logs_data))--}}
{{--                                                                @foreach($abandonedCartCampaign_logs_data as $key=>$abandonedCartCampaign_log)--}}

{{--                                                                    <tr class="td-text-center">--}}
{{--                                                                        <td>--}}
{{--                                                                            {{$abandonedCartCampaign_log->action}}--}}
{{--                                                                        </td>--}}
{{--                                                                        <td >--}}
{{--                                                                            {{$abandonedCartCampaign_log->created_at}}--}}
{{--                                                                        </td>--}}
{{--                                                                    </tr>--}}
{{--                                                                @endforeach--}}
{{--                                                                @endif--}}
                                                            </tbody>
                                                        </table>
{{--                                                        @if(isset($abandonedCartCampaign_logs_data))--}}
{{--                                                        {!!  $abandonedCartCampaign_logs_data->links() !!}--}}
{{--                                                            @endif--}}
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="plan" role="tabpanel" aria-labelledby="plan-tab">
                        <div class="card col-md-12">
                            <div class="card-header" style="background: white;">
                                <div class="row ">
                                    <div class="col-md-12 px-3 pt-2">
                                        <div class="d-flex justify-content-between">
                                            <h5>User Plans</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div id="product_append">
                                    <div class="row px-3" style="overflow-x:auto;">

                                        <table id="datatabled" class="table table-borderless  table-hover  table-class ">
                                            <thead class="border-0 ">

                                            <tr class="th-tr table-tr text-white text-center">
                                                <th class="font-weight-bold " style="width: 50%">Action</th>
                                                <th class="font-weight-bold " style="width: 50%">Created_at</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            {{--                                        @dd($users_data)--}}
                                            @foreach($plan_logs_data as $key=>$plan_log)

                                                <tr class="td-text-center">
                                                    <td>
                                                        {{$plan_log->action}}
                                                    </td>
                                                    <td >
                                                        {{$plan_log->created_at}}
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                        {!!  $plan_logs_data->links() !!}
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="usershopdetail" role="tabpanel" aria-labelledby="usershopdetail-tab">
                        <div class="card col-md-12">
                            <div class="card-header" style="background: white;">
                                <div class="row ">
                                    <div class="col-md-12 px-3 pt-2">
                                        <div class="d-flex justify-content-between">
                                            <h5>User Shop Detail</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div id="product_append">
                                    <div class="row px-3" style="overflow-x:auto;">

                                        <table id="datatabled" class="table table-borderless  table-hover  table-class ">
                                            <thead class="border-0 ">

                                            <tr class="th-tr table-tr text-white text-center">
                                                <th class="font-weight-bold " style="width: 50%">Action</th>
                                                <th class="font-weight-bold " style="width: 50%">Created_at</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            {{--                                        @dd($users_data)--}}
                                            @foreach($user_shop_detail_logs_data as $key=>$user_shop_detail_log)

                                                <tr class="td-text-center">
                                                    <td>
                                                        {{$user_shop_detail_log->action}}
                                                    </td>
                                                    <td >
                                                        {{$user_shop_detail_log->created_at}}
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                        {!!  $user_shop_detail_logs_data->links() !!}
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>

@endsection
