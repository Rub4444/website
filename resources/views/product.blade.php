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
                                            <img class="product__media--preview__items--img" src="{{Storage::url($product->image)}}" alt="product-media-img">
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="product__details--info">
                        <form action="#">
                            <h2 class="product__details--info__title mb-15">{{$product->__('name')}}</h2>
                            <div class="product__details--info__price mb-15">
                                <span class="current__price">{{$product->price}} {{ $currencySymbol}}</span>
                                {{-- <span class="old__price">$68.00</span> --}}
                            </div>
                            <p class="product__details--info__desc mb-20">
                                {{$product->__('description')}}
                            </p>
                            <div class="product__variant--list quantity d-flex align-items-center mb-20">
                                @if($product->isAvailable())
                                    <div class="quantity__box">
                                        <button type="button" class="quantity__value quickview__value--quantity decrease" aria-label="quantity value" value="Decrease Value">-</button>
                                        <label>
                                            <input type="number" class="quantity__number quickview__value--number" value="1" data-counter />
                                        </label>
                                        <button type="button" class="quantity__value quickview__value--quantity increase" aria-label="quantity value" value="Increase Value">+</button>
                                    </div>
                                @endif

                                @if($product->isAvailable())
                                    <form action="{{route('basket-add', $product)}}" method="POST">
                                        <button class="btn quickview__cart--btn" type="submit">Ավելացնել զամբյուղի մեջ</button>
                                        @csrf
                                    </form>
                                @endif
                            </div>

                            <div class="product__variant--list mb-15">
                                <div class="product__details--info__meta">
                                    <p class="product__details--info__meta--list"><strong>Կատեգորիա:</strong>  <span>{{$product->category->name}}</span> </p>
                                    <p class="product__details--info__meta--list">
                                        @if($product->isAvailable())
                                            <strong>Քանակ:</strong>
                                            <span>{{$product->count}}</span>
                                        @else
                                            <strong>Հասանելի չէ`</strong>
                                            <br>
                                            <span>Տեղեկացնել ինձ ապրանքի առկայության դեպքում</span>
                                            <br>

                                            <span class="warning">
                                                @if ($errors->has('email'))
                                                    {!! $errors->first('email') !!}
                                                @endif
                                            </span>
                                            <form method="POST" action="{{route('subscription', $product)}}">
                                                @csrf
                                                <input type="text" name="email"></input>
                                                <button type="submit" class="btn btn-primary btn-sm me-2">
                                                    Ուղարկել
                                                </button>
                                            </form>

                                        @endif
                                    </p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End product details section -->
@endsection

