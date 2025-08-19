<!doctype html>
<html lang="hy">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Գտեք լավագույն ապրանքները առցանց մեր օնլայն խանութում։ Արագ առաքում, մատչելի գներ և որակյալ սպասարկում։">
    <meta name="keywords" content="Ijevan Market, Իջևան Մարկետ, խանութ Իջևանում, առաքում Իջևանում, իջևան, պատվեր, խանութ, օնլայն խանութ, առցանց գնումներ, մթերքներ, հագուստ, կենցաղային ապրանքներ, հայկական խանութ">

    <title>@lang('main.online_shop')</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/swiper-bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/glightbox.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&family=Lora:ital,wght@0,400;0,500;0,600;0,700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('img/favicon.png') }}">

    <style>
        /* Logo section styling */
        .main__logo {
            font-size: 2rem;
            font-weight: bold;
            transition: transform 0.3s ease;
        }

        .main__logo:hover {
            transform: scale(1.1);
        }

        html, body {
        height: 100%;
        margin: 0;
        padding: 0;
        overflow-y: auto; /* или: overflow-y: overlay (если поддерживается) */
        }
        /* стандартный padding для ПК */
.main__header {
    padding-top: 50px; /* или сколько нужно */
}

/* планшеты */
@media (max-width: 991.98px) {
    .main__header {
        padding-top: 60px;
    }
}

/* мобильные */
@media (max-width: 767.98px) {
    .main__header {
        padding-top: 40px;
    }
}

/* очень маленькие экраны */
@media (max-width: 575.98px) {
    .main__header {
        padding-top: 70px;
    }
}

    </style>
