@extends('layouts.master')
@section('title', 'Իջևան Մարկետ')
@section('content')
    <!-- Start product details section -->
    <section class="product__details--section section--padding">
        <div class="container">
            <div class="row row-cols-1 row-cols-md-2 g-4">
                <!-- Product Image -->
                <div class="col">
                    <div class="card shadow-sm h-100">
                        <div class="card-body text-center">
                            @auth
                                <button class="btn btn-sm shadow position-absolute top-0 end-0 m-2 toggle-wishlist rounded-circle d-flex align-items-center justify-content-center"
                                        data-id="{{ $skus->id }}"
                                        data-active="{{ Auth::user()->hasInWishlist($skus->id) ? '1' : '0' }}"
                                       style="z-index: 10; width: 36px; height: 36px; border: 2px solid white;">
                                    <i class="bi {{ Auth::user()->hasInWishlist($skus->id) ? 'bi-heart-fill text-danger' : 'bi-heart ' }}"></i>
                                </button>
                            @endauth

                            <div class="product__media--preview">
                                <a class="glightbox" data-gallery="product-media-preview"
                                href="{{ Storage::url($skus->product->image) }}">
                                    <img src="{{ Storage::url($skus->product->image) }}"
                                        alt="product-media-img"
                                        class="img-fluid rounded"
                                        style="max-height: 200px; object-fit: contain;">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Info -->
                <div class="col">
                    <div class="product__details--info h-100 d-flex flex-column justify-content-between">
                        <div>
                            <h2 class="mb-3">{{ $skus->product->__('name') }}</h2>
                            <div class="mb-3">
                                <span class="fs-4 fw-bold">{{ $skus->price }} {{ $currencySymbol }}</span>
                            </div>

                            @isset($skus->product->properties)
                                @foreach ($skus->propertyOptions as $propertyOption)
                                    <h6 class="mb-2">
                                        {{ $propertyOption->property->__('name') }}: {{ $propertyOption->__('name') }}
                                    </h6>
                                @endforeach
                            @endisset

                            <p class="mb-4 text-justify">
                                {{ $skus->product->__('description') }}
                            </p>
                        </div>

                        @if ($skus->isAvailable())
                            <div class="row g-3 align-items-center mb-3">
                                <div class="col-12 col-sm-6">
                                    <div class="d-flex justify-content-center">
                                        <button type="button" class="btn btn-outline-secondary" value="Decrease Value">-</button>
                                        <input type="number" class="form-control text-center mx-2" value="1" data-counter style="width: 70px;">
                                        <button type="button" class="btn btn-outline-secondary" value="Increase Value">+</button>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <form action="{{ route('basket-add', $skus->product) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-primary w-100" type="submit">Ավելացնել զամբյուղի մեջ</button>
                                    </form>
                                </div>
                            </div>

                            <div class="mb-3">
                                <p><strong>Կատեգորիա:</strong> {{ $skus->product->category->name }}</p>
                                <p><strong>Քանակ:</strong> {{ $skus->count }}</p>
                            </div>
                        @else
                            <div class="mb-3">
                                <p><strong>Հասանելի չէ`</strong></p>
                                <p>Տեղեկացնել ինձ ապրանքի առկայության դեպքում</p>

                                @if ($errors->has('email'))
                                    <span class="text-danger">{!! $errors->first('email') !!}</span>
                                @endif

                                <form method="POST" action="{{ route('subscription', $skus) }}" class="d-flex flex-column flex-sm-row gap-2 mt-2">
                                    @csrf
                                    <input type="email" name="email" class="form-control" placeholder="Մուտքագրեք էլ․ հասցե">
                                    <button type="submit" class="btn btn-primary">Ուղարկել</button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End product details section -->

    @if($relatedSkus->count())
        <section class="related-products section--padding">
            <div class="container">
                <div class="text-center mb-4">
                    <h2>Այլ տարբերակներ</h2>
                </div>
                <div class="row g-4">
                    @foreach($relatedSkus as $related)
                        <div class="col-6 col-sm-6 col-md-4 col-lg-3">
                            <div class="card h-100 shadow-sm text-center">
                                <a href="{{ route('sku', [$related->product->category->code, $related->product->code, $related->id]) }}">
                                    <img src="{{ Storage::url($related->product->image) }}" class="card-img-top" alt="{{ $related->product->__('name') }}" style="height: 200px; object-fit: contain;">
                                </a>
                                <div class="card-body">
                                    <h5 class="card-title">
                                        {{ $related->product->__('name') }}
                                        @foreach ($related->propertyOptions as $option)
                                            <br><small>{{ $option->property->__('name') }}: {{ $option->__('name') }}</small>
                                        @endforeach
                                    </h5>
                                    <p class="card-text">{{ $related->price }} {{ $currencySymbol }}</p>

                                    <form action="{{ route('basket-add', $related) }}" method="POST">
                                        @csrf
                                        @if($related->isAvailable())
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
                    @endforeach
                </div>
            </div>
        </section>
    @endif

@endsection
