@extends('layouts.master')
@section('title', 'Իջևան Մարկետ')
@section('content')
    <!-- Start product details section -->
    <section class="product__details--section section--padding">
        <div class="container">
            <div class="row row-cols-lg-2 row-cols-md-2">
                <div class="col">
                    <div class="product__details--media">
                        <div class="product__media--preview  swiper">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide">
                                    <div class="product__media--preview__items">
                                        <a class="product__media--preview__items--link glightbox" data-gallery="product-media-preview">
                                            <img class="product__media--preview__items--img" src="{{ Storage::url($skus->product->image) }}" alt="product-media-img">
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="product__details--info">
                            <h2 class="product__details--info__title mb-15">{{ $skus->product->__('name') }}</h2>
                            <div class="product__details--info__price mb-15">
                                <span class="current__price">{{ $skus->price }} {{ $currencySymbol }}</span>
                            </div>

                            @isset($skus->product->properties)
                                @foreach ($skus->propertyOptions as $propertyOption)
                                    <h4>
                                        {{ $propertyOption->property->__('name') }}: {{ $propertyOption->__('name') }}
                                    </h4>
                                @endforeach
                            @endisset

                            <p class="product__details--info__desc mb-20">
                                {{ $skus->product->__('description') }}
                            </p>
                            @if ($skus->isAvailable())
                                <div class="product__variant--list quantity d-flex align-items-center mb-20">
                                    <div class="quantity__box">
                                        <button type="button" class="quantity__value quickview__value--quantity decrease" aria-label="quantity value" value="Decrease Value">-</button>
                                        <label>
                                            <input type="number" class="quantity__number quickview__value--number" value="1" data-counter />
                                        </label>
                                        <button type="button" class="quantity__value quickview__value--quantity increase" aria-label="quantity value" value="Increase Value">+</button>
                                    </div>
                                    <form action="{{ route('basket-add', $skus->product) }}" method="POST">
                                        @csrf
                                        <button class="btn quickview__cart--btn" type="submit">Ավելացնել զամբյուղի մեջ</button>
                                    </form>
                                </div>
                                <div class="product__variant--list mb-15">
                                    <div class="product__details--info__meta">
                                        <p class="product__details--info__meta--list"><strong>Կատեգորիա:</strong> <span>{{ $skus->product->category->name }}</span></p>
                                        <p class="product__details--info__meta--list">
                                                <strong>Քանակ:</strong>
                                                <span>{{ $skus->count }}</span>
                                        </p>
                                    </div>
                                </div>

                            @else
                                <div class="product__variant--list mb-15">
                                    <div class="product__details--info__meta">
                                        <strong>Հասանելի չէ`</strong>
                                        <br>
                                        <span>Տեղեկացնել ինձ ապրանքի առկայության դեպքում</span>
                                        <br>

                                        <span class="warning">
                                            @if ($errors->has('email'))
                                                {!! $errors->first('email') !!}
                                            @endif
                                        </span>

                                        <form method="POST" action="{{ route('subscription', $skus) }}">
                                            @csrf
                                            <input type="email" name="email" placeholder="Մուտքագրեք էլ․ հասցե">
                                            <button type="submit" class="btn btn-primary btn-sm me-2">Ուղարկել</button>
                                        </form>
                                    </div>
                                </div>
                            @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End product details section -->
@endsection
