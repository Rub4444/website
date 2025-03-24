@extends('layouts.master')
@section('title', 'Իջևան Մարկետ')
@section('content')
    @if($order)
    <!-- cart section start -->
    <section class="cart__section section--padding">
        <div class="container-fluid">
            <div class="cart__section--inner">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="cart__table">
                            <table class="cart__table--inner">
                                <thead class="cart__table--header">
                                    <tr class="cart__table--header__items">
                                        <th class="cart__table--header__list">@lang('basket.name')</th>
                                        <th class="cart__table--header__list">@lang('basket.count')</th>
                                        <th class="cart__table--header__list">@lang('basket.price')</th>
                                        <th class="cart__table--header__list">@lang('basket.cost')</th>
                                    </tr>
                                </thead>
                                <tbody class="cart__table--body">
                                    @foreach($order->skus as $sku)
                                        <tr class="cart__table--body__items">
                                            <td class="cart__table--body__list">
                                                <a href="{{ route('sku', [$sku->product->category->code, $sku->product->code, $sku->id]) }}">
                                                    <div class="cart__thumbnail">
                                                        <img class="border-radius-5" src="{{ Storage::url($sku->product->image) }}" alt="cart-product">
                                                    </div>
                                                    {{ $sku->product->__('name') }}
                                                </a>
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
                                            <td class="cart__table--body__list">
                                                <span class="cart__price">{{ $sku->price }} {{ $currencySymbol }}</span>
                                            </td>
                                            <td class="cart__table--body__list">
                                                <span class="cart__price end">{{ $sku->price * $sku->countInOrder }}{{ $currencySymbol}}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="cart__summary border-radius-10">
                            <div class="cart__summary--total mb-20">
                                <table class="cart__summary--total__table">
                                    <tbody>
                                        <tr class="cart__summary--total__list">
                                            <td class="cart__summary--total__title text-left">@lang('basket.cost')</td>
                                            <td class="cart__summary--amount text-right">{{ $order->getFullSum() }} {{ $currencySymbol }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="cart__summary--footer">
                                <p class="cart__summary--footer__desc">Shipping & taxes calculated at checkout</p>
                                <ul class="d-flex justify-content-between">
                                    <li><a class="cart__summary--footer__btn btn checkout" href="{{route('basket-place')}}">@lang('basket.confirm')</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @else
        <p>Your basket is empty.</p>
    @endif
@endsection

