@extends('adminpanel.user-layout.default')
@section('content')
    <div class="col-lg-12 col-md-12 p-4">
        <!-- start info box -->
        <div class="row ">
            <div class="col-md-12 pl-3 pt-2" style="margin: auto;">
                <div class="card" style="width: 100%">
                    <div class="card-header" style="background: white;">
                        <div class="row ">
                            @if (session('status'))
                                <div class="alert alert-success">
                                    {{ session('status') }}
                                </div>
                            @endif
                            <div class="col-md-12 px-3 pt-2">
                                <div class="d-flex justify-content-between align-items-center mr-2">
                                    <h3>Customers</h3>
                                    <a href="{{route('customer-sync')}}"><button class="btn-primary">Customer Sync</button></a>
                                    <div>
                                        <a href="{{route('welcome-campaign')}}"><button class="btn-primary">Welcome Campaign</button></a>
                                        <a href="{{route('abandoned-cart-campaign')}}"><button class="btn-primary">Abandoned Cart Campaign</button></a>
                                    </div>

                                    {{--                                    <div >--}}
                                    {{--                                        <a style="display: inline-block;" type="submit" href=""   class="btn btn-sm btn-primary text-white"  >Sync Collections</a>--}}

                                    {{--                                        <form style="display: inline-block;" action="" method="post">--}}
                                    {{--                                            @csrf--}}
                                    {{--                                            <button type="submit"   class="btn btn-sm btn-primary text-white"  >Sync Products</button>--}}
                                    {{--                                        </form>--}}
                                    {{--                                    </div>--}}
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
                                        <th class="font-weight-bold " >Name</th>
                                        <th class="font-weight-bold " >Email</th>
                                        <th class="font-weight-bold " >Phone</th>
                                        <th class="font-weight-bold " >Country</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    {{--                                        @dd($users_data)--}}
                                    @foreach($customers_data as $key=>$customer)

                                        <tr class="td-text-center">
                                            <td>
                                                {{$customer->first_name ." ".$customer->last_name}}
                                            </td>
                                            <td>
                                                {{$customer->email}}
                                            </td>
                                            <td>
                                                {{$customer->phone}}
                                            </td>
                                            @php
                                                   #dd($customer->addressess->first()->country);
                                            @endphp
                                            <td>
                                                {{$customer->addressess->first()->country}}
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                {!!  $customers_data->links() !!}
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
