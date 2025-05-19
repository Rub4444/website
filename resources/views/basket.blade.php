@extends('layouts.master')
@section('title', 'Իջևան Մարկետ')

@section('content')
@if($order)
<section class="py-5 bg-light rounded-top">
    <div class="container">
        <div class="table-responsive">
            <table class="table align-middle">
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
                                <form action="{{ route('basket-remove', $sku) }}" method="POST" class="me-2">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger btn-sm">-</button>
                                </form>
                                <input type="number" value="{{ $sku->countInOrder }}" class="form-control form-control-sm text-center" style="width: 60px;" readonly>
                                <form action="{{ route('basket-add', $sku) }}" method="POST" class="ms-2">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-success btn-sm">+</button>
                                </form>
                            </div>
                        </td>
                        <td class="text-center">{{ $sku->price }} {{ $currencySymbol }}</td>
                        <td class="text-end">{{ $sku->price * $sku->countInOrder }} {{ $currencySymbol }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Купон --}}
        @if(!$order->hasCoupon())
            <div class="row mt-4">
                <div class="col-md-6 offset-md-6">
                    <form method="POST" action="{{ route('set-coupon') }}" class="input-group">
                        @csrf
                        <input type="text" name="coupon" class="form-control" placeholder="@lang('basket.coupon.add_coupon')">
                        <button type="submit" class="btn btn-success">@lang('basket.coupon.apply')</button>
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

        {{-- Итоговая стоимость --}}
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

            <div class=" d-none d-lg-block">
                <a href="{{ route('index') }}" class="btn btn-outline-secondary">@lang('basket.continue_shopping')</a>
            </div>
            <div>
                <a href="{{ route('basket-place') }}" class="btn btn-primary">@lang('basket.confirm')</a>
            </div>
        </div>
    </div>
</section>
@endif
@endsection
