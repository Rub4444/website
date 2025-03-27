<!doctype html>
<html>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@lang('main.online_shop') @yield('title')</title>

    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&family=Lora:ital,wght@0,400;0,500;0,600;0,700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/swiper-bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/glightbox.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('img/favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('css/all.min.css') }}">

        <!--Start preloader -->
        <div id="preloader">
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
</head>
<header class="header__section header__transparent header">
    <div class="main__header header__sticky">
        <div class="container">
            <div class="main__header--inner position__relative d-flex justify-content-between align-items-center">
                <div class="main__logo d-none d-lg-block">
                    <h1 class="main__logo--title"><a class="main__logo--link" href="{{route('index')}}"><img class="main__logo--img" src="{{ asset('img/logo/nav-log.png') }}" alt="logo-img"></a></h1>
                </div>
                <div class="main_menu d-none d-lg-block">
                    <nav class="header-main-menu">
                        <ul class="d-flex">
                            @admin
                                <li class="header__menu--items"><a class="header__menu--link" href="{{route('categories.index')}}">Категории</a></li>
                                <li class="header__menu--items"><a class="header__menu--link" href="{{route('products.index')}}">Товары</a></li>
                                <li class="header__menu--items"><a class="header__menu--link" href="{{ route('properties.index') }}">Свойство</a></li>
                                <li class="header__menu--items"><a class="header__menu--link" href="{{ route('coupons.index') }}">Купоны</a></li>
                                <li class="header__menu--items"><a class="header__menu--link" href="{{ route('merchants.index') }}">Поставшики</a></li>
                                <li class="header__menu--items"><a class="header__menu--link" href="{{ route('home') }}">Заказы</a></li>
                            @endadmin
                            @guest
                                <li class="header__menu--items"><a class="header__menu--link" href="{{ route('login') }}">@lang('main.login')</a></li>
                                <li class="header__menu--items"><a class="header__menu--link" href="{{ route('register') }}">@lang('main.registration')</a></li>
                            @endguest
                            @auth
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Выйти
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            @endauth
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</header>
<body>
    @yield('content')
</body>

<footer class="text-white py-5">

    <!-- Подключение JavaScript -->
    <script src="{{ asset('js/popper.js') }}"></script>
    <script src="{{ asset('js/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('js/glightbox.min.js') }}"></script>
    <script src="{{ asset('js/script.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/jquery.min.js') }}"></script>

    <div class="container">
        <div class="row">
            <!-- О нас -->
            <div class="col-lg-3 col-md-6 mb-4">
                <h5 class="text-uppercase">
                        <a class="main__logo--link" href="">
                            <img class=" footer__logo" src="{{ asset('img/logo/footer-log.png') }}" alt="logo-img">
                        </a>
                </h5>
                <p>
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
                    <li><i class="fas fa-map-marker-alt me-2 mb-4"></i>ք․ Իջևան,փ.Մետաղագործնորի 6/7</li>
                </ul>
                <ul class="list-unstyled">
                    <li><i class="fas fa-phone-alt me-2 mb-4"></i><a href="tel:+37444464412">+374 44 464-412</a></li>
                </ul>
                <ul class="list-unstyled">
                    <li><i class="fas fa-envelope me-2 mb-4"></i><a href="ijevanmarket@gmail.com">ijevanmarket@gmail.com</a></li>
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
            <p class="mb-0">&copy; 2024 <a href="">Իջևան Մարկետ</a>. Բոլոր իրավունքները պաշտպանված են</p>
        </div>
    </div>
    <!-- Scroll top bar -->
    <button id="scroll__top"><svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="48" d="M112 244l144-144 144 144M256 120v292"/></svg></button>
</footer>
</html>


