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
            <div class="tab_content">
                <div id="product_all" class="tab_pane active show">
                    <div class="product__section--inner">
                        <div class="row row-cols-lg-4 row-cols-md-3 row-cols-2 mb--n28">
                            @foreach($products as $product)
                                @include('card', compact('product'))
                            @endforeach

                            {{$products->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <h2>Best Products</h2>
            <ul>
                @foreach($bestProducts as $bestProduct)
                    <li>
                        <a href="{{route('product', [$bestProduct->category->code, $bestProduct->code])}}">
                            <img src="{{ asset('storage/' . $bestProduct->image) }}" alt="">
                        </a>
                        <h3 class="w-50">
                            {{ $bestProduct->name }}
                        </h3>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

@endsection

