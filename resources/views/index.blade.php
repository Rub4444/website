@extends('layouts.master')
@section('title', 'Իջևան Մարկետ')
@section('content')
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

        <!-- Product Listing -->
        <h2 class="text-center mb-4">Պատվիրեք հիմա</h2>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4 mb-5">
            @foreach($skus as $sku)
                {{-- @if($sku->product && $sku->product->category) --}}
                    <div class="col mb-1">
                        <div class="card h-100 shadow-sm">
                            <a href="{{ route('sku', [$sku->product->category->code, $sku->product->code, $sku]) }}">
                                <img src="{{ asset('storage/' . $sku->product->image) }}" class="card-img-top" alt="Product">
                            </a>
                            <div class="card-body text-center">
                                <a href="{{ route('sku', [$sku->product->category->code, $sku->product->code, $sku]) }}" class="text-decoration-none text-dark">
                                    <h5 class="card-title">{{ $sku->product->__('name') }}</h5>
                                </a>
                                <p class="card-text fw-bold">{{ $sku->price }} {{ $currencySymbol }}</p>
                                <form action="{{ route('basket-add', $sku) }}" method="POST">
                                    @csrf
                                    @if($sku->isAvailable())
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
                {{-- @endif --}}
            @endforeach
        </div>
        <!-- Pagination -->
        <nav class="d-flex justify-content-center">
            {{ $skus->links('pagination::bootstrap-5') }}
        </nav>

        <!-- Best Sellers -->
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
@endsection
