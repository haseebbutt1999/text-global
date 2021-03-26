@extends('adminpanel.user-layout.default')
@section('content')
    <div class="col-lg-12 col-md-12 p-4">
        <!-- start info box -->
        <div class="row ">

                <div class="col-md-12 col-lg-12 ">
                    <div class="card">
                        <form action="{{route('shop-detail')}}" method="post">
                            @csrf
                        <div class="card-header d-flex justify-content-between align-items-center bg-white pb-1">
                            <h5>User Detail</h5>
                            <div class="form-group">
                                <input type="submit" class="btn btn-primary" value="Register">
                            </div>
                        </div>
                        <div class="card-body">

                                <input hidden type="number" name="user_id" value="{{Auth::user()->id}}">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="#">Firstname</label>
                                            <input placeholder="Enter your firstname" name="firstname" type="text" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="#">Surname</label>
                                            <input placeholder="Enter your surname" name="surname" type="text" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="#">Email</label>
                                            <input placeholder="Enter email" name="email" type="email" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="#">Mobile#</label>
                                            <input placeholder="Enter mobile number" name="mobile_number" type="number" class="form-control">
                                            <small class="text-muted">Mobile format must be 447</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="#">Company Name</label>
                                            <input placeholder="Enter your company name" name="company_name" type="text" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="#">Sender Name</label>
                                            <input placeholder="Enter your sendername" name="sender_name" type="text" class="form-control">
                                            <small class="text-muted">where the SMS is being sent from</small>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        </form>
                    </div>

                </div>

        </div>
    </div>
@endsection
