<div class="col mb-1">
    <div class="card shadow-sm">
        <a href="{{ route('sku', [$sku->product->category->code, $sku->product->code, $sku]) }}">
            <img src="{{ asset('storage/' . $sku->product->image) }}"
            class="card-img-top img-fluid"
            style="height: 150px; object-fit: contain; background-color: #f8f9fa;"
            alt="{{ $sku->product->__('name') }}">

        </a>
        <div class="position-absolute top-0 start-0 m-2">
            @if($sku->product->isNew())
                <span class="badge bg-success">@lang('main.properties.new')</span>
            @endif
            @if($sku->product->isRecommend())
                <span class="badge bg-primary">@lang('main.properties.recommend')</span>
            @endif
            @if($sku->product->isHit())
                <span class="badge bg-danger">@lang('main.properties.hit')</span>
            @endif
        </div>
        <div class="card-body text-center">
            <a href="{{ route('sku', [$sku->product->category->code, $sku->product->code, $sku]) }}" class="text-decoration-none text-dark">
                <h5 class="card-title">{{ $sku->product->__('name') }} {{ $sku->propertyOptions->map->name->implode(', ') }}</h5>
            </a>
            <p class="card-text fw-bold">{{ $sku->price }} {{ $currencySymbol }}</p>
            <!-- Описание (только в режиме списка) -->
            <p class="card-text text-muted d-none d-md-block description-for-list d-none">
                {{ Str::limit($sku->product->__('description'), 150) }}
            </p>
            <form action="{{ route('basket-add', $sku) }}" method="POST">
                @csrf
                @if($sku->isAvailable())
                    <button type="submit" class="btn btn-success w-100">
                        <i class="bi bi-cart-plus"></i> @lang('main.basket')
                    </button>
                @else
                    <span class="btn btn-outline-danger w-100 disabled">@lang('main.available')</span>
                @endif
            </form>
        </div>
    </div>
</div>
