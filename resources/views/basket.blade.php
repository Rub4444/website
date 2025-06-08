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
                                        <img src="{{ Storage::url($sku->product->image) }}" alt="Product" class="rounded me-3" width="70" height="70">
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

                <div class="d-flex justify-content-between mt-4">
                    @if($order->hasCoupon())
                        <h5>
                            @lang('basket.cost'):
                            <strike>{{ $order->getFullSum(false) }} {{ $currencySymbol }}</strike>
                            <strong>{{ $order->getFullSum() }} {{ $currencySymbol }}</strong>
                        </h5>
                    @else
                        <h5>
                            @lang('basket.cost'):
                            <strong>{{ $order->getFullSum() }} {{ $currencySymbol }}</strong>
                        </h5>
                    @endif

                    <div class="d-none d-lg-block">
                        <a href="{{ route('index') }}" class="btn btn-outline-secondary">@lang('basket.continue_shopping')</a>
                    </div>
                    <div>
                        <a href="{{ route('basket-place') }}" class="btn btn-primary">@lang('basket.confirm')</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif

@endsection

