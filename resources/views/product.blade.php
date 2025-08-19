@extends('layouts.master')

@section('title', $skus->product->__('name'))

@section('content')

@if (in_array(($category->code ?? $skus->product->category->code), ['alkvohvolayin-khmichqner', 'cxaxotner']))
    @include('partials.age-check-modal')
@endif

<section class="product__details--section py-5">
    <div class="container">
        <div class="row gx-5">
            <!-- Left: Product Image -->
            <div class="col-lg-6">
                <div class="bg-white rounded shadow-sm p-4 position-relative d-flex justify-content-center align-items-center" style="min-height: 320px;">
                    <a class="glightbox" data-gallery="product-gallery" href="{{ Storage::url($skus->image) }}">
                        <img src="{{ Storage::url($skus->image) }}" alt="{{ $skus->product->__('name') }}"
                            class="img-fluid" style="max-height: 280px; object-fit: contain;">
                    </a>

                    @auth
                        <button class="btn btn-sm shadow position-absolute top-0 end-0 m-2 toggle-wishlist rounded-circle d-flex align-items-center justify-content-center"
                                    data-id="{{ $skus->id }}"
                                    data-active="{{ Auth::user()->hasInWishlist($skus->id) ? '1' : '0' }}"
                                    style="z-index: 10; width: 36px; height: 36px; border: 2px solid white;">
                                <i class="bi {{ Auth::user()->hasInWishlist($skus->id) ? 'bi-heart-fill text-danger' : 'bi-heart' }}"></i>
                        </button>
                        <!-- <button
                            class="btn btn-sm btn-outline-danger position-absolute top-0 end-0 m-3 rounded-circle shadow"
                            data-id="{{ $skus->id }}"
                            data-active="{{ Auth::user()->hasInWishlist($skus->id) ? '1' : '0' }}"
                            style="width: 40px; height: 40px;">
                            <i class="bi {{ Auth::user()->hasInWishlist($skus->id) ? 'bi-heart-fill' : 'bi-heart' }} fs-5"></i>
                        </button> -->
                    @endauth
                </div>
            </div>

            <!-- Right: Product Info -->
            <div class="col-lg-6">
                <div class="product__details--info">
                    <h1 class="h3 fw-bold mb-3">
                        {{ $skus->product->__('name') }} {{ $skus->name ? ' ' . $skus->name : '' }} {{ $option->__('name') }} {{ $option->property->__('name') }}
                    </h1>

                    <div class="mb-4">
                        <span class="fs-3 fw-bold text-success">{{ $skus->price }} {{ $currencySymbol }}</span>
                    </div>

                    {{-- <div class="mb-3">
                        @foreach ($skus->propertyOptions as $option)
                            <p class="mb-1"><strong>{{ $option->property->__('name') }}:</strong> {{ $option->__('name') }}</p>
                        @endforeach
                    </div> --}}

                    <div class="mb-4">
                        <p class="text-muted">{{ $skus->product->__('description') }}</p>
                    </div>

                    @if ($skus->isAvailable())
                        <form action="{{ route('basket-add', $skus) }}" method="POST" class="d-flex align-items-center gap-3 mb-3">
                            @csrf
                            {{-- <input type="number" name="quantity" class="form-control w-auto" value="1" min="1" style="max-width: 100px;"> --}}
                            <button type="submit" class="btn btn-success px-4">
                                <i class="bi bi-cart-plus me-2"></i> @lang('main.basket')
                            </button>
                        </form>
                        <p><strong>@lang('main.count'):</strong> {{ $skus->count }}</p>
                    @else
                        <div class="alert alert-warning">
                            <p class="mb-3">@lang('main.available')</p>
                            <form method="POST" action="{{ route('subscription', $skus) }}" class="d-flex gap-2 flex-wrap">
                                @csrf
                                <input type="email" name="email" class="form-control flex-grow-1" placeholder="@lang('basket.email')" required>
                                <button type="submit" class="btn btn-outline-primary px-4">@lang('basket.confirm')</button>
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
<section class="related-products py-5 bg-light">
    <div class="container">
        <h3 class="mb-4 fw-bold">@lang('main.it_may_be_interest')</h3>
        <div class="row g-4">
            @foreach($relatedSkus as $sku)
                <div class="col-6 col-md-4 col-lg-3">
                    @include('card', ['sku' => $sku])
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection
