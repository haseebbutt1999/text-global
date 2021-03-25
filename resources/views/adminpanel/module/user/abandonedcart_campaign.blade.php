@extends('adminpanel.user-layout.default')
@section('content')
    <div class="col-lg-12 col-md-12 p-4">
        <!-- start info box -->
        <div class="row ">

            <div class="col-md-5 col-lg-5 m-auto">
                <div class="card">
                    <div class="card-header bg-white pb-1 d-flex justify-content-between align-items-center">
                        <h5>Abandoned Cart Campaign</h5>
                    </div>
                    <div class="card-body">
                        <form  action="{{Route('abandoned-cart-campaign-save')}}" method="post"  >
                            @csrf
                            <div class="modal-body">
                                <div class="form-group">
                                    <label class="text-left"  for="#">Campaign Name</label>
                                    <input @if(isset($abandoned_cart_campaign->campaign_name)) value="{{$abandoned_cart_campaign->campaign_name}}" @endif name="campaign_name" type="text"  class="form-control ">
                                </div>

                                <div class="form-group">
                                    <label class="text-left"  for="#">Sender Name</label>
                                    <input @if(isset($abandoned_cart_campaign->sender_name)) value="{{$abandoned_cart_campaign->sender_name}}" @endif name="sender_name" type="text"  class="form-control name">
                                </div>

                                <div class="form-group">
                                    <label class="text-left"  for="#">Text Message</label>
                                    <div id="cct_embed_counts">
                                        <textarea maxlength="160" class="form-control create-campaign-textarea"  name="message_text"  rows="4">@if(isset($abandoned_cart_campaign->message_text)){{$abandoned_cart_campaign->message_text}}@endif</textarea>
                                        <span id="rchars">160</span> Character(s) Remaining
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit"  class=" btn btn-primary ">Save</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function(){
        $('body').on('change','.status-switch',function () {
            var status = '';
            // console.log(id)
            if($(this).is(':checked')){
                status = 'active';
            }
            else{
                status = 'inactive';
            }
            console.log(status)
            $.ajax({
                url: $(this).data('route'),
                type: 'get',
                data:{
                    status : status
                }
            })
        });
    });

    function createCampaignTextareaFunction() {
        var textarea_val = $('.create-campaign-textarea').val().length;
        $('.textarea-count-val').text("characters: "+ textarea_val);

    }
</script>
