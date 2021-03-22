@extends('adminpanel.layout.default')
@section('content')
    <div class="col-lg-12 col-md-12 p-4">
        <!-- start info box -->
        <div class="row ">

            <div class="col-md-6 col-lg-7 m-auto">
                <div class="card">
                    <div class="card-header bg-white pb-1">
                        <h5>User Status Detail</h5>
                    </div>
                    <div class="card-body">
{{--                    @dd($shop_data)--}}
                        <form action="{{route('shop-status-detail-save')}}" method="post">
                            @csrf
{{--                            @dd($shop_data)--}}
                            <input hidden type="number" name="user_id" value="{{$shop_data->user_id}}">
                            <div class="row">
                                <div class="col-md-6">
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
                                    <div class="form-group">
                                        <label for="#">Company Name</label>
                                        <input placeholder="Enter your company name" value="{{ $shop_data-> company_name}}" name="company_name" type="text" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="#">Sender Name</label>
                                        <input placeholder="Enter your sendername" value="{{ $shop_data-> sender_name}}" name="sender_name" type="text" class="form-control">
                                        <small class="text-muted">where the SMS is being sent from</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
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

                            <div>
                                <div class="mb-2">
                                   Shop Status
                                </div>
                                <label class="switch" style="margin-left: -4px;">
{{--                                    @dd($shop_data->user)--}}
                                    <input @if($shop_data->user->user_status == "active")checked="" @endif name="user_status" type="checkbox" value="active" class="custom-control-input  status-switch">
                                    <span class="slider round"></span>
                                </label>
                            </div>

                            <br>
                            <div class="form-group">
                                <input type="submit" class="btn btn-lg btn-primary" value="Save">
                            </div>
                        </form>
                    </div>
                </div>

            </div>

        </div>
    </div>
@endsection
