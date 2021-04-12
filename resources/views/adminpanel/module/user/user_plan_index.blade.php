@extends('adminpanel.user-layout.default')
@section('content')

    <!-- POST CARDS -->
<div class="container">
    <div class="row pt-2 p-5">
        @foreach($plans_data as $key=>$plan)
{{--            @if($plan->on_install != 1)--}}
                <div class="col-sm-4 mb-4">
                    <div class="card bg-white border-0 shadow-sm">
                        <div class="card-header bg-white border-light" style="background: #202E78 !important; color: white">
                            <h4 class="my-0 font-weight-normal">{{$plan->name}}</h4>
                        </div>
                        <div class="card-body">
                            <h1 class="card-title pricing-card-title">${{$plan->price}}<small class="text-muted">/ mo</small></h1>
                            <ul class="list-unstyled mt-3 mb-4">
                                <li>{{"Credits: " .$plan->credit}}</li>
                            </ul>
                            <div class="form-group d-flex">
                                <a style="width: 50%;" class="subscribe"  href="{{ route('billing', ['plan' => $plan->id]) }}" ><button type="button" class="btn btn-lg btn-block btn-outline-primary">Subscribe</button></a>
                                <a style="margin-left: 1%; width: 50%;" class="subscribe"  href="{{ route('billing', ['plan' => $plan->id]) }}" data-toggle="modal" data-target="#payment_modal{{$key}}" ><button type="button" class="btn btn-lg btn-block btn-outline-primary">Buy Credits</button></a>
                                <!-- Modal  start-->
                                <div class="modal fade" id="payment_modal{{$key}}" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-popout" role="document">
                                        <div class="modal-content ">
                                            <div class="block card p-3 block-themed block-transparent mb-0">
                                                <div class="block-header bg-primary-dark">
                                                    <div class="block-options d-flex justify-content-between">
                                                        <h5 class="block-title">Payment for Buy Credits</h5>
                                                        <button type="button" class="btn-block-option">
                                                            <i class="fa fa-fw fa-times"  data-dismiss="modal" aria-label="Close"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="block-content">
                                                    <form
                                                        role="form"
                                                        action="{{ route('stripe.process.payment') }}"
                                                        method="post"
                                                        class="require-validation"
                                                        data-cc-on-file="false"
                                                        data-stripe-publishable-key="{{ env('STRIPE_KEY') }}"
                                                        id="payment-form">
                                                        @csrf

                                                        <input type="hidden" class="plan_id" name="plan_id" value="{{$plan->id}}">
                                                        <div class='form-group '>
                                                            <label class='control-label'>Select Credits</label>

                                                            <div class="form-check">
                                                                @if(isset($plan->credits))
                                                                    @foreach($plan->credits as $key2=>$credit_data)
                                                                        <label class="form-check-label d-flex price-credit-main-div">
                                                                            <input type="hidden" name="price" class="price">
                                                                            <input type="hidden" name="credits" class="credits">
                                                                            <input required type="radio" style="display: block !important;" name="radio[]" data-price = "{{$credit_data->price}}" data-credits = "{{$credit_data->credits}}"  class="form-radio-input">
                                                                            <p class="ml-2">Credits {{$credit_data->credits}} / </p>
                                                                            <p class="mr-2"> {{ $credit_data->price}}$</p>
                                                                        </label>
                                                                    @endforeach
                                                                @else
                                                                    <label>Not Available</label>
                                                                @endif
                                                            </div>

                                                        </div>
                                                        <div>
                                                            <div class='form-group required'>
                                                                <label class='control-label'>Name on Card</label>
                                                                <input
                                                                    class='form-control' size='4' type='text'>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <div class='form-group  required'>
                                                                <label class='control-label'>Card Number</label> <input
                                                                     class='form-control card-number-{{$plan->id}}' size='20'
                                                                    type='text'>
                                                            </div>
                                                        </div>
                                                        <div class='row'>
                                                            <div class='col-4 form-group cvc required'>
                                                                <label class='control-label'>CVC</label> <input
                                                                                                                class='form-control card-cvc-{{$plan->id}}' placeholder='ex. 311' size='4'
                                                                                                                type='text'>
                                                            </div>
                                                            <div class='col-4 form-group expiration required'>
                                                                <label class='control-label'>Expiration Month</label> <input
                                                                    class='form-control card-expiry-month-{{$plan->id}}' placeholder='MM' size='2'
                                                                    type='text'>
                                                            </div>
                                                            <div class='col-4 form-group expiration required'>
                                                                <label class='control-label'>Expiration Year</label> <input
                                                                    class='form-control card-expiry-year-{{$plan->id}}' placeholder='YYYY' size='4'
                                                                    type='text'>
                                                            </div>
                                                        </div>
                                                        <div >
                                                            <div class='error form-group' style="display: none;">
                                                                <div class='alert-danger alert'>Please correct the errors and try
                                                                    again.
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="text-right mb-2">
                                                            <div class="">
                                                                <button class="btn btn-primary btn-lg btn-block pay-btn" type="submit">Pay Now</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
{{--                                modal end--}}
                            </div>

                        </div>
                    </div>
                </div>
{{--            @endif--}}
        @endforeach
    </div>
</div>

    <!-- END POST CARDS -->

    </div>

@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script>
    $(document).ready(function(){
        $(".form-radio-input").on('click', function() {
            // in the handler, 'this' refers to the box clicked on
            var $box = $(this);
            if ($box.is(":checked")) {
                var get_price = $(this).data('price');
                var get_credits = $(this).data('credits');
                $('.price-credit-main-div').find('.price').val(get_price);
                $('.price-credit-main-div').find('.credits').val(get_credits);

            } else {
                $box.prop("checked", false);
            }
        });
        // Stripe Order Payment
        var $form = $(".require-validation");
        $('form.require-validation').bind('submit', function(e) {
            var plan_id = $(this).find('.plan_id').val();
            console.log(plan_id);
            var $form = $(".require-validation"),
                inputSelector = ['input[type=email]', 'input[type=password]',
                    'input[type=text]', 'input[type=file]',
                    'textarea'
                ].join(', '),
                $inputs = $form.find('.required').find(inputSelector),
                $errorMessage = $form.find('div.error'),
                valid = true;
            $errorMessage.hide();
            $('.pay-btn').prop('disabled', true);
            $('.pay-btn').text('Processing..');
            $('.has-error').removeClass('has-error');
            $inputs.each(function(i, el) {
                var $input = $(el);
                if ($input.val() === '') {
                    $input.parent().addClass('has-error');
                    $errorMessage.removeClass('hide');
                    e.preventDefault();
                }
            });
            if (!$form.data('cc-on-file')) {
                e.preventDefault();
                Stripe.setPublishableKey($form.data('stripe-publishable-key'));
                Stripe.createToken({
                    number: $(`.card-number-${plan_id}`).val(),
                    cvc: $(`.card-cvc-${plan_id}`).val(),
                    exp_month: $(`.card-expiry-month-${plan_id}`).val(),
                    exp_year: $(`.card-expiry-year-${plan_id}`).val()
                }, stripeResponseHandler);
            }
        });
        function stripeResponseHandler(status, response) {
            if (response.error) {
                $('.error')
                    .show()
                    .find('.alert')
                    .text(response.error.message);
                $('.pay-btn').prop('disabled', false);
                $('.pay-btn').text('Pay Now');
            } else {
                /* token contains id, last4, and card type */
                var token = response['id'];
                var $form = $(".require-validation");
                $form.find('input[type=text]').empty();
                $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
                $form.get(0).submit();
            }
        }
    });
</script>





