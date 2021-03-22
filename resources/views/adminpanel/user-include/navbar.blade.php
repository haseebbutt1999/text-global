<body>


<nav class="navbar navbar-expand-lg " style="background: #202e78;">
    <a  style="margin-left: 10px;" class="navbar-brand" href="{{Route('home')}}">TextGlobalShopify</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse custom-nav-collapse" id="navbarSupportedContent">
        <div class="text-center">
                <ul class="custom-ul navbar-nav mr-auto text-center">
                    <li class="nav-item active">
                        <a class="nav-link" href="{{route('user-shop-detail')}}">Settings<span class="sr-only"></span></a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="{{route('customers')}}">Customers<span class="sr-only"></span></a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="{{route('countries')}}">Countries<span class="sr-only"></span></a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="{{route('user-plans')}}">Plans<span class="sr-only"></span></a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="{{route('sms-campaign-index')}}">SMS Campaigns<span class="sr-only"></span></a>
                    </li>
                </ul>
        </div>
    </div>


    <div style="width: 10%; " class="text-white mr-2">
        <div class="bg-primary" style="
    background: white;
    border-radius: 10px;
    padding: 5px;
    text-align: center;">{{"Credits - ". Auth::user()->credit }}</div>
    </div>


</nav>


{{--Nav Bar End--}}
