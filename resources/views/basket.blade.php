@extends('layouts.master')
@section('title', 'Իջևան Մարկետ')
@section('content')
<div class="panel">
@if($order)
    <table class="table table-striped">
        <thead>
            <tr>
                <th>@lang('basket.name')</th>
                <th>@lang('basket.count')</th>
                <th>@lang('basket.price')</th>
                <th>@lang('basket.cost')</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->products()->with('category')->get() as $product)
                <tr>
                    <td>
                        <a href="{{ route('product', [$product->category->code, $product->code]) }}">
                            <img height="56px" src="{{ Storage::url($product->image) }}" alt="">
                            {{ $product->name }}
                        </a>
                    </td>
                    <td>
                        <span class="badge" style="color:black;">{{ $product->pivot->count }}</span>
                        <div>
                            <form action="{{ route('basket-remove', $product) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger">
                                    <span class="glyphicon glyphicon-minus" aria-hidden="true">-</span>
                                </button>
                            </form>
                            <form action="{{ route('basket-add', $product) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    <span class="glyphicon glyphicon-plus" aria-hidden="true">+</span>
                                </button>
                            </form>
                        </div>
                    </td>
                    <td>{{ $product->price }} AMD</td>
                    <td>{{ $product->getPriceForCount() }} AMD</td>
                </tr>
            @endforeach
            <tr>
                <td>Full price:</td>
                <td>{{ $order->getFullSum() }} AMD</td>
            </tr>
        </tbody>
    </table>
@else
    <p>Your basket is empty.</p>
@endif

    <div class="row">
        <br>
        <div class="btn-group pull-right" role="group">
            <a type="button" class="btn btn-success" href="{{route('basket-place')}}">Confirm</a>
        </div>
    </div>
</div>
@endsection

