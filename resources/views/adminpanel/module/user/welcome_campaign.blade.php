@extends('adminpanel.user-layout.default')
@section('content')
    <div class="col-lg-12 col-md-12 p-4">
        <!-- start info box -->
        <div class="row ">

            <div class="col-md-12 col-lg-12 ">
                <div class="card">
                    <form  action="{{Route('welcome-sms-campaign-save')}}" method="post"  >
                        @csrf
                    <div class="card-header bg-white  d-flex justify-content-between align-items-center">
                        <h5>Welcome Sms Campaign</h5>
                        <div class="">
                            <button type="submit"  class=" btn btn-primary ">Save</button>
                        </div>
                    </div>
                        <div class="row px-3">
                            <div class="card-body col-md-6 col-lg-6 ">
                                <div class="form-group">
                                    <label class="text-left"  for="#">Campaign Name</label>
                                    <input @if(isset($welcome_campaign->campaign_name)) value="{{$welcome_campaign->campaign_name}}" @endif name="campaign_name" type="text"  class="form-control ">
                                </div>

                                <div class="form-group">
                                    <label class="text-left"  for="#">Sender Name</label>
                                    <input @if(isset($welcome_campaign->sender_name)) value="{{$welcome_campaign->sender_name}}" @endif name="sender_name" type="text"  class="form-control name">
                                </div>
                            </div>
                            <div class="card-body col-md-6 col-lg-6 ">
                                <div class="form-group">
                                    <label class="text-left"  for="#">Text Message</label>
                                    <div id="cct_embed_counts">
                                        <textarea maxlength="160" class="form-control create-campaign-textarea"  name="message_text"  rows="4">@if(isset($welcome_campaign->message_text)){{$welcome_campaign->message_text}}@endif</textarea>
                                        <span id="rchars">160</span> Character(s) Remaining
                                    </div>
                                </div>
                                <div class="mb-2">
                                    Status
                                </div>
                                <label class="switch" style="">
                                    {{--                                    @dd($shop_data->user)--}}
                                    <input @if($welcome_campaign->status == "active")checked="" @endif name="status" type="checkbox" value="active" class="custom-control-input  status-switch">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                    </form>
                </div>

            </div>

        </div>
    </div>
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    // $(document).ready(function(){
    //     $('body').on('change','.status-switch',function () {
    //         var status = '';
    //         // console.log(id)
    //         if($(this).is(':checked')){
    //             status = 'active';
    //         }
    //         else{
    //             status = 'inactive';
    //         }
    //         console.log(status)
    //         $.ajax({
    //             url: $(this).data('route'),
    //             type: 'get',
    //             data:{
    //                 status : status
    //             }
    //         })
    //     });
    // });

    function createCampaignTextareaFunction() {
        var textarea_val = $('.create-campaign-textarea').val().length;
        $('.textarea-count-val').text("characters: "+ textarea_val);

    }
</script>
