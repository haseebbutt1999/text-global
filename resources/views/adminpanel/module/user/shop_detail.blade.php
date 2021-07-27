@extends('adminpanel.user-layout.default')
@section('content')
    <div class="col-lg-12 col-md-12 p-4">
        <!-- start info box -->
        <div class="row ">

                <div class="col-md-12 col-lg-12 ">
                    <div class="card">
                        <form id="shop-detail" action="{{route('shop-detail')}}" method="post">
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
                                            <label for="#">First Name</label>
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
                                            <label class="text-left"  for="#">Sender Name</label>
                                            <div class="custom-select-div ">
                                                <input required placeholder="Enter Sendername" name="sender_name" type="text"  class="form-control sendername-character-count">
                                            </div>
                                            <div class="sender-char-msg"><span style="color: gray;font-size: 14px">Min character 3 and Max character 11</span></div>
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
@section('js_after')
    <script>
        $(document).ready(function(){
            $( "#shop-detail" ).submit(function( event ) {

                var sender_text = $(this).find('.sendername-character-count').val();

                if(parseInt(sender_text.length) >=3 && parseInt(sender_text.length) <= 11)
                {
                    return true;
                }else{
                    $(this).find('.sender-char-msg').html(`<div style="font-size: 14px; padding: 5px 10px;" class="alert alert-danger" role="alert">Min character 3 and Max character 11 </div>`)
                    event.preventDefault();
                }

            });
        });

    </script>
@endsection
