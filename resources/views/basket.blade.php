@extends('layouts.master')
@section('title', 'Իջևան Մարկետ')
@section('content')
    @if($order)
        <!-- cart section start -->
        <section class="cart__section section--padding bg-white" style="border-radius: 15px 15px 0 0;">
            <div class="container">
                <div class="cart__section--inner">
                    <form action="#">
                        {{-- <h2 class="cart__title mb-40 text-center">@lang('main.basket')</h2> --}}
                        <div class="cart__table">
                            <table class="cart__table--inner">
                                <thead class="cart__table--header">
                                    <tr class="cart__table--header__items">
                                        <th class="cart__table--header__list">@lang('basket.name')</th>
                                        <th class="cart__table--header__list">@lang('basket.count')</th>
                                        <th class="cart__table--header__list text-center">@lang('basket.price')</th>
                                        <th class="cart__table--header__list text-right">@lang('basket.cost')</th>
                                    </tr>
                                </thead>
                                <tbody class="cart__table--body">
                                    @foreach($order->skus as $sku)
                                        <tr class="cart__table--body__items">
                                            <td class="cart__table--body__list">
                                                <div class="cart__product d-flex align-items-center">
                                                    <div class="cart__thumbnail">
                                                        <a href="{{ route('sku', [$sku->product->category->code, $sku->product->code, $sku->id]) }}">
                                                            <div class="cart__thumbnail">
                                                                <img class="border-radius-5" src="{{ Storage::url($sku->product->image) }}" alt="cart-product">
                                                            </div>
                                                        </a>
                                                    </div>
                                                    <div class="cart__content">
                                                        <h3 class="cart__content--title h4"><a href="product-details.html">{{ $sku->product->__('name') }}</a></h3>
                                                        <span class="cart__content--variant">COLOR: Blue</span>
                                                        <span class="cart__content--variant">WEIGHT: 2 Kg</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="cart__table--body__list">
                                                <div class="quantity__box">
                                                    <form action="{{ route('basket-remove', $sku) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="quantity__value quickview__value--quantity decrease" aria-label="quantity value" value="Decrease Value">-</button>
                                                    </form>
                                                    <label>
                                                        <input type="number" class="quantity__number quickview__value--number" value="{{ $sku->countInOrder }}" data-counter />
                                                    </label>
                                                    <form action="{{ route('basket-add', $sku) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="quantity__value quickview__value--quantity increase" aria-label="quantity value" value="Increase Value">+</button>
                                                    </form>
                                                </div>
                                            </td>
                                            <td class="cart__table--body__list text-center">
                                                <span class="in__stock text__secondary">{{ $sku->price }} {{ $currencySymbol }}</span>
                                            </td>
                                            <td class="cart__table--body__list text-right">
                                                <span class="in__stock text__secondary">{{ $sku->price * $sku->countInOrder }}{{ $currencySymbol}}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="continue__shopping d-flex justify-content-between">
                                <a class="continue__shopping--link" href="{{ route('index') }}">@lang('basket.continue_shopping')</a>
                                <a class="continue__shopping--clear" href="{{route('basket-place')}}">@lang('basket.confirm')</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
        <!-- cart section end -->
    @else
        <p>@lang('basket.basket_is_empty')</p>
    @endif

@endsection

