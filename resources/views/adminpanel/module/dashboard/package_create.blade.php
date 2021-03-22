@extends('adminpanel.layout.default')
@section('content')
    <div class="col-lg-12 col-md-12 p-4">
        <!-- start info box -->
        <div class="row ">
            <div class="col-md-6 col-lg-7 m-auto">
                <div class="card">
                    <div class="card-header bg-white pb-1">
                        <h5>Package Create</h5>
                    </div>
                    <div class="card-body">
                        {{--                        @dd($user_shop_data)--}}

                        <form action="{{route('package-save')}}" method="post">
                            @csrf
                            <input hidden type="number" name="user_id" value="{{$user_shop_data->shopdetail->user_id}}">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="#">Firstname</label>
                                        <input placeholder="Enter your firstname" value="{{ $user_shop_data->shopdetail->firstname }}" name="firstname" type="text" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="#">Surname</label>
                                        <input placeholder="Enter your surname" value="{{ $user_shop_data->shopdetail->surname }}" name="surname" type="text" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="#">Email</label>
                                        <input placeholder="Enter email" value="{{ $user_shop_data->shopdetail-> email}}" name="email" type="email" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="#">Mobile#</label>
                                        <input placeholder="Enter mobile number" value="{{ $user_shop_data->shopdetail-> mobile_number}}" name="mobile_number" type="number" class="form-control">
                                        <small class="text-muted">Mobile format must be 447</small>
                                    </div>
                                    <div class="form-group">
                                        <label for="#">Company Name</label>
                                        <input placeholder="Enter your company name" value="{{ $user_shop_data->shopdetail-> company_name}}" name="company_name" type="text" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="#">Sender Name</label>
                                        <input placeholder="Enter your sendername" value="{{ $user_shop_data->shopdetail-> sender_name}}" name="sender_name" type="text" class="form-control">
                                        <small class="text-muted">where the SMS is being sent from</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="#">Username</label>
                                        <input placeholder="Enter your username" name="user_name" disabled value="{{$user_shop_data->shopdetail->user_name}}" type="text" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="#">Password</label>
                                        <input placeholder="Enter your password" name="password" type="text" disabled value="{{$user_shop_data->shopdetail->password}}" class="form-control">
                                    </div>
                                </div>
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
