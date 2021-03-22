@extends('adminpanel.user-layout.default')
@section('content')

    <!-- POST CARDS -->
<div class="container">
    <div class="row pt-2 p-5">
        @foreach($plans_data as $plan)
            @if($plan->on_install != 1)
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
                            <a class="subscribe" @if(\Illuminate\Support\Facades\Auth::user()->credit <= 0)  href="{{ route('billing', ['plan' => $plan->id]) }}" @else check="0" @endif ><button type="button" class="btn btn-lg btn-block btn-outline-primary">Subscribe</button></a>

                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</div>

    <!-- END POST CARDS -->

    </div>

@endsection


