
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

<!-- Start header area -->
<header class="header__section header__transparent header">
        <div class="main__header header__sticky">
            <div class="container">
                <div class="main__header--inner position__relative d-flex justify-content-between align-items-center">
                    <div class="offcanvas__header--menu__open d-none d-lg-block">
                        <a class="offcanvas__header--menu__open--btn" href="javascript:void(0)" data-offcanvas>
                            <svg xmlns="http://www.w3.org/2000/svg" class="ionicon offcanvas__header--menu__open--svg" viewBox="0 0 512 512"><path fill="currentColor" stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="32" d="M80 160h352M80 256h352M80 352h352"/></svg>
                            <span class="visually-hidden">Offcanvas Menu Open</span>
                        </a>
                    </div>
                    <div class="main__logo d-none d-lg-block">
                        <h1 class="main__logo--title"><a class="main__logo--link" href="{{ route('home') }}"><img class="main__logo--img" src="{{ asset('img/logo/nav-log.png') }}" alt="logo-img"></a></h1>
                    </div>
                    <div class="main_menu d-none d-lg-block">
                        <nav class="header-main-menu">
                            <ul>
                                <li><a href="{{ route('work') }}">Աշխատանք</a></li>
                                <li><a href="{{ route('news') }}">Նորություններ</a></li>
                                <li><a href="{{ route('contact')}}">Կոնտակտներ</a></li>
                                <li><a href="{{ route('delivery-and-pay') }}">Առաքում և վճարում</a></li>
                            </ul>
                        </nav>
                    </div>
                    <ul class="d-flex">
                        <li class="header__account--items  header__account--search__items  d-none d-lg-block">
                            <a class="header__account--btn search__open--btn" href="javascript:void(0)" data-offcanvas>
                                <svg class="product__items--action__btn--svg" xmlns="http://www.w3.org/2000/svg" width="22.51" height="20.443" viewBox="0 0 512 512"><path d="M221.09 64a157.09 157.09 0 10157.09 157.09A157.1 157.1 0 00221.09 64z" fill="none" stroke="currentColor" stroke-miterlimit="10" stroke-width="32"/><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="32" d="M338.29 338.29L448 448"/></svg>
                                <span class="visually-hidden">Search</span>
                            </a>
                        </li>
                        <li class="header__account--items  d-none d-lg-block">
                                <a class="header__account--btn minicart__open--btn" href="javascript:void(0)" data-offcanvas>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16.706" height="15.534" viewBox="0 0 14.706 13.534">
                                        <g  transform="translate(0 0)">
                                          <g >
                                            <path  data-name="Path 16787" d="M4.738,472.271h7.814a.434.434,0,0,0,.414-.328l1.723-6.316a.466.466,0,0,0-.071-.4.424.424,0,0,0-.344-.179H3.745L3.437,463.6a.435.435,0,0,0-.421-.353H.431a.451.451,0,0,0,0,.9h2.24c.054.257,1.474,6.946,1.555,7.33a1.36,1.36,0,0,0-.779,1.242,1.326,1.326,0,0,0,1.293,1.354h7.812a.452.452,0,0,0,0-.9H4.74a.451.451,0,0,1,0-.9Zm8.966-6.317-1.477,5.414H5.085l-1.149-5.414Z" transform="translate(0 -463.248)" fill="#fefefe"/>
                                            <path  data-name="Path 16788" d="M5.5,478.8a1.294,1.294,0,1,0,1.293-1.353A1.325,1.325,0,0,0,5.5,478.8Zm1.293-.451a.452.452,0,1,1-.431.451A.442.442,0,0,1,6.793,478.352Z" transform="translate(-1.191 -466.622)" fill="#fefefe"/>
                                            <path  data-name="Path 16789" d="M13.273,478.8a1.294,1.294,0,1,0,1.293-1.353A1.325,1.325,0,0,0,13.273,478.8Zm1.293-.451a.452.452,0,1,1-.431.451A.442.442,0,0,1,14.566,478.352Z" transform="translate(-2.875 -466.622)" fill="#fefefe"/>
                                          </g>
                                        </g>
                                    </svg>
                                    <span class="items__count">3</span>
                                </a>
                            </li>
                        <li class="header__account--items d-none d-lg-block">
                            <a class="header__account--btn" href="{{ route('my-account') }}">
                                <svg xmlns="http://www.w3.org/2000/svg"  width="20.51" height="19.443" viewBox="0 0 512 512"><path d="M344 144c-3.92 52.87-44 96-88 96s-84.15-43.12-88-96c-4-55 35-96 88-96s92 42 88 96z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/><path d="M256 304c-87 0-175.3 48-191.64 138.6C62.39 453.52 68.57 464 80 464h352c11.44 0 17.62-10.48 15.65-21.4C431.3 352 343 304 256 304z" fill="none" stroke="currentColor" stroke-miterlimit="10" stroke-width="32"/></svg>
                                <span class="visually-hidden">My account</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Второе меню -->
        <div class="container">
            <nav class="offcanvas__menu2">
                <div class="offcanvas__logo">
                    <a class="offcanvas__logo_link" href="{{ route('home') }}">
                        <img src="{{ asset('img/logo/nav-log.png') }}" alt="Grocee Logo" width="158" height="36">
                    </a>
                    <button class="offcanvas__menu2--close">Close</button>
                </div>
                <ul class="offcanvas__menu2_ul">
                    <li class="offcanvas__menu2_li"><a class="offcanvas__menu2_item" href="{{ route('work') }}">Աշխատանք</a></li>
                    <li class="offcanvas__menu2_li"><a class="offcanvas__menu2_item" href="{{ route('news') }}">Նորություններ</a></li>
                    <li class="offcanvas__menu2_li"><a class="offcanvas__menu2_item" href="{{ route('contact') }}">Կոնտակտներ</a></li>
                    <li class="offcanvas__menu2_li"><a class="offcanvas__menu2_item" href="{{ route('delivery-and-pay') }}">Առաքում և վճարում</a></li>
                </ul>
            </nav>
        </div>
        <!-- Start serch box area -->
        <div class="predictive__search--box ">
            <div class="predictive__search--box__inner">
                <h2 class="predictive__search--title">Ապրանքների որոնում</h2>
                <form class="predictive__search--form" action="#">
                    <label>
                        <input class="predictive__search--input" placeholder="Ապրանքների որոնում" type="text">
                    </label>
                    <button class="predictive__search--button" aria-label="search button"><svg class="product__items--action__btn--svg" xmlns="http://www.w3.org/2000/svg" width="30.51" height="25.443" viewBox="0 0 512 512"><path d="M221.09 64a157.09 157.09 0 10157.09 157.09A157.1 157.1 0 00221.09 64z" fill="none" stroke="currentColor" stroke-miterlimit="10" stroke-width="32"/><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="32" d="M338.29 338.29L448 448"/></svg>  </button>
                </form>
            </div>
            <button class="predictive__search--close__btn" aria-label="search close" data-offcanvas>
                <svg class="predictive__search--close__icon" xmlns="http://www.w3.org/2000/svg" width="40.51" height="30.443"  viewBox="0 0 512 512"><path fill="currentColor" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M368 368L144 144M368 144L144 368"/></svg>
            </button>
        </div>
        <!-- End serch box area -->
        <!-- Mobile -->
        <div class="main__logo d-block d-lg-none parent d-flex justify-content-center bg-white">
            <h1 class="main__logo--title child"><a class="main__logo--link" href="{{ route('home') }}"><img class="main__logo--img" src="{{ asset('img/logo/nav-log.png') }}" alt="logo-img"></a></h1>
        </div>

        <div class="container menu-container d-block d-lg-none">
            <div class="menu-left">
                <ul>
                    <li class="offcanvas__stikcy--toolbar__list mobile-menu-li">
                        <a class="offcanvas__menu2--btn offcanvas__stikcy--toolbar__btn mobile_menu" href="javascript:void(0)" data-offcanvas>
                            <span class="offcanvas__stikcy--toolbar__icon">
                                <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512">
                                    <path fill="currentColor" stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="18.51" d="M80 160h352M80 256h352M80 352h352" />
                                </svg>
                            </span>
                        </a>
                    </li>

                    <li class="offcanvas__stikcy--toolbar__list  mobile-menu-li">
                        <a class="offcanvas__stikcy--toolbar__btn search__open--btn mobile_menu" href="javascript:void(0)" data-offcanvas>
                            <span class="offcanvas__stikcy--toolbar__icon">
                                <svg xmlns="http://www.w3.org/2000/svg"  width="22.51" height="20.443" viewBox="0 0 512 512"><path d="M221.09 64a157.09 157.09 0 10157.09 157.09A157.1 157.1 0 00221.09 64z" fill="none" stroke="currentColor" stroke-miterlimit="10" stroke-width="32"/><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="32" d="M338.29 338.29L448 448"/></svg>
                            </span>
                        </a>
                    </li>
                    <li class="offcanvas__stikcy--toolbar__list  mobile-menu-li">
                        <a class="offcanvas__stikcy--toolbar__btn minicart__open--btn mobile_menu" href="javascript:void(0)" data-offcanvas>
                            <span class="offcanvas__stikcy--toolbar__icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18.51" height="15.443" viewBox="0 0 18.51 15.443">
                                <path  d="M79.963,138.379l-13.358,0-.56-1.927a.871.871,0,0,0-.6-.592l-1.961-.529a.91.91,0,0,0-.226-.03.864.864,0,0,0-.226,1.7l1.491.4,3.026,10.919a1.277,1.277,0,1,0,1.844,1.144.358.358,0,0,0,0-.049h6.163c0,.017,0,.034,0,.049a1.277,1.277,0,1,0,1.434-1.267c-1.531-.247-7.783-.55-7.783-.55l-.205-.8h7.8a.9.9,0,0,0,.863-.651l1.688-5.943h.62a.936.936,0,1,0,0-1.872Zm-9.934,6.474H68.568c-.04,0-.1.008-.125-.085-.034-.118-.082-.283-.082-.283l-1.146-4.037a.061.061,0,0,1,.011-.057.064.064,0,0,1,.053-.025h1.777a.064.064,0,0,1,.063.051l.969,4.34,0,.013a.058.058,0,0,1,0,.019A.063.063,0,0,1,70.03,144.853Zm3.731-4.41-.789,4.359a.066.066,0,0,1-.063.051h-1.1a.064.064,0,0,1-.063-.051l-.789-4.357a.064.064,0,0,1,.013-.055.07.07,0,0,1,.051-.025H73.7a.06.06,0,0,1,.051.025A.064.064,0,0,1,73.76,140.443Zm3.737,0L76.26,144.8a.068.068,0,0,1-.063.049H74.684a.063.063,0,0,1-.051-.025.064.064,0,0,1-.013-.055l.973-4.357a.066.066,0,0,1,.063-.051h1.777a.071.071,0,0,1,.053.025A.076.076,0,0,1,77.5,140.448Z" transform="translate(-62.393 -135.3)" fill="currentColor"/>
                                </svg>
                            </span>
                        </a>
                    </li>
                    <li class="offcanvas__stikcy--toolbar__list  mobile-menu-li">
                        <a class="offcanvas__stikcy--toolbar__btn minicart__open--btn mobile_menu" href="{{ route('my-account') }}">
                            <span class="offcanvas__stikcy--toolbar__icon">
                                <svg xmlns="http://www.w3.org/2000/svg"  width="20.51" height="19.443" viewBox="0 0 512 512"><path d="M344 144c-3.92 52.87-44 96-88 96s-84.15-43.12-88-96c-4-55 35-96 88-96s92 42 88 96z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/><path d="M256 304c-87 0-175.3 48-191.64 138.6C62.39 453.52 68.57 464 80 464h352c11.44 0 17.62-10.48 15.65-21.4C431.3 352 343 304 256 304z" fill="none" stroke="currentColor" stroke-miterlimit="10" stroke-width="32"/></svg>
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- Start Offcanvas header menu -->
        <div class="offcanvas__header">
            <div class="offcanvas__inner">
                <div class="offcanvas__logo">
                    <a class="offcanvas__logo_link" href="{{route('home')}}">
                        <img src="{{ asset('img/logo/nav-log.png') }}" alt="Grocee Logo" width="158" height="36">
                    </a>
                    <button class="offcanvas__close--btn" data-offcanvas>close</button>
                </div>
                <nav class="offcanvas__menu">
                    <ul class="offcanvas__menu_ul">
                        <li class="offcanvas__menu_li">
                            <a class="offcanvas__menu_item" href="index.html">All Products</a>
                            <ul class="offcanvas__sub_menu">
                                <li class="offcanvas__sub_menu_li"><a href="index.html" class="offcanvas__sub_menu_item">Home One</a></li>
                                <li class="offcanvas__sub_menu_li"><a href="index-2.html" class="offcanvas__sub_menu_item">Home Two</a></li>
                                <li class="offcanvas__sub_menu_li"><a href="index-3.html" class="offcanvas__sub_menu_item">Home Three</a></li>
                                <li class="offcanvas__sub_menu_li"><a href="index-4.html" class="offcanvas__sub_menu_item">Home Four</a></li>
                            </ul>
                        </li>
                        <li class="offcanvas__menu_li">
                            <a class="offcanvas__menu_item" href="shop.html">Grocery & Frozen</a>
                            <ul class="offcanvas__sub_menu">
                                <li class="offcanvas__sub_menu_li">
                                    <a href="#" class="offcanvas__sub_menu_item">Column One</a>
                                    <ul class="offcanvas__sub_menu">
                                        <li class="offcanvas__sub_menu_li"><a class="offcanvas__sub_menu_item" href="shop.html">Shop Left Sidebar</a></li>
                                        <li class="offcanvas__sub_menu_li"><a class="offcanvas__sub_menu_item" href="shop-right-sidebar.html">Shop Right Sidebar</a></li>
                                        <li class="offcanvas__sub_menu_li"><a class="offcanvas__sub_menu_item" href="shop-grid.html">Shop Grid</a></li>
                                        <li class="offcanvas__sub_menu_li"><a class="offcanvas__sub_menu_item" href="shop-grid-list.html">Shop Grid List</a></li>
                                        <li class="offcanvas__sub_menu_li"><a class="offcanvas__sub_menu_item" href="shop-list.html">Shop List</a></li>
                                    </ul>
                                </li>
                                <li class="offcanvas__sub_menu_li">
                                    <a href="#" class="offcanvas__sub_menu_item">Column Two</a>
                                    <ul class="offcanvas__sub_menu">
                                        <li class="offcanvas__sub_menu_li"><a class="offcanvas__sub_menu_item" href="product-details.html">Product Details</a></li>
                                        <li class="offcanvas__sub_menu_li"><a class="offcanvas__sub_menu_item" href="product-video.html">Video Product</a></li>
                                        <li class="offcanvas__sub_menu_li"><a class="offcanvas__sub_menu_item" href="product-details.html">Variable Product</a></li>
                                        <li class="offcanvas__sub_menu_li"><a class="offcanvas__sub_menu_item" href="product-left-sidebar.html">Product Left Sidebar</a></li>
                                        <li class="offcanvas__sub_menu_li"><a class="offcanvas__sub_menu_item" href="product-gallery.html">Product Gallery</a></li>
                                    </ul>
                                </li>
                                <li class="offcanvas__sub_menu_li">
                                    <a href="#" class="offcanvas__sub_menu_item">Column Three</a>
                                    <ul class="offcanvas__sub_menu">
                                        <li class="offcanvas__sub_menu_li"><a class="offcanvas__sub_menu_item" href="my-account.html">My Account</a></li>
                                        <li class="offcanvas__sub_menu_li"><a class="offcanvas__sub_menu_item" href="my-account-2.html">My Account 2</a></li>
                                        <li class="offcanvas__sub_menu_li"><a class="offcanvas__sub_menu_item" href="404.html">404 Page</a></li>
                                        <li class="offcanvas__sub_menu_li"><a class="offcanvas__sub_menu_item" href="login.html">Login Page</a></li>
                                        <li class="offcanvas__sub_menu_li"><a class="offcanvas__sub_menu_item" href="faq.html">Faq Page</a></li>
                                    </ul>
                                </li>
                                <li class="offcanvas__sub_menu_li">
                                    <a href="#" class="offcanvas__sub_menu_item">Column Three</a>
                                    <ul class="offcanvas__sub_menu">
                                        <li class="offcanvas__sub_menu_li"><a class="offcanvas__sub_menu_item" href="compare.html">Compare Pages</a></li>
                                        <li class="offcanvas__sub_menu_li"><a class="offcanvas__sub_menu_item" href="checkout.html">Checkout page</a></li>
                                        <li class="offcanvas__sub_menu_li"><a class="offcanvas__sub_menu_item" href="checkout-2.html">Checkout Style 2</a></li>
                                        <li class="offcanvas__sub_menu_li"><a class="offcanvas__sub_menu_item" href="checkout-3.html">Checkout Style 3</a></li>
                                        <li class="offcanvas__sub_menu_li"><a class="offcanvas__sub_menu_item" href="checkout-4.html">Checkout Style 4</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li class="offcanvas__menu_li">
                            <a class="offcanvas__menu_item" href="blog.html">Fresh Fruits</a>
                            <ul class="offcanvas__sub_menu">
                                <li class="offcanvas__sub_menu_li"><a href="blog.html" class="offcanvas__sub_menu_item">Blog Grid</a></li>
                                <li class="offcanvas__sub_menu_li"><a href="blog-details.html" class="offcanvas__sub_menu_item">Blog Details</a></li>
                                <li class="offcanvas__sub_menu_li"><a href="blog-left-sidebar.html" class="offcanvas__sub_menu_item">Blog Left Sidebar</a></li>
                                <li class="offcanvas__sub_menu_li"><a href="blog-right-sidebar.html" class="offcanvas__sub_menu_item">Blog Right Sidebar</a></li>
                            </ul>
                        </li>
                        <li class="offcanvas__menu_li">
                            <a class="offcanvas__menu_item" href="#">Package Foods</a>
                            <ul class="offcanvas__sub_menu">
                                <li class="offcanvas__sub_menu_li"><a href="about.html" class="offcanvas__sub_menu_item">About Us</a></li>
                                <li class="offcanvas__sub_menu_li"><a href="contact.html" class="offcanvas__sub_menu_item">Contact Us</a></li>
                                <li class="offcanvas__sub_menu_li"><a href="cart.html" class="offcanvas__sub_menu_item">Cart Page</a></li>
                                <li class="offcanvas__sub_menu_li"><a href="portfolio.html" class="offcanvas__sub_menu_item">Portfolio Page</a></li>
                                <li class="offcanvas__sub_menu_li"><a href="login.html" class="offcanvas__sub_menu_item">Login Page</a></li>
                                <li class="offcanvas__sub_menu_li"><a href="404.html" class="offcanvas__sub_menu_item">Error Page</a></li>
                            </ul>
                        </li>
                        <li class="offcanvas__menu_li"><a class="offcanvas__menu_item" href="about.html">Organic Foods</a></li>
                        <li class="offcanvas__menu_li"><a class="offcanvas__menu_item" href="contact.html">Health & Wellness</a></li>
                    </ul>
                </nav>
            </div>
        </div>
        <!-- End Offcanvas header menu -->

        <!-- Start offCanvas minicart -->
        <div class="offCanvas__minicart">
            <div class="minicart__header ">
                <div class="minicart__header--top d-flex justify-content-between align-items-center">
                    <h3 class="minicart__title"> Shopping Cart</h3>
                    <button class="minicart__close--btn" aria-label="minicart close btn" data-offcanvas>
                        <svg class="minicart__close--icon" xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 512 512"><path fill="currentColor" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M368 368L144 144M368 144L144 368"/></svg>
                    </button>
                </div>
                <p class="minicart__header--desc">The organic foods products are limited</p>
            </div>
            <div class="minicart__product">
                <div class="minicart__product--items d-flex">
                    <div class="minicart__thumb">
                        <a href="product-details.html"><img src="{{ asset('img/product/product1.png') }}" alt="prduct-img"></a>
                    </div>
                    <div class="minicart__text">
                        <h4 class="minicart__subtitle"><a href="product-details.html">The is Garden Vegetable.</a></h4>
                        <span class="color__variant"><b>Color:</b> Beige</span>
                        <div class="minicart__price">
                            <span class="current__price">$125.00</span>
                            <span class="old__price">$140.00</span>
                        </div>
                        <div class="minicart__text--footer d-flex align-items-center">
                            <div class="quantity__box minicart__quantity">
                                <button type="button" class="quantity__value decrease" aria-label="quantity value" value="Decrease Value">-</button>
                                <label>
                                    <input type="number" class="quantity__number" value="1" data-counter />
                                </label>
                                <button type="button" class="quantity__value increase" aria-label="quantity value" value="Increase Value">+</button>
                            </div>
                            <button class="minicart__product--remove" type="button">Remove</button>
                        </div>
                    </div>
                </div>
                <div class="minicart__product--items d-flex">
                    <div class="minicart__thumb">
                        <a href="product-details.html"><img src="{{ asset('img/product/product2.png') }}" alt="prduct-img"></a>
                    </div>
                    <div class="minicart__text">
                        <h4 class="minicart__subtitle"><a href="product-details.html">Fresh Tomatoe is organic.</a></h4>
                        <span class="color__variant"><b>Color:</b> Green</span>
                        <div class="minicart__price">
                            <span class="current__price">$115.00</span>
                            <span class="old__price">$130.00</span>
                        </div>
                        <div class="minicart__text--footer d-flex align-items-center">
                            <div class="quantity__box minicart__quantity">
                                <button type="button" class="quantity__value decrease" aria-label="quantity value" value="Decrease Value">-</button>
                                <label>
                                    <input type="number" class="quantity__number" value="1" data-counter />
                                </label>
                                <button type="button" class="quantity__value increase" aria-label="quantity value" value="Increase Value">+</button>
                            </div>
                            <button class="minicart__product--remove" type="button">Remove</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="minicart__amount">
                <div class="minicart__amount_list d-flex justify-content-between">
                    <span>Sub Total:</span>
                    <span><b>$240.00</b></span>
                </div>
                <div class="minicart__amount_list d-flex justify-content-between">
                    <span>Total:</span>
                    <span><b>$240.00</b></span>
                </div>
            </div>
            <div class="minicart__conditions text-center">
                <input class="minicart__conditions--input" id="accept" type="checkbox">
                <label class="minicart__conditions--label" for="accept">Ես համաձայն եմ՝ <a class="minicart__conditions--link" href="{{route('privacy-policy')}}">գաղտնիության քաղաքականությանը</a></label>
            </div>
            <div class="minicart__button d-flex justify-content-center">
                <a class="btn minicart__button--link" href="{{route('cart')}}">Տեսնել զամբյուղը</a>
                <a class="btn minicart__button--link" href="{{route('checkout')}}">Ստուգել</a>
            </div>
        </div>
        <!-- End offCanvas minicart -->
    </header>
    <!-- End header area -->
