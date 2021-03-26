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

                                    <form  action="{{Route('sms-campaign-save')}}" method="post"  >
                                        @csrf
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label class="text-left"  for="#">Campaign Name</label>
                                                <input  name="campaign_name" type="text"  class="form-control ">
                                            </div>

                                            <div class="form-group">
                                                <label class="text-left"  for="#">Sender Name</label>
                                                <input  value="{{\Illuminate\Support\Facades\Auth::user()->shopdetail->sender_name}}" name="sender_name" type="text"  class="form-control name">
                                            </div>

                                            <div class="form-group">
                                                <label class="text-left"  for="#">Text Message</label>
                                                <div id="cct_embed_counts">
                                                    <textarea maxlength="160" class="form-control create-campaign-textarea"  name="message_text"  rows="4"></textarea>
                                                    <span id="rchars">160</span> Character(s) Remaining
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="text-left"  for="#">Published_at</label>
                                                <input  name="published_at" type="datetime-local"  class="form-control name">
                                            </div>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit"  class="SaveRule btn btn-primary ">Send</button>
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
                                <th class="font-weight-bold " >Campaign Name</th>
                                <th class="font-weight-bold " >Sender Name</th>
                                <th class="font-weight-bold " >Message Text</th>
                                <th class="font-weight-bold " >Published</th>
                                <th class="font-weight-bold " >Published_at</th>
                                <th class="font-weight-bold " >Status</th>
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
                                    <td>
                                        {{$campaign->message_text}}
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
                                            <button type="button" class="btn btn-sm btn-primary "  data-toggle="modal" data-target="#EditCampaignModal{{$key}}" >Edit</button>
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

                                                        <form  action="{{Route('edit-campaign-save', $campaign->id)}}" method="post"  >
                                                            @csrf
                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <label class="text-left"  for="#">Campaign Name</label>
                                                                    <input value="{{$campaign->campaign_name}}"  name="campaign_name" type="text"  class="form-control ">
                                                                </div>

                                                                <div class="form-group">
                                                                    <label class="text-left"  for="#">Sender Name</label>
                                                                    <input  value="{{$campaign->sender_name}}" name="sender_name" type="text"  class="form-control name">
                                                                </div>

                                                                <div class="form-group">
                                                                    <label class="text-left"  for="#">Text Message</label>
                                                                    <div id="cct_embed_counts">
                                                                        <textarea maxlength="160" name="message_text" class="form-control edit-campaign-textarea" rows="4">{{$campaign->message_text}}</textarea>
                                                                        <span class="edit-char-div">160</span> Character(s) Remaining

                                                                    </div>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label class="text-left"  for="#">Published_at</label>
{{--                                                                    @dd()--}}{{--2021-03-17T18:13--}}
                                                                    @php


                                                                    @endphp
                                                                    <input value="{{\Illuminate\Support\Carbon::createFromTimeString($campaign->published_at)->format('Y-m-d\TH:i')}}"  name="published_at" type="datetime-local"  class="form-control name">
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
