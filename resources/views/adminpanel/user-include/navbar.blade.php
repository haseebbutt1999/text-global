<body>


<nav class="navbar navbar-expand-lg " style="background: #4C74B8">
    <div>
        <a  style="margin-left: 10px;" class="navbar-brand" href="{{Route('home')}}"><img style="max-height: 65px;" src="{{asset('text-global-uk.jpg')}}"></a>
    </div>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse custom-nav-collapse" id="navbarSupportedContent">
        <div class="text-center">
            <ul class="custom-ul navbar-nav mr-auto text-center">

                <li class="nav-item active">
                    <a class="nav-link" href="{{route('home')}}">Dashboard<span class="sr-only"></span></a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="{{route('customers')}}">Customers<span class="sr-only"></span></a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="{{route('sms-triggers-index')}}">SMS Triggers<span class="sr-only"></span></a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="{{route('countries')}}">Countries<span class="sr-only"></span></a>
                </li>
                {{--                    <li class="nav-item active">--}}
                {{--                        <a class="nav-link" href="{{route('user-plans')}}">Plans<span class="sr-only"></span></a>--}}
                {{--                    </li>--}}
                {{--                    <li class="nav-item active">--}}
                {{--                        <a class="nav-link" href="{{route('sms-campaign-index')}}">SMS Campaigns<span class="sr-only"></span></a>--}}
                {{--                    </li>--}}

                <li class="nav-item active">
                    <a class="nav-link" href="{{route('user-shop-detail')}}">Settings<span class="sr-only"></span></a>
                </li>
            </ul>
        </div>
    </div>


    <div style="width: auto; " class="text-white mr-2 d-flex">

        <div style="
    color: white;background-color: #F4B340 ;
    padding: 5px 25px;    font-weight: 500;
    text-align: center;">{{"Credits - ". Auth::user()->credit }}</div>
        <a style="font-weight: 500;" class="btn btn-success text-white"  href="{{route('user-plans')}}">
            Buy SMS Bundles
        </a>
    </div>


</nav>


{{--Nav Bar End--}}
