@extends('adminpanel.user-layout.default')
@section('content')
    <div class="col-lg-12 col-md-12 p-4">
        <!-- start info box -->
        <div class="row ">
            <div class="col-md-12 col-lg-12 m-auto">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="welcome_sms-tab" data-toggle="tab" href="#welcome_sms" role="tab" aria-controls="welcome_sms"
                           aria-selected="true">Welcome SMS</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="abandoned_cart-tab" data-toggle="tab" href="#abandoned_cart" role="tab" aria-controls="abandoned_cart"
                           aria-selected="false">Abandoned Cart</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="order_confirmation_sms-tab" data-toggle="tab" href="#order_confirmation_sms" role="tab" aria-controls="order_confirmation_sms"
                           aria-selected="false">Order Confirmation</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="order_refund-tab" data-toggle="tab" href="#order_refund" role="tab" aria-controls="order_refund"
                           aria-selected="false">Order Refund</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="order_dispatch-tab" data-toggle="tab" href="#order_dispatch" role="tab" aria-controls="order_dispatch"
                           aria-selected="false">Order Dispatch</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="welcome_sms" role="tabpanel" aria-labelledby="welcome_sms-tab">
                        <div class="card col-md-12">

                            <div class="card-body col-md-12">
                                {{--                    @dd($shop_data)--}}

                                {{--                            @dd($shop_data)--}}
                                <ul class="nav nav-tabs" id="myTab" role="tablist">

                                    <li class="nav-item">
                                        <a class="nav-link" id="welcome_sms_campaign-tab" data-toggle="tab" href="#welcome_sms_campaign" role="tab" aria-controls="welcome_sms_campaign"
                                           aria-selected="false">Welcome Sms Campaign</a>
                                    </li>
{{--                                    <li class="nav-item">--}}
{{--                                        <a class="nav-link" id="welcomeCampaignLogs-tab" data-toggle="tab" href="#welcomeCampaignLogs" role="tab" aria-controls="welcomeCampaignLogs"--}}
{{--                                           aria-selected="false">Welcome Logs</a>--}}
{{--                                    </li>--}}
                                </ul>
                                <div class="tab-content" id="myTabContent">

                                    <div class="tab-pane fade show active" id="welcome_sms_campaign" role="tabpanel" aria-labelledby="welcome_sms_campaign-tab">
                                        <div class="col-md-12 col-lg-12 card">
                                            <form  action="{{Route('welcome-sms-campaign-save')}}" method="post"  >
                                                @csrf
                                                <div class="card-header bg-white  d-flex justify-content-between align-items-center">
                                                    <h5>Welcome Sms Campaign</h5>
                                                    <div class="">
                                                        <button type="submit"  class=" btn btn-primary ">Save</button>
                                                    </div>
                                                </div>
                                                <div class="row px-3">
                                                    <div class="card-body col-md-8 col-lg-8 ">
                                                        <div class="form-group">
                                                            <label class="text-left"  for="#">Campaign Name</label>
                                                            <input @if(isset($welcome_campaign->campaign_name)) value="{{$welcome_campaign->campaign_name}}" @endif name="campaign_name" type="text"  class="form-control ">
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="text-left"  for="#">Sender Name</label>
                                                            <input @if(isset($welcome_campaign->sender_name)) value="{{$welcome_campaign->sender_name}}" @endif name="sender_name" type="text"  class="form-control name">
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="text-left"  for="#">Text Message</label>
                                                            <div id="cct_embed_counts">
                                                                <textarea maxlength="160" class="form-control create-campaign-textarea"  name="message_text"  rows="4">@if(isset($welcome_campaign->message_text)){{$welcome_campaign->message_text}}@endif</textarea>
                                                                <span id="rchars">160</span> Character(s) Remaining
                                                            </div>
                                                        </div>
                                                        <div class="mb-2">
                                                            Status
                                                        </div>
                                                        <label class="switch" style="">
                                                            {{--                                    @dd($shop_data->user)--}}
                                                            <input @if( isset($welcome_campaign->status) && $welcome_campaign->status == "active")checked="" @endif name="status" type="checkbox" value="active" class="custom-control-input  status-switch">
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>
                                                    <div class="card-body col-md-4 col-lg-4 ">
                                                        <div class="card" >
                                                            <div class="card-body">
                                                                <h6 class="card-title" style="font-size: 15px">Variables</h6>
                                                                <p class="card-text custom-variable-font-size" >You can use variables to output in your 'Sender Name' and 'Text Message' fields. The available variables are:</p>
                                                            </div>
                                                            <ul class="list-group list-group-flush">
                                                                <li class="list-group-item custom-variable-font-size">{CustomerName}</li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
{{--                                        <div class="card col-md-12">--}}
{{--                                            <form action="{{route('shop-status-detail-save')}}" method="post">--}}
{{--                                                @csrf--}}
{{--                                                <div class="card-header" style="background: white;padding-bottom: 0;">--}}
{{--                                                    <div class="row ">--}}
{{--                                                        <div class="col-md-12 px-3 ">--}}
{{--                                                            <div class="d-flex justify-content-end ">--}}
{{--                                                                <div class="form-group">--}}
{{--                                                                    <input type="submit" class="btn btn-lg btn-primary" value="Save">--}}
{{--                                                                </div>--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                                <div class="card-body">--}}
{{--                                                    <input hidden type="number" name="user_id" value="{{$shop_data->user_id}}">--}}
{{--                                                    <div class="row">--}}
{{--                                                        <div class="col-md-4">--}}
{{--                                                            <div class="form-group">--}}
{{--                                                                <label for="#">Firstname</label>--}}
{{--                                                                <input placeholder="Enter your firstname" value="{{ $shop_data->firstname }}" name="firstname" type="text" class="form-control">--}}
{{--                                                            </div>--}}
{{--                                                            <div class="form-group">--}}
{{--                                                                <label for="#">Surname</label>--}}
{{--                                                                <input placeholder="Enter your surname" value="{{ $shop_data->surname }}" name="surname" type="text" class="form-control">--}}
{{--                                                            </div>--}}
{{--                                                            <div class="form-group">--}}
{{--                                                                <label for="#">Email</label>--}}
{{--                                                                <input placeholder="Enter email" value="{{ $shop_data-> email}}" name="email" type="email" class="form-control">--}}
{{--                                                            </div>--}}
{{--                                                            <div class="form-group">--}}
{{--                                                                <label for="#">Mobile#</label>--}}
{{--                                                                <input placeholder="Enter mobile number" value="{{ $shop_data-> mobile_number}}" name="mobile_number" type="number" class="form-control">--}}
{{--                                                                <small class="text-muted">Mobile format must be 447</small>--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}
{{--                                                        <div class="col-md-4">--}}
{{--                                                            <div class="form-group">--}}
{{--                                                                <label for="#">Sender Name</label>--}}
{{--                                                                <input placeholder="Enter your sendername" value="{{ $shop_data-> sender_name}}" name="sender_name" type="text" class="form-control">--}}
{{--                                                                <small class="text-muted">where the SMS is being sent from</small>--}}
{{--                                                            </div>--}}
{{--                                                            <div class="form-group">--}}
{{--                                                                <label for="#">Company Name</label>--}}
{{--                                                                <input placeholder="Enter your company name" value="{{ $shop_data-> company_name}}" name="company_name" type="text" class="form-control">--}}
{{--                                                            </div>--}}
{{--                                                            <div class="mb-2">--}}
{{--                                                                Shop Status--}}
{{--                                                            </div>--}}
{{--                                                            <label class="switch" style="">--}}
{{--                                                                --}}{{--                                    @dd($shop_data->user)--}}
{{--                                                                <input @if($shop_data->user->user_status == "active")checked="" @endif name="user_status" type="checkbox" value="active" class="custom-control-input  status-switch">--}}
{{--                                                                <span class="slider round"></span>--}}
{{--                                                            </label>--}}

{{--                                                        </div>--}}
{{--                                                        <div class="col-md-4">--}}
{{--                                                            <div class="form-group">--}}
{{--                                                                <label for="#">Username</label>--}}
{{--                                                                <input placeholder="Enter your username" name="user_name" value="{{$shop_data->user_name}}" type="text" class="form-control">--}}
{{--                                                                <small class="text-muted">username format must be xxxxxxxx.textglobal</small>--}}
{{--                                                            </div>--}}
{{--                                                            <div class="form-group">--}}
{{--                                                                <label for="#">Password</label>--}}
{{--                                                                <input placeholder="Enter your password" name="password" type="text" value="{{$shop_data->password}}" class="form-control">--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                            </form>--}}
{{--                                        </div>--}}
                                    </div>

{{--                                    <div class="tab-pane fade " id="welcomeCampaignLogs" role="tabpanel" aria-labelledby="welcomeCampaignLogs-tab">--}}
{{--                                        <div class="card col-md-12">--}}
{{--                                            <div class="card-body">--}}
{{--                                                <div id="product_append">--}}
{{--                                                    <div class="row px-3" style="overflow-x:auto;">--}}

{{--                                                        <table id="datatabled" class="table table-borderless  table-hover  table-class ">--}}
{{--                                                            <thead class="border-0 ">--}}

{{--                                                            <tr class="th-tr table-tr text-white text-center">--}}
{{--                                                                <th class="font-weight-bold " style="width: 50%">Action</th>--}}
{{--                                                                <th class="font-weight-bold " style="width: 50%">Created_at</th>--}}
{{--                                                            </tr>--}}
{{--                                                            </thead>--}}
{{--                                                            <tbody>--}}
{{--                                                            --}}{{--                                        @dd($users_data)--}}
{{--                                                            @foreach($welcomeCampaign_logs_data as $key=>$welcomeCampaign_log)--}}

{{--                                                                <tr class="td-text-center">--}}
{{--                                                                    <td>--}}
{{--                                                                        {{$welcomeCampaign_log->action}}--}}
{{--                                                                    </td>--}}
{{--                                                                    <td >--}}
{{--                                                                        {{$welcomeCampaign_log->created_at}}--}}
{{--                                                                    </td>--}}
{{--                                                                </tr>--}}
{{--                                                            @endforeach--}}
{{--                                                            </tbody>--}}
{{--                                                        </table>--}}
{{--                                                        {!!  $welcomeCampaign_logs_data->links() !!}--}}
{{--                                                    </div>--}}

{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}

                                </div>

                            </div>

                        </div>
                    </div>
                    <div class="tab-pane fade" id="abandoned_cart" role="tabpanel" aria-labelledby="abandoned_cart-tab">
                        <div class="col-md-12 col-lg-12 card">
                            <form  action="{{Route('abandoned-cart-campaign-save')}}" method="post"  >
                                @csrf
                                <div class="card-header bg-white  d-flex justify-content-between align-items-center">
                                    <h5>Abandoned Cart Campaign</h5>
                                    <div class="">
                                        <button type="submit"  class=" btn btn-primary ">Save</button>
                                    </div>
                                </div>
                                <div class="row px-3">
                                    <div class="card-body col-md-8 col-lg-8 ">
                                        <div class="form-group">
                                            <label class="text-left"  for="#">Campaign Name</label>
                                            <input @if(isset($abandoned_cart_campaign->campaign_name)) value="{{$abandoned_cart_campaign->campaign_name}}" @endif name="campaign_name" type="text"  class="form-control ">
                                        </div>

                                        <div class="form-group">
                                            <label class="text-left"  for="#">Sender Name</label>
                                            <input @if(isset($abandoned_cart_campaign->sender_name)) value="{{$abandoned_cart_campaign->sender_name}}" @endif name="sender_name" type="text"  class="form-control name">
                                        </div>
                                        <div class="form-group ">
                                            <div class="form-group">
                                                <label class="text-left"  for="#">Text Message</label>
                                                <div id="cct_embed_counts">
                                                    <textarea maxlength="160" class="form-control create-campaign-textarea"  name="message_text"  rows="4">@if(isset($abandoned_cart_campaign->message_text)){{$abandoned_cart_campaign->message_text}}@endif</textarea>
                                                    <span id="rchars">160</span> Character(s) Remaining
                                                </div>
                                            </div>
                                            <label class="text-left"  for="#">Delay Time</label>
                                            <div class="pl-3 d-flex justify-content-around">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input type="radio" @if($abandoned_cart_campaign->delay_time == 1) checked @endif value="1" class="form-check-input" name="delay_time">1 Hour
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input type="radio" @if($abandoned_cart_campaign->delay_time == 2) checked @endif value="2" class="form-check-input" name="delay_time">2 Hour
                                                    </label>
                                                </div>
                                                <div class="form-check ">
                                                    <label class="form-check-label">
                                                        <input type="radio" value="3" @if($abandoned_cart_campaign->delay_time == 3) checked @endif class="form-check-input" name="delay_time" >3 Hour
                                                    </label>
                                                </div>
                                                <div class="form-check ">
                                                    <label class="form-check-label">
                                                        <input type="radio" value="12" @if($abandoned_cart_campaign->delay_time == 12) checked @endif class="form-check-input" name="delay_time" >12 Hour
                                                    </label>
                                                </div>
                                                <div class="form-check ">
                                                    <label class="form-check-label">
                                                        <input type="radio" value="24" @if($abandoned_cart_campaign->delay_time == 24) checked @endif class="form-check-input" name="delay_time" >24 Hour
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                Status
                                            </div>
                                            <label class="switch" style="">
                                                {{--                                    @dd($shop_data->user)--}}
                                                <input @if(isset($abandoned_cart_campaign->status) && $abandoned_cart_campaign->status == "active")checked @endif name="status" type="checkbox" value="active" class="custom-control-input  status-switch">
                                                <span class="slider round"></span>
                                            </label>
                                        </div>

                                    </div>
                                    <div class="card-body col-md-4 col-lg-4 ">
                                        <div class="card" >
                                            <div class="card-body">
                                                <h6 class="card-title" style="font-size: 15px">Variables</h6>
                                                <p class="card-text custom-variable-font-size" >You can use variables to output in your 'Sender Name' and 'Text Message' fields. The available variables are:</p>
                                            </div>
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item custom-variable-font-size">{CustomerName}</li>
                                                <li class="list-group-item custom-variable-font-size">{OrderName}</li>
                                                <li class="list-group-item custom-variable-font-size">{FulfillmentStatus}</li>
                                                <li class="list-group-item custom-variable-font-size">{FinancialStatus}</li>
                                                <li class="list-group-item custom-variable-font-size">{OrderStatusUrl}</li>
                                                <li class="list-group-item custom-variable-font-size">{OrderTotalPrice}</li>
                                                <li class="list-group-item custom-variable-font-size">{Currency}</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        {{--                                        <div class="card col-md-12">--}}
                        {{--                                            <form action="{{route('shop-status-detail-save')}}" method="post">--}}
                        {{--                                                @csrf--}}
                        {{--                                                <div class="card-header" style="background: white;padding-bottom: 0;">--}}
                        {{--                                                    <div class="row ">--}}
                        {{--                                                        <div class="col-md-12 px-3 ">--}}
                        {{--                                                            <div class="d-flex justify-content-end ">--}}
                        {{--                                                                <div class="form-group">--}}
                        {{--                                                                    <input type="submit" class="btn btn-lg btn-primary" value="Save">--}}
                        {{--                                                                </div>--}}
                        {{--                                                            </div>--}}
                        {{--                                                        </div>--}}
                        {{--                                                    </div>--}}
                        {{--                                                </div>--}}
                        {{--                                                <div class="card-body">--}}
                        {{--                                                    <input hidden type="number" name="user_id" value="{{$shop_data->user_id}}">--}}
                        {{--                                                    <div class="row">--}}
                        {{--                                                        <div class="col-md-4">--}}
                        {{--                                                            <div class="form-group">--}}
                        {{--                                                                <label for="#">Firstname</label>--}}
                        {{--                                                                <input placeholder="Enter your firstname" value="{{ $shop_data->firstname }}" name="firstname" type="text" class="form-control">--}}
                        {{--                                                            </div>--}}
                        {{--                                                            <div class="form-group">--}}
                        {{--                                                                <label for="#">Surname</label>--}}
                        {{--                                                                <input placeholder="Enter your surname" value="{{ $shop_data->surname }}" name="surname" type="text" class="form-control">--}}
                        {{--                                                            </div>--}}
                        {{--                                                            <div class="form-group">--}}
                        {{--                                                                <label for="#">Email</label>--}}
                        {{--                                                                <input placeholder="Enter email" value="{{ $shop_data-> email}}" name="email" type="email" class="form-control">--}}
                        {{--                                                            </div>--}}
                        {{--                                                            <div class="form-group">--}}
                        {{--                                                                <label for="#">Mobile#</label>--}}
                        {{--                                                                <input placeholder="Enter mobile number" value="{{ $shop_data-> mobile_number}}" name="mobile_number" type="number" class="form-control">--}}
                        {{--                                                                <small class="text-muted">Mobile format must be 447</small>--}}
                        {{--                                                            </div>--}}
                        {{--                                                        </div>--}}
                        {{--                                                        <div class="col-md-4">--}}
                        {{--                                                            <div class="form-group">--}}
                        {{--                                                                <label for="#">Sender Name</label>--}}
                        {{--                                                                <input placeholder="Enter your sendername" value="{{ $shop_data-> sender_name}}" name="sender_name" type="text" class="form-control">--}}
                        {{--                                                                <small class="text-muted">where the SMS is being sent from</small>--}}
                        {{--                                                            </div>--}}
                        {{--                                                            <div class="form-group">--}}
                        {{--                                                                <label for="#">Company Name</label>--}}
                        {{--                                                                <input placeholder="Enter your company name" value="{{ $shop_data-> company_name}}" name="company_name" type="text" class="form-control">--}}
                        {{--                                                            </div>--}}
                        {{--                                                            <div class="mb-2">--}}
                        {{--                                                                Shop Status--}}
                        {{--                                                            </div>--}}
                        {{--                                                            <label class="switch" style="">--}}
                        {{--                                                                --}}{{--                                    @dd($shop_data->user)--}}
                        {{--                                                                <input @if($shop_data->user->user_status == "active")checked="" @endif name="user_status" type="checkbox" value="active" class="custom-control-input  status-switch">--}}
                        {{--                                                                <span class="slider round"></span>--}}
                        {{--                                                            </label>--}}

                        {{--                                                        </div>--}}
                        {{--                                                        <div class="col-md-4">--}}
                        {{--                                                            <div class="form-group">--}}
                        {{--                                                                <label for="#">Username</label>--}}
                        {{--                                                                <input placeholder="Enter your username" name="user_name" value="{{$shop_data->user_name}}" type="text" class="form-control">--}}
                        {{--                                                                <small class="text-muted">username format must be xxxxxxxx.textglobal</small>--}}
                        {{--                                                            </div>--}}
                        {{--                                                            <div class="form-group">--}}
                        {{--                                                                <label for="#">Password</label>--}}
                        {{--                                                                <input placeholder="Enter your password" name="password" type="text" value="{{$shop_data->password}}" class="form-control">--}}
                        {{--                                                            </div>--}}
                        {{--                                                        </div>--}}
                        {{--                                                    </div>--}}
                        {{--                                                </div>--}}
                        {{--                                            </form>--}}
                        {{--                                        </div>--}}
                    </div>
                    <div class="tab-pane fade" id="order_confirmation_sms" role="tabpanel" aria-labelledby="order_confirmation_sms-tab">
                        <div class="col-md-12 col-lg-12 card">
                            <form  action="{{Route('orderconfirm-campaign-save')}}" method="post"  >
                                @csrf
                                <div class="card-header bg-white  d-flex justify-content-between align-items-center">
                                    <h5>Order Confirm Campaign</h5>
                                    <div class="">
                                        <button type="submit"  class=" btn btn-primary ">Save</button>
                                    </div>
                                </div>
                                <div class="row px-3">
                                    <div class="card-body col-md-8 col-lg-8 ">
                                        <div class="form-group">
                                            <label class="text-left"  for="#">Campaign Name</label>
                                            <input @if(isset($orderconfirm_campaign->campaign_name)) value="{{$orderconfirm_campaign->campaign_name}}" @endif name="campaign_name" type="text"  class="form-control ">
                                        </div>

                                        <div class="form-group">
                                            <label class="text-left"  for="#">Sender Name</label>
                                            <input @if(isset($orderconfirm_campaign->sender_name)) value="{{$orderconfirm_campaign->sender_name}}" @endif name="sender_name" type="text"  class="form-control name">
                                        </div>
                                        <div class="form-group">
                                            <label class="text-left"  for="#">Text Message</label>
                                            <div id="cct_embed_counts">
                                                <textarea maxlength="160" class="form-control create-campaign-textarea"  name="message_text"  rows="4">@if(isset($orderconfirm_campaign->message_text)){{$orderconfirm_campaign->message_text}}@endif</textarea>
                                                <span id="rchars">160</span> Character(s) Remaining
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            Status
                                        </div>
                                        <label class="switch" style="">
                                            {{--                                    @dd($shop_data->user)--}}
                                            <input @if(isset($orderconfirm_campaign->status) && $orderconfirm_campaign->status == "active")checked @endif name="status" type="checkbox" value="active" class="custom-control-input  status-switch">
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                    <div class="card-body col-md-4 col-lg-4 ">
                                        <div class="card" >
                                            <div class="card-body">
                                                <h6 class="card-title" style="font-size: 15px">Variables</h6>
                                                <p class="card-text custom-variable-font-size" >You can use variables to output in your 'Sender Name' and 'Text Message' fields. The available variables are:</p>
                                            </div>
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item custom-variable-font-size">{CustomerName}</li>
                                                <li class="list-group-item custom-variable-font-size">{OrderName}</li>
                                                <li class="list-group-item custom-variable-font-size">{FinancialStatus}</li>
                                                <li class="list-group-item custom-variable-font-size">{OrderStatusUrl}</li>
                                                <li class="list-group-item custom-variable-font-size">{OrderTotalPrice}</li>
                                                <li class="list-group-item custom-variable-font-size">{Currency}</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        {{--                                        <div class="card col-md-12">--}}
                        {{--                                            <form action="{{route('shop-status-detail-save')}}" method="post">--}}
                        {{--                                                @csrf--}}
                        {{--                                                <div class="card-header" style="background: white;padding-bottom: 0;">--}}
                        {{--                                                    <div class="row ">--}}
                        {{--                                                        <div class="col-md-12 px-3 ">--}}
                        {{--                                                            <div class="d-flex justify-content-end ">--}}
                        {{--                                                                <div class="form-group">--}}
                        {{--                                                                    <input type="submit" class="btn btn-lg btn-primary" value="Save">--}}
                        {{--                                                                </div>--}}
                        {{--                                                            </div>--}}
                        {{--                                                        </div>--}}
                        {{--                                                    </div>--}}
                        {{--                                                </div>--}}
                        {{--                                                <div class="card-body">--}}
                        {{--                                                    <input hidden type="number" name="user_id" value="{{$shop_data->user_id}}">--}}
                        {{--                                                    <div class="row">--}}
                        {{--                                                        <div class="col-md-4">--}}
                        {{--                                                            <div class="form-group">--}}
                        {{--                                                                <label for="#">Firstname</label>--}}
                        {{--                                                                <input placeholder="Enter your firstname" value="{{ $shop_data->firstname }}" name="firstname" type="text" class="form-control">--}}
                        {{--                                                            </div>--}}
                        {{--                                                            <div class="form-group">--}}
                        {{--                                                                <label for="#">Surname</label>--}}
                        {{--                                                                <input placeholder="Enter your surname" value="{{ $shop_data->surname }}" name="surname" type="text" class="form-control">--}}
                        {{--                                                            </div>--}}
                        {{--                                                            <div class="form-group">--}}
                        {{--                                                                <label for="#">Email</label>--}}
                        {{--                                                                <input placeholder="Enter email" value="{{ $shop_data-> email}}" name="email" type="email" class="form-control">--}}
                        {{--                                                            </div>--}}
                        {{--                                                            <div class="form-group">--}}
                        {{--                                                                <label for="#">Mobile#</label>--}}
                        {{--                                                                <input placeholder="Enter mobile number" value="{{ $shop_data-> mobile_number}}" name="mobile_number" type="number" class="form-control">--}}
                        {{--                                                                <small class="text-muted">Mobile format must be 447</small>--}}
                        {{--                                                            </div>--}}
                        {{--                                                        </div>--}}
                        {{--                                                        <div class="col-md-4">--}}
                        {{--                                                            <div class="form-group">--}}
                        {{--                                                                <label for="#">Sender Name</label>--}}
                        {{--                                                                <input placeholder="Enter your sendername" value="{{ $shop_data-> sender_name}}" name="sender_name" type="text" class="form-control">--}}
                        {{--                                                                <small class="text-muted">where the SMS is being sent from</small>--}}
                        {{--                                                            </div>--}}
                        {{--                                                            <div class="form-group">--}}
                        {{--                                                                <label for="#">Company Name</label>--}}
                        {{--                                                                <input placeholder="Enter your company name" value="{{ $shop_data-> company_name}}" name="company_name" type="text" class="form-control">--}}
                        {{--                                                            </div>--}}
                        {{--                                                            <div class="mb-2">--}}
                        {{--                                                                Shop Status--}}
                        {{--                                                            </div>--}}
                        {{--                                                            <label class="switch" style="">--}}
                        {{--                                                                --}}{{--                                    @dd($shop_data->user)--}}
                        {{--                                                                <input @if($shop_data->user->user_status == "active")checked="" @endif name="user_status" type="checkbox" value="active" class="custom-control-input  status-switch">--}}
                        {{--                                                                <span class="slider round"></span>--}}
                        {{--                                                            </label>--}}

                        {{--                                                        </div>--}}
                        {{--                                                        <div class="col-md-4">--}}
                        {{--                                                            <div class="form-group">--}}
                        {{--                                                                <label for="#">Username</label>--}}
                        {{--                                                                <input placeholder="Enter your username" name="user_name" value="{{$shop_data->user_name}}" type="text" class="form-control">--}}
                        {{--                                                                <small class="text-muted">username format must be xxxxxxxx.textglobal</small>--}}
                        {{--                                                            </div>--}}
                        {{--                                                            <div class="form-group">--}}
                        {{--                                                                <label for="#">Password</label>--}}
                        {{--                                                                <input placeholder="Enter your password" name="password" type="text" value="{{$shop_data->password}}" class="form-control">--}}
                        {{--                                                            </div>--}}
                        {{--                                                        </div>--}}
                        {{--                                                    </div>--}}
                        {{--                                                </div>--}}
                        {{--                                            </form>--}}
                        {{--                                        </div>--}}
                    </div>
                    <div class="tab-pane fade" id="order_refund" role="tabpanel" aria-labelledby="order_refund-tab">
                        <div class="col-md-12 col-lg-12 card">
                            <form  action="{{Route('orderrefund-campaign-save')}}" method="post"  >
                                @csrf
                                <div class="card-header bg-white  d-flex justify-content-between align-items-center">
                                    <h5>Order Refund Campaign</h5>
                                    <div class="">
                                        <button type="submit"  class=" btn btn-primary ">Save</button>
                                    </div>
                                </div>
                                <div class="row px-3">
                                    <div class="card-body col-md-8 col-lg-8 ">
                                        <div class="form-group">
                                            <label class="text-left"  for="#">Campaign Name</label>
                                            <input @if(isset($orderrefund_campaign->campaign_name)) value="{{$orderrefund_campaign->campaign_name}}" @endif name="campaign_name" type="text"  class="form-control ">
                                        </div>

                                        <div class="form-group">
                                            <label class="text-left"  for="#">Sender Name</label>
                                            <input @if(isset($orderrefund_campaign->sender_name)) value="{{$orderrefund_campaign->sender_name}}" @endif name="sender_name" type="text"  class="form-control name">
                                        </div>
                                        <div class="form-group">
                                            <label class="text-left"  for="#">Text Message</label>
                                            <div id="cct_embed_counts">
                                                <textarea maxlength="160" class="form-control create-campaign-textarea"  name="message_text"  rows="4">@if(isset($orderrefund_campaign->message_text)){{$orderrefund_campaign->message_text}}@endif</textarea>
                                                <span id="rchars">160</span> Character(s) Remaining
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            Status
                                        </div>
                                        <label class="switch" style="">
                                            {{--                                    @dd($shop_data->user)--}}
                                            <input @if( isset($orderrefund_campaign->status) && $orderrefund_campaign->status == "active")checked @endif name="status" type="checkbox" value="active" class="custom-control-input  status-switch">
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                    <div class="card-body col-md-4 col-lg-4 ">
                                        <div class="card" >
                                            <div class="card-body">
                                                <h6 class="card-title" style="font-size: 15px">Variables</h6>
                                                <p class="card-text custom-variable-font-size" >You can use variables to output in your 'Sender Name' and 'Text Message' fields. The available variables are:</p>
                                            </div>
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item custom-variable-font-size">{CustomerName}</li>
                                                <li class="list-group-item custom-variable-font-size">{OrderName}</li>
                                                <li class="list-group-item custom-variable-font-size">{FinancialStatus}</li>
                                                <li class="list-group-item custom-variable-font-size">{OrderStatusUrl}</li>
                                                <li class="list-group-item custom-variable-font-size">{RefundedPaymentCurrency}</li>
                                                <li class="list-group-item custom-variable-font-size">{RefundedAmount}</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        {{--                                        <div class="card col-md-12">--}}
                        {{--                                            <form action="{{route('shop-status-detail-save')}}" method="post">--}}
                        {{--                                                @csrf--}}
                        {{--                                                <div class="card-header" style="background: white;padding-bottom: 0;">--}}
                        {{--                                                    <div class="row ">--}}
                        {{--                                                        <div class="col-md-12 px-3 ">--}}
                        {{--                                                            <div class="d-flex justify-content-end ">--}}
                        {{--                                                                <div class="form-group">--}}
                        {{--                                                                    <input type="submit" class="btn btn-lg btn-primary" value="Save">--}}
                        {{--                                                                </div>--}}
                        {{--                                                            </div>--}}
                        {{--                                                        </div>--}}
                        {{--                                                    </div>--}}
                        {{--                                                </div>--}}
                        {{--                                                <div class="card-body">--}}
                        {{--                                                    <input hidden type="number" name="user_id" value="{{$shop_data->user_id}}">--}}
                        {{--                                                    <div class="row">--}}
                        {{--                                                        <div class="col-md-4">--}}
                        {{--                                                            <div class="form-group">--}}
                        {{--                                                                <label for="#">Firstname</label>--}}
                        {{--                                                                <input placeholder="Enter your firstname" value="{{ $shop_data->firstname }}" name="firstname" type="text" class="form-control">--}}
                        {{--                                                            </div>--}}
                        {{--                                                            <div class="form-group">--}}
                        {{--                                                                <label for="#">Surname</label>--}}
                        {{--                                                                <input placeholder="Enter your surname" value="{{ $shop_data->surname }}" name="surname" type="text" class="form-control">--}}
                        {{--                                                            </div>--}}
                        {{--                                                            <div class="form-group">--}}
                        {{--                                                                <label for="#">Email</label>--}}
                        {{--                                                                <input placeholder="Enter email" value="{{ $shop_data-> email}}" name="email" type="email" class="form-control">--}}
                        {{--                                                            </div>--}}
                        {{--                                                            <div class="form-group">--}}
                        {{--                                                                <label for="#">Mobile#</label>--}}
                        {{--                                                                <input placeholder="Enter mobile number" value="{{ $shop_data-> mobile_number}}" name="mobile_number" type="number" class="form-control">--}}
                        {{--                                                                <small class="text-muted">Mobile format must be 447</small>--}}
                        {{--                                                            </div>--}}
                        {{--                                                        </div>--}}
                        {{--                                                        <div class="col-md-4">--}}
                        {{--                                                            <div class="form-group">--}}
                        {{--                                                                <label for="#">Sender Name</label>--}}
                        {{--                                                                <input placeholder="Enter your sendername" value="{{ $shop_data-> sender_name}}" name="sender_name" type="text" class="form-control">--}}
                        {{--                                                                <small class="text-muted">where the SMS is being sent from</small>--}}
                        {{--                                                            </div>--}}
                        {{--                                                            <div class="form-group">--}}
                        {{--                                                                <label for="#">Company Name</label>--}}
                        {{--                                                                <input placeholder="Enter your company name" value="{{ $shop_data-> company_name}}" name="company_name" type="text" class="form-control">--}}
                        {{--                                                            </div>--}}
                        {{--                                                            <div class="mb-2">--}}
                        {{--                                                                Shop Status--}}
                        {{--                                                            </div>--}}
                        {{--                                                            <label class="switch" style="">--}}
                        {{--                                                                --}}{{--                                    @dd($shop_data->user)--}}
                        {{--                                                                <input @if($shop_data->user->user_status == "active")checked="" @endif name="user_status" type="checkbox" value="active" class="custom-control-input  status-switch">--}}
                        {{--                                                                <span class="slider round"></span>--}}
                        {{--                                                            </label>--}}

                        {{--                                                        </div>--}}
                        {{--                                                        <div class="col-md-4">--}}
                        {{--                                                            <div class="form-group">--}}
                        {{--                                                                <label for="#">Username</label>--}}
                        {{--                                                                <input placeholder="Enter your username" name="user_name" value="{{$shop_data->user_name}}" type="text" class="form-control">--}}
                        {{--                                                                <small class="text-muted">username format must be xxxxxxxx.textglobal</small>--}}
                        {{--                                                            </div>--}}
                        {{--                                                            <div class="form-group">--}}
                        {{--                                                                <label for="#">Password</label>--}}
                        {{--                                                                <input placeholder="Enter your password" name="password" type="text" value="{{$shop_data->password}}" class="form-control">--}}
                        {{--                                                            </div>--}}
                        {{--                                                        </div>--}}
                        {{--                                                    </div>--}}
                        {{--                                                </div>--}}
                        {{--                                            </form>--}}
                        {{--                                        </div>--}}
                    </div>
                    <div class="tab-pane fade" id="order_dispatch" role="tabpanel" aria-labelledby="order_dispatch-tab">
                        <div class="col-md-12 col-lg-12 card">
                            <form  action="{{Route('orderdispatch-campaign-save')}}" method="post"  >
                                @csrf
                                <div class="card-header bg-white  d-flex justify-content-between align-items-center">
                                    <h5>Order Dispatch Campaign</h5>
                                    <div class="">
                                        <button type="submit"  class=" btn btn-primary ">Save</button>
                                    </div>
                                </div>
                                <div class="row px-3">
                                    <div class="card-body col-md-8 col-lg-8 ">
                                        <div class="form-group">
                                            <label class="text-left"  for="#">Campaign Name</label>
                                            <input @if(isset($orderdispatch_campaign->campaign_name)) value="{{$orderdispatch_campaign->campaign_name}}" @endif name="campaign_name" type="text"  class="form-control ">
                                        </div>

                                        <div class="form-group">
                                            <label class="text-left"  for="#">Sender Name</label>
                                            <input @if(isset($orderdispatch_campaign->sender_name)) value="{{$orderdispatch_campaign->sender_name}}" @endif name="sender_name" type="text"  class="form-control name">
                                        </div>
                                        <div class="form-group">
                                            <label class="text-left"  for="#">Text Message</label>
                                            <div id="cct_embed_counts">
                                                <textarea maxlength="160" class="form-control create-campaign-textarea"  name="message_text"  rows="4">@if(isset($orderdispatch_campaign->message_text)){{$orderdispatch_campaign->message_text}}@endif</textarea>
                                                <span id="rchars">160</span> Character(s) Remaining
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            Status
                                        </div>
                                        <label class="switch" style="">
                                            {{--                                    @dd($shop_data->user)--}}
                                            <input @if(isset($orderdispatch_campaign->status) && $orderdispatch_campaign->status == "active")checked @endif name="status" type="checkbox" value="active" class="custom-control-input  status-switch">
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                    <div class="card-body col-md-4 col-lg-4 ">
                                        <div class="card" >
                                            <div class="card-body">
                                                <h6 class="card-title" style="font-size: 15px">Variables</h6>
                                                <p class="card-text custom-variable-font-size" >You can use variables to output in your 'Sender Name' and 'Text Message' fields. The available variables are:</p>
                                            </div>
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item custom-variable-font-size">{CustomerName}</li>
                                                <li class="list-group-item custom-variable-font-size">{OrderName}</li>
                                                <li class="list-group-item custom-variable-font-size">{FulfillmentStatus}</li>
                                                <li class="list-group-item custom-variable-font-size">{FinancialStatus}</li>
                                                <li class="list-group-item custom-variable-font-size">{OrderStatusUrl}</li>
                                                <li class="list-group-item custom-variable-font-size">{OrderTotalPrice}</li>
                                                <li class="list-group-item custom-variable-font-size">{Currency}</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        {{--                                        <div class="card col-md-12">--}}
                        {{--                                            <form action="{{route('shop-status-detail-save')}}" method="post">--}}
                        {{--                                                @csrf--}}
                        {{--                                                <div class="card-header" style="background: white;padding-bottom: 0;">--}}
                        {{--                                                    <div class="row ">--}}
                        {{--                                                        <div class="col-md-12 px-3 ">--}}
                        {{--                                                            <div class="d-flex justify-content-end ">--}}
                        {{--                                                                <div class="form-group">--}}
                        {{--                                                                    <input type="submit" class="btn btn-lg btn-primary" value="Save">--}}
                        {{--                                                                </div>--}}
                        {{--                                                            </div>--}}
                        {{--                                                        </div>--}}
                        {{--                                                    </div>--}}
                        {{--                                                </div>--}}
                        {{--                                                <div class="card-body">--}}
                        {{--                                                    <input hidden type="number" name="user_id" value="{{$shop_data->user_id}}">--}}
                        {{--                                                    <div class="row">--}}
                        {{--                                                        <div class="col-md-4">--}}
                        {{--                                                            <div class="form-group">--}}
                        {{--                                                                <label for="#">Firstname</label>--}}
                        {{--                                                                <input placeholder="Enter your firstname" value="{{ $shop_data->firstname }}" name="firstname" type="text" class="form-control">--}}
                        {{--                                                            </div>--}}
                        {{--                                                            <div class="form-group">--}}
                        {{--                                                                <label for="#">Surname</label>--}}
                        {{--                                                                <input placeholder="Enter your surname" value="{{ $shop_data->surname }}" name="surname" type="text" class="form-control">--}}
                        {{--                                                            </div>--}}
                        {{--                                                            <div class="form-group">--}}
                        {{--                                                                <label for="#">Email</label>--}}
                        {{--                                                                <input placeholder="Enter email" value="{{ $shop_data-> email}}" name="email" type="email" class="form-control">--}}
                        {{--                                                            </div>--}}
                        {{--                                                            <div class="form-group">--}}
                        {{--                                                                <label for="#">Mobile#</label>--}}
                        {{--                                                                <input placeholder="Enter mobile number" value="{{ $shop_data-> mobile_number}}" name="mobile_number" type="number" class="form-control">--}}
                        {{--                                                                <small class="text-muted">Mobile format must be 447</small>--}}
                        {{--                                                            </div>--}}
                        {{--                                                        </div>--}}
                        {{--                                                        <div class="col-md-4">--}}
                        {{--                                                            <div class="form-group">--}}
                        {{--                                                                <label for="#">Sender Name</label>--}}
                        {{--                                                                <input placeholder="Enter your sendername" value="{{ $shop_data-> sender_name}}" name="sender_name" type="text" class="form-control">--}}
                        {{--                                                                <small class="text-muted">where the SMS is being sent from</small>--}}
                        {{--                                                            </div>--}}
                        {{--                                                            <div class="form-group">--}}
                        {{--                                                                <label for="#">Company Name</label>--}}
                        {{--                                                                <input placeholder="Enter your company name" value="{{ $shop_data-> company_name}}" name="company_name" type="text" class="form-control">--}}
                        {{--                                                            </div>--}}
                        {{--                                                            <div class="mb-2">--}}
                        {{--                                                                Shop Status--}}
                        {{--                                                            </div>--}}
                        {{--                                                            <label class="switch" style="">--}}
                        {{--                                                                --}}{{--                                    @dd($shop_data->user)--}}
                        {{--                                                                <input @if($shop_data->user->user_status == "active")checked="" @endif name="user_status" type="checkbox" value="active" class="custom-control-input  status-switch">--}}
                        {{--                                                                <span class="slider round"></span>--}}
                        {{--                                                            </label>--}}

                        {{--                                                        </div>--}}
                        {{--                                                        <div class="col-md-4">--}}
                        {{--                                                            <div class="form-group">--}}
                        {{--                                                                <label for="#">Username</label>--}}
                        {{--                                                                <input placeholder="Enter your username" name="user_name" value="{{$shop_data->user_name}}" type="text" class="form-control">--}}
                        {{--                                                                <small class="text-muted">username format must be xxxxxxxx.textglobal</small>--}}
                        {{--                                                            </div>--}}
                        {{--                                                            <div class="form-group">--}}
                        {{--                                                                <label for="#">Password</label>--}}
                        {{--                                                                <input placeholder="Enter your password" name="password" type="text" value="{{$shop_data->password}}" class="form-control">--}}
                        {{--                                                            </div>--}}
                        {{--                                                        </div>--}}
                        {{--                                                    </div>--}}
                        {{--                                                </div>--}}
                        {{--                                            </form>--}}
                        {{--                                        </div>--}}
                    </div>
                </div>


            </div>
        </div>
    </div>
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function(){


    });

</script>
