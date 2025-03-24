<div class="col md-28">
    <div class="product__items ">
        <div class="product__items--thumbnail">
            <a class="product__items--link"
                href="{{ route('sku', [$sku->product->category->code, $sku->product->code, $sku->id]) }}">
                <img class="product__items--img product__primary--img" src="{{ asset('storage/' . $sku->product->image) }}" alt="product-img">
            </a>
            <div class="product__badge">
                @if($sku->product->isNew())
                    <span class="product__badge--items new">@lang('main.properties.new')</span>
                @endif
                @if($sku->product->isRecommend())
                    <span class="product__badge--items recommend">@lang('main.properties.recommend')</span>
                @endif
                @if($sku->product->isHit())
                    <span class="product__badge--items hit">@lang('main.properties.hit')</span>
                @endif
            </div>
        </div>
        <div class="product__items--content text-center">
            <h3 class="product__items--content__title h4">
                <a href="{{ route('sku', [$sku->product->category->code, $sku->product->code, $sku->id]) }}">
                    {{$sku->product->__('name')}}
                </a>
            </h3>

            @isset ($sku->product->properties)
                @foreach ($sku->propertyOptions as $propertyOption)
                    <h4>
                        {{$propertyOption->property->__('name')}}: {{$propertyOption->__('name')}}
                    </h4>
                @endforeach
            @endisset
            <span class="current__price">{{$sku->count}} հատ</span>

            <div class="product__items--price">
                <span class="current__price">{{$sku->price}} {{ $currencySymbol }} </span>
            </div>
            <form action="{{route('basket-add', $sku)}}" method="POST">
                @if($sku->isAvailable())
                    <button class="add__to--cart__btn" type="submit">@lang('main.cart')</button>
                @else
                    <p class="add__to--cart__btn">@lang('main.available')</p>
                @endif
                @csrf
            </form>
        </div>
    </div>
</div>
