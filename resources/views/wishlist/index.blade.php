@extends('layouts.master')

@section('content')
<!-- cart section start -->
<section class="cart__section section--padding">
    <div class="container">
        <div class="cart__section--inner">
            <form action="#">
                <div class="cart__table">
                    <table class="cart__table--inner">
                        <thead class="cart__table--header">
                            <tr class="cart__table--header__items">
                                <th class="cart__table--header__list">@lang('main.product')</th>
                                <th class="cart__table--header__list">@lang('main.price')</th>
                                <th class="cart__table--header__list text-center">@lang('main.stock_status')</th>
                                <th class="cart__table--header__list text-right">@lang('main.add_to_card')</th>
                            </tr>
                        </thead>
                        <tbody class="cart__table--body">
                            @forelse($skus as $sku)
                                <tr class="cart__table--body__items">
                                    <td class="cart__table--body__list">
                                        <div class="cart__product d-flex align-items-center">
                                            @auth
                                                @php $isInWishlist = Auth::user()->hasInWishlist($sku->id); @endphp
                                                <button
                                                    class="btn btn-sm shadow position-absolute me-2 toggle-wishlist rounded-circle d-flex align-items-center justify-content-center"
                                                    data-id="{{ $sku->id }}"
                                                    aria-pressed="{{ $isInWishlist ? 'true' : 'false' }}"
                                                    style="z-index: 10; width: 36px; height: 36px; border: 2px solid white;"
                                                    title="{{ $isInWishlist ? 'Удалить из избранного' : 'Добавить в избранное' }}">
                                                    <i class="bi {{ $isInWishlist ? 'bi-heart-fill text-danger' : 'bi-heart ' }}"></i>
                                                </button>
                                            @endauth

                                            <div class="cart__thumbnail" >
                                                <a href="{{ route('sku', [$sku->product->category->code, $sku->product->code, $sku]) }}">
                                                    <img class="border-radius-5" width="50" height="50" src="{{ asset('storage/' . $sku->product->image) }}" alt="{{ $sku->product->__('name') }}">
                                                </a>
                                            </div>
                                            <div class="cart__content">
                                                <h3 class="cart__content--title h4"><a href="product-details.html">{{ $sku->product->__('name') }}</a></h3>
                                                @foreach ($sku->propertyOptions as $option)
                                                    <span class="cart__content--variant">
                                                        {{ $option->property->name }}: {{ $option->name }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    </td>
                                    <td class="cart__table--body__list">
                                        <span class="cart__price">{{ $sku->price }} {{ $currencySymbol }}</span>
                                    </td>
                                    <td class="cart__table--body__list text-center">
                                        @if ($sku->count > 0)
                                            <span class="in__stock text__secondary">@lang('main.in_stock')</span>
                                        @else
                                            <span class="text-danger">@lang('main.out_off_stock')</span>
                                        @endif
                                    </td>
                                    <td class="cart__table--body__list text-right ">
                                        <form action="{{ route('basket-add', $sku) }}" method="POST" class="mt-2">
                                            @csrf
                                            @if($sku->isAvailable())
                                                <button type="submit" class="btn btn-success btn-sm">
                                                    <i class="bi bi-cart-plus me-1"></i> @lang('main.basket')
                                                </button>
                                            @else
                                                <button type="button" class="btn btn-outline-danger btn-sm" disabled>
                                                    @lang('main.available')
                                                </button>
                                            @endif
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <div class="col-12">
                                    <div class="text-center" style=" position: relative;padding: 1rem 1rem;margin-bottom: 1rem;border: 1px solid transparent;border-radius: 0.25rem; background-color:#6bc391;color:white;">@lang('main.there_are_no_suitable_products')</div>
                                </div>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="continue__shopping d-flex justify-content-between">
                        <a class="continue__shopping--link" href="{{route('index')}}">@lang('basket.continue_shopping')</a>
                        <a class="continue__shopping--clear" href="{{route('shop')}}">@lang('main.view_all_products')</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
<!-- cart section end -->
@endsection

