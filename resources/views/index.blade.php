@extends('layouts.master')
@section('title', 'Իջևան Մարկետ')
@section('content')
    <style>
        .category-card {
            width: 150px;
            height: 75px;
            padding: 0.25rem;
            border-radius: 0.5rem;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .category-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
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
            font-size: 1.5rem;
            color: #2E8B57;
            flex-shrink: 0;
        }

        .category-card span {
            font-size: 1rem;
            font-weight: 500;
            color: #333;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            text-align: left;
        }

        .carousel-row > .col {
            padding: 2px;
            display: flex;
            justify-content: center;
        }

        .carousel-control-prev {
            left: -90px;
        }

        .carousel-control-next {
            right: -90px;
        }

        /* Hide carousel controls on small screens */
        @media (max-width: 576px) {
            .carousel-control-prev,
            .carousel-control-next {
                display: none;
            }
        }

        @media (max-width: 767.98px) {
            .hide-on-mobile {
                display: none !important;
            }
        }
        .more-btn {
            padding: 0.25rem 0.5rem;
            border-radius: 50%;
            border: 1px solid #ccc;
            background-color: #2E8B57;
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

        .banner-wrapper {
            width: 100%;
            height: 100%;
            overflow: hidden;
            border-radius: 1.5rem;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .banner-wrapper img.banner-img {
            min-width: 100%;
            min-height: 100%;
            object-fit: cover;
            object-position: center center;
            display: block;
        }

    </style>

    @if (auth()->check() && !auth()->user()->hasVerifiedEmail())
        <div class="alert alert-warning d-flex justify-content-between align-items-center shadow-sm px-4 py-3 mb-4 rounded-3">
            <div class="col-6">
                <strong>⚠️ @lang('main.warning')</strong>
                <span>@lang('main.your_email_address_has_not')</span>
            </div>
            <div class="col-6">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button id="resendBtn" type="submit" class="btn btn-sm btn-outline-dark ms-3">
                        <i class="bi bi-send"></i> @lang('main.send_again')
                    </button>
                </form>
            </div>
        </div>
    @endif

    <div class="container py-3">
        {{-- Desktop Banner --}}
        <div class="container my-4 hide-on-mobile">
            <div id="bannerCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    @php $chunks = $banners->chunk(2); @endphp
                    @foreach($chunks as $chunkIndex => $bannerChunk)
                        <div class="carousel-item {{ $chunkIndex == 0 ? 'active' : '' }}">
                            <div class="row g-3">
                                @foreach($bannerChunk as $banner)
                                    {{-- <div class="col-12 col-md-6" style="height: 200px;">
                                        <div class="position-relative h-100" style="overflow: hidden; border-radius: 1.5rem !important;">
                                            <a href="{{ $banner->link }}">
                                                <img src="{{ Storage::url($banner->image) }}"
                                                    style="object-fit: cover !important; width: 100% !important; height: 100% !important; border-radius: 1.5rem !important;"
                                                    alt="{{ $banner->title }}">
                                            </a>
                                        </div>
                                    </div> --}}
                                    <div class="col-12 col-md-6" style="height: 200px;">
                                        <div class="banner-wrapper position-relative h-100">
                                            <a href="{{ $banner->link }}">
                                                @if ($banner->image && Storage::disk('public')->exists($banner->image))
                                                    <img src="{{ Storage::url($banner->image) }}" alt="{{ $banner->title }}" class="banner-img" loading="lazy" decoding="async">
                                                @else
                                                    <img src="{{ asset('img/no-image.png') }}" alt="No image" class="banner-img" loading="lazy">
                                                @endif
                                            </a>
                                        </div>
                                    </div>

                                @endforeach

                            </div>
                        </div>
                    @endforeach
                </div>
                @if($chunks->count() > 2)
                    <!-- Стрелки -->
                    <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon bg-dark rounded-circle p-3" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon bg-dark rounded-circle p-3" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                @endif
            </div>
        </div>

        {{-- Mobile Banner --}}
        <div class="container my-4">
            <div id="bannerCarouselMobile" class="carousel slide d-block d-md-none" data-bs-ride="carousel">
                <div class="carousel-inner">
                    @foreach($banners as $index => $banner)
                        <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                            <div style="height: 200px;">
                                <div class="position-relative h-100" style="overflow: hidden; border-radius: 1.5rem !important;">
                                    <a href="{{ $banner->link }}">
                                        @if ($banner->img_mobile && Storage::disk('public')->exists($banner->img_mobile))
                                            <img src="{{ Storage::url($banner->img_mobile) }}"
                                                class="d-block w-100 rounded-4 h-100 img-fluid"
                                                style="object-fit: cover !important; width: 100% !important; height: 100% !important; border-radius: 1.5rem !important;"
                                                alt="{{ $banner->title }}" loading="lazy" decoding="async">
                                        @else
                                            <img src="{{ asset('img/no-image.png') }}"
                                                class="d-block w-100 rounded-4 h-100 img-fluid"
                                                style="object-fit: cover !important; width: 100% !important; height: 100% !important; border-radius: 1.5rem !important;"
                                                alt="No image" loading="lazy">
                                        @endif
                                    </a>
                                    {{-- <div class="carousel-caption text-start">
                                        <h5 class="fw-bold">{{ $banner->title }}</h5>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($banners->count() > 1)
                    <!-- Стрелки -->
                    <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarouselMobile" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon bg-dark rounded-circle p-3" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#bannerCarouselMobile" data-bs-slide="next">
                        <span class="carousel-control-next-icon bg-dark rounded-circle p-3" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                @endif
            </div>
        </div>

        {{-- <h2 class="text-center mb-4">@lang('main.all_categories')</h2> --}}
        <div class="container">
            <div class="row" id="category-list">
                @foreach($categories as $index => $category)
                    <div class="col-6 col-sm-4 col-md-4 col-lg-2 py-2 p-1 category-item {{ $index >= 6 ? 'd-none extra-category' : '' }}">
                        <a href="{{ route('category', $category->code) }}" class="text-decoration-none">
                            <div class="card h-100 shadow-sm category-card">
                                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                                    <i class="{{ $category->icon }}"></i>
                                    <span>{{ $category->__('name') }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            @if(count($categories) > 6)
                <div class="d-flex justify-content-center mt-2">
                    <button class="btn btn-light btn-sm more-btn" id="toggleCategoriesBtn" aria-label="Toggle more categories">
                        <i class="bi bi-chevron-down"></i>
                    </button>
                </div>
            @endif
        </div>

        <!-- Random 8 -->
        <h2 class="text-center mb-4">@lang('main.rec_product')</h2>
        <div class="row row-cols-2 row-cols-md-4 g-3 mb-5">
            @foreach($randomSkus as $sku)
                <div class="col">@include('card', ['sku' => $sku])</div>
            @endforeach
        </div>

        <!-- Latest 8 -->
        <h2 class="text-center mb-4">@lang('main.new_skus')</h2>
        <div class="row row-cols-2 row-cols-md-4 g-3 mb-5">
            @foreach($newSkus as $sku)
                <div class="col">@include('card', ['sku' => $sku])</div>
            @endforeach
        </div>

        <!-- Best Sellers -->
        <h2 class="text-center mb-4">@lang('main.best_sales')</h2>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4 mb-5">
            @foreach($bestSkus as $bestSku)
                <div class="col-6 col-xss-6 col-sm-4 col-lg-3 col-xxl-2 my-2 p-1 p-lg-2">
                    @include('card', ['sku' => $bestSku])
                </div>
            @endforeach
        </div>
    </div>

@endsection

