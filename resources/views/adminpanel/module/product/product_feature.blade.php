@extends('adminpanel.admin-module.layout.default')
@section('content')
    <div class="col-lg-12 col-md-12 p-4">
        <!-- start info box -->
        <div class="row ">
            <div class="col-md-12 pl-3 pt-2">
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
                                    <h3>Featured Products</h3>
                                {{--                                        Product Search Api Form Start--}}
                                    <div    >
                                        <div class="d-flex">
                                            <a href="{{route('feature.product.search.api')}}"  type="button" class=" btn btn-lg btn-primary">Json</a>
                                        </div>
                                    </div>
                                {{--                                        Product Search Api Form End--}}
                                </div>
                            </div>
                        </div>
                    </div>

                <div class="row mx-1 mt-1">
                    <div class="card-body">
                        <div id="product_append">
                            <div class="row px-3" style="overflow-x:auto;">

                                <table id="datatabled" class="table table-borderless  table-hover  table-class ">
                                    <thead class="border-0 ">

                                    <tr class="th-tr table-tr text-white text-center">
                                        <th class="font-weight-bold " style="width: 70%;">Title</th>
                                        <th class="font-weight-bold " style="width: 30%;">Featured Product</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($product_data as $key=>$product)

                                        <tr class="td-text-center">
                                            <td scope="row">
                                                <div style="display:flex;flex-wrap:nowrap;align-items: center;">
                                                    <div>
                                                        @if($product->image != null)
                                                            {{--                                                            @dd()--}}
                                                            <img style="border: 1px solid lightgrey; border-radius: 5px;margin-right: 20px;" width="50px" height="50px" src="{{$product->image}}">
                                                        @else
                                                            <img style="border: 1px solid lightgrey; border-radius: 5px;margin-right: 20px;" width="50px" height="50px" src="{{asset('assets\images\random_product.jpg')}}">
                                                        @endif
                                                    </div>
                                                    <div>
                                                        {{$product->title}}
                                                    </div>
                                                </div>
                                            </td>
                                            <td style="float: right;">
                                                <form action="{{Route('feature.product.remove', $product->id)}}" method="post">
                                                    @csrf
                                                    <button class="btn btn-sm btn-primary">Remove Featured Product</button>
                                                </form>
                                            </td>
                                            {{--                                <td><a class="btn btn-primary text-white" href="">View</a></td>--}}
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                {!!  $product_data->links() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end info box -->
    </div>
    <script type="text/javascript">


        function searchFunction(element){
            console.log(element);
            $.ajax({
                method: "get",
                url: $('#search-form').attr('action'),
                data: $(element).serialize(),
                success: function (response) {
                    console.log(response);
                    $('#product_append').html(response);
                },
                error: function (error) {
                    console.log(error);
                    // alert("Mailbox Not Saved");
                }
            });
        }
        //Product Search APi JQ Function
        function featureProductSearchApi(element){
            console.log(element);
            $.ajax({
                method: "get",
                url: $('#feature-search-form-api').attr('action'),
                data: $(element).serialize(),
                success: function (response) {
                    console.log(response);
                    window.location.href = $('#feature-search-form-api').attr('action')+'?'+$(element).serialize();

                },
                error: function (error) {
                    console.log(error);
                }
            });
        }
        //Product Search APi JQ Function End

        function PackageTitle(t) {
            var package_title = t.val();
            var current_product_id = t.prev('input').val();
            console.log(current_product_id);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                method: "post",
                url: 'save/product_package',
                data: {
                    package_value: t.val(),
                    current_product_id: current_product_id,
                },

                success: function (response) {
                    console.log(response);
                },
                error: function (error) {
                    console.log(error);
                }
            });
        }

        function MailboxTitle(t) {
            var mailbox_title = t.val();
            var current_product_id = t.prev('input').val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                method: "post",
                url: 'save/product_mailbox',
                data: {
                    mailbox_value: t.val(),
                    current_product_id: current_product_id,
                },

                success: function (response) {
                    console.log(response);
                },
                error: function (error) {
                    console.log(error);
                }
            });
        }
        $(document).ready(function(){
            //Feature Product Search APi JQ Button
            $("#feature-search-button-api").click(function(){
                featureProductSearchApi($(this).closest('form'));
            });
            //Product Search APi JQ Button End

            $("#search-button").click(function(){
                searchFunction($(this).closest('form'));
            });
        });
    </script>
@endsection
