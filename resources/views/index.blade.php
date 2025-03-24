@extends('layouts.master')
@section('title', 'Իջևան Մարկետ')
@section('content')
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
    <!-- Start product section -->
    <section class="product__section section--padding pt-0">
        <div class="container">
            <div class="section__heading text-center mb-25">
                <span class="section__heading--subtitle">Products</span>
                <h2 class="section__heading--maintitle">Our Products</h2>
            </div>
            <div class="tab_content">
                <div id="product_all" class="tab_pane active show">
                    <div class="product__section--inner">
                        <div class="row row-cols-lg-4 row-cols-md-3 row-cols-2 mb--n28">
                            @foreach($skus as $sku)
                                @include('card', compact('sku'))
                            @endforeach
                        </div>
                        {{$skus->links()}}
                    </div>
                </div>
            </div>
        </div>

        <section class="product__section section--padding pt-0">
            <div class="container">
                <div class="section__heading text-center mb-25">
                    <span class="section__heading--subtitle">Recently added our store</span>
                    <h2 class="section__heading--maintitle">Trending Products</h2>
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
                                                    </a>
                                                </h3>
                                                <div class="product__items--price">
                                                    <span class="current__price">{{ $bestSku->product->price }} {{ $currencySymbol }}</span>
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

@endsection

