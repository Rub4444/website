@extends('layouts.master')

@section('title', $skus->product->__('name'))

@section('content')

    @if (($category->code ?? $skus->product->category->code) === 'alkvohvolayin-khmichqner')
        @include('partials.age-check-modal')
    @endif

    <section class="product__details--section section--padding">
        <div class="container">
            <div class="row">
                <!-- Left: Product Image Gallery -->
                <div class="col-lg-6">
                    <div class="product__media--preview position-relative bg-white rounded shadow-sm p-3" style=" display: flex; align-items: center; justify-content: center;">
                        <a class="glightbox" data-gallery="product-gallery" href="{{ Storage::url($skus->product->image) }}">
                            <img class="img-fluid" src="{{ Storage::url($skus->product->image) }}"
                                alt="{{ $skus->product->__('name') }}"
                                style="max-height: 280px; object-fit: contain;">
                        </a>
                        @auth
                            <button class="btn btn-sm shadow position-absolute top-0 end-0 m-2 toggle-wishlist rounded-circle d-flex align-items-center justify-content-center"
                                    data-id="{{ $skus->id }}"
                                    data-active="{{ Auth::user()->hasInWishlist($skus->id) ? '1' : '0' }}"
                                    style="z-index: 10; width: 36px; height: 36px; border: 2px solid white;">

                                <i class="bi {{ Auth::user()->hasInWishlist($skus->id) ? 'bi-heart-fill text-danger' : 'bi-heart' }}"></i>
                            </button>
                        @endauth
                    </div>
                </div>

                <!-- Right: Product Info -->
                <div class="col-lg-6">
                    <div class="product__details--info">
                        <h1 class="mb-3 h3">{{ $skus->product->__('name') }}</h1>
                        <div class="product__price mb-3">
                            <span class="fs-4 fw-bold text-success">{{ $skus->price }} {{ $currencySymbol }}</span>
                        </div>

                        @foreach ($skus->propertyOptions as $option)
                            <p><strong>{{ $option->property->__('name') }}:</strong> {{ $option->__('name') }}</p>
                        @endforeach

                        <div class="product__description my-4">
                            <p class="text-muted">{{ $skus->product->__('description') }}</p>
                        </div>

                        @if ($skus->isAvailable())
                            <form action="{{ route('basket-add', $skus) }}" method="POST" class="d-flex gap-3 align-items-center">
                                @csrf
                                <input type="number" name="quantity" class="form-control w-auto" value="1" min="1" style="max-width: 80px;">
                                <button class="btn btn-primary" type="submit">@lang('main.basket')</button>
                            </form>
                            <p class="mt-3"><strong>@lang('main.count'):</strong> {{ $skus->count }}</p>
                        @else
                            <div class="alert alert-warning mt-3">
                                <p class="mb-2">@lang('main.available')</p>
                                <form method="POST" action="{{ route('subscription', $skus) }}" class="d-flex gap-2">
                                    @csrf
                                    <input type="email" name="email" class="form-control" placeholder="@lang('basket.email')">
                                    <button type="submit" class="btn btn-outline-primary">@lang('basket.confirm')</button>
                                </form>
                            </div>
                        @endif

                        <div class="mt-4">
                            <p><strong>@lang('main.category'):</strong> {{ $skus->product->category->__('name') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if($relatedSkus->count())
        <section class="related-products section--padding bg-light">
            <div class="container">
                <div class="row g-4">
                    @foreach($relatedSkus as $sku)
                        @include('card', ['sku' => $sku])
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
