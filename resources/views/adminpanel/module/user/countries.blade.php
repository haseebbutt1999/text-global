@extends('adminpanel.user-layout.default')
@section('content')
    <div class="col-lg-12 col-md-12 p-4">
        <!-- start info box -->
        <div class="row ">

            <div class="col-md-6 col-lg-6 m-auto">
                <div class="card">
                    <form action="{{route('country-user-save')}}" method="post">
                        @csrf
                        <div class="card-header d-flex justify-content-between align-items-center bg-white pb-1">
                            <h5>Countries</h5>
                            <div class="form-group">
                                <input type="submit" class="btn btn-lg btn-primary" value="Save">
                            </div>
                        </div>
                        <div class="card-body">
                        {{--                        @dd($user_shop_data)--}}
{{--                        {{route('user-country-save')}}--}}

                            <input hidden type="number" name="user_id" value="{{auth()->user()->id}}">
                            <div class="row">
                                <div class="col-md-12">
{{--                                    @dd($countries_data)--}}

                                    <div class="form-group">
                                        <div>
                                            @foreach($countries_data as $key=>$countries)
{{--                                                <input hidden type="number" name="country_id" value="{{$countries->id}}">--}}
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div >{{$countries->name}}</div>
{{--                                                    <input hidden type="number" name="country_id[]" value="{{$countries->id}}">--}}
{{--                                                    @php--}}
{{--                                                        dd($country_user_data);--}}
{{--                                                    @endphp--}}
                                                    <label class="switch">
{{--                                                        @dd($country_user_data)--}}
                                                        <input @foreach($country_user_data as $country_user) @if( $countries->id === $country_user->country_id) checked @endif @endforeach value="{{$countries->id}}"  name="country_id[]" type="checkbox" >
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

        </div>
    </div>
@endsection
{{--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>--}}
{{--<script>--}}
{{--    $(document).ready(function(){--}}
{{--        $('body').on('change','.status-switch',function () {--}}
{{--            var status = '';--}}
{{--            var id = $(this).data('id');--}}
{{--            console.log(id)--}}
{{--            if($(this).is(':checked')){--}}
{{--                status = 'active';--}}
{{--                $(this).next().text('Active')--}}
{{--            }--}}
{{--            else{--}}
{{--                status = 0;--}}
{{--                $(this).next().text('Inactive')--}}
{{--            }--}}
{{--            $.ajax({--}}
{{--                url: $(this).data('route'),--}}
{{--                type: 'get',--}}
{{--                data:{--}}
{{--                    id:id,--}}
{{--                    _token: $(this).data('csrf'),--}}
{{--                    type : 'user_status_update',--}}
{{--                    status : status--}}
{{--                }--}}
{{--            })--}}
{{--        });--}}
{{--    });--}}
{{--</script>--}}
