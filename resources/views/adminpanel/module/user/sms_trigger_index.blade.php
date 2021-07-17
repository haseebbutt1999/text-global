@extends('adminpanel.user-layout.default')
@section('content')
    <div class="col-lg-12 col-md-12 p-4">
        <!-- start info box -->
        <div class="row ">
            <div class="col-md-12 col-lg-12 m-auto">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="welcome_sms-tab" data-toggle="tab" href="#welcome_sms" role="tab"
                           aria-controls="welcome_sms"
                           aria-selected="true">Welcome SMS Campaign</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="abandoned_cart-tab" data-toggle="tab" href="#abandoned_cart" role="tab"
                           aria-controls="abandoned_cart"
                           aria-selected="false">Abandoned Cart Campaign</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="order_confirmation_sms-tab" data-toggle="tab"
                           href="#order_confirmation_sms" role="tab" aria-controls="order_confirmation_sms"
                           aria-selected="false">Order Confirmation Campaign</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="order_refund-tab" data-toggle="tab" href="#order_refund" role="tab"
                           aria-controls="order_refund"
                           aria-selected="false">Order Refund Campaign</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="order_dispatch-tab" data-toggle="tab" href="#order_dispatch" role="tab"
                           aria-controls="order_dispatch"
                           aria-selected="false">Order Dispatch Campaign</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="welcome_sms" role="tabpanel"
                         aria-labelledby="welcome_sms-tab">
                        <div class="card col-md-12">
                            <div class="card-body col-md-12">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link" id="welcome_sms_campaign-tab" data-toggle="tab"
                                           href="#welcome_sms_campaign" role="tab" aria-controls="welcome_sms_campaign"
                                           aria-selected="false">Welcome SMS Campaign</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="welcome_log-tab" data-toggle="tab" href="#welcome_log"
                                           role="tab" aria-controls="welcome_log"
                                           aria-selected="false">Logs</a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="myTabContent">

                                    <div class="tab-pane fade show active" id="welcome_sms_campaign" role="tabpanel"
                                         aria-labelledby="welcome_sms_campaign-tab">
                                        <div class="col-md-12 col-lg-12 card">
                                            <form class="welcome-save-campaign"
                                                  action="{{Route('welcome-sms-campaign-save')}}" method="post">
                                                <input hidden value="" class="  welcome_sms_calculated_credit_per_sms"
                                                       name="calculated_credit_per_sms" type="number">
                                                @csrf
                                                <div
                                                    class="card-header bg-white d-flex justify-content-between align-items-center">
                                                    <div class="">Send an automated SMS text to every new customer.
                                                        Welcoming them to the brand, inviting the customer to follow the
                                                        social media pages or leave an online review.
                                                    </div>
                                                    <div class="">
                                                        <button type="submit" class=" btn btn-primary">Save</button>
                                                    </div>
                                                </div>
                                                <div class="row px-3">
                                                    <div class="card-body col-md-8 col-lg-8 ">
                                                        <div class="form-group">
                                                            <label class="text-left" for="#">Campaign Name</label>
                                                            <input
                                                                @if(isset($welcome_campaign->campaign_name)) value="{{$welcome_campaign->campaign_name}}"
                                                                @endif name="campaign_name" type="text"
                                                                class="form-control ">
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="text-left" for="#">Sender Name</label>
                                                            @php
                                                                $welcome_sender_name = \App\Welcomecampaign::where('user_id', \Illuminate\Support\Facades\Auth::user()->id)->select('sender_name')->distinct()->get();
                                                            @endphp

                                                            <div class="custom-select-div ">
                                                                <select required name="sender_name"
                                                                        class=" js-example-tags welcome-sendername-character-count">
                                                                    @foreach($welcome_sender_name as $welcome_sender)
                                                                        <option
                                                                            @if($welcome_sender->sender_name == $welcome_campaign->sender_name) selected @endif>{{$welcome_sender->sender_name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="welcome-sender-char-msg"><span
                                                                    style="color: gray;font-size: 14px">Min character 3 and Max character 11</span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div
                                                                class="d-flex justify-content-between align-items-center">
                                                                <label class="text-left" for="#">Text Message</label>
                                                                <div style="color: gray;">Characters used <span
                                                                        id="welcome-rchars"
                                                                        style="text-align: right">0</span> / <span
                                                                        id="welcome-credit"> {{$welcome_campaign->calculated_credit_per_sms}} </span>
                                                                    credits.<br> Emoji's are not supported
                                                                </div>
                                                            </div>
                                                            <div id="cct_embed_counts">
                                                                <textarea class="form-control welcome-campaign-textarea"
                                                                          id="welcome-campaign-textarea"
                                                                          maxlength="612" name="message_text"
                                                                          rows="4">@if(isset($welcome_campaign->message_text)){{$welcome_campaign->message_text}}@endif</textarea>
                                                                <div class="welcome-textarea-char-limit"><span
                                                                        style="color: gray;font-size: 14px">Max characters limit is '612'.</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="mb-2">
                                                            Status
                                                        </div>
                                                        <label class="switch" style="">
                                                            {{--                                    @dd($shop_data->user)--}}
                                                            <input
                                                                @if( isset($welcome_campaign->status) && $welcome_campaign->status == "active")checked=""
                                                                @endif name="status" type="checkbox" value="active"
                                                                class="custom-control-input  status-switch">
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>
                                                    <div class="card-body col-md-4 col-lg-4 ">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <h6 class="card-title" style="font-size: 15px">
                                                                    Placeholder</h6>
                                                                <p class="card-text custom-variable-font-size">You can
                                                                    use placeholders to output in your 'Text Message'
                                                                    fields. The available placeholders are:</p>
                                                            </div>
                                                            <ul class="list-group list-group-flush">
                                                                <li class="list-group-item custom-variable-font-size">
                                                                    <button type="button"
                                                                            class="wellcome-placeholder btn btn-primary">
                                                                        {CustomerName}
                                                                    </button>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade " id="welcome_log" role="tabpanel"
                                         aria-labelledby="welcome_log-tab">
                                        <div class="col-md-12 col-lg-12 card">
                                            <div class="card-body">
                                                <div id="product_append">
                                                    <div class="row px-3" style="overflow-x:auto;">
                                                        <table id="datatabled"
                                                               class="table table-borderless  table-hover  table-class ">
                                                            <thead class="border-0 ">

                                                            <tr class="th-tr table-tr text-white text-center">

                                                                <th class="font-weight-bold " style="">First Name</th>
                                                                <th class="font-weight-bold " style="">Last Name</th>
                                                                <th class="font-weight-bold " style="">Mobile#</th>
                                                                <th class="font-weight-bold " style="">SMS Text</th>
                                                                <th class="font-weight-bold " style="">Action</th>
                                                                <th class="font-weight-bold " style="">Sent_at</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            {{--                                        @dd($users_data)--}}
                                                            @foreach($user_welcome_logs_data as $key=>$user_welcome_log)

                                                                <tr class="td-text-center">
                                                                    <td>
                                                                        {{$user_welcome_log->firstname}}
                                                                    </td>
                                                                    <td>
                                                                        {{$user_welcome_log->lastname}}
                                                                    </td>
                                                                    <td>
                                                                        {{$user_welcome_log->mobileno}}
                                                                    </td>
                                                                    <td>
                                                                        {{$user_welcome_log->sms_text}}
                                                                    </td>
                                                                    <td>
                                                                        {{$user_welcome_log->action}}
                                                                    </td>
                                                                    <td>
                                                                        {{$user_welcome_log->created_at}}
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                        {!!  $user_welcome_logs_data->links() !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="abandoned_cart" role="tabpanel" aria-labelledby="abandoned_cart-tab">
                        <div class="col-md-12 col-lg-12 card">
                            <div class="card-body col-md-12">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link" id="abandonedcart_campaign-tab" data-toggle="tab"
                                           href="#abandonedcart_campaign" role="tab"
                                           aria-controls="abandonedcart_campaign"
                                           aria-selected="false">Abandoned Cart SMS Campaign</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="abandonedcart_log-tab" data-toggle="tab"
                                           href="#abandonedcart_log" role="tab" aria-controls="abandonedcart_log"
                                           aria-selected="false">Logs</a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="myTabContent">

                                    <div class="tab-pane fade show active" id="abandonedcart_campaign" role="tabpanel"
                                         aria-labelledby="abandonedcart_campaign-tab">
                                        <div class="col-md-12 col-lg-12 card">
                                            <form class="abandoned-save-campaign"
                                                  action="{{Route('abandoned-cart-campaign-save')}}" method="post">
                                                <input hidden value="" class="  abandoned_sms_calculated_credit_per_sms"
                                                       name="calculated_credit_per_sms" type="number">
                                                @csrf
                                                <div
                                                    class="card-header bg-white  d-flex justify-content-between align-items-center">
                                                    <div class="">Send automated abandoned cart notification SMS texts.
                                                        Set a delayed time as to when the shopper will receive the SMS
                                                        message. To boost your checkout conversion rate, why not include
                                                        a free delivery code or small discount?
                                                    </div>
                                                    <div class="">
                                                        <button type="submit" class=" btn btn-primary ">Save</button>
                                                    </div>
                                                </div>
                                                <div class="row px-3">
                                                    <div class="card-body col-md-8 col-lg-8 ">
                                                        <div class="form-group">
                                                            <label class="text-left" for="#">Campaign Name</label>
                                                            <input
                                                                @if(isset($abandoned_cart_campaign->campaign_name)) value="{{$abandoned_cart_campaign->campaign_name}}"
                                                                @endif name="campaign_name" type="text"
                                                                class="form-control ">
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="text-left" for="#">Sender Name</label>
                                                            @php
                                                                $abandonedcart_sender_name = \App\Abandonedcartcampaign::where('user_id', \Illuminate\Support\Facades\Auth::user()->id)->select('sender_name')->distinct()->get();
                                                            @endphp

                                                            <div class="custom-select-div ">
                                                                <select required name="sender_name"
                                                                        class=" js-example-tags abandonedcart-sendername-character-count">
                                                                    @foreach($abandonedcart_sender_name as $abandonedcart_sender)
                                                                        <option
                                                                            @if($abandonedcart_sender->sender_name == $abandoned_cart_campaign->sender_name) selected @endif>{{$abandonedcart_sender->sender_name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="abandonedcart-sender-char-msg"><span
                                                                    style="color: gray;font-size: 14px">Min character 3 and Max character 11</span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group ">
                                                            <div class="form-group">
                                                                <div
                                                                    class="d-flex justify-content-between align-items-center">
                                                                    <label class="text-left" for="#">Text
                                                                        Message</label>
                                                                    <div style="color: gray;font-size: 13px;">Characters
                                                                        used <span id="abandoned-rchars"
                                                                                   style="text-align: right">0</span> /
                                                                        <span
                                                                            id="abandoned-credit"> {{$abandoned_cart_campaign->calculated_credit_per_sms}} </span>
                                                                        credits.<br> Emoji's are not supported
                                                                    </div>
                                                                </div>
                                                                <div id="cct_embed_counts">
                                                                    <textarea
                                                                        class="form-control abandoned-campaign-textarea"
                                                                        id="abandoned-campaign-textarea"
                                                                        maxlength="612" name="message_text"
                                                                        rows="4">@if(isset($abandoned_cart_campaign->message_text)){{$abandoned_cart_campaign->message_text}}@endif</textarea>
                                                                    <div class="abandoned-textarea-char-limit"><span
                                                                            style="color: gray;font-size: 14px">Max characters limit is '612'.</span>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                            <label class="text-left" for="#">Delay Time</label>
                                                            <div class="pl-3 d-flex justify-content-around">
                                                                <div class="form-check">
                                                                    <label class="form-check-label">
                                                                        <input type="radio"
                                                                               @if($abandoned_cart_campaign->delay_time == 1) checked
                                                                               @endif value="1" class="form-check-input"
                                                                               name="delay_time">1 Hour
                                                                    </label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <label class="form-check-label">
                                                                        <input type="radio"
                                                                               @if($abandoned_cart_campaign->delay_time == 2) checked
                                                                               @endif value="2" class="form-check-input"
                                                                               name="delay_time">2 Hour
                                                                    </label>
                                                                </div>
                                                                <div class="form-check ">
                                                                    <label class="form-check-label">
                                                                        <input type="radio" value="3"
                                                                               @if($abandoned_cart_campaign->delay_time == 3) checked
                                                                               @endif class="form-check-input"
                                                                               name="delay_time">3 Hour
                                                                    </label>
                                                                </div>
                                                                <div class="form-check ">
                                                                    <label class="form-check-label">
                                                                        <input type="radio" value="12"
                                                                               @if($abandoned_cart_campaign->delay_time == 12) checked
                                                                               @endif class="form-check-input"
                                                                               name="delay_time">12 Hour
                                                                    </label>
                                                                </div>
                                                                <div class="form-check ">
                                                                    <label class="form-check-label">
                                                                        <input type="radio" value="24"
                                                                               @if($abandoned_cart_campaign->delay_time == 24) checked
                                                                               @endif class="form-check-input"
                                                                               name="delay_time">24 Hour
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="mb-2">
                                                                Status
                                                            </div>
                                                            <label class="switch" style="">
                                                                {{--                                    @dd($shop_data->user)--}}
                                                                <input
                                                                    @if(isset($abandoned_cart_campaign->status) && $abandoned_cart_campaign->status == "active")checked
                                                                    @endif name="status" type="checkbox" value="active"
                                                                    class="custom-control-input  status-switch">
                                                                <span class="slider round"></span>
                                                            </label>
                                                        </div>

                                                    </div>
                                                    <div class="card-body col-md-4 col-lg-4 ">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <h6 class="card-title" style="font-size: 15px">
                                                                    Placeholder</h6>
                                                                <p class="card-text custom-variable-font-size">You can
                                                                    use placeholders to output in your 'Text Message'
                                                                    fields. The available placeholders are:</p>
                                                            </div>
                                                            <ul class="list-group list-group-flush">
                                                                <li class="list-group-item custom-variable-font-size">
                                                                    <button type="button"
                                                                            class="abandoned-placeholder btn btn-primary">
                                                                        {CustomerName}
                                                                    </button>
                                                                </li>
                                                                <li class="list-group-item custom-variable-font-size">
                                                                    <button type="button"
                                                                            class="abandoned-placeholder btn btn-primary">
                                                                        {ProductId}
                                                                    </button>
                                                                </li>
                                                                <li class="list-group-item custom-variable-font-size">
                                                                    <button type="button"
                                                                            class="abandoned-placeholder btn btn-primary">
                                                                        {TotalPrice}
                                                                    </button>

                                                                </li>
                                                                <li class="list-group-item custom-variable-font-size">
                                                                    <button type="button"
                                                                            class="abandoned-placeholder btn btn-primary">
                                                                        {AbandonedCheckoutUrl}
                                                                    </button>

                                                                </li>
                                                                <li class="list-group-item custom-variable-font-size">
                                                                    <button type="button"
                                                                            class="abandoned-placeholder btn btn-primary">
                                                                        {Currency}
                                                                    </button>

                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade " id="abandonedcart_log" role="tabpanel"
                                         aria-labelledby="abandonedcart_log-tab">
                                        <div class="col-md-12 col-lg-12 card">
                                            <div class="card-body">
                                                <div id="product_append">
                                                    <div class="row px-3" style="overflow-x:auto;">
                                                        <table id="datatabled"
                                                               class="table table-borderless  table-hover  table-class ">
                                                            <thead class="border-0 ">

                                                            <tr class="th-tr table-tr text-white text-center">
                                                                <th class="font-weight-bold " style="">First Name</th>
                                                                <th class="font-weight-bold " style="">Last Name</th>
                                                                <th class="font-weight-bold " style="">Mobile#</th>
                                                                <th class="font-weight-bold " style="">SMS Text</th>
                                                                <th class="font-weight-bold " style="">Action</th>
                                                                <th class="font-weight-bold " style="">Sent_at</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @foreach($user_abandonedcart_logs_data as $key=>$user_abandonedcart_log)
                                                                <tr class="td-text-center">
                                                                    <td>
                                                                        {{$user_abandonedcart_log->firstname}}
                                                                    </td>
                                                                    <td>
                                                                        {{$user_abandonedcart_log->lastname}}
                                                                    </td>
                                                                    <td>
                                                                        {{$user_abandonedcart_log->mobileno}}
                                                                    </td>
                                                                    <td>
                                                                        {{$user_abandonedcart_log->sms_text}}
                                                                    </td>

                                                                    <td>
                                                                        {{$user_abandonedcart_log->action}}
                                                                    </td>
                                                                    <td>
                                                                        {{$user_abandonedcart_log->created_at}}
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                        {!!  $user_abandonedcart_logs_data->links() !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="order_confirmation_sms" role="tabpanel"
                         aria-labelledby="order_confirmation_sms-tab">
                        <div class="col-md-12 col-lg-12 card">
                            <div class="card-body col-md-12">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link" id="order_confirm_campaign-tab" data-toggle="tab"
                                           href="#order_confirm_campaign" role="tab"
                                           aria-controls="order_confirm_campaign"
                                           aria-selected="false">Order Confirm SMS Campaign</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="orderconfirm_log-tab" data-toggle="tab"
                                           href="#orderconfirm_log" role="tab" aria-controls="orderconfirm_log"
                                           aria-selected="false">Logs</a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="myTabContent">

                                    <div class="tab-pane fade show active" id="order_confirm_campaign" role="tabpanel"
                                         aria-labelledby="order_confirm_campaign-tab">
                                        <div class="col-md-12 col-lg-12 card">
                                            <form class="orderconfirm-save-campaign"
                                                  action="{{Route('orderconfirm-campaign-save')}}" method="post">
                                                @csrf
                                                <input hidden value=""
                                                       class="  orderconfirm_sms_calculated_credit_per_sms"
                                                       name="calculated_credit_per_sms" type="number">
                                                <div
                                                    class="card-header bg-white  d-flex justify-content-between align-items-center">
                                                    <div class=""> Improve service to customer's with an automated order
                                                        confirmation SMS text message. Give customer's peace of mind
                                                        with a simple SMS.
                                                    </div>

                                                    <div class="">
                                                        <button type="submit" class=" btn btn-primary ">Save</button>
                                                    </div>
                                                </div>
                                                <div class="row px-3">
                                                    <div class="card-body col-md-8 col-lg-8 ">
                                                        <div class="form-group">
                                                            <label class="text-left" for="#">Campaign Name</label>
                                                            <input
                                                                @if(isset($orderconfirm_campaign->campaign_name)) value="{{$orderconfirm_campaign->campaign_name}}"
                                                                @endif name="campaign_name" type="text"
                                                                class="form-control ">
                                                        </div>
                                                        {{--                                        --}}
                                                        <div class="form-group">
                                                            <label class="text-left" for="#">Sender Name</label>
                                                            @php
                                                                $orderconfirm_sender_name = \App\Orderconfirm::where('user_id', \Illuminate\Support\Facades\Auth::user()->id)->select('sender_name')->distinct()->get();
                                                            @endphp

                                                            <div class="custom-select-div ">
                                                                <select required name="sender_name"
                                                                        class=" js-example-tags orderconfirm-sendername-character-count">
                                                                    @foreach($orderconfirm_sender_name as $orderconfirm_sender)
                                                                        <option
                                                                            @if($orderconfirm_sender->sender_name == $orderconfirm_campaign->sender_name) selected @endif>{{$orderconfirm_sender->sender_name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="orderconfirm-sender-char-msg"><span
                                                                    style="color: gray;font-size: 14px">Min character 3 and Max character 11</span>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <div
                                                                class="d-flex justify-content-between align-items-center">
                                                                <label class="text-left" for="#">Text Message</label>
                                                                <div style="color: gray;font-size: 13px;">Characters
                                                                    used <span id="orderconfirm-rchars"
                                                                               style="text-align: right">0</span> /
                                                                    <span
                                                                        id="orderconfirm-credit"> {{$orderconfirm_campaign->calculated_credit_per_sms}} </span>
                                                                    credits.<br> Emoji's are not supported
                                                                </div>
                                                            </div>
                                                            <div id="cct_embed_counts">
                                                                <textarea maxlength="612"
                                                                          class="form-control orderconfirm-campaign-textarea"
                                                                          id="orderconfirm-campaign-textarea"
                                                                          name="message_text"
                                                                          rows="4">@if(isset($orderconfirm_campaign->message_text)){{$orderconfirm_campaign->message_text}}@endif</textarea>
                                                                <div class="orderconfirm-textarea-char-limit"><span
                                                                        style="color: gray;font-size: 14px">Max characters limit is '612'.</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="mb-2">
                                                            Status
                                                        </div>
                                                        <label class="switch" style="">
                                                            {{--                                    @dd($shop_data->user)--}}
                                                            <input
                                                                @if(isset($orderconfirm_campaign->status) && $orderconfirm_campaign->status == "active")checked
                                                                @endif name="status" type="checkbox" value="active"
                                                                class="custom-control-input  status-switch">
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>
                                                    <div class="card-body col-md-4 col-lg-4 ">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <h6 class="card-title" style="font-size: 15px">
                                                                    Placeholder</h6>
                                                                <p class="card-text custom-variable-font-size">You can
                                                                    use placeholders to output in your 'Text Message'
                                                                    fields. The available placeholders are:</p>
                                                            </div>
                                                            <ul class="list-group list-group-flush">
                                                                <li class="list-group-item custom-variable-font-size">
                                                                    <button type="button"
                                                                            class="orderconfirm-placeholder btn btn-primary">
                                                                        {CustomerName}
                                                                    </button>
                                                                </li>
                                                                <li class="list-group-item custom-variable-font-size">
                                                                    <button type="button"
                                                                            class="orderconfirm-placeholder btn btn-primary">
                                                                        {OrderName}
                                                                    </button>

                                                                </li>
                                                                <li class="list-group-item custom-variable-font-size">
                                                                    <button type="button"
                                                                            class="orderconfirm-placeholder btn btn-primary">
                                                                        {FinancialStatus}
                                                                    </button>

                                                                </li>
                                                                <li class="list-group-item custom-variable-font-size">
                                                                    <button type="button"
                                                                            class="orderconfirm-placeholder btn btn-primary">
                                                                        {OrderStatusUrl}
                                                                    </button>

                                                                </li>
                                                                <li class="list-group-item custom-variable-font-size">
                                                                    <button type="button"
                                                                            class="orderconfirm-placeholder btn btn-primary">
                                                                        {OrderTotalPrice}
                                                                    </button>

                                                                </li>
                                                                <li class="list-group-item custom-variable-font-size">
                                                                    <button type="button"
                                                                            class="orderconfirm-placeholder btn btn-primary">
                                                                        {Currency}
                                                                    </button>

                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade " id="orderconfirm_log" role="tabpanel"
                                         aria-labelledby="orderconfirm_log-tab">
                                        <div class="col-md-12 col-lg-12 card">
                                            <div class="card-body">
                                                <div id="product_append">
                                                    <div class="row px-3" style="overflow-x:auto;">
                                                        <table id="datatabled"
                                                               class="table table-borderless  table-hover  table-class ">
                                                            <thead class="border-0 ">

                                                            <tr class="th-tr table-tr text-white text-center">

                                                                <th class="font-weight-bold " style="">First Name</th>
                                                                <th class="font-weight-bold " style="">Last Name</th>
                                                                <th class="font-weight-bold " style="">Mobile#</th>
                                                                <th class="font-weight-bold " style="">Order#</th>
                                                                <th class="font-weight-bold " style="">SMS Text</th>
                                                                <th class="font-weight-bold " style="">Action</th>
                                                                <th class="font-weight-bold " style="">Sent_at</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            {{--                                        @dd($users_data)--}}
                                                            @foreach($user_orderconfirm_logs_data as $key=>$user_orderconfirm_log)

                                                                <tr class="td-text-center">
                                                                    <td>
                                                                        {{$user_orderconfirm_log->firstname}}
                                                                    </td>
                                                                    <td>
                                                                        {{$user_orderconfirm_log->lastname}}
                                                                    </td>
                                                                    <td>
                                                                        {{$user_orderconfirm_log->mobileno}}
                                                                    </td>
                                                                    <td>
                                                                        {{$user_orderconfirm_log->order_name}}
                                                                    </td>
                                                                    <td>
                                                                        {{$user_orderconfirm_log->sms_text}}
                                                                    </td>

                                                                    <td>
                                                                        {{$user_orderconfirm_log->action}}
                                                                    </td>
                                                                    <td>
                                                                        {{$user_orderconfirm_log->created_at}}
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                        {!!  $user_orderconfirm_logs_data->links() !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="order_refund" role="tabpanel" aria-labelledby="order_refund-tab">
                        <div class="col-md-12 col-lg-12 card">
                            <div class="card-body col-md-12">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link" id="order_refund_campaign-tab" data-toggle="tab"
                                           href="#order_refund_campaign" role="tab"
                                           aria-controls="order_refund_campaign"
                                           aria-selected="false">Order Refund SMS Campaign</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="orderrefund_log-tab" data-toggle="tab"
                                           href="#orderrefund_log" role="tab" aria-controls="orderrefund_log"
                                           aria-selected="false">Logs</a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="myTabContent">

                                    <div class="tab-pane fade show active" id="order_refund_campaign" role="tabpanel"
                                         aria-labelledby="order_refund_campaign-tab">
                                        <div class="col-md-12 col-lg-12 card">
                                            <form class="orderrefund-save-campaign"
                                                  action="{{Route('orderrefund-campaign-save')}}" method="post">
                                                @csrf
                                                <input hidden value=""
                                                       class="  orderrefund_sms_calculated_credit_per_sms"
                                                       name="calculated_credit_per_sms" type="number">

                                                <div
                                                    class="card-header bg-white  d-flex justify-content-between align-items-center">
                                                    <div class="">Let customer's know instantly via SMS text when their
                                                        order has been refunded. Reduce inbound service enquires and
                                                        improve overall customer service.
                                                    </div>
                                                    <div class="">
                                                        <button type="submit" class=" btn btn-primary ">Save</button>
                                                    </div>
                                                </div>
                                                <div class="row px-3">
                                                    <div class="card-body col-md-8 col-lg-8 ">
                                                        <div class="form-group">
                                                            <label class="text-left" for="#">Campaign Name</label>
                                                            <input
                                                                @if(isset($orderrefund_campaign->campaign_name)) value="{{$orderrefund_campaign->campaign_name}}"
                                                                @endif name="campaign_name" type="text"
                                                                class="form-control ">
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="text-left" for="#">Sender Name</label>
                                                            @php
                                                                $orderrefund_sender_name = \App\Orderrefund::where('user_id', \Illuminate\Support\Facades\Auth::user()->id)->select('sender_name')->distinct()->get();
                                                            @endphp

                                                            <div class="custom-select-div ">
                                                                <select required name="sender_name"
                                                                        class=" js-example-tags orderrefund-sendername-character-count">
                                                                    @foreach($orderrefund_sender_name as $orderrefund_sender)
                                                                        <option
                                                                            @if($orderrefund_sender->sender_name == $orderrefund_campaign->sender_name) selected @endif>{{$orderrefund_campaign->sender_name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="orderrefund-sender-char-msg"><span
                                                                    style="color: gray;font-size: 14px">Min character 3 and Max character 11</span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div
                                                                class="d-flex justify-content-between align-items-center">
                                                                <label class="text-left" for="#">Text Message</label>
                                                                <div style="color: gray;font-size: 13px;">Characters
                                                                    used <span id="orderrefund-rchars"
                                                                               style="text-align: right">0</span> /
                                                                    <span
                                                                        id="orderrefund-credit"> {{$orderrefund_campaign->calculated_credit_per_sms}} </span>
                                                                    credits.<br> Emoji's are not supported
                                                                </div>
                                                            </div>
                                                            <div id="cct_embed_counts">
                                                                <textarea
                                                                    class="form-control orderrefund-campaign-textarea"
                                                                    id="orderrefund-campaign-textarea"
                                                                    maxlength="612" name="message_text"
                                                                    rows="4">@if(isset($orderrefund_campaign->message_text)){{$orderrefund_campaign->message_text}}@endif</textarea>
                                                                <div class="orderrefund-textarea-char-limit"><span
                                                                        style="color: gray;font-size: 14px">Max characters limit is '612'.</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="mb-2">
                                                            Status
                                                        </div>
                                                        <label class="switch" style="">
                                                            {{--                                    @dd($shop_data->user)--}}
                                                            <input
                                                                @if( isset($orderrefund_campaign->status) && $orderrefund_campaign->status == "active")checked
                                                                @endif name="status" type="checkbox" value="active"
                                                                class="custom-control-input  status-switch">
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>
                                                    <div class="card-body col-md-4 col-lg-4 ">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <h6 class="card-title" style="font-size: 15px">
                                                                    Placeholder</h6>
                                                                <p class="card-text custom-variable-font-size">You can
                                                                    use placeholders to output in your 'Text Message'
                                                                    fields. The available placeholders are:</p>
                                                            </div>
                                                            <ul class="list-group list-group-flush">
                                                                <li class="list-group-item custom-variable-font-size">
                                                                    <button type="button"
                                                                            class="orderrefund-placeholder btn btn-primary">
                                                                        {CustomerName}
                                                                    </button>

                                                                </li>
                                                                <li class="list-group-item custom-variable-font-size">
                                                                    <button type="button"
                                                                            class="orderrefund-placeholder btn btn-primary">
                                                                        {OrderName}
                                                                    </button>

                                                                </li>
                                                                <li class="list-group-item custom-variable-font-size">
                                                                    <button type="button"
                                                                            class="orderrefund-placeholder btn btn-primary">
                                                                        {FinancialStatus}
                                                                    </button>

                                                                </li>
                                                                <li class="list-group-item custom-variable-font-size">
                                                                    <button type="button"
                                                                            class="orderrefund-placeholder btn btn-primary">
                                                                        {OrderStatusUrl}
                                                                    </button>

                                                                </li>
                                                                <li class="list-group-item custom-variable-font-size">
                                                                    <button type="button"
                                                                            class="orderrefund-placeholder btn btn-primary">
                                                                        {RefundedPaymentCurrency}
                                                                    </button>

                                                                </li>
                                                                <li class="list-group-item custom-variable-font-size">
                                                                    <button type="button"
                                                                            class="orderrefund-placeholder btn btn-primary">
                                                                        {RefundedAmount}
                                                                    </button>

                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade " id="orderrefund_log" role="tabpanel"
                                         aria-labelledby="orderrefund_log-tab">
                                        <div class="col-md-12 col-lg-12 card">
                                            <div class="card-body">
                                                <div id="product_append">
                                                    <div class="row px-3" style="overflow-x:auto;">
                                                        <table id="datatabled"
                                                               class="table table-borderless  table-hover  table-class ">
                                                            <thead class="border-0 ">

                                                            <tr class="th-tr table-tr text-white text-center">

                                                                <th class="font-weight-bold " style="">First Name</th>
                                                                <th class="font-weight-bold " style="">Last Name</th>
                                                                <th class="font-weight-bold " style="">Mobile#</th>
                                                                <th class="font-weight-bold " style="">Order#</th>
                                                                <th class="font-weight-bold " style="">SMS Text</th>
                                                                <th class="font-weight-bold " style="">Action</th>
                                                                <th class="font-weight-bold " style="">Sent_at</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            {{--                                        @dd($users_data)--}}
                                                            @foreach($user_orderrefund_logs_data as $key=>$user_orderrefund_log)

                                                                <tr class="td-text-center">
                                                                    <td>
                                                                        {{$user_orderrefund_log->firstname}}
                                                                    </td>
                                                                    <td>
                                                                        {{$user_orderrefund_log->lastname}}
                                                                    </td>
                                                                    <td>
                                                                        {{$user_orderrefund_log->mobileno}}
                                                                    </td>
                                                                    <td>
                                                                        {{$user_orderrefund_log->order_name}}
                                                                    </td>
                                                                    <td>
                                                                        {{$user_orderrefund_log->sms_text}}
                                                                    </td>
                                                                    <td>
                                                                        {{$user_orderrefund_log->action}}
                                                                    </td>
                                                                    <td>
                                                                        {{$user_orderrefund_log->created_at}}
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                        {!!  $user_orderrefund_logs_data->links() !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="order_dispatch" role="tabpanel" aria-labelledby="order_dispatch-tab">
                        <div class="col-md-12 col-lg-12 card">
                            <div class="card-body col-md-12">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link" id="order_dispatch_campaign-tab" data-toggle="tab"
                                           href="#order_dispatch_campaign" role="tab"
                                           aria-controls="order_dispatch_campaign"
                                           aria-selected="false">Order Dispatch SMS Campaign</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="orderdispatch_log-tab" data-toggle="tab"
                                           href="#orderdispatch_log" role="tab" aria-controls="orderdispatch_log"
                                           aria-selected="false">Logs</a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="myTabContent">

                                    <div class="tab-pane fade show active" id="order_dispatch_campaign" role="tabpanel"
                                         aria-labelledby="order_dispatch_campaign-tab">
                                        <div class="col-md-12 col-lg-12 card">
                                            <form class="orderdispatch-save-campaign"
                                                  action="{{Route('orderdispatch-campaign-save')}}" method="post">
                                                @csrf
                                                <input hidden value=""
                                                       class="  orderdispatch_sms_calculated_credit_per_sms"
                                                       name="calculated_credit_per_sms" type="number">

                                                <div
                                                    class="card-header bg-white  d-flex justify-content-between align-items-center">
                                                    <div class="">Send automated order dispatch notifications via SMS
                                                        text. Giving customer's peace of mind and improving service.
                                                    </div>
                                                    <div class="">
                                                        <button type="submit" class=" btn btn-primary ">Save</button>
                                                    </div>
                                                </div>
                                                <div class="row px-3">
                                                    <div class="card-body col-md-8 col-lg-8 ">
                                                        <div class="form-group">
                                                            <label class="text-left" for="#">Campaign Name</label>
                                                            <input
                                                                @if(isset($orderdispatch_campaign->campaign_name)) value="{{$orderdispatch_campaign->campaign_name}}"
                                                                @endif name="campaign_name" type="text"
                                                                class="form-control ">
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="text-left" for="#">Sender Name</label>
                                                            @php
                                                                $orderdispatch_sender_name = \App\Orderdispatch::where('user_id', \Illuminate\Support\Facades\Auth::user()->id)->select('sender_name')->distinct()->get();
                                                            @endphp

                                                            <div class="custom-select-div ">
                                                                <select required name="sender_name"
                                                                        class=" js-example-tags orderdispatch-sendername-character-count">
                                                                    @foreach($orderdispatch_sender_name as $orderdispatch_sender)
                                                                        <option
                                                                            @if($orderdispatch_sender->sender_name == $orderdispatch_campaign->sender_name) selected @endif>{{$orderdispatch_sender->sender_name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="orderdispatch-sender-char-msg"><span
                                                                    style="color: gray;font-size: 14px">Min character 3 and Max character 11</span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div
                                                                class="d-flex justify-content-between align-items-center">
                                                                <label class="text-left" for="#">Text Message</label>
                                                                <div style="color: gray;font-size: 13px;">Characters used
                                                                    <span id="orderdispatch-rchars"
                                                                               style="text-align: right">0</span> /
                                                                    <span
                                                                        id="orderdispatch-credit"> {{$orderdispatch_campaign->calculated_credit_per_sms}} </span>
                                                                    credits.<br> Emoji's are not supported
                                                                </div>
                                                            </div>
                                                            <div id="cct_embed_counts">
                                                                <textarea maxlength="612"
                                                                          class="form-control orderdispatch-campaign-textarea"
                                                                          id="orderdispatch-campaign-textarea"
                                                                          name="message_text"
                                                                          rows="4">@if(isset($orderdispatch_campaign->message_text)){{$orderdispatch_campaign->message_text}}@endif</textarea>
                                                                <div class="orderdispatch-textarea-char-limit"><span
                                                                        style="color: gray;font-size: 14px">Max characters limit is '612'.</span>
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <div class="mb-2">
                                                            Status
                                                        </div>
                                                        <label class="switch" style="">
                                                            {{--                                    @dd($shop_data->user)--}}
                                                            <input
                                                                @if(isset($orderdispatch_campaign->status) && $orderdispatch_campaign->status == "active")checked
                                                                @endif name="status" type="checkbox" value="active"
                                                                class="custom-control-input  status-switch">
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>
                                                    <div class="card-body col-md-4 col-lg-4 ">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <h6 class="card-title" style="font-size: 15px">
                                                                    Placeholder</h6>
                                                                <p class="card-text custom-variable-font-size">You can
                                                                    use placeholders to output in your 'Text Message'
                                                                    fields. The available placeholders are:</p>
                                                            </div>
                                                            <ul class="list-group list-group-flush">
                                                                <li class="list-group-item custom-variable-font-size">
                                                                    <button type="button"
                                                                            class="dispatch-placeholder btn btn-primary">
                                                                        {CustomerName}
                                                                    </button>

                                                                </li>
                                                                <li class="list-group-item custom-variable-font-size">
                                                                    <button type="button"
                                                                            class="dispatch-placeholder btn btn-primary">
                                                                        {OrderName}
                                                                    </button>

                                                                </li>
                                                                <li class="list-group-item custom-variable-font-size">
                                                                    <button type="button"
                                                                            class="dispatch-placeholder btn btn-primary">
                                                                        {FulfillmentStatus}
                                                                    </button>

                                                                </li>
                                                                <li class="list-group-item custom-variable-font-size">
                                                                    <button type="button"
                                                                            class="dispatch-placeholder btn btn-primary">
                                                                        {FinancialStatus}
                                                                    </button>

                                                                </li>
                                                                <li class="list-group-item custom-variable-font-size">
                                                                    <button type="button"
                                                                            class="dispatch-placeholder btn btn-primary">
                                                                        {OrderStatusUrl}
                                                                    </button>

                                                                </li>
                                                                <li class="list-group-item custom-variable-font-size">
                                                                    <button type="button"
                                                                            class="dispatch-placeholder btn btn-primary">
                                                                        {OrderTotalPrice}
                                                                    </button>

                                                                </li>
                                                                <li class="list-group-item custom-variable-font-size">
                                                                    <button type="button"
                                                                            class="dispatch-placeholder btn btn-primary">
                                                                        {Currency}
                                                                    </button>

                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade " id="orderdispatch_log" role="tabpanel"
                                         aria-labelledby="orderdispatch_log-tab">
                                        <div class="col-md-12 col-lg-12 card">
                                            <div class="card-body">
                                                <div id="product_append">
                                                    <div class="row px-3" style="overflow-x:auto;">
                                                        <table id="datatabled"
                                                               class="table table-borderless  table-hover  table-class ">
                                                            <thead class="border-0 ">

                                                            <tr class="th-tr table-tr text-white text-center">

                                                                <th class="font-weight-bold " style="">First Name</th>
                                                                <th class="font-weight-bold " style="">Last Name</th>
                                                                <th class="font-weight-bold " style="">Mobile#</th>
                                                                <th class="font-weight-bold " style="">Order#</th>
                                                                <th class="font-weight-bold " style="">SMS Text</th>
                                                                <th class="font-weight-bold " style="">Action</th>
                                                                <th class="font-weight-bold " style="">Sent_at</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            {{--                                        @dd($users_data)--}}
                                                            @foreach($user_orderdispatch_logs_data as $key=>$user_orderdispatch_log)
                                                                <tr class="td-text-center">
                                                                    <td>
                                                                        {{$user_orderdispatch_log->firstname}}
                                                                    </td>
                                                                    <td>
                                                                        {{$user_orderdispatch_log->lastname}}
                                                                    </td>
                                                                    <td>
                                                                        {{$user_orderdispatch_log->mobileno}}
                                                                    </td>
                                                                    <td>
                                                                        {{$user_orderdispatch_log->order_name}}
                                                                    </td>
                                                                    <td>
                                                                        {{$user_orderdispatch_log->sms_text}}
                                                                    </td>
                                                                    <td>
                                                                        {{$user_orderdispatch_log->action}}
                                                                    </td>
                                                                    <td>
                                                                        {{$user_orderdispatch_log->created_at}}
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                        {!!  $user_orderdispatch_logs_data->links() !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input class="current_user_credits" value="{{\Illuminate\Support\Facades\Auth::user()->credit}}" hidden>
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        // var maxLength = 160;
        // var textlen, credit;


        $(".js-example-tags").select2({
            tags: true
        });
        //welcome campaign textarea checks
        $('.welcome-campaign-textarea').keyup(function () {

            var nmbr_char = $(this).val().length;
            var textlen = nmbr_char;
            if (textlen <= 0) {
                var credit = 0;
            } else if (textlen <= 160) {
                var credit = 1;
            } else if (textlen <= 306) {
                var credit = 2;
            } else if (textlen <= 460) {
                var credit = 3;
            } else if (textlen <= 612) {
                var credit = 4;
            }
            if (textlen == 612) {
                $('.welcome-textarea-char-limit').html(`<div style="font-size: 14px; padding: 5px 10px;" class="alert alert-danger" role="alert">Max characters limit is '612'.</div>`)
            } else {
                $('.welcome-textarea-char-limit').html(`<span style="color: gray;font-size: 14px">Max characters limit is '612'.</span>`)
            }

            $('#welcome-rchars').text(textlen);
            $('#welcome-credit').text(credit);

        });

        $(document).ready(function () {

            $(".wellcome-placeholder").click(function () {
                var cntrl = $(this).html().trim();
                console.log(cntrl)
                $("#welcome-campaign-textarea").val(function (_, val) {
                    return val + cntrl
                });
            });
        });

        $(document).ready(function () {

            $(".abandoned-placeholder").click(function () {
                var cntrl = $(this).html().trim();
                // console.log(cntrl)
                $("#abandoned-campaign-textarea").val(function (_, val) {
                    return val + cntrl
                });
            });
        });

        $(document).ready(function () {

            $(".orderconfirm-placeholder").click(function () {
                var cntrl = $(this).html().trim();
                // console.log(cntrl)
                $("#orderconfirm-campaign-textarea").val(function (_, val) {
                    return val + cntrl
                });
            });
        });

        $(document).ready(function () {

            $(".orderrefund-placeholder").click(function () {
                var cntrl = $(this).html().trim();
                // console.log(cntrl)
                $("#orderrefund-campaign-textarea").val(function (_, val) {
                    return val + cntrl
                });
            });
        });
        $(document).ready(function () {

            $(".dispatch-placeholder").click(function () {
                var cntrl = $(this).html().trim();
                // console.log(cntrl)
                $("#orderdispatch-campaign-textarea").val(function (_, val) {
                return val + cntrl
            });
        });
    });
    $(".welcome-save-campaign").submit(function (event) {
        var showTextlen = $(this).find('#welcome-rchars').text();
        var showCredit = $(this).find('#welcome-credit').text();
        var current_user_credits = $('.current_user_credits').val();

        var sender_text = $(this).find('.welcome-sendername-character-count').val();
        if (parseInt(sender_text.length) >= 3 && parseInt(sender_text.length) <= 11) {
            if (parseInt(showCredit) <= parseInt(current_user_credits)) {
                if (confirm("Characters used " + showTextlen + " / " + showCredit + " credits")) {
                    $(this).find('.welcome_sms_calculated_credit_per_sms').val(parseInt(showCredit));
                } else {
                    return false;
                }
            } else {
                alert("Your SMS credits is not enough to create this campaign.")
                event.preventDefault();
            }
        } else {
            $(this).find('.welcome-sender-char-msg').html(`<div style="font-size: 14px; padding: 5px 10px;" class="alert alert-danger" role="alert">Min character 3 and Max character 11 </div>`)
            event.preventDefault();
        }
    });
    // adandoned cart textarea checks
    $('.abandoned-campaign-textarea').keyup(function () {

        var nmbr_char = $(this).val().length;
        var textlen = nmbr_char;
        if (textlen <= 0) {
            var credit = 0;
        } else if (textlen <= 160) {
            var credit = 1;
        } else if (textlen <= 306) {
            var credit = 2;
        } else if (textlen <= 460) {
            var credit = 3;
        } else if (textlen <= 612) {
            var credit = 4;
        }
        if (textlen == 612) {
            $('.abandoned-textarea-char-limit').html(`<div style="font-size: 14px; padding: 5px 10px;" class="alert alert-danger" role="alert">Max characters limit is '612'.</div>`)
        } else {
            $('.abandoned-textarea-char-limit').html(`<span style="color: gray;font-size: 14px">Max characters limit is '612'.</span>`)
        }

        $('#abandoned-rchars').text(textlen);
        $('#abandoned-credit').text(credit);
    });

    $(".abandoned-save-campaign").submit(function (event) {
        var showTextlen = $(this).find('#abandoned-rchars').text();
        var showCredit = $(this).find('#abandoned-credit').text();
        var current_user_credits = $('.current_user_credits').val();

        var sender_text = $(this).find('.abandonedcart-sendername-character-count').val();
        if (parseInt(sender_text.length) >= 3 && parseInt(sender_text.length) <= 11) {
            if (parseInt(showCredit) <= parseInt(current_user_credits)) {
                if (confirm("Characters used " + showTextlen + " / " + showCredit + " credits")) {
                    $(this).find('.abandoned_sms_calculated_credit_per_sms').val(parseInt(showCredit));
                } else {
                    return false;
                }
            } else {
                alert("Your SMS credits is not enough to create this campaign.")
                event.preventDefault();
            }
        } else {
            $(this).find('.abandonedcart-sender-char-msg').html(`<div style="font-size: 14px; padding: 5px 10px;" class="alert alert-danger" role="alert">Min character 3 and Max character 11 </div>`)
            event.preventDefault();
        }
    });
    // order confirm campaign textarea checks
    $('.orderconfirm-campaign-textarea').keyup(function () {

        var nmbr_char = $(this).val().length;
        var textlen = nmbr_char;
        if (textlen <= 0) {
            var credit = 0;
        } else if (textlen <= 160) {
            var credit = 1;
        } else if (textlen <= 306) {
            var credit = 2;
        } else if (textlen <= 460) {
            var credit = 3;
        } else if (textlen <= 612) {
            var credit = 4;
        }
        if (textlen == 612) {
            $('.orderconfirm-textarea-char-limit').html(`<div style="font-size: 14px; padding: 5px 10px;" class="alert alert-danger" role="alert">Max characters limit is '612'.</div>`)
        } else {
            $('.orderconfirm-textarea-char-limit').html(`<span style="color: gray;font-size: 14px">Max characters limit is '612'.</span>`)
        }

        $('#orderconfirm-rchars').text(textlen);
        $('#orderconfirm-credit').text(credit);
    });

    $(".orderconfirm-save-campaign").submit(function (event) {
        var showTextlen = $(this).find('#orderconfirm-rchars').text();
        var showCredit = $(this).find('#orderconfirm-credit').text();
        var current_user_credits = $('.current_user_credits').val();
        var sender_text = $(this).find('.orderconfirm-sendername-character-count').val();

        if (parseInt(sender_text.length) >= 3 && parseInt(sender_text.length) <= 11) {
            if (parseInt(showCredit) <= parseInt(current_user_credits)) {
                if (confirm("Characters used " + showTextlen + " / " + showCredit + " credits")) {
                    $(this).find('.orderconfirm_sms_calculated_credit_per_sms').val(parseInt(showCredit));
                } else {
                    return false;
                }
            } else {
                alert("Your SMS credits is not enough to create this campaign.")
                event.preventDefault();
            }
        } else {
            $('.orderconfirm-save-campaign').find('.orderconfirm-sender-char-msg').html(`<div style="font-size: 14px; padding: 5px 10px;" class="alert alert-danger" role="alert">Min character 3 and Max character 11 </div>`)
            event.preventDefault();
        }

    });
    // order refund campaign textarea checks
    $('.orderrefund-campaign-textarea').keyup(function () {

        var nmbr_char = $(this).val().length;
        var textlen = nmbr_char;
        if (textlen <= 0) {
            var credit = 0;
        } else if (textlen <= 160) {
            var credit = 1;
        } else if (textlen <= 306) {
            var credit = 2;
        } else if (textlen <= 460) {
            var credit = 3;
        } else if (textlen <= 612) {
            var credit = 4;
        }
        if (textlen == 612) {
            $('.orderrefund-textarea-char-limit').html(`<div style="font-size: 14px; padding: 5px 10px;" class="alert alert-danger" role="alert">Max characters limit is '612'.</div>`)
        } else {
            $('.orderrefund-textarea-char-limit').html(`<span style="color: gray;font-size: 14px">Max characters limit is '612'.</span>`)
        }

        $('#orderrefund-rchars').text(textlen);
        $('#orderrefund-credit').text(credit);
    });

    $(".orderrefund-save-campaign").submit(function (event) {
        var showTextlen = $(this).find('#orderrefund-rchars').text();
        var showCredit = $(this).find('#orderrefund-credit').text();
        var current_user_credits = $('.current_user_credits').val();
        var sender_text = $(this).find('.orderrefund-sendername-character-count').val();

        if (parseInt(sender_text.length) >= 3 && parseInt(sender_text.length) <= 11) {
            if (parseInt(showCredit) <= parseInt(current_user_credits)) {
                if (confirm("Characters used " + showTextlen + " / " + showCredit + " credits")) {
                    $(this).find('.orderrefund_sms_calculated_credit_per_sms').val(parseInt(showCredit));
                } else {
                    return false;
                }
            } else {
                alert("Your SMS credits is not enough to create this campaign.")
                event.preventDefault();
            }
        } else {
            $(this).find('.orderrefund-sender-char-msg').html(`<div style="font-size: 14px; padding: 5px 10px;" class="alert alert-danger" role="alert">Min character 3 and Max character 11 </div>`)
            event.preventDefault();
        }
    });
    // order dispatch  campaign textarea checks
    $('.orderdispatch-campaign-textarea').keyup(function () {

        var nmbr_char = $(this).val().length;
        var textlen = nmbr_char;
        if (textlen <= 0) {
            var credit = 0;
        } else if (textlen <= 160) {
            var credit = 1;
        } else if (textlen <= 306) {
            var credit = 2;
        } else if (textlen <= 460) {
            var credit = 3;
        } else if (textlen <= 612) {
            var credit = 4;
        }
        if (textlen == 612) {
            $('.orderdispatch-textarea-char-limit').html(`<div style="font-size: 14px; padding: 5px 10px;" class="alert alert-danger" role="alert">Max characters limit is '612'.</div>`)
        } else {
            $('.orderdispatch-textarea-char-limit').html(`<span style="color: gray;font-size: 14px">Max characters limit is '612'.</span>`)
        }

        $('#orderdispatch-rchars').text(textlen);
        $('#orderdispatch-credit').text(credit);
    });

    $(".orderdispatch-save-campaign").submit(function (event) {
        var showTextlen = $(this).find('#orderdispatch-rchars').text();
        var showCredit = $(this).find('#orderdispatch-credit').text();
        var current_user_credits = $('.current_user_credits').val();
        var sender_text = $(this).find('.orderdispatch-sendername-character-count').val();
        if (parseInt(sender_text.length) >= 3 && parseInt(sender_text.length) <= 11) {
            if (parseInt(showCredit) <= parseInt(current_user_credits)) {
                if (confirm("Characters used " + showTextlen + " / " + showCredit + " credits")) {
                    $(this).find('.orderdispatch_sms_calculated_credit_per_sms').val(parseInt(showCredit));
                } else {
                    return false;
                }
            } else {
                alert("Your SMS credits is not enough to create this campaign.")
                event.preventDefault();
            }
        } else {
            $(this).find('.orderdispatch-sender-char-msg').html(`<div style="font-size: 14px; padding: 5px 10px;" class="alert alert-danger" role="alert">Min character 3 and Max character 11 </div>`)
            event.preventDefault();
        }
    });

    })
    ;

</script>
