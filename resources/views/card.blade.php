<div class="col md-28">
    <div class="product__items ">
        <div class="product__items--thumbnail">
            <a class="product__items--link"  href="{{ route('product', [$product->category->code, $product->code]) }}">
                <img class="product__items--img product__primary--img" src="{{ asset('storage/' . $product->image) }}" alt="product-img">
            </a>
            <div class="product__badge">
                {{-- <span class="product__badge--items sale">Sale</span> --}}
                @if($product->isNew())
                    <span class="product__badge--items new">@lang('main.properties.new')</span>
                @endif
                @if($product->isRecommend())
                    <span class="product__badge--items recommend">@lang('main.properties.recommend')</span>
                @endif
                @if($product->isHit())
                    <span class="product__badge--items hit">@lang('main.properties.hit')</span>
                @endif
            </div>
        </div>
        <div class="product__items--content text-center">
            <h3 class="product__items--content__title h4"><a href="product-details.html">{{$product->__('name')}}</a></h3>
            <div class="product__items--price">
                <span class="current__price">{{$product->price}} {{App\Services\CurrencyConversion::getCurrencySymbol()}} </span>
            </div>
            <form action="{{route('basket-add', $product)}}" method="POST">
                @if($product->isAvailable())
                    <button class="add__to--cart__btn" type="submit">@lang('main.cart')</button>
                @else
                    <p class="add__to--cart__btn">@lang('main.available')</p>
                @endif
                @csrf
            </form>
        </div>
    </div>
</div>

