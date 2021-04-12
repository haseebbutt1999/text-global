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
                                    <h3>Credits</h3>
                                    <button type="button" data-toggle="modal" data-target="#AddPlanModal" class="btn btn-sm btn-primary" >Add Credit</button>
                                </div>
                                {{--                              Modal--}}
                                <div aria-hidden="true" class="modal fade" id="AddPlanModal" role="dialog" tabindex="-1">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Add Credit</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>

                                            <form  action="{{Route('credits-save')}}" method="post"  >
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label class="text-left"  >Price</label>
                                                        <input   name="price" step="any" type="number"   class="form-control weight">
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="text-left"  for="#">Credit</label>
                                                        <input   name="credits" type="number"  class="form-control weight">
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="text-left"  for="#">Plan Type</label>
                                                        <select name="plan_id" class="form-control">
                                                            @foreach($plans_data as $plan)
                                                                <option value="{{$plan->id}}" >{{$plan->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="mb-2">
                                                            Status
                                                        </div>
                                                        <label class="switch" style="">
                                                            <input  name="status" type="checkbox" value="active" class="custom-control-input  status-switch">
                                                            <span class="slider round"></span>
                                                        </label>
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
                                {{--                              Modal End--}}
                            </div>
                        </div>
                    </div>


                    <div class="card-body">
                        <div id="product_append">
                            <div class="row px-3" style="overflow-x:auto;">

                                <table id="datatabled" class="table table-borderless  table-hover  table-class ">
                                    <thead class="border-0 ">

                                    <tr class="th-tr table-tr text-white text-center">
                                        <th class="font-weight-bold " >Credits</th>
                                        <th class="font-weight-bold " >Price</th>
                                        <th class="font-weight-bold " >Plan Type</th>
                                        <th class="font-weight-bold " >Status</th>
                                        <th class="font-weight-bold " style="width: 10%">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    {{--                                        @dd($users_data)--}}
                                    @foreach($credits_data as $key=>$credit)

                                        <tr class="td-text-center">

                                            <td>
                                                {{$credit->credits}}
                                            </td>
                                            <td>
                                                {{$credit->price}}
                                            </td>
                                            <?php
                                               $plan_name = \Osiset\ShopifyApp\Storage\Models\Plan::where('id', $credit->plan_id)->first();
                                            ?>
                                            <td>
                                                {{$plan_name->name}}
                                            </td>
                                            <td>
                                                {{$credit->status}}
                                            </td>

                                            <td>
                                                <div class="btn-group btn-group-sm float-right" role="group" aria-label="Basic example">
                                                    <button type="button" class="btn btn-sm btn-info "  data-toggle="modal" data-target="#EditCreditModal{{$key}}" >Edit</button>
{{--                                                                                  Modal--}}
                                                    <div aria-hidden="true" class="modal fade edit-package" id="EditCreditModal{{$key}}" role="dialog" tabindex="-1">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Edit Credit</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>

                                                                <form  action="{{Route('edit-credits-save', $credit->id)}}" method="post"  >
                                                                    @csrf
                                                                    <div class="modal-body">
                                                                        <div class="form-group">
                                                                            <label class="text-left"  >Price</label>
                                                                            <input  value="{{$credit->price}}" name="price" step="any" type="number"   class="form-control weight">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="text-left"  for="#">Credit</label>
                                                                            <input  value="{{$credit->credits}}" name="credits" type="number"  class="form-control weight">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="text-left"  for="#">Plan Type</label>
                                                                            <select name="plan_id" class="form-control">
                                                                                @foreach($plans_data as $plan)
                                                                                    <option @if($credit->plan_id == $plan->id) selected @endif value="{{$plan->id}}" >{{$plan->name}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <div class="mb-2">
                                                                                Status
                                                                            </div>
                                                                            <label class="switch" style="">
                                                                                <input @if(isset($credit->status) && $credit->status == "active")checked @endif name="status" type="checkbox" value="active" class="custom-control-input  status-switch">
                                                                                <span class="slider round"></span>
                                                                            </label>
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
{{--                                                                                  Modal End--}}
                                                    <a href="{{Route('credits-plan', $credit->id)}}"><button type="submit" class="btn btn-sm btn-danger DeleteBtn">Delete</button></a>
                                                </div>
                                            </td>

                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
{{--                                {!!  $customers_data->links() !!}--}}
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
