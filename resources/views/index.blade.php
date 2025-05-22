@extends('layouts.master')
@section('title', 'Իջևան Մարկետ')
@section('content')

    @if (auth()->check() && !auth()->user()->hasVerifiedEmail())
        <div class="alert alert-warning d-flex justify-content-between align-items-center shadow-sm px-4 py-3 mb-4 rounded-3">
            <div>
                <strong>⚠️ Ուշադրություն:</strong>
                <span>Ձեր Էլ-հասցեն հաստատված չէ:</span>
            </div>
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button id="resendBtn" type="submit" class="btn btn-sm btn-outline-dark ms-3">
                    <i class="bi bi-send"></i> Կրկին ուղարկել
                </button>
            </form>
        </div>

        {{-- <script>
            const resendBtn = document.getElementById('resendBtn');
            resendBtn.addEventListener('click', () => {
                resendBtn.disabled = true;
                resendBtn.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Ուղարկվում է...`;
            });
        </script> --}}
    @endif


    <div class="container py-5">
        {{-- <!-- Кнопка для раскрытия/скрытия фильтров -->
        <button class="btn btn-outline-dark mb-3" type="button" data-bs-toggle="collapse" data-bs-target="#filterBox" aria-expanded="false" aria-controls="filterBox">
            <i class="bi bi-sliders"></i> Ֆիլտրեր
        </button>

        <!-- Панель фильтров -->
        {{-- <div class="collapse show" id="filterBox">
            <div class="bg-light rounded p-4 mb-4 shadow-sm">
                <form method="GET" action="{{ route('index') }}">
                    <div class="row g-3 align-items-center">
                        <!-- Price Filter -->
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="number" name="price_from" id="price_from" class="form-control" placeholder="@lang('main.properties.price_from')" value="{{ request()->price_from }}">
                                <span class="input-group-text">-</span>
                                <input type="number" name="price_to" id="price_to" class="form-control" placeholder="@lang('main.properties.price_to')" value="{{ request()->price_to }}">
                            </div>
                        </div>
                        <!-- Checkboxes -->
                        <div class="col-md-4 d-flex justify-content-center">
                            <div class="form-check me-3">
                                <input class="form-check-input" type="checkbox" id="hit" name="hit" @if(request()->has('hit')) checked @endif>
                                <label class="form-check-label" for="hit">@lang('main.properties.hit')</label>
                            </div>
                            <div class="form-check me-3">
                                <input class="form-check-input" type="checkbox" id="new" name="new" @if(request()->has('new')) checked @endif>
                                <label class="form-check-label" for="new">@lang('main.properties.new')</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="recommend" name="recommend" @if(request()->has('recommend')) checked @endif>
                                <label class="form-check-label" for="recommend">@lang('main.properties.recommend')</label>
                            </div>
                        </div>
                        <!-- Buttons -->
                        <div class="col-md-4 text-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="bi bi-funnel"></i> @lang('main.properties.apply')
                            </button>
                            <a href="{{ route('index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle"></i> @lang('main.properties.reset')
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div> --}}


        <style>
            .category-card {
                width: 150px;
                height: 75px;
                padding: 0.25rem;
            }

            .category-card .card-body {
                padding: 0;
                height: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 0.5rem;
            }

            .category-card i {
                font-size: 1.25rem;
                color: #35A212;
                flex-shrink: 0;
            }

            .category-card span {
                font-size: 1.25rem;
                font-weight: 500;
                color: #333;
                text-align: left;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .carousel-row > .col {
                padding: 2px;
                display: flex;
                justify-content: center;
            }
            .carousel-control-prev {
                left: -90px; /* или -50px, чтобы сдвинуть ещё левее */
            }

            .carousel-control-next {
                right: -90px;
            }

            .category-card {
                transition: transform 0.2s, box-shadow 0.2s;
                border-radius: 0.5rem;
            }

            .category-card:hover {
                transform: translateY(-4px);
                box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            }

            .category-card i {
                font-size: 1.5rem;
                color: #35A212;
            }

            .category-card span {
                font-size: 1rem;
                font-weight: 500;
                color: #333;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            /* Опционально: скрыть стрелки на маленьких экранах */
            @media (max-width: 576px) {
                .carousel-control-prev,
                .carousel-control-next {
                    display: none;
                }
            }

            .more-btn {
                padding: 0.25rem 0.5rem;
                border-radius: 50%;
                border: 1px solid #ccc;
                background-color: #35A212;
                transition: all 0.3s ease;
                width: 32px;
                height: 32px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .more-btn:hover {
                background-color: #E6AC00;
                border-color: #bbb;
            }

            .more-btn i {
                font-size: 1rem;
                transition: transform 0.3s ease;
            }


        </style>


        <h2 class="text-center mb-4">@lang('main.all_categories')</h2>

        <div class="container">
            <div class="row" id="category-list">
                @foreach($categories as $index => $category)
                    <div class="col-6 col-sm-4 col-md-3 col-lg-2 py-2 p-1 category-item {{ $index >= 6 ? 'd-none extra-category' : '' }}">
                        <a href="{{ route('category', $category->code) }}" class="text-decoration-none">
                            <div class="card h-100 shadow-sm category-card">
                                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                                    <i class="{{ $category->icon }} mt-3 mb-1" style="font-size: 1.5rem; color: #35A212;"></i>
                                    <span class="text-center fw-medium">{{ $category->__('name') }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            @if(count($categories) > 6)
               <div class="d-flex justify-content-center mt-2">
                    <button class="btn btn-light btn-sm more-btn" id="toggleCategoriesBtn">
                        <i class="bi bi-chevron-down"></i>
                    </button>
                </div>
            @endif
        </div>

        <script>
            document.getElementById('toggleCategoriesBtn')?.addEventListener('click', function () {
                const extraItems = document.querySelectorAll('.extra-category');
                const icon = this.querySelector('i');

                const isHidden = extraItems[0]?.classList.contains('d-none');
                extraItems.forEach(item => {
                    item.classList.toggle('d-none');
                });

                if (isHidden) {
                    icon.classList.remove('bi-chevron-down');
                    icon.classList.add('bi-chevron-up');
                } else {
                    icon.classList.remove('bi-chevron-up');
                    icon.classList.add('bi-chevron-down');
                }
            });
        </script>



        <!-- Product Listing -->
        <h2 class="text-center mb-4">@lang('main.order_now')</h2>
        {{-- <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4 mb-5">
            @foreach($skus as $sku)
                @include('card', compact('sku'))
            @endforeach
        </div> --}}
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4 mb-5">
            @foreach($skus as $sku)
                <div class="col-6 col-xss-6 col-sm-4 col-lg-3 col-xxl-2 my-2 p-1 p-lg-2">
                    @include('card', compact('sku'))
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <nav class="d-flex justify-content-center">
            {{ $skus->links('vendor.custom') }}
        </nav>


        <!-- Best Sellers -->
        <h2 class="text-center mb-4">@lang('main.best_sales')</h2>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4 mb-5">
            @foreach($bestSkus as $bestSku)
                <div class="col-6 col-xss-6 col-sm-4 col-lg-3 col-xxl-2 my-2 p-1 p-lg-2">
                    @include('card', compact('bestSku'))
                </div>
            @endforeach
        </div>


        {{-- <h2 class="text-center mt-5 mb-4">Թոփ Վաճառք</h2>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            @foreach($bestSkus as $bestSku)
                @if($bestSku->product && $bestSku->product->category)
                    <div class="col mb-1">
                        <div class="card h-100 shadow-sm">
                            <a href="{{ route('sku', [$bestSku->product->category->code, $bestSku->product->code, $bestSku]) }}">
                                <img src="{{ asset('storage/' . $bestSku->product->image) }}" class="card-img-top" alt="Product">
                            </a>
                            <div class="card-body text-center">
                                <a href="{{ route('sku', [$bestSku->product->category->code, $bestSku->product->code, $bestSku]) }}" class="text-decoration-none text-dark">
                                    <h5 class="card-title">{{ $bestSku->product->__('name') }}</h5>
                                </a>
                                <p class="card-text fw-bold">{{ $bestSku->price }} {{ $currencySymbol }}</p>
                                <form action="{{ route('basket-add', $bestSku) }}" method="POST">
                                    @csrf
                                    @if($bestSku->isAvailable())
                                        <button type="submit" class="btn btn-success w-100">
                                            <i class="bi bi-cart-plus"></i> @lang('main.basket')
                                        </button>
                                    @else
                                        <span class="btn btn-outline-danger w-100 disabled">@lang('main.available')</span>
                                    @endif
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div> --}}
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const menu2OpenBtn = document.getElementById("menu2OpenBtn");
            const menu2 = document.getElementById("menu2");

            if (menu2OpenBtn && menu2) {
                menu2OpenBtn.addEventListener("click", function () {
                    menu2.classList.add("open");
                    document.body.classList.add("mobile_menu_open");
                });
            }
        });
    </script>

@endsection
