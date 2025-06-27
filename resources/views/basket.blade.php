@extends('layouts.master')
@section('title', 'Իջևան Մարկետ')
@section('content')
    @if($order)
    <section class="py-5 bg-light rounded-top">
        <div class="container">
            <div id="basket-items">
                <table class="table align-middle ">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">@lang('basket.name')</th>
                            <th scope="col">@lang('basket.count')</th>
                            <th scope="col" class="text-center">@lang('basket.price')</th>
                            <th scope="col" class="text-end">@lang('basket.cost')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->skus as $sku)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <a href="{{ route('sku', [$sku->product->category->code, $sku->product->code, $sku->id]) }}">
                                        <img src="{{ Storage::url($sku->image) }}" alt="Product" class="rounded me-3" width="70" height="70">
                                    </a>
                                    <div>
                                        <h6 class="mb-0">{{ $sku->product->__('name') }} {{ $sku->propertyOptions->map->name->implode(', ') }}</h6>
                                    </div>
                                </div>

                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <form action="{{ route('basket-remove', $sku) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="quantity__value quickview__value--quantity decrease" aria-label="quantity value" value="Decrease Value">-</button>
                                    </form>
                                    <label>
                                        <input readonly type="number" class="quantity__number quickview__value--number" value="{{ $sku->countInOrder }}" data-counter />
                                    </label>
                                    <form action="{{ route('basket-add', $sku) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="quantity__value quickview__value--quantity increase" aria-label="quantity value" value="Increase Value">+</button>
                                    </form>
                                </div>
                            </td>
                            <td class="text-center">{{ $sku->price }} {{ $currencySymbol }}</td>
                            <td class="text-end">{{ $sku->price * $sku->countInOrder }} {{ $currencySymbol }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @if(!$order->hasCoupon())
                    <div class="row mt-4">
                        <div class="col-md-4 offset-md-8">
                            <form method="POST" action="{{ route('set-coupon') }}" class="input-group">
                                @csrf
                                <input type="text" name="coupon" class="form-control" placeholder="@lang('basket.your_coupon')">
                                <button type="submit" class="btn btn-success">@lang('basket.confirm')</button>
                            </form>
                            @error('coupon')
                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                @else
                    <div class="alert alert-success mt-3">
                        @lang('basket.your_coupon') <strong>{{ $order->coupon->code }}</strong>
                    </div>
                @endif

                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 gap-3">
                    {{-- Сумма заказа --}}
                    <div class="text-center text-md-start w-100">
                        <div class="cart__summary--footer">
                            <p class="cart__summary--footer__desc">@lang('basket.shipping_cost_not_calculated')</p>
                            @if($order->hasCoupon())
                                <h5 class="mb-0">
                                    @lang('basket.cost'):
                                    <strike class="text-muted">{{ $order->getFullSum(false) }} {{ $currencySymbol }}</strike>
                                    <strong class="text-danger ms-2">{{ $order->getFullSum() }} {{ $currencySymbol }}</strong>
                                </h5>
                            @else
                                <h5 class="mb-0">
                                    @lang('basket.cost'):
                                    <strong>{{ $order->getFullSum() }} {{ $currencySymbol }}</strong>
                                </h5>
                            @endif
                        </div>
                    </div>

                    {{-- Кнопка очистки корзины --}}
                    @if($order && $order->skus->count())
                        <form method="POST" action="{{ route('basket.clear') }}"
                            onsubmit="return confirm('@lang('basket.clear_all_question')');"
                            class="w-100 text-center text-md-end">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-orange w-100 w-md-auto">
                                @lang('basket.clear_cart')
                            </button>
                        </form>
                    @endif

                    <style>
                        .btn-orange {
                            background-color: #F65005;
                            color: white;
                            border: none;
                            transition: background-color 0.3s ease;
                        }

                        .btn-orange:hover {
                            background-color: #E6AC00;
                            color: white;
                        }
                    </style>

                    {{-- Кнопка подтверждения заказа --}}
                    <div class="w-100 text-center text-md-end">
                        <a href="{{ route('basket-place') }}" class="btn btn-success w-100 w-md-auto">
                            @lang('basket.confirm')
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif

@endsection

