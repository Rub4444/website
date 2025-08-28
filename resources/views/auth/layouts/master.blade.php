<!doctype html>
<html lang="hy">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@lang('main.online_shop')</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&family=Lora:ital,wght@0,400;0,500;0,600;0,700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <link rel="stylesheet" href="{{ asset('css/swiper-bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/glightbox.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('img/favicon.png') }}">

    <style>
        .navbar-brand img {
            max-height: 40px;
        }

        /* Logo section styling */
        .main__logo {
            font-size: 2rem;
            font-weight: bold;
            transition: transform 0.3s ease;
        }

        .main__logo:hover {
            transform: scale(1.1);
        }

        /* Navbar menu styling */
        .header-main-menu ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            gap: 20px;
        }

        .header__menu--items {
            margin: 0;
        }

        .header__menu--link {
            font-size: 2rem;
            text-decoration: none;
            padding: 10px 15px;
            display: block;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <!--Start preloader -->
    <div id="preloader" role="presentation" aria-hidden="true">
        <div id="ctn-preloader" class="ctn-preloader">
            <div class="animation-preloader">
                <div class="spinner"></div>
                <div class="txt-loading">
                    <span data-text-preloader="Բ" class="letters-loading">
                        Բ
                    </span>

                    <span data-text-preloader="Ե" class="letters-loading">
                        Ե
                    </span>

                    <span data-text-preloader="Ռ" class="letters-loading">
                        Ռ
                    </span>

                    <span data-text-preloader="Ն" class="letters-loading">
                        Ն
                    </span>

                    <span data-text-preloader="ՈՒ" class="letters-loading">
                        ՈԻ
                    </span>

                    <span data-text-preloader="Մ" class="letters-loading">
                        Մ
                    </span>
                </div>
            </div>
            <div class="loader-section section-left"></div>
            <div class="loader-section section-right"></div>
        </div>
    </div>
    <!-- End preloader -->
    <header class="bg-white border-bottom shadow-sm">
        <nav class="navbar navbar-expand-lg navbar-light bg-white py-3">
            <div class="container">
                <!-- Logo -->
                <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="{{ route('index') }}">
                    <img src="{{ asset('img/logo/nav-log.png') }}" alt="Logo" height="40">
                </a>

                <!-- Burger button -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
                    aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Menu -->
                <div class="collapse navbar-collapse" id="mainNavbar">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center gap-lg-3">

                        @admin
                            <li class="nav-item"><a class="nav-link" href="{{route('categories.index')}}">Կատեգորիաներ</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{route('products.index')}}">Ապրանքներ</a></li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('products.tree') }}">Ապրանքների ծառ</a>
                            </li>
                            <li class="nav-item"><a class="nav-link" href="{{route('properties.index')}}">Հատկանիշներ</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{route('coupons.index')}}">Կուպոններ</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{route('merchants.index')}}">Մատակարարներ</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{route('home')}}">Պատվերներ</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{route('banners.index')}}">Բաններներ</a></li>
                        @endadmin

                        @guest
                            {{-- <div class="account__currency mb-2">
                                <a class="account__currency--link " href="{{route('locale', __('main.set_lang') )}}">
                                    <span>@lang('main.set_lang')</span>
                                </a>
                            </div> --}}

                            <li class="nav-item mb-2"><a class="btn btn-outline-primary" href="{{ route('login') }}">@lang('main.login')</a></li>
                            <li class="nav-item mb-2"><a class="btn btn-primary ms-lg-2" href="{{ route('register') }}">@lang('main.registration')</a></li>
                        @endguest

                        @auth
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown"
                                role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-person-circle me-1"></i>{{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                    <li>
                                        <a class="dropdown-item" href="{{route('profile.index')}}">@lang('main.my_account')</a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            @lang('main.logout')
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    @yield('content')

    <footer class="text-white py-5">

        <!-- Подключение JavaScript -->
        <script src="{{ asset('js/popper.js') }}"></script>
        <script src="{{ asset('js/swiper-bundle.min.js') }}"></script>
        <script src="{{ asset('js/glightbox.min.js') }}"></script>
        <script src="{{ asset('js/script.js') }}"></script>
        <script src="{{ asset('js/jquery.min.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


        <div class="container">
            <div class="row">
                <!-- О нас -->
                <div class="col-lg-3 col-md-6 mb-4 d-none d-lg-block">
                    <h5 class="text-uppercase">
                            <a class="main__logo--link" href="">
                                <img class=" footer__logo" src="{{ asset('img/logo/footer-log.png') }}" alt="logo-img">
                            </a>
                    </h5>
                    <p class="d-none d-lg-block">
                        Մեր կայքում կգտնեք մթերային ապրանքներ, կենցաղային պարագաներ, հագուստ և զարդեր:
                        Գործում է <a href="">առաքում</a> քաղաք Իջևանի տարածքում։
                    </p>
                </div>

                <!-- Полезные ссылки -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="text-uppercase">Օգտակար հղումներ</h5>
                    <ul class="list-unstyled">
                        <li><a href="" class="text-white text-decoration-none mb-4">Գլխավոր</a></li>
                    </ul>
                    <ul class="list-unstyled">
                        <li><a href="" class="text-white text-decoration-none mb-4">Ինչպես օգտվել</a></li>
                    </ul>
                    <ul class="list-unstyled">
                        <li><a href="" class="text-white text-decoration-none mb-4">Օֆերտա</a></li>
                    </ul>
                    <ul class="list-unstyled">
                        <li><a href="" class="text-white text-decoration-none mb-4">Առաքում և վճարում</a></li>
                    </ul>
                    <ul class="list-unstyled">
                        <li><a href="" class="text-white text-decoration-none mb-4">Գաղտնիության քաղաքականություն</a></li>
                    </ul>
                </div>
                <style>
                    .fas{
                        color: #2E8B57;
                    }
                </style>
                <!-- Контакты -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="text-uppercase">Կապ</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-map-marker-alt me-2 mb-4"></i>ք․ Իջևան,փ.Մետաղագործների 6/7</li>
                    </ul>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-phone-alt me-2 mb-4"></i><a href="tel:+37444464412">+374 44 464-412</a></li>
                    </ul>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-envelope me-2 mb-4"></i><a href="mailto:ijevanmarket@gmail.com">ijevanmarket@gmail.com</a></li>
                    </ul>
                </div>

                <!-- Подписка на новости -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="text-uppercase">Բաժանորդագրվիր նորությունների համար</h5>
                    <form class="d-flex">
                        <input type="email" class="form-control me-2" placeholder="Ձեր էլ․ հասցեն">
                        <button type="submit" class="btn btn-primary">OK</button>
                    </form>
                    <div class="mt-3">
                        <ul>
                            <li>
                                <a class="quickview__social--icon" target="_blank" href="https://www.facebook.com/profile.php?id=100040392503868&mibextid=JRoKGi">
                                    <svg  xmlns="http://www.w3.org/2000/svg" width="7.667" height="16.524" viewBox="0 0 7.667 16.524">
                                        <path  data-name="Path 237" d="M967.495,353.678h-2.3v8.253h-3.437v-8.253H960.13V350.77h1.624v-1.888a4.087,4.087,0,0,1,.264-1.492,2.9,2.9,0,0,1,1.039-1.379,3.626,3.626,0,0,1,2.153-.6l2.549.019v2.833h-1.851a.732.732,0,0,0-.472.151.8.8,0,0,0-.246.642v1.719H967.8Z" transform="translate(-960.13 -345.407)" fill="currentColor"/>
                                    </svg>
                                    <span class="visually-hidden">Facebook</span>
                                </a>
                            </li>
                            <li>
                                <a class="quickview__social--icon" target="_blank" href="https://www.instagram.com/ijevanmarket/profilecard/?igsh=MXFwd2YycXA2b3U3MA==">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="17.497" height="17.492" viewBox="0 0 19.497 19.492">
                                        <path  data-name="Icon awesome-instagram" d="M9.747,6.24a5,5,0,1,0,5,5A4.99,4.99,0,0,0,9.747,6.24Zm0,8.247A3.249,3.249,0,1,1,13,11.238a3.255,3.255,0,0,1-3.249,3.249Zm6.368-8.451A1.166,1.166,0,1,1,14.949,4.87,1.163,1.163,0,0,1,16.115,6.036Zm3.31,1.183A5.769,5.769,0,0,0,17.85,3.135,5.807,5.807,0,0,0,13.766,1.56c-1.609-.091-6.433-.091-8.042,0A5.8,5.8,0,0,0,1.64,3.13,5.788,5.788,0,0,0,.065,7.215c-.091,1.609-.091,6.433,0,8.042A5.769,5.769,0,0,0,1.64,19.341a5.814,5.814,0,0,0,4.084,1.575c1.609.091,6.433.091,8.042,0a5.769,5.769,0,0,0,4.084-1.575,5.807,5.807,0,0,0,1.575-4.084c.091-1.609.091-6.429,0-8.038Zm-2.079,9.765a3.289,3.289,0,0,1-1.853,1.853c-1.283.509-4.328.391-5.746.391S5.28,19.341,4,18.837a3.289,3.289,0,0,1-1.853-1.853c-.509-1.283-.391-4.328-.391-5.746s-.113-4.467.391-5.746A3.289,3.289,0,0,1,4,3.639c1.283-.509,4.328-.391,5.746-.391s4.467-.113,5.746.391a3.289,3.289,0,0,1,1.853,1.853c.509,1.283.391,4.328.391,5.746S17.855,15.705,17.346,16.984Z" transform="translate(0.004 -1.492)" fill="currentColor"></path>
                                    </svg>
                                    <span class="visually-hidden">Instagram</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <hr class="text-white">
            <div class="text-center">
                <p class="mb-0">&copy; 2025 <a href="">Իջևան Մարկետ</a>. Բոլոր իրավունքները պաշտպանված են</p>
            </div>
        </div>
    </footer>
</body>

</html>


