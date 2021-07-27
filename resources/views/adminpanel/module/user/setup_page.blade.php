@extends('adminpanel.user-layout.default')
@section('content')
    <div class="col-lg-12 col-md-12 p-4">
        <!-- start info box -->
        <div class="row ">
            <div class="col-md-6 col-lg-6 m-auto">
                <div class="card">
                    <div class="card-header bg-white pb-1">
                        <h5>Notification</h5>
                    </div>
                    <div class="card-body">
                        Wait for further instruction and that setup can take 1 working day. And if urgent you can “Contact us” during business hours 8:30am – 5pm Monday – Friday.
                        <div class="form-group" style="
    margin-top: 10px;
    text-align: center;
">
                            <a class="btn btn-primary" href="{{ route('contact-form') }}">
                                Contact US
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
