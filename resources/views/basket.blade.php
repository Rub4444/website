@extends('layouts.master')
@section('title', 'Իջևան Մարկետ')
@section('content')
@if($order)
<section class="py-5 bg-light rounded-top">
    <div class="container">
        <h2 class="mb-4 text-center">Ձեր զամբյուղը</h2>
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
                    @php
                        $fullPrice = 0;
                    @endphp

                    @foreach($order->skus as $sku)

                    @php
                        $fullPrice += $sku->price * $sku->countInOrder;
                    @endphp

                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <a href="{{ route('sku', [$sku->product->category->code, $sku->product->code, $sku->id]) }}">
                                    <img src="{{ Storage::url($sku->product->image) }}" alt="Product" class="rounded me-3" width="70" height="70">
                                </a>
                                <div>
                                    <h6 class="mb-0">{{ $sku->product->__('name') }}</h6>
                                    {{-- Optional additional info --}}
                                    {{-- <small class="text-muted">COLOR: Blue, WEIGHT: 2Kg</small> --}}
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

        <div class="d-flex justify-content-between mt-4">
            <h5>@lang('basket.cost'): <strong>{{ $fullPrice }} {{ $currencySymbol }}</strong></h5>
            <a href="{{ route('index') }}" class="btn btn-outline-secondary">@lang('basket.continue_shopping')</a>
            <a href="{{ route('basket-place') }}" class="btn btn-primary">@lang('basket.confirm')</a>
        </div>
    </div>
</section>
@else
    <div class="container py-5">
        <div class="alert alert-info text-center">@lang('basket.basket_is_empty')</div>
    </div>
@endif
@endsection
