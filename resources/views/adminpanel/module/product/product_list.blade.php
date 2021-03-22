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
                                    <h3>Products</h3>
                                    <div >
                                        <a style="display: inline-block;" type="submit" href="{{Route('collection.fetch')}}"   class="btn btn-sm btn-primary text-white"  >Sync Collections</a>

                                        <form style="display: inline-block;" action="{{Route('product.fetch')}}" method="post">
                                            @csrf
                                            <button type="submit"   class="btn btn-sm btn-primary text-white"  >Sync Products</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
{{--                                        Product Search Api Form Start --}}
{{--                    <div class="row mt-4">--}}
{{--                        <div class="ml-auto mr-4" style="width:35%">--}}
{{--                            <form  id="search-form-api" action="{{Route('product.search.api')}}" onsubmit="searchFunctionApi(this)" method="get" role="search">--}}
{{--                                <div class="d-flex">--}}
{{--                                    <input type="text" class="form-control" name="key" placeholder="Search Product">--}}
{{--                                    <button id="search-button-api" type="button" class=" btn btn-sm btn-primary">Search</button>--}}
{{--                                </div>--}}
{{--                            </form>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                                        Product Search Api Form End --}}

                    <form  id="search-form" action="{{Route('products')}}"  method="get" >
                        <div class="row mx-2 mt-4" >
                            <div class="col-md-3 mb-3 col-sm-6">
                                <input type="text" class="form-control" name="product_search" @if(isset($current_product_search))  value="{{$current_product_search}}" @endif placeholder="Title">
                            </div>
                            <div class="col-md-2 col-sm-6">
                                <div class="form-group">
{{--                                    @dd();--}}
                                    <select class="form-control " name="type" >
                                        <option value="" selected>Select Product Type</option>
                                        @foreach($product_type as $product)
                                            @if($product->product_type != null)
                                                <option class="product-type" value="{{$product->product_type}}"
                                                    @if(isset($current_product_type) && ($product->product_type == $current_product_type) ) selected @endif>
                                                    {{$product->product_type}}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-6">
                                <div class="form-group">
                                    <select class="form-control " name="vendor" >
                                        <option value="" selected>Select Vendor</option>
                                        @foreach($product_vendor as $product)
                                            @if($product->vendor != null)
                                                <option class="product-type" value="{{$product->vendor}}"
                                                    @if(isset($current_product_vendor) && ($product->vendor == $current_product_vendor) ) selected @endif>
                                                     {{$product->vendor}}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-6">
                                <div class="form-group">
{{--                                    @dd($product_tag);--}}
                                    <select class="form-control " name="tag" >
                                        <option value="" selected>Select Product Tag</option>
                                        @foreach($product_tag as $product)
                                                <option class="product-tag" value="{{$product}}"
                                                    @if(isset($current_product_tag) && ($product == $current_product_tag) ) selected @endif>
                                                    {{$product}}
                                                </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="form-group d-flex">
                                    <select class="form-control " name="collection_title" >
                                        <option value="" selected>Select Collection</option>
                                        @foreach($collection_title as $collection)
                                            @if($collection->title != null)
                                                <option class="collection-type" value="{{$collection->title}}"
                                                    @if(isset($current_collection_title) && ($collection->title == $current_collection_title) ) selected @endif>
                                                    {{$collection->title}}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <button id="search-button" type="submit" class=" btn btn-lg btn-primary">Filter</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    {{--                    </div>--}}
                    <div class="card-body">
                        <div id="product_append">
                            <div class="row px-3" style="overflow-x:auto;">

                                <table id="datatabled" class="table table-borderless  table-hover  table-class ">
                                    <thead class="border-0 ">

                                    <tr class="th-tr table-tr text-white text-center">
                                        <th class="font-weight-bold " style="width: 45%">Title</th>
                                        <th class="font-weight-bold " style="width: 22%;">Packages</th>
                                        <th class="font-weight-bold " style="width: 22%;">Mailbox</th>
                                        <th class="font-weight-bold " style="width: 11%;">Featured Product</th>
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
                                            <td>

                                                <input class="current-product-id" name=""
                                                       value="{{$product->shopify_product_id}}" hidden type="text">
                                                <select class="form-control " onchange="PackageTitle($(this))">
                                                    <option value="" selected>Select Package</option>
                                                    @foreach($package_data as $package)
                                                        <option class="package-title" value="{{$package->id}}"
                                                                @if(isset($product->package->first()->id) && $product->package->first()->id === $package->id) selected @endif>
                                                            {{$package->title}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input class="current-product-id" name=""
                                                       value="{{$product->shopify_product_id}}" hidden type="text">
                                                <select class="form-control " onchange="MailboxTitle($(this))">
                                                    <option value="" selected>Select Mailbox</option>
                                                    @foreach($mailbox_data as $mailbox)
                                                        <option class="mailbox-title" value="{{$mailbox->id}}"
                                                                @if(isset($product->mailbox->first()->id) && $product->mailbox->first()->id === $mailbox->id) selected @endif>
                                                            {{$mailbox->title}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td style="float: right;">
                                                <form action="{{Route('feature.product.save', $product->id)}}" method="post">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-primary">Add Featured Product</button>
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

        //Product Search APi JQ Function
        function searchFunctionApi(element){
            console.log(element);
            $.ajax({
                method: "get",
                url: $('#search-form-api').attr('action'),
                data: $(element).serialize(),
                success: function (response) {
                    console.log(response);
                    window.location.href = $('#search-form-api').attr('action')+'?'+$(element).serialize();

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
            //Product Search APi JQ Button
            $("#search-button-api").click(function(){
                searchFunctionApi($(this).closest('form'));
            });
            //Product Search APi JQ Button End

            $("#search-button").click(function(){
                searchFunction($(this).closest('form'));
            });
        });
    </script>
@endsection
