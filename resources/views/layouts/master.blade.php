<title>@yield('title')</title>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@lang('main.online_shop'): @yield('title')</title>

    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <script src="/js/jquery.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/starter-template.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="nav navbar-nav">
        <li>
          <a @if(Route::currentRouteNamed('index')) class="active nav-link" @endif class="nav-link" aria-current="page" href="{{route('index')}}">Home</a>
        </li>
        <li>
          <a @if(Route::currentRouteNamed('categor*')) class="active nav-link" @endif class="nav-link" aria-current="page" href="{{route('categories')}}">All Categories</a>
        </li>
        <li>
          <a @if(Route::currentRouteNamed('basket*')) class="active nav-link" @endif class="nav-link " aria-current="page" href="{{route('basket')}}">Basket</a>
        </li>
        <li>
            <a href="{{ route('reset') }}"
            class="nav-link @if(Route::currentRouteNamed('reset')) active @endif"
            aria-current="page">
                Reset
            </a>
        </li>

        @guest
            <li>
            <a @if(Route::currentRouteNamed('login')) class="active nav-link" @endif class="nav-link " aria-current="page" href="{{route('login')}}">Log In</a>
            </li>
            <li>
            <a @if(Route::currentRouteNamed('register')) class="active nav-link" @endif class="nav-link " aria-current="page" href="{{route('register')}}">Registration</a>
            </li>
        @endguest
        @auth
            @admin
                <li class="nav-item">
                    <a class="nav-link " aria-current="page" href="{{route('home')}}">Admin Panel</a>
                </li>
            @else
                <li class="nav-item">
                    <a class="nav-link " aria-current="page" href="{{route('person.orders.index')}}">My Account</a>
                </li>
            @endadmin
                <li class="nav-item">
                    <a class="nav-link " aria-current="page" href="{{route('get-logout')}}">Log Out</a>
                </li>
        @endauth
      </ul>
    </div>
  </div>
</nav>

<div class="container">
    <div class="starter-template">
    @if(session()->has('success'))
        <p class="alert alert-success">{{session()->get('success')}}</p>
    @endif

    @if(session()->has('warning'))
        <p class="alert alert-warning">{{session()->get('warning')}}</p>
    @endif

    @yield('content')
    </div>
</div>

<footer>
    <div class="container">
        <div class="row">

        </div>
    </div>
</footer>

</body>
</html>
