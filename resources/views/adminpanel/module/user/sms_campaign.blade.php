@extends('adminpanel.user-layout.default')
@section('content')
    <div class="col-lg-12 col-md-12 p-4">
        <!-- start info box -->
        <div class="row ">

            <div class="col-md-12 col-lg-12 m-auto">
                <div class="card">
                    <div class="card-header bg-white pb-1 d-flex justify-content-between align-items-center">
                        <h5>Sms Campaign</h5>
                        <button type="button" class="btn btn-sm btn-primary create-sms-button"  data-toggle="modal" data-target="#CreateSmsModal" >Create Campaign</button>
                        {{--                              Modal--}}
                        <div aria-hidden="true" class="modal fade create-sms" id="CreateSmsModal" role="dialog" tabindex="-1">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header ">
                                        <h5 class="modal-title" id="exampleModalLabel">Create Campaign</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>

                                    <form id="save-campaign" action="{{Route('sms-campaign-save')}}" method="post"  >
                                        @csrf
                                        <div class="modal-body">
                                            <input hidden value="" id="calculated_credit_per_sms" name="calculated_credit_per_sms" type="number">
                                            <div class="form-group">
                                                <label class="text-left"  for="#">Campaign Name</label>
                                                <input required name="campaign_name" type="text"  class="form-control ">
                                            </div>

                                            <div class="form-group">
                                                <label class="text-left"  for="#">Sender Name</label>
                                                @php
                                                    $campaign_sender_name = \App\Campaign::where('user_id', \Illuminate\Support\Facades\Auth::user()->id)->select('sender_name')->distinct()->get();
                                                @endphp
{{--                                                <input   type="text"  class="form-control name ">--}}
                                                <div class="custom-select-div ">
{{--                                                    <select required name="sender_name" class=" js-example-tags sendername-character-count">--}}
{{--                                                        @foreach($campaign_sender_name as $sender)--}}
{{--                                                            <option value="{{$sender->sender_name}}">{{$sender->sender_name}}</option>--}}
{{--                                                        @endforeach--}}
{{--                                                    </select>--}}
                                                    <input required  name="sender_name" type="text"  class="form-control sendername-character-count">

                                                </div>
                                                <div class="sender-char-msg"><span style="color: gray;font-size: 14px">Min character 3 and Max character 11</span></div>
                                            </div>

                                            <div class="form-group">
                                                <div class="d-flex justify-content-between align-items-center" >
                                                    <label class="text-left"  for="#">Text Message</label>
                                                    <div style="color: gray;font-size: 13px;">Characters used <span id="rchars" style="text-align: right">0</span> / <span id="credit"> 0 </span> credits.<br> Emoji's are not supported</div>
                                                </div>
                                                <div id="cct_embed_counts">
                                                    <textarea required class="form-control create-campaign-textarea" maxlength="612"  name="message_text"  rows="6" ></textarea>
                                                    <div class="create-textarea-char-limit"><span style="color: gray;font-size: 14px">Max characters limit is '612'.</span></div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="text-left"  for="#">Published_at</label>
                                                <input required name="published_at" type="datetime-local"  class="form-control name">
                                            </div>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit"  class=" btn btn-primary ">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        {{--                              Modal End--}}
                    </div>
                    <div class="card-body">
                        <div class="row px-3" style="overflow-x:auto;">
                            <table id="datatabled" class="table table-borderless  table-hover  table-class ">
                            <thead class="border-0 ">

                            <tr class="th-tr table-tr text-white text-center">
                                <th class="font-weight-bold " style="width: 10%;">Campaign Name</th>
                                <th class="font-weight-bold " style="width: 10%;">Sender Name</th>
                                <th class="font-weight-bold " style="width: 30%">Message Text</th>
                                <th class="font-weight-bold " style="width: 9%">Credit per SMS</th>
                                <th class="font-weight-bold " style="width: 8%">Published</th>
                                <th class="font-weight-bold " style="width: 10%">Published_at</th>
                                <th class="font-weight-bold " style="width: 8%">Status</th>
                                {{--                                        <th class="font-weight-bold " >Status</th>--}}
                                <th class="font-weight-bold " style="width: 10%">Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($sms_campaign_data as $key=>$campaign)

                                <tr class="td-text-center">
                                    <td>
                                        {{$campaign->campaign_name}}
                                    </td>
                                    <td>
                                        {{$campaign->sender_name}}
                                    </td>
                                    <td style="    max-width: 350px;" >
                                        {{$campaign->message_text}}
                                    </td>
                                    <td class="text-center">
                                        <div class="badge badge-primary text-light px-3 py-1">{{$campaign->calculated_credit_per_sms}}</div>
                                    </td>
                                    <td>
                                        <label class="switch">
                                            {{--                                                        @dd($country_user_data)--}}
                                            <input class="status-switch" data-route="{{route('edit-status-campaign-save',$campaign->id)}}" data-csrf="{{csrf_token()}}"@if( $campaign->send_status == 'Sended') disabled   @endif @if( $campaign->status == 'active') checked @endif  value="active" name="status" type="checkbox" >
                                            <span class="slider round"></span>
                                        </label>
                                    </td>
                                    <td>
                                        {{\Illuminate\Support\Carbon::createFromTimeString($campaign->published_at)->format('d-M-Y\ h:i A')}}
                                    </td>
                                    <td >
                                        @if($campaign->send_status == "Sended")
                                            <div class="badge badge-primary text-light p-1">Sended</div>
                                        @else
                                            <div class="badge badge-danger text-light p-1">Not Sended</div>
                                        @endif
                                    </td>

                                    <td>
                                        <div class="btn-group btn-group-sm float-right" role="group" aria-label="Basic example">
                                            <button type="button"  class="btn btn-sm btn-primary edit-button"  data-toggle="modal" data-target="#EditCampaignModal{{$key}}" >Edit</button>
{{--                                                                          Modal--}}
                                            <div aria-hidden="true" class="modal fade " id="EditCampaignModal{{$key}}" role="dialog" tabindex="-1">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Edit Campaign</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>

                                                        <form class="edit-save-campaign"  action="{{Route('edit-campaign-save', $campaign->id)}}" method="post"  >
                                                            @csrf
                                                            <input hidden value="" class="edit_calculated_credit_per_sms" name="calculated_credit_per_sms" type="number">

                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <label class="text-left"  for="#">Campaign Name</label>
                                                                    <input required value="{{$campaign->campaign_name}}"  name="campaign_name" type="text"  class="form-control ">
                                                                </div>

                                                                <div class="form-group">
                                                                    <label class="text-left"  for="#">Sender Name</label>
                                                                    @php
                                                                        $edit_campaign_sender_name = \App\Campaign::where('user_id', \Illuminate\Support\Facades\Auth::user()->id)->select('sender_name')->distinct()->get();
                                                                    @endphp

                                                                    <div class="custom-select-div ">
{{--                                                                        <select required name="sender_name" class=" js-example-tags edit-sendername-character-count">--}}
{{--                                                                            @foreach($edit_campaign_sender_name as $edit_sender)--}}
{{--                                                                                <option @if($edit_sender->sender_name == $campaign->sender_name) selected @endif>{{$edit_sender->sender_name}}</option>--}}
{{--                                                                            @endforeach--}}
{{--                                                                        </select>--}}
                                                                        <input required value="{{$campaign->sender_name}}" name="sender_name" type="text"  class="form-control edit-sendername-character-count">
                                                                    </div>
                                                                    <div class="sender-char-msg"><span style="color: gray;font-size: 14px">Min character 3 and Max character 11</span></div>
                                                                </div>

                                                                <div class="form-group">
                                                                    <div class="d-flex justify-content-between align-items-center" >
                                                                        <label class="text-left"  for="#">Text Message</label>
                                                                        <div style="color: gray;font-size: 13px;">Characters used <span id="edit-char-div-sub" class="edit-char-div" style="text-align: right">0</span> / <span id="edit-credit-sub" class="edit-credit"> {{$campaign->calculated_credit_per_sms}} </span> credits.<br> Emoji's are not supported</div>
                                                                    </div>
                                                                    <div id="cct_embed_counts">
                                                                        <textarea required name="message_text" maxlength="612" class="form-control edit-campaign-textarea" rows="6">{{$campaign->message_text}}</textarea>
                                                                        <div class="edit-textarea-char-limit"><span style="color: gray;font-size: 14px">Max characters limit is '612'.</span></div>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label class="text-left"  for="#">Published_at</label>
{{--                                                                    @dd()--}}{{--2021-03-17T18:13--}}
                                                                    <input required value="{{\Illuminate\Support\Carbon::createFromTimeString($campaign->published_at)->format('Y-m-d\TH:i')}}"  name="published_at" type="datetime-local"  class="form-control name">
                                                                </div>

                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                <button type="submit"  class="SaveRule btn btn-primary ">Save</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
{{--                                                                          Modal End--}}
{{--                                            <a href="{{Route('campaign-published', $campaign->id)}}"><button type="submit" class="btn btn-sm btn-success ">Published</button></a>--}}
                                            <a href="{{Route('delete-campaign', $campaign->id)}}"><button type="submit" class="btn btn-sm btn-danger DeleteBtn">Delete</button></a>
                                        </div>
                                    </td>

                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
{{--    reuiqred input for js checks --}}
    <input id="current_user_credits" value="{{\Illuminate\Support\Facades\Auth::user()->credit}}" hidden>
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function(){
        // var maxLength = 160;
        // var textlen, credit;

        $('.create-campaign-textarea').keyup(function() {
            var nmbr_char = $(this).val().length;
            var textlen = nmbr_char;
            if(textlen <= 0){
                var credit = 0;
            }
            else if(textlen <= 160){
                var credit = 1;
            }else if(textlen <= 306){
                var credit = 2;
            }else if(textlen <= 460){
                var credit = 3;
            }else if(textlen <= 612){
                var credit = 4;
            }
            if(textlen == 612){
                $('.create-textarea-char-limit').html(`<div style="font-size: 14px; padding: 5px 10px;" class="alert alert-danger" role="alert">Max characters limit is '612'.</div>`)
            }else{
                $('.create-textarea-char-limit').html(`<span style="color: gray;font-size: 14px">Max characters limit is '612'.</span>`)
            }

            $('#rchars').text(textlen);
            $('#credit').text(credit);
        });

        $( "#save-campaign" ).submit(function( event ) {

            var showTextlen= $('#rchars').text();
            var showCredit= $('#credit').text();
            var current_user_credits = $('#current_user_credits').val();

            var sender_text = $(this).find('.sendername-character-count').val();

            if(parseInt(sender_text.length) >=3 && parseInt(sender_text.length) <= 11)
            {
                if(parseInt(showCredit) <= parseInt(current_user_credits) ){
                    if(confirm("Characters used "+ showTextlen +" / "+showCredit+ " credits")){
                        $('#calculated_credit_per_sms').val(parseInt(showCredit));
                    }
                    else{
                        return false;
                    }
                }else{
                    alert("Your SMS credits is not enough to create this campaign.")
                    event.preventDefault();
                }
            }else{
                $(this).find('.sender-char-msg').html(`<div style="font-size: 14px; padding: 5px 10px;" class="alert alert-danger" role="alert">Min character 3 and Max character 11 </div>`)
                event.preventDefault();
            }


        });
        $(".js-example-tags").select2({
            tags: true
        });

        $('.edit-campaign-textarea').keyup(function() {

            var nmbr_char = $(this).val().length;
            var textlen = nmbr_char;
            if(textlen <= 0){
                var credit = 0;
            }
            else if(textlen < 160){
                var credit = 1;
            }else if(textlen <= 306){
                var credit = 2;
            }else if(textlen <= 460){
                var credit = 3;
            }else if(textlen <= 612){
                var credit = 4;
            }
            if(textlen == 612){
                $('.edit-textarea-char-limit').html(`<div style="font-size: 14px; padding: 5px 10px;" class="alert alert-danger" role="alert">Max characters limit is '612'.</div>`)
            }else{
                $('.edit-textarea-char-limit').html(`<span style="color: gray;font-size: 14px">Max characters limit is '612'.</span>`)
            }

            $('.edit-char-div').text(textlen);
            $('.edit-credit').text(credit);
        });

        $( ".edit-save-campaign" ).submit(function( event ) {
            var showTextlen= $(this).find('.edit-char-div').text();
            var showCredit= $(this).find('.edit-credit').text();
            var current_user_credits = $('#current_user_credits').val();
            console.log(showTextlen)
            console.log(showCredit)
            console.log(current_user_credits)
            var sender_text = $(this).find('.edit-sendername-character-count').val();

            if(parseInt(sender_text.length) >=3 && parseInt(sender_text.length) <= 11)
            {
                if(parseInt(showCredit) <= parseInt(current_user_credits)){
                    if(confirm("Characters used "+ showTextlen +" / "+showCredit+ " credits")){
                        $(this).find('.edit_calculated_credit_per_sms').val(parseInt(showCredit));
                    }
                    else{
                        return false;
                    }
                }else{
                    alert("Your SMS credits is not enough to create this campaign.")
                    event.preventDefault();
                }
            }else{
                $(this).find('.sender-char-msg').html(`<div style="font-size: 14px; padding: 5px 10px;" class="alert alert-danger" role="alert">Min character 3 and Max character 11 </div>`)
                event.preventDefault();
            }
        });

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
