@extends('adminpanel.user-layout.default')
@section('content')
    <div class="col-lg-12 col-md-12 p-4">
        <!-- start info box -->
        <div class="row ">

            <div class="col-md-12 col-lg-12 ">
                <div class="card">
                    <form id="shop-detail" action="{{route('contact-save')}}" method="post">
                        @csrf
                        <div class="card-header bg-white">
                            <div class="col-md-6 m-auto d-flex justify-content-between align-items-center  ">
                                <h5>Contact-us</h5>
                                <div class="form-group">
                                    <input type="submit" class="btn btn-primary" value="Submit">
                                </div>
                            </div>

                        </div>
                        <div class="card-body">

                            <input hidden type="number" name="user_id" value="{{Auth::user()->id}}">
                            <div class="row ">
                                <div class="col-md-6 m-auto">
                                    <div class="form-group">
                                        <label for="#">Name</label>
                                        <input placeholder="Enter your name" name="name" type="text" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="#">Email</label>
                                        <input placeholder="Enter email" name="email" type="email" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="#">Subject</label>
                                        <input placeholder="Enter email" name="subject" type="text" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="#">Message</label>
                                        <textarea  name="message" rows="6"  class="form-control"></textarea>

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

        });

    </script>
@endsection