</head>
<body  style="background-color: white;">
    <!--Start preloader -->
    <div id="preloader" aria-live="polite">
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
    <!-- Start header area -->
    <header class="header__section header__transparent ">
        <div class="header__topbar bg__primary2 fixed-top">
            <div class="container">
                <div class="header__topbar--inner d-flex align-items-center justify-content-center">
                    <div class="header__shipping d-flex align-items-center gap-2">
                        <i class="fas fa-truck text-white me-2"></i>
                        <p class="header__shipping--text text-white mb-0">
                            <strong>@lang('main.free_delivery')</strong>
                        </p>
                    </div>
                </div>
            </div>
        </div>


        <div class="main__header">
            <div class="container">
                <div class="main__header--inner position__relative d-flex justify-content-between align-items-center">
                    <div class="offcanvas__header--menu__open d-block d-lg-none">
                        <a class="offcanvas__header--menu__open--btn" href="javascript:void(0)" data-offcanvas>
                            <svg xmlns="http://www.w3.org/2000/svg" class="ionicon offcanvas__header--menu__open--svg" viewBox="0 0 512 512"><path fill="currentColor" stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="32" d="M80 160h352M80 256h352M80 352h352"/></svg>
                            <span class="visually-hidden">Offcanvas Menu Open</span>
                        </a>
                    </div>
                    <div class="main__logo">
                        <h1 class="main__logo--title">
                            <a class="main__logo--link" href="{{route('index')}}">
                                <img class="main__logo--img" src="{{ asset('img/logo/nav-log.png') }}" alt="logo-img">
                            </a>
                        </h1>
                    </div>
                    <div class="header__account header__sticky--block">
                        <ul class="d-flex">
                            <li class="header__account--items d-none d-lg-block">
                                <a href="{{ route('wishlist') }}" class="header__account--btn">
                                    <i class="bi bi-heart-fill"></i>
                                    <span class="visually-hidden">Wishlist</span>
                                </a>
                            </li>

                            <li class="header__account--items d-none d-lg-block">
                                <a href="{{ route('shop') }}" class="header__account--btn">
                                    <i class="bi bi-bag-check-fill"></i>
                                </a>
                            </li>
                            <li class="header__account--items  header__account--search__items d-none d-lg-block">
                                <a class="header__account--btn search__open--btn" href="javascript:void(0)" data-offcanvas>
                                    <svg class="product__items--action__btn--svg" xmlns="http://www.w3.org/2000/svg" width="22.51" height="20.443" viewBox="0 0 512 512"><path d="M221.09 64a157.09 157.09 0 10157.09 157.09A157.1 157.1 0 00221.09 64z" fill="none" stroke="currentColor" stroke-miterlimit="10" stroke-width="32"/><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="32" d="M338.29 338.29L448 448"/></svg>
                                    <span class="visually-hidden">Search</span>
                                </a>
                            </li>
                            <li class="header__account--items d-none d-lg-block">
                                <a class="header__account--btn minicart__open--btn" href="{{route('basket')}}" data-offcanvas>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16.706" height="15.534" viewBox="0 0 14.706 13.534">
                                        <g  transform="translate(0 0)">
                                            <g >
                                            <path  data-name="Path 16787" d="M4.738,472.271h7.814a.434.434,0,0,0,.414-.328l1.723-6.316a.466.466,0,0,0-.071-.4.424.424,0,0,0-.344-.179H3.745L3.437,463.6a.435.435,0,0,0-.421-.353H.431a.451.451,0,0,0,0,.9h2.24c.054.257,1.474,6.946,1.555,7.33a1.36,1.36,0,0,0-.779,1.242,1.326,1.326,0,0,0,1.293,1.354h7.812a.452.452,0,0,0,0-.9H4.74a.451.451,0,0,1,0-.9Zm8.966-6.317-1.477,5.414H5.085l-1.149-5.414Z" transform="translate(0 -463.248)" fill="#fefefe"/>
                                            <path  data-name="Path 16788" d="M5.5,478.8a1.294,1.294,0,1,0,1.293-1.353A1.325,1.325,0,0,0,5.5,478.8Zm1.293-.451a.452.452,0,1,1-.431.451A.442.442,0,0,1,6.793,478.352Z" transform="translate(-1.191 -466.622)" fill="#fefefe"/>
                                            <path  data-name="Path 16789" d="M13.273,478.8a1.294,1.294,0,1,0,1.293-1.353A1.325,1.325,0,0,0,13.273,478.8Zm1.293-.451a.452.452,0,1,1-.431.451A.442.442,0,0,1,14.566,478.352Z" transform="translate(-2.875 -466.622)" fill="#fefefe"/>
                                            </g>
                                        </g>
                                    </svg>
                                </a>
                            </li>

                            @guest
                                <li class="header__account--items d-none d-lg-block">
                                    <a class="header__account--btn" href="{{route('register')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg"  width="20.51" height="19.443" viewBox="0 0 512 512"><path d="M344 144c-3.92 52.87-44 96-88 96s-84.15-43.12-88-96c-4-55 35-96 88-96s92 42 88 96z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/><path d="M256 304c-87 0-175.3 48-191.64 138.6C62.39 453.52 68.57 464 80 464h352c11.44 0 17.62-10.48 15.65-21.4C431.3 352 343 304 256 304z" fill="none" stroke="currentColor" stroke-miterlimit="10" stroke-width="32"/></svg>
                                        <span class="visually-hidden">@lang('main.registration')</span>
                                    </a>
                                </li>
                            @endguest
                            @auth
                                @admin
                                    <li class="header__account--items d-none d-lg-block">
                                        <a class="header__account--btn" href="{{route('home')}}">
                                            <svg xmlns="http://www.w3.org/2000/svg"  width="20.51" height="19.443" viewBox="0 0 512 512"><path d="M344 144c-3.92 52.87-44 96-88 96s-84.15-43.12-88-96c-4-55 35-96 88-96s92 42 88 96z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/><path d="M256 304c-87 0-175.3 48-191.64 138.6C62.39 453.52 68.57 464 80 464h352c11.44 0 17.62-10.48 15.65-21.4C431.3 352 343 304 256 304z" fill="none" stroke="currentColor" stroke-miterlimit="10" stroke-width="32"/></svg>
                                            <span class="visually-hidden">Admin Panel</span>
                                        </a>
                                    </li>
                                @else
                                    <li class="header__account--items d-none d-lg-block">
                                        <a class="header__account--btn" href="{{route('person.orders.index')}}">
                                            <svg xmlns="http://www.w3.org/2000/svg"  width="20.51" height="19.443" viewBox="0 0 512 512"><path d="M344 144c-3.92 52.87-44 96-88 96s-84.15-43.12-88-96c-4-55 35-96 88-96s92 42 88 96z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/><path d="M256 304c-87 0-175.3 48-191.64 138.6C62.39 453.52 68.57 464 80 464h352c11.44 0 17.62-10.48 15.65-21.4C431.3 352 343 304 256 304z" fill="none" stroke="currentColor" stroke-miterlimit="10" stroke-width="32"/></svg>
                                            <span class="visually-hidden">My Account</span>
                                        </a>
                                    </li>
                                @endadmin
                            @endauth
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="header__bottom bg__secondary">
            <div class="container">
                <div class="header__bottom--inner position__relative d-flex align-items-center">
                    <div class="categories__menu d-none d-lg-block" >
                        <div class="categories__menu--header text-white d-flex align-items-center">
                            <svg class="categories__list--icon" xmlns="http://www.w3.org/2000/svg" width="21.007" height="16.831" viewBox="0 0 21.007 16.831">
                                <path id="listine-dots" d="M20.66,99.786a1.036,1.036,0,0,0-.347-.13H4.227a2.013,2.013,0,0,1,0,3.012q7.988,0,15.976,0h.063a.7.7,0,0,0,.454-.162.9.9,0,0,0,.286-.452v-1.765A.861.861,0,0,0,20.66,99.786ZM3.323,101.162A1.662,1.662,0,1,1,1.662,99.5,1.661,1.661,0,0,1,3.323,101.162Zm16.99,3H4.227a2.013,2.013,0,0,1,0,3.012q7.988,0,15.976,0h.063a.7.7,0,0,0,.454-.164.9.9,0,0,0,.286-.452v-1.765a.861.861,0,0,0-.347-.5A1.082,1.082,0,0,0,20.314,104.161Zm-16.99,1.506a1.662,1.662,0,1,1-1.662-1.662A1.663,1.663,0,0,1,3.323,105.668Zm16.99,3H4.227a2.013,2.013,0,0,1,0,3.012q7.988,0,15.976,0h.063a.7.7,0,0,0,.454-.164.9.9,0,0,0,.286-.45v-1.767a.861.861,0,0,0-.347-.5A1.083,1.083,0,0,0,20.314,108.663Zm-16.99,1.506a1.662,1.662,0,1,1-1.662-1.662A1.663,1.663,0,0,1,3.323,110.169Zm16.99,2.993H4.227a2.013,2.013,0,0,1,0,3.012q7.988,0,15.976,0h.063a.687.687,0,0,0,.454-.162.9.9,0,0,0,.286-.452v-1.765a.861.861,0,0,0-.347-.5A1.035,1.035,0,0,0,20.314,113.163Zm-16.99,1.506a1.662,1.662,0,1,1-1.662-1.662A1.661,1.661,0,0,1,3.323,114.669Z" transform="translate(0 -99.5)" fill="currentColor"/>
                            </svg>
                            <span class="categories__menu--title">@lang('main.all_categories')</span>
                            <svg class="categories__arrowdown--icon" xmlns="http://www.w3.org/2000/svg" width="12.355" height="8.394" viewBox="0 0 10.355 6.394">
                                <path  d="M15.138,8.59l-3.961,3.952L7.217,8.59,6,9.807l5.178,5.178,5.178-5.178Z" transform="translate(-6 -8.59)" fill="currentColor"/>
                            </svg>
                        </div>
                        <div class="dropdown__categories--menu">
                            <ul class="d-none d-lg-block">
                                @foreach($categories as $category)
                                    <li class="categories__menu--items" >
                                        <a  class="categories__menu--link" href="{{route('category', $category->code)}}"><i class="{{ $category->icon }}" style="color: #097739;"></i>{{$category->__('name')}}</a>
                                    </li>
                                @endforeach
                            </ul>
                            <nav class="category__mobile--menu">
                                <ul class="category__mobile--menu_ul">
                                    @foreach($categories as $category)
                                        <li class="categories__menu--items" >
                                            <a class="categories__menu--link"  href="{{route('category', $category->code)}}">{{$category->__('name')}}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="header__right--info d-none d-lg-flex align-items-center ms-auto">
                        <div class="account__currency">
                                <a class="account__currency--link text-white" href="javascript:void(0)">
                                    <span>{{ $currencySymbol }}</span>
                                </a>
                            </div>
                            <div class="account__currency">
                                {{-- <a class="account__currency--link text-white" href="{{route('locale', __('main.set_lang') )}}">
                                    <span>@lang('main.set_lang')</span>
                                </a> --}}
                                <a class="account__currency--link text-white" href="{{ route('locale', app()->getLocale() === 'en' ? 'hy' : 'en') }}">
                                    <span>{{ app()->getLocale() === 'en' ? 'Հայերեն' : 'English' }}</span>
                                </a>
                            </div>
                            {{-- <div class="account__currency">
                                @auth
                                    @admin
                                        <a class="account__currency--link text-white" href="{{ route('reset') }}">
                                            <span>@lang('main.reset_all')</span>
                                        </a>
                                    @endadmin
                                @endauth
                            </div> --}}
                            <div class="suport__contact d-flex align-items-center">
                                <svg class="suport__contact--icon text-white" xmlns="http://www.w3.org/2000/svg" width="36.725" height="36.743" viewBox="0 0 36.725 36.743">
                                    <path id="headphone-alt-2" d="M28.893,18.469c-.026-2.873.1-5.754-.761-8.565-1.587-5.21-5.306-7.742-10.781-7.272-4.681.4-7.588,2.715-8.785,7.573a24.031,24.031,0,0,0,.2,13.3,11.447,11.447,0,0,0,6.254,7.253c.658.3,1.091.408,1.595-.356a3.732,3.732,0,0,1,4.38-1.334,3.931,3.931,0,1,1-4.582,5.82,2.989,2.989,0,0,0-1.782-1.466c-4.321-1.573-6.842-4.869-8.367-9.032a1.686,1.686,0,0,0-1.238-1.275,7.046,7.046,0,0,1-3.718-2.447A5.739,5.739,0,0,1,3.242,11.83,5.338,5.338,0,0,0,6.318,7.957C7.644,3.033,11.62.193,16.845.02a19.923,19.923,0,0,1,6.324.544c4.479,1.3,6.783,4.52,7.72,8.881a1.966,1.966,0,0,0,1.389,1.723,6.235,6.235,0,0,1,4.439,6.324,5.211,5.211,0,0,1-1.33,3.27,7.98,7.98,0,0,1-5.449,2.774c-.731.077-1.124-.051-1.069-.952.085-1.367.022-2.745.026-4.115Z" transform="translate(0.006 0.01)" fill="currentColor"/>
                                </svg>
                                <p class="suport__contact--text text-white">
                                    <a class="suport__contact--number" href="tel:+374 44 464 412">+374 44 464 412</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Start Offcanvas header menu -->
        <div class="offcanvas__header">
            <div class="offcanvas__inner">
                <div class="offcanvas__logo">
                    <a class="offcanvas__logo_link" href="{{route('index')}}">
                        <img src="{{ asset('img/logo/nav-log.png') }}" alt="Grocee Logo" width="158" height="36">
                    </a>
                    <button class="offcanvas__close--btn" data-offcanvas>close</button>
                </div>
                <nav class="offcanvas__menu">
                    <ul class="offcanvas__menu_ul">
                        @foreach($categories as $category)

                            <li class="offcanvas__menu_li">
                                <a class="offcanvas__menu_item" href="{{route('category', $category->code)}}">
                                    <i class="{{ $category->icon }}" style="color: #097739;"></i> {{$category->__('name')}}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    <div class="offcanvas__account--items">
                        @guest
                            <a class="offcanvas__account--items__btn d-flex align-items-center mb-3"  href="{{route('register')}}">
                                <span class="offcanvas__account--items__icon">
                                    <svg xmlns="http://www.w3.org/2000/svg"  width="20.51" height="19.443" viewBox="0 0 512 512"><path d="M344 144c-3.92 52.87-44 96-88 96s-84.15-43.12-88-96c-4-55 35-96 88-96s92 42 88 96z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/><path d="M256 304c-87 0-175.3 48-191.64 138.6C62.39 453.52 68.57 464 80 464h352c11.44 0 17.62-10.48 15.65-21.4C431.3 352 343 304 256 304z" fill="none" stroke="currentColor" stroke-miterlimit="10" stroke-width="32"/></svg>
                                </span>
                                <span class="offcanvas__account--items__label">@lang('main.registration')</span>
                            </a>
                        @endguest
                        @auth
                            @admin
                                <a class="offcanvas__account--items__btn d-flex align-items-center mb-3" href="{{route('home')}}">
                                    <span class="offcanvas__account--items__icon">
                                        <svg xmlns="http://www.w3.org/2000/svg"  width="20.51" height="19.443" viewBox="0 0 512 512"><path d="M344 144c-3.92 52.87-44 96-88 96s-84.15-43.12-88-96c-4-55 35-96 88-96s92 42 88 96z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/><path d="M256 304c-87 0-175.3 48-191.64 138.6C62.39 453.52 68.57 464 80 464h352c11.44 0 17.62-10.48 15.65-21.4C431.3 352 343 304 256 304z" fill="none" stroke="currentColor" stroke-miterlimit="10" stroke-width="32"/></svg>
                                    </span>
                                    <span class="offcanvas__account--items__label">Admin Panel</span>
                                </a>
                            @else
                                <a class="offcanvas__account--items__btn d-flex align-items-center mb-3" href="{{route('person.orders.index')}}">
                                    <span class="offcanvas__account--items__icon">
                                        <svg xmlns="http://www.w3.org/2000/svg"  width="20.51" height="19.443" viewBox="0 0 512 512"><path d="M344 144c-3.92 52.87-44 96-88 96s-84.15-43.12-88-96c-4-55 35-96 88-96s92 42 88 96z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/><path d="M256 304c-87 0-175.3 48-191.64 138.6C62.39 453.52 68.57 464 80 464h352c11.44 0 17.62-10.48 15.65-21.4C431.3 352 343 304 256 304z" fill="none" stroke="currentColor" stroke-miterlimit="10" stroke-width="32"/></svg>
                                    </span>
                                    <span class="offcanvas__account--items__label">My Account</span>
                                </a>
                            @endadmin
                        @endauth

                        <!-- Валюта -->
                        <a href="javascript:void(0)" class="offcanvas__account--items__btn d-flex align-items-center mb-3">
                            <span class="offcanvas__account--items__icon">
                                <i class="bi bi-currency-exchange fs-5" width="20.51" height="19.443"></i>
                            </span>
                            <span class="offcanvas__account--items__label">@lang('main.currency')` {{ $currencySymbol }}</span>
                        </a>

                        <!-- Смена языка -->
                        <a class="offcanvas__account--items__btn d-flex align-items-center mb-3" href="{{ route('locale', app()->getLocale() === 'en' ? 'hy' : 'en') }}">
                            <span class="offcanvas__account--items__icon">
                                <i class="bi bi-translate fs-5"></i>
                            </span>
                            <span class="offcanvas__account--items__label">{{ app()->getLocale() === 'en' ? 'Հայերեն' : 'English' }}</span>
                        </a>

                        <!-- Contact -->
                        <a href="tel:+374 44 464 412" class="offcanvas__account--items__btn d-flex align-items-center mb-3">
                            <span class="offcanvas__account--items__icon d-flex justify-content-center align-items-center">
                                <i class="bi bi-headset fs-5"></i>
                            </span>
                            <span class="offcanvas__account--items__label">
                                +374 44 464 412
                            </span>
                        </a>
                    </div>
                </nav>
            </div>
        </div>
        <!-- End Offcanvas header menu -->

        <!-- Start Offcanvas stikcy toolbar -->
        <div class="offcanvas__stikcy--toolbar">
            <ul class="d-flex justify-content-between">
                <li class="offcanvas__stikcy--toolbar__list">
                    <a class="offcanvas__stikcy--toolbar__btn" href="{{route('index')}}">
                    <span class="offcanvas__stikcy--toolbar__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" width="21.51" height="21.443" viewBox="0 0 22 17"><path fill="currentColor" d="M20.9141 7.93359c.1406.11719.2109.26953.2109.45703 0 .14063-.0469.25782-.1406.35157l-.3516.42187c-.1172.14063-.2578.21094-.4219.21094-.1406 0-.2578-.04688-.3515-.14062l-.9844-.77344V15c0 .3047-.1172.5625-.3516.7734-.2109.2344-.4687.3516-.7734.3516h-4.5c-.3047 0-.5742-.1172-.8086-.3516-.2109-.2109-.3164-.4687-.3164-.7734v-3.6562h-2.25V15c0 .3047-.11719.5625-.35156.7734-.21094.2344-.46875.3516-.77344.3516h-4.5c-.30469 0-.57422-.1172-.80859-.3516-.21094-.2109-.31641-.4687-.31641-.7734V8.46094l-.94922.77344c-.11719.09374-.24609.14062-.38672.14062-.16406 0-.30468-.07031-.42187-.21094l-.35157-.42187C.921875 8.625.875 8.50781.875 8.39062c0-.1875.070312-.33984.21094-.45703L9.73438.832031C10.1094.527344 10.5312.375 11 .375s.8906.152344 1.2656.457031l8.6485 7.101559zm-3.7266 6.50391V7.05469L11 1.99219l-6.1875 5.0625v7.38281h3.375v-3.6563c0-.3046.10547-.5624.31641-.7734.23437-.23436.5039-.35155.80859-.35155h3.375c.3047 0 .5625.11719.7734.35155.2344.211.3516.4688.3516.7734v3.6563h3.375z"></path></svg>
                        </span>
                        {{-- <span class="offcanvas__stikcy--toolbar__label">@lang('main.home')</span> --}}
                    </a>
                </li>
                <li class="offcanvas__stikcy--toolbar__list">
                    <a class="offcanvas__stikcy--toolbar__btn" href="{{route('shop')}}">
                    <span class="offcanvas__stikcy--toolbar__icon">
                        {{-- <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" width="18.51" height="17.443" viewBox="0 0 448 512"><path d="M416 32H32A32 32 0 0 0 0 64v384a32 32 0 0 0 32 32h384a32 32 0 0 0 32-32V64a32 32 0 0 0-32-32zm-16 48v152H248V80zm-200 0v152H48V80zM48 432V280h152v152zm200 0V280h152v152z"></path></svg> --}}
                        <i class="bi bi-bag-check-fill"></i>
                    </span>
                    {{-- <span class="offcanvas__stikcy--toolbar__label">@lang('main.shop')</span> --}}
                    </a>
                </li>
                <li class="offcanvas__stikcy--toolbar__list ">
                    <a class="offcanvas__stikcy--toolbar__btn search__open--btn" href="javascript:void(0)" data-offcanvas>
                        <span class="offcanvas__stikcy--toolbar__icon">
                            <svg xmlns="http://www.w3.org/2000/svg"  width="22.51" height="20.443" viewBox="0 0 512 512"><path d="M221.09 64a157.09 157.09 0 10157.09 157.09A157.1 157.1 0 00221.09 64z" fill="none" stroke="currentColor" stroke-miterlimit="10" stroke-width="32"/><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="32" d="M338.29 338.29L448 448"/></svg>
                        </span>
                    {{-- <span class="offcanvas__stikcy--toolbar__label">@lang('main.search')</span> --}}
                    </a>

                </li>
                <li class="offcanvas__stikcy--toolbar__list">
                    <a class="offcanvas__stikcy--toolbar__btn minicart__open--btn" href="{{route('basket')}}" data-offcanvas>
                        <span class="offcanvas__stikcy--toolbar__icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18.51" height="15.443" viewBox="0 0 18.51 15.443">
                            <path  d="M79.963,138.379l-13.358,0-.56-1.927a.871.871,0,0,0-.6-.592l-1.961-.529a.91.91,0,0,0-.226-.03.864.864,0,0,0-.226,1.7l1.491.4,3.026,10.919a1.277,1.277,0,1,0,1.844,1.144.358.358,0,0,0,0-.049h6.163c0,.017,0,.034,0,.049a1.277,1.277,0,1,0,1.434-1.267c-1.531-.247-7.783-.55-7.783-.55l-.205-.8h7.8a.9.9,0,0,0,.863-.651l1.688-5.943h.62a.936.936,0,1,0,0-1.872Zm-9.934,6.474H68.568c-.04,0-.1.008-.125-.085-.034-.118-.082-.283-.082-.283l-1.146-4.037a.061.061,0,0,1,.011-.057.064.064,0,0,1,.053-.025h1.777a.064.064,0,0,1,.063.051l.969,4.34,0,.013a.058.058,0,0,1,0,.019A.063.063,0,0,1,70.03,144.853Zm3.731-4.41-.789,4.359a.066.066,0,0,1-.063.051h-1.1a.064.064,0,0,1-.063-.051l-.789-4.357a.064.064,0,0,1,.013-.055.07.07,0,0,1,.051-.025H73.7a.06.06,0,0,1,.051.025A.064.064,0,0,1,73.76,140.443Zm3.737,0L76.26,144.8a.068.068,0,0,1-.063.049H74.684a.063.063,0,0,1-.051-.025.064.064,0,0,1-.013-.055l.973-4.357a.066.066,0,0,1,.063-.051h1.777a.071.071,0,0,1,.053.025A.076.076,0,0,1,77.5,140.448Z" transform="translate(-62.393 -135.3)" fill="currentColor"/>
                            </svg>
                        </span>
                        {{-- <span class="offcanvas__stikcy--toolbar__label">@lang('main.cart')</span> --}}
                    </a>
                </li>
                <li class="offcanvas__stikcy--toolbar__list">
                    <a class="offcanvas__stikcy--toolbar__btn" href="{{route('wishlist')}}">
                        <span class="offcanvas__stikcy--toolbar__icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18.541" height="15.557" viewBox="0 0 18.541 15.557">
                            <path  d="M71.775,135.51a5.153,5.153,0,0,1,1.267-1.524,4.986,4.986,0,0,1,6.584.358,4.728,4.728,0,0,1,1.174,4.914,10.458,10.458,0,0,1-2.132,3.808,22.591,22.591,0,0,1-5.4,4.558c-.445.282-.9.549-1.356.812a.306.306,0,0,1-.254.013,25.491,25.491,0,0,1-6.279-4.8,11.648,11.648,0,0,1-2.52-4.009,4.957,4.957,0,0,1,.028-3.787,4.629,4.629,0,0,1,3.744-2.863,4.782,4.782,0,0,1,5.086,2.447c.013.019.025.034.057.076Z" transform="translate(-62.498 -132.915)" fill="currentColor"/>
                            </svg>
                        </span>
                        {{-- <span class="offcanvas__stikcy--toolbar__label">@lang('main.wishlist')</span> --}}
                    </a>
                </li>
            </ul>
        </div>
        <!-- End Offcanvas stikcy toolbar -->

        <!-- Start serch box area -->
        <div class="predictive__search--box ">
            <div class="predictive__search--box__inner">
                <h2 class="predictive__search--title">@lang('main.product_search')</h2>
                <form action="{{ route('products.search') }}" method="GET" class="predictive__search--form">
                    <label>
                        <input type="text" name="query" class="predictive__search--input" placeholder="@lang('main.product_search')..." value="{{ request('query') }}">
                    </label>
                    <button type="submit" class="predictive__search--button" aria-label="search button"><svg class="product__items--action__btn--svg" xmlns="http://www.w3.org/2000/svg" width="30.51" height="25.443" viewBox="0 0 512 512"><path d="M221.09 64a157.09 157.09 0 10157.09 157.09A157.1 157.1 0 00221.09 64z" fill="none" stroke="currentColor" stroke-miterlimit="10" stroke-width="32"/><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="32" d="M338.29 338.29L448 448"/></svg>  </button>
                </form>
            </div>
            <button class="predictive__search--close__btn" aria-label="search close" data-offcanvas>
                <svg class="predictive__search--close__icon" xmlns="http://www.w3.org/2000/svg" width="40.51" height="30.443"  viewBox="0 0 512 512"><path fill="currentColor" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M368 368L144 144M368 144L144 368"/></svg>
            </button>
        </div>
        <!-- End serch box area -->
    <!-- End Offcanvas header menu -->
    </header>
    <!-- End header area -->

    @if(session()->has('success'))
        <div class="col-12">
            <div class="text-center" style=" position: relative;padding: 1rem 1rem;margin-bottom: 1rem;border: 1px solid transparent;border-radius: 0.25rem; background-color:#6bc391;color:white;">
                {{session()->get('success')}}
            </div>
        </div>
    @endif

    @if(session()->has('warning'))
        <div class="col-12">
            <div class="text-center" style=" position: relative;padding: 1rem 1rem;margin-bottom: 1rem;border: 1px solid transparent;border-radius: 0.25rem; background-color:#6bc391;color:white;">
                {{session()->get('warning')}}
            </div>
        </div>
    @endif

    <!-- Scroll top button -->
    <button id="scroll__top" style="display:none; position: fixed; bottom: 20px; right: 20px; z-index: 9999; background: #2E8B57; color: #fff; border: none; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; cursor: pointer;">
        <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512" width="20" height="20">
            <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="48" d="M112 244l144-144 144 144M256 120v292"/>
        </svg>
    </button>


    @yield('content')
    @stack('scripts')

    {{-- <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.toggle-wishlist').forEach(button => {
                button.addEventListener('click', function () {
                    const skuId = this.dataset.id;
                    const icon = this.querySelector('i');

                    fetch('/wishlist/toggle/' + skuId, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'added') {
                            this.setAttribute('aria-pressed', 'true');
                            icon.classList.remove('bi-heart', 'text-secondary');
                            icon.classList.add('bi-heart-fill', 'text-danger');
                            this.title = 'Удалить из избранного';
                        } else if (data.status === 'removed') {
                            this.setAttribute('aria-pressed', 'false');
                            icon.classList.remove('bi-heart-fill', 'text-danger');
                            icon.classList.add('bi-heart', 'text-white');
                            this.title = 'Добавить в избранное';
                        }
                    })
                    .catch(error => {
                        console.error('Ошибка при обновлении избранного:', error);
                    });
                });
            });
        });

        document.addEventListener("DOMContentLoaded", function () {
            // Toggle hidden categories
            const toggleBtn = document.getElementById('toggleCategoriesBtn');
            toggleBtn?.addEventListener('click', function () {
                const extraItems = document.querySelectorAll('.extra-category');
                const icon = this.querySelector('i');
                const isHidden = extraItems[0]?.classList.contains('d-none');

                extraItems.forEach(item => {
                    item.classList.toggle('d-none');
                });

                if (isHidden) {
                    icon.classList.replace('bi-chevron-down', 'bi-chevron-up');
                } else {
                    icon.classList.replace('bi-chevron-up', 'bi-chevron-down');
                }
            });
        });

        const scrollBtn = document.getElementById('scroll__top');

        console.log(scrollBtn);

        window.addEventListener('scroll', () => {
            if (window.scrollY > 300) {
                scrollBtn.style.display = 'flex';
            } else {
                scrollBtn.style.display = 'none';
            }
        });

        scrollBtn.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });


    </script> --}}
    <script>
        document.addEventListener("DOMContentLoaded", function () {

            // Wishlist
            document.querySelectorAll('.toggle-wishlist').forEach(button => {
                button.addEventListener('click', function () {
                    const skuId = this.dataset.id;
                    const icon = this.querySelector('i');

                    fetch('/wishlist/toggle/' + skuId, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'added') {
                            this.setAttribute('aria-pressed', 'true');
                            icon.classList.remove('bi-heart', 'text-secondary');
                            icon.classList.add('bi-heart-fill', 'text-danger');
                            this.title = 'Удалить из избранного';
                        } else if (data.status === 'removed') {
                            this.setAttribute('aria-pressed', 'false');
                            icon.classList.remove('bi-heart-fill', 'text-danger');
                            icon.classList.add('bi-heart', 'text-white');
                            this.title = 'Добавить в избранное';
                        }
                    })
                    .catch(error => {
                        console.error('Ошибка при обновлении избранного:', error);
                    });
                });
            });

            // Toggle categories
            const toggleBtn = document.getElementById('toggleCategoriesBtn');
            toggleBtn?.addEventListener('click', function () {
                const extraItems = document.querySelectorAll('.extra-category');
                const icon = this.querySelector('i');
                const isHidden = extraItems[0]?.classList.contains('d-none');

                extraItems.forEach(item => {
                    item.classList.toggle('d-none');
                });

                if (isHidden) {
                    icon.classList.replace('bi-chevron-down', 'bi-chevron-up');
                } else {
                    icon.classList.replace('bi-chevron-up', 'bi-chevron-down');
                }
            });
        });

        document.addEventListener('DOMContentLoaded', () => {
            const scrollBtn = document.getElementById('scroll__top');

            window.addEventListener('scroll', () => {
                if (window.scrollY > 300) {
                    scrollBtn.style.display = 'flex';  // Показываем кнопку
                } else {
                    scrollBtn.style.display = 'none';  // Скрываем кнопку
                }
            });

            scrollBtn.addEventListener('click', () => {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        });

    </script>


    <footer class="text-white py-5">
        <script src="{{ asset('js/popper.js') }}"></script>
        <script src="{{ asset('js/swiper-bundle.min.js') }}"></script>
        <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('js/glightbox.min.js') }}"></script>
        <script src="{{ asset('js/jquery.min.js') }}"></script>
        <script src="{{ asset('js/script.js') }}"></script>


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
                        @lang('main.how_to_use_desc')
                    </p>
                </div>

                <!-- Полезные ссылки -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="text-uppercase mb-2">@lang('main.useful_links')</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('howToUse') }}" class="text-white text-decoration-none mb-4">@lang('main.how_to_use')</a></li>
                    </ul>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('offer') }}" class="text-white text-decoration-none mb-4">@lang('main.offer')</a></li>
                    </ul>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('delivery') }}" class="text-white text-decoration-none mb-4">@lang('main.delivery_and_payment')</a></li>
                    </ul>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('about-us') }}" class="text-white text-decoration-none mb-4">@lang('main.about-us')</a></li>
                    </ul>

                    {{-- <ul class="list-unstyled">
                        <li><a href="{{ route('privacy') }}" class="text-white text-decoration-none mb-4">Գաղտնիության քաղաքականություն</a></li>
                    </ul> --}}
                </div>
                <style>
                    .fas{
                        color: #2E8B57;
                    }
                </style>
                <!-- Контакты -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="text-uppercase mb-2">@lang('main.contact')</h5>
                    <ul class="list-unstyled">
                        <li><i class="fa fa-location-arrow me-2 mb-4 text-success" style="font-size: 1.5rem;"></i>@lang('main.ijevan_market_address')</li>
                    </ul>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-phone-alt me-2 mb-4"></i><a href="tel:+37444464412">+374 44 464 412</a></li>
                    </ul>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-envelope me-2 mb-4"></i><a href="ijevanmarket@gmail.com">ijevanmarket@gmail.com</a></li>
                    </ul>
                </div>

                <!-- Подписка на новости -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="text-uppercase mb-2">@lang('main.subscribe_to_the_news')</h5>
                    <form class="d-flex">
                        <input type="email" class="form-control me-2" placeholder="@lang('basket.email')">
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
                <p class="mb-0">&copy; 2025 <a href="">@lang('main.online_shop')</a>. @lang('main.all_right')</p>
            </div>
        </div>
    </footer>
</body>
</html>
