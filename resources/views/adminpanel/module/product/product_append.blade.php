<div class="row px-3" style="overflow-x:auto;">

    <table id="datatabled" class="table table-borderless  table-hover  table-class ">
        <thead class="border-0 ">

        <tr class="th-tr table-tr text-white text-center">
            <th class="font-weight-bold w-50">Title</th>
            <th class="font-weight-bold w-25">Packages</th>
            <th class="font-weight-bold w-25">Mailbox</th>
        </tr>
        </thead>
        <tbody>

        @foreach($product_data as $key=>$product)

            <tr class="td-text-center">
                <td scope="row">
                    <div style="display:flex;flex-wrap:nowrap;align-items: center;">
                        <div>
                            @if($product->image != null)
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
                {{--                                <td><a class="btn btn-primary text-white" href="">View</a></td>--}}
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<div class="container-fluid">
    <div class="row">
        {!!  $product_data->links() !!}
    </div>
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
        $("#search-button-api").click(function(){
            searchFunctionApi($(this).closest('form'));
        });

        $("#search-button").click(function(){
            searchFunction($(this).closest('form'));
        });
    });
</script>
