@extends('layouts.master')
@section('title', 'Իջևան Մարկետ')
@section('content')
    <section class="product__section product__categories--section section--padding">
        <form method="GET" action="{{ route('index') }}" class="p-3 border rounded shadow-sm bg-light">
            <div class="row align-items-center">
                <!-- Price filter -->
                <div class="col-md-4 d-flex">
                    <div class="form-group me-2 w-50">
                        <input type="number" name="price_from" id="price_from" class="form-control form-control-sm"
                            value="{{ request()->price_from }}" placeholder="@lang('main.properties.price_from')">
                    </div>
                    <div class="form-group w-50">
                        <input type="number" name="price_to" id="price_to" class="form-control form-control-sm"
                            value="{{ request()->price_to }}" placeholder="@lang('main.properties.price_to')">
                    </div>
                </div>

                <!-- Checkboxes -->
                <div class="col-md-4 d-flex justify-content-center">
                    <div class="form-check form-check-inline">
                        <input type="checkbox" name="hit" id="hit" class="form-check-input"
                            @if(request()->has('hit')) checked @endif>
                        <label for="hit" class="form-check-label">@lang('main.properties.hit')</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="checkbox" name="new" id="new" class="form-check-input"
                            @if(request()->has('new')) checked @endif>
                        <label for="new" class="form-check-label">@lang('main.properties.new')</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="checkbox" name="recommend" id="recommend" class="form-check-input"
                            @if(request()->has('recommend')) checked @endif>
                        <label for="recommend" class="form-check-label">@lang('main.properties.recommend')</label>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="col-md-4 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary btn-sm me-2">
                        @lang('main.properties.apply')
                    </button>
                    <a href="{{ route('index') }}" class="btn btn-outline-secondary btn-sm">
                        @lang('main.properties.reset')
                    </a>
                </div>
            </div>
        </form>
    </section>
    <div class="container">
        <!-- Start product section -->
        <section class="product__section section--padding pt-0">
            <div class="container">
                <div class="section__heading text-center mb-25">
                    {{-- <span class="section__heading--subtitle">Products</span> --}}
                    <h2 class="section__heading--maintitle">Պատվիրեք հիմա</h2>
                </div>
                <div class="tab_content">
                    <div id="product_all" class="tab_pane active show">
                        <div class="product__section--inner">
                            <div class="row row-cols-lg-4 row-cols-md-3 row-cols-2 mb--n28">
                                @foreach($skus as $sku)
                                    @include('card', compact('sku'))
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- {{dd($skus->links())}}
            <div class="pagination__area bg__gray--color">
                <nav class="pagination justify-content-center">
                    <ul class="pagination__wrapper d-flex align-items-center justify-content-center">
                        <li class="pagination__list">
                            <a href="shop.html" class="pagination__item--arrow  link ">
                                <svg xmlns="http://www.w3.org/2000/svg"  width="22.51" height="20.443" viewBox="0 0 512 512"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="48" d="M244 400L100 256l144-144M120 256h292"/></svg>
                                <span class="visually-hidden">page left arrow</span>
                            </a>
                        <li>
                        <li class="pagination__list"><span class="pagination__item pagination__item--current">1</span></li>
                        <li class="pagination__list"><a href="shop.html" class="pagination__item link">2</a></li>
                        <li class="pagination__list">
                            <a href="shop.html" class="pagination__item--arrow  link ">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22.51" height="20.443" viewBox="0 0 512 512"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="48" d="M268 112l144 144-144 144M392 256H100"/></svg>
                                <span class="visually-hidden">page right arrow</span>
                            </a>
                        <li>
                    </ul>
                </nav>
            </div> --}}

            <div class="pagination__area bg__gray--color">
                <nav class="pagination justify-content-center">
                    <ul class="pagination__wrapper d-flex align-items-center justify-content-center">
                        {{-- Кнопка "назад" --}}
                        @if ($skus->onFirstPage())
                            <li class="pagination__list disabled">
                                <span class="pagination__item--arrow link">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22.51" height="20.443" viewBox="0 0 512 512">
                                        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="48" d="M244 400L100 256l144-144M120 256h292"/>
                                    </svg>
                                </span>
                            </li>
                        @else
                            <li class="pagination__list">
                                <a href="{{ $skus->previousPageUrl() }}" class="pagination__item--arrow link">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22.51" height="20.443" viewBox="0 0 512 512">
                                        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="48" d="M244 400L100 256l144-144M120 256h292"/>
                                    </svg>
                                </a>
                            </li>
                        @endif

                        {{-- Вывод номеров страниц --}}
                        @foreach ($skus->links()->elements[0] as $page => $url)
                            @if ($page == $skus->currentPage())
                                <li class="pagination__list"><span class="pagination__item pagination__item--current">{{ $page }}</span></li>
                            @else
                                <li class="pagination__list"><a href="{{ $url }}" class="pagination__item link">{{ $page }}</a></li>
                            @endif
                        @endforeach

                        {{-- Кнопка "вперёд" --}}
                        @if ($skus->hasMorePages())
                            <li class="pagination__list">
                                <a href="{{ $skus->nextPageUrl() }}" class="pagination__item--arrow link">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22.51" height="20.443" viewBox="0 0 512 512">
                                        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="48" d="M268 112l144 144-144 144M392 256H100"/>
                                    </svg>
                                </a>
                            </li>
                        @else
                            <li class="pagination__list disabled">
                                <span class="pagination__item--arrow link">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22.51" height="20.443" viewBox="0 0 512 512">
                                        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="48" d="M268 112l144 144-144 144M392 256H100"/>
                                    </svg>
                                </span>
                            </li>
                        @endif
                    </ul>
                </nav>
            </div>
        </section>
        <section class="product__section section--padding pt-0">
                <div class="container">
                    <div class="section__heading text-center mb-25">
                        {{-- <span class="section__heading--subtitle">Recently added our store</span> --}}
                        <h2 class="section__heading--maintitle">Թոփ Վաճառք</h2>
                    </div>
                    <div class="tab_content">
                        <div id="product_all" class="tab_pane active show">
                            <div class="product__section--inner">
                                <div class="row row-cols-lg-4 row-cols-md-3 row-cols-2 mb--n28">
                                    @foreach($bestSkus as $bestSku)
                                        <div class="col md-28">
                                            <div class="product__items ">
                                                <div class="product__items--thumbnail">
                                                    <a class="product__items--link" href="{{route('sku', [$bestSku->product->category->code, $bestSku->product->code, $bestSku])}}">
                                                        <img class="product__items--img product__primary--img" src="{{ asset('storage/' . $bestSku->product->image) }}" alt="product-img">
                                                    </a>
                                                    <div class="product__badge">
                                                        @if($bestSku->product->isNew())
                                                            <span class="product__badge--items new">@lang('main.properties.new')</span>
                                                        @endif
                                                        @if($bestSku->product->isRecommend())
                                                            <span class="product__badge--items recommend">@lang('main.properties.recommend')</span>
                                                        @endif
                                                        @if($bestSku->product->isHit())
                                                            <span class="product__badge--items hit">@lang('main.properties.hit')</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="product__items--content text-center">
                                                    <form action="{{route('basket-add', $bestSku)}}" method="POST">
                                                        @if($bestSku->isAvailable())
                                                            <button class="add__to--cart__btn" type="submit">@lang('main.cart')</button>
                                                        @else
                                                            <p class="add__to--cart__btn">@lang('main.available')</p>
                                                        @endif
                                                        @csrf
                                                    </form>
                                                    <h3 class="product__items--content__title h4">
                                                        <a href="{{route('sku', [$bestSku->product->category->code, $bestSku->product->code, $bestSku])}}">
                                                            {{ $bestSku->product->__('name') }}
                                                            @isset ($bestSku->product->properties)
                                                                @foreach ($bestSku->propertyOptions as $propertyOption)
                                                                        {{$propertyOption->__('name')}}
                                                                @endforeach
                                                            @endisset
                                                        </a>
                                                    </h3>
                                                    <div class="product__items--price">
                                                        <span class="current__price">{{ $bestSku->price }} {{ $currencySymbol }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </section>
    </div>
@endsection

