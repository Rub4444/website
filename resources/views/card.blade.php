<div class="col">
    <div class="card shadow-sm h-100 border-0">
        <div class="position-relative bg-light" style="height: 200px;">
            <a href="{{ route('sku', [$sku->product->category->code, $sku->product->code, $sku->id]) }}">
                <img src="{{ asset('storage/' . $sku->product->image) }}"
                    class="card-img-top p-3"
                    style="height: 100%; object-fit: contain;"
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
        </div>

        <div class="card-body text-center d-flex flex-column justify-content-between">
            <a href="{{ route('sku', [$sku->product->category->code, $sku->product->code, $sku->id]) }}"
                class="text-decoration-none text-dark">
                <h6 class="card-title fw-semibold">
                    {{ $sku->product->__('name') }}
                    @isset ($sku->product->properties)
                        @foreach ($sku->propertyOptions as $propertyOption)
                            {{ $propertyOption->__('name') }}
                        @endforeach
                    @endisset
                </h6>
            </a>

            <p class="fw-bold mb-2">{{ $sku->price }} {{ $currencySymbol }}</p>

            <form action="{{ route('basket-add', $sku) }}" method="POST" class="mt-auto">
                @csrf
                @if($sku->isAvailable())
                    <button type="submit" class="btn btn-success w-100">
                        <i class="bi bi-cart-plus"></i> @lang('main.basket')
                    </button>
                @else
                    <button class="btn btn-outline-danger w-100" disabled>@lang('main.available')</button>
                @endif
            </form>
        </div>
    </div>
</div>
