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
                                    <h3>Plans</h3>
                                    <button type="button" data-toggle="modal" data-target="#AddPlanModal" class="btn btn-sm btn-primary" >Add Plan</button>
                                </div>
                                {{--                              Modal--}}
                                <div aria-hidden="true" class="modal fade" id="AddPlanModal" role="dialog" tabindex="-1">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Add Plan</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>

                                            <form  action="{{Route('plan-save')}}" method="post"  >
                                                @csrf

                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label class="text-left"  for="#">Type</label>
                                                        <select name="type" class="form-control">
                                                            <option value="RECURRING" selected>RECURRING</option>
{{--                                                            <option value="ONETIME ">ONETIME </option>--}}
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="text-left"  for="#">Name</label>
                                                        <input  value="" name="name" type="text"  class="form-control name">
                                                    </div>


                                                    <div class="form-group">
                                                        <label class="text-left"  >User Displayed Price</label>
                                                        <input   name="price" step="any" type="number"   class="form-control weight">
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="text-left"  for="#">Credit</label>
                                                        <input   name="credit" type="number"  class="form-control weight">
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="text-left"  for="#">Capped Amount</label>
                                                        <input   name="capped_amount" step="any" type="number"  class="form-control weight">
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="text-left"  for="#">Interval</label>
                                                        <select name="interval"  class="form-control">
                                                            <option value="EVERY_30_DAYS  " selected>EVERY_30_DAYS  </option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="text-left"  for="#">Terms</label>
                                                        <textarea   name="terms"   class="form-control weight"></textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="text-left"  for="#">Trial Days</label>
                                                        <input   name="trial_days" type="number"  class="form-control weight">
                                                    </div>
{{--                                                    <div class="form-group">--}}
{{--                                                        <label class="text-left"  for="#">Select Subscription Status</label>--}}
{{--                                                        <select class="form-control " name="status" >--}}
{{--                                                            <option selected value="active">Active</option>--}}
{{--                                                            <option  value="inactive">Inactive</option>--}}
{{--                                                        </select>--}}
{{--                                                    </div>--}}

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
                                        <th class="font-weight-bold " >Type</th>
                                        <th class="font-weight-bold " >Name</th>
                                        <th class="font-weight-bold " >Price</th>
                                        <th class="font-weight-bold " >User Displayed Price</th>
                                        <th class="font-weight-bold " >Credit</th>
                                        <th class="font-weight-bold " >Interval</th>
                                        <th class="font-weight-bold " >Capped Amount</th>
                                        <th class="font-weight-bold " >Terms</th>
                                        <th class="font-weight-bold " >Trial Days</th>
{{--                                        <th class="font-weight-bold " >Status</th>--}}
                                        <th class="font-weight-bold " style="width: 10%">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    {{--                                        @dd($users_data)--}}
                                    @foreach($plans_data as $key=>$plan)

                                        <tr class="td-text-center">
                                            <td>
                                                {{$plan->type}}
                                            </td>
                                            <td>
                                                {{ $plan->name }}
                                            </td>
                                            <td>
                                                {{$plan->price}}
                                            </td>
                                            <td>
                                                {{$plan->user_price}}
                                            </td>
                                            <td>
                                                {{$plan->credit}}
                                            </td>
                                            <td>
                                                {{$plan->interval}}
                                            </td>
                                            <td>
                                                {{$plan->capped_amount}}
                                            </td>
                                            <td>
                                                {{$plan->terms}}
                                            </td>
                                            <td>
                                                {{$plan->trial_days}}
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm float-right" role="group" aria-label="Basic example">
                                                    <button type="button" class="btn btn-sm btn-info edit-package"  data-toggle="modal" data-target="#EditPlanModal{{$key}}" >Edit</button>
                                                    {{--                              Modal--}}
                                                    <div aria-hidden="true" class="modal fade edit-package" id="EditPlanModal{{$key}}" role="dialog" tabindex="-1">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Edit Plan</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>

                                                                <form  action="{{Route('edit-plan-save', $plan->id)}}" method="post"  >
                                                                    @csrf
                                                                    <div class="modal-body">
                                                                        <div class="form-group">
                                                                            <label class="text-left"  for="#">Type</label>
                                                                            <select name="type"  class="form-control">

                                                                                <option value="RECURRING" selected > RECURRING</option>
{{--                                                                                <option value="ONETIME " @if(isset($plan->type) && $plan->type == 'ONETIME') selected @endif>ONETIME </option>--}}
                                                                            </select>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="text-left"  for="#">Name</label>
                                                                            <input  value="{{$plan->name}}" name="name" type="text"  class="form-control name">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="text-left"  >User Displayed Price</label>
                                                                            <input value="{{$plan->user_price}}"  name="price" step="any" type="number"   class="form-control weight">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="text-left"  for="#">Credit</label>
                                                                            <input  value="{{$plan->credit}}" name="credit" type="number"  class="form-control weight">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="text-left"  for="#">Capped Amount</label>
                                                                            <input value="{{$plan->capped_amount}}"  name="capped_amount" step="any" type="number"  class="form-control weight">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="text-left"  for="#">Interval</label>
                                                                            <select name="interval"  class="form-control">
                                                                                <option value="EVERY_30_DAYS  " selected>EVERY_30_DAYS  </option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="text-left"  for="#">Terms</label>
                                                                            <textarea   name="terms"   class="form-control weight">{{$plan->terms}}</textarea>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="text-left"  for="#">Trial Days</label>
                                                                            <input  value="{{$plan->trial_days}}" name="trial_days" type="number"  class="form-control weight">
                                                                        </div>
{{--                                                                        <div class="form-group">--}}
{{--                                                                            <label class="text-left"  for="#">Select Subscription Status</label>--}}
{{--                                                                            <select class="form-control " name="status" >--}}
{{--                                                                                <option selected value="active">Active</option>--}}
{{--                                                                                <option  value="inactive">Inactive</option>--}}
{{--                                                                            </select>--}}
{{--                                                                        </div>--}}

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
                                                    <a href="{{Route('delete-plan', $plan->id)}}"><button type="submit" class="btn btn-sm btn-danger DeleteBtn">Delete</button></a>
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
