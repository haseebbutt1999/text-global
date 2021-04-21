@extends('adminpanel.layout.default')
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
                                <div class="d-flex justify-content-between">
                                    <h5>Shops</h5>
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
                                        <th class="font-weight-bold " >Shop Name</th>
                                        <th class="font-weight-bold " >Plan Name</th>
                                        <th class="font-weight-bold " >Credits</th>
                                        <th class="font-weight-bold text-right pr-3"  >Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
{{--                                        @dd($users_data)--}}
                                    @foreach($users_data as $key=>$user)

                                        <tr class="td-text-center">
                                            <td>
                                                {{$user->name}}
                                            </td>
                                            <td>
                                                @if(isset($user->plan->name))
                                                {{$user->plan->name}}
                                                @else
                                                No Plan Selected
                                                @endif
                                            </td>
                                            <td>
                                                <div class="badge badge-primary text-light px-3 py-1">{{$user->credit}}</div>
                                            </td>
                                            <td style="text-align: right;">
                                                @if(isset($user->id))
                                                    <a href="{{route('shop-status-detail', $user->id)}}"><button class="btn btn-primary btn-sm" >Shop Detail</button></a>
                                                @else
                                                    No Shop Details
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                {!!  $users_data->links() !!}
                            </div>

                        </div>
                    </div>
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
{{--            console.log(id) --}}
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

