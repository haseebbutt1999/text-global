<body>


<nav class="navbar navbar-expand-lg " style="background: #202e78;">
    <a  style="margin-left: 10px;" class="navbar-brand" href="{{Route('home')}}">TextGlobalShopify</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse admin-nav navbar-collapse custom-nav-collapse" id="navbarSupportedContent">
        <div class="text-center">
            <ul class="custom-ul navbar-nav mr-auto text-center">
                <li class="nav-item active">
                    <a class="nav-link" href="{{route('shops')}}">Shops<span class="sr-only"></span></a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="{{route('plans')}}">Bundles<span class="sr-only"></span></a>
                </li>
{{--                <li class="nav-item active">--}}
{{--                    <a class="nav-link" href="{{route('credits')}}">Credits<span class="sr-only"></span></a>--}}
{{--                </li>--}}
            </ul>
        </div>
    </div>
    <div class="dropdown">
        @if(auth()->user()->role == 'admin')
            <button class="btn btn-sm  dropdown-toggle text-white btn-primary pb-2 px-3 mr-2" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Admin
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="{{route('admin-logout')}}">Logout</a>
            </div>
        @endif
    </div>
</nav>


{{--Nav Bar End--}}
