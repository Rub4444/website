@extends('layouts.master')

@section('title', 'Իջևան Մարկետ')

@section('content')
@if($order)
<section class="py-5 bg-light rounded-top">
    <div class="container">
        <div id="basket-items">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>@lang('basket.name')</th>
                        <th class="text-center">@lang('basket.count')</th>
                        <th class="text-center">@lang('basket.price')</th>
                        <th class="text-end">@lang('basket.cost')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->skus as $sku)
                    <tr>
                        <!-- Название + Картинка -->
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <a href="{{ route('sku', [$sku->product->category->code, $sku->product->code, $sku->id]) }}">
                                    <img src="{{ Storage::url($sku->image) }}"
                                         alt="Product"
                                         class="rounded"
                                         style="width: 70px; height: 70px; object-fit: cover;">
                                </a>
                                <div>
                                    <h6 class="mb-0">{{ $sku->product->__('name') }} {{ $sku->propertyOptions->map->name->implode(', ') }}</h6>
                                </div>
                            </div>
                        </td>

                        <!-- Количество -->
                        <td class="text-center">
                            <div class="input-group justify-content-center" style="max-width: 130px; margin: auto;">
                                <form action="{{ route('basket-remove', $sku) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-secondary btn-sm px-2">−</button>
                                </form>

                                <input type="text"
                                       class="form-control form-control-sm text-center"
                                       value="{{ $sku->countInOrder }}"
                                       readonly
                                       style="max-width: 45px;">

                                <form action="{{ route('basket-add', $sku) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-secondary btn-sm px-2">+</button>
                                </form>
                            </div>
                        </td>

                        <!-- Цена -->
                        <td class="text-center">
                            {{ $sku->price }} {{ $currencySymbol }}
                        </td>

                        <!-- Сумма -->
                        <td class="text-end">
                            {{ $sku->price * $sku->countInOrder }} {{ $currencySymbol }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Купон -->
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

            <!-- Итоги -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 gap-3">
                <div class="w-100 text-center text-md-start">
                    <p class="text-muted mb-1">@lang('basket.shipping_cost_not_calculated')</p>
                    @if($order->hasCoupon())
                        <h5>
                            @lang('basket.cost'):
                            <strike class="text-muted">{{ $order->getFullSum(false) }} {{ $currencySymbol }}</strike>
                            <strong class="text-danger ms-2">{{ $order->getFullSum() }} {{ $currencySymbol }}</strong>
                        </h5>
                    @else
                        <h5>
                            @lang('basket.cost'):
                            <strong>{{ $order->getFullSum() }} {{ $currencySymbol }}</strong>
                        </h5>
                    @endif
                </div>

                <!-- Очистить корзину -->
                @if($order && $order->skus->count())
                    <form method="POST"
                          action="{{ route('basket.clear') }}"
                          onsubmit="return confirm('@lang('basket.clear_all_question')');"
                          class="w-100 text-center text-md-end">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-orange w-100 w-md-auto">
                            @lang('basket.clear_cart')
                        </button>
                    </form>
                @endif

                <!-- Подтвердить заказ -->
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

    table td, table th {
        vertical-align: middle !important;
    }
</style>
@endsection
