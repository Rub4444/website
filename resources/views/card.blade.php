<div class="col mb-4">
    <div class="card h-100 shadow-sm border-0 rounded-4 position-relative hover-shadow transition">

        {{-- <!-- Картинка -->
        <a href="{{ route('sku', [$sku->product->category->code, $sku->product->code, $sku]) }}">
            <div class="product-img-wrapper">
                <img src="{{ asset('storage/' . $sku->product->image) }}"
                     class="card-img-top img-fluid"
                     alt="{{ $sku->product->__('name') }}">
            </div>
        </a> --}}
        <a href="{{ route('sku', [$sku->product->category->code, $sku->product->code, $sku]) }}">
            <div class="product-img-wrapper">
                @if ($sku->image)
                    <img src="{{ asset('storage/' . $sku->image) }}"
                        class="card-img-top img-fluid"
                        alt="{{ $sku->product->__('name') }}">
                @else
                    <img src="{{ asset('img/no-image.png') }}"
                        class="card-img-top img-fluid"
                        alt="No image">
                @endif
            </div>
        </a>

        <!-- Бейджи -->
        <div class="position-absolute top-0 start-0 m-2 d-flex flex-column gap-1">
            @if($sku->product->isNew())
                <span class="badge bg-success">@lang('main.properties.new')</span>
            @endif
            @if($sku->product->isRecommend())
                <span class="badge bg-success">@lang('main.properties.recommend')</span>
            @endif
            @if($sku->product->isHit())
                <span class="badge bg-success">@lang('main.properties.hit')</span>
            @endif
        </div>

        <!-- Кнопка избранного -->
        @auth
            @php $isInWishlist = Auth::user()->hasInWishlist($sku->id); @endphp
            <button
                class="btn btn-sm shadow position-absolute top-0 end-0 m-2 toggle-wishlist rounded-circle d-flex align-items-center justify-content-center"
                data-id="{{ $sku->id }}"
                aria-pressed="{{ $isInWishlist ? 'true' : 'false' }}"
                style="z-index: 10; width: 36px; height: 36px; border: 2px solid white;"
                title="{{ $isInWishlist ? 'Удалить из избранного' : 'Добавить в избранное' }}">
                <i class="bi {{ $isInWishlist ? 'bi-heart-fill text-danger' : 'bi-heart' }}"></i>
            </button>
        @endauth

        <!-- Контент -->
        <div class="card-body text-center px-3 pb-3 pt-2 ">
            <a href="{{ route('sku', [$sku->product->category->code, $sku->product->code, $sku]) }}"
               class="text-decoration-none text-dark">
                <h6 class="card-title fw-semibold text-break">
                    {{-- {{ $sku->product->__('name') }} {{ $sku->propertyOptions->map->name->implode(', ') }} --}}
                    {{ $sku->product->__('name') }} {{ $sku->name ? ' ' . $sku->name : '' }} {{ $sku->propertyOptions->map->name->implode(', ') }}
                </h6>
            </a>

            <p class="card-text fw-bold text-success mb-2">{{ $sku->price }} {{ $currencySymbol }}</p>

            <!-- Описание в режиме списка -->
            <p class="card-text small text-muted description-for-list d-none">
                {{ Str::limit($sku->product->__('description'), 100) }}
            </p>

            <!-- Кнопка корзины -->
            <form action="{{ route('basket-add', $sku) }}" method="POST" class="mt-2">
                @csrf
                @if($sku->isAvailable())
                    <button type="submit" class="btn btn-success w-100">
                        <i class="bi bi-cart-plus me-1"></i> @lang('main.basket')
                    </button>
                @else
                    <button type="button" class="btn btn-outline-danger w-100" disabled>
                        @lang('main.available')
                    </button>
                @endif
            </form>
        </div>
    </div>
</div>

<style>
    /* Контейнер для изображения фиксированного размера */
    .product-img-wrapper {
        height: 200px;
        background-color: #f9f9f9;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        padding: 1rem;
        border-top-left-radius: 1rem;
        border-top-right-radius: 1rem;
    }
    .product-img-wrapper {
    width: 200px;
    height: 200px;
    margin: 0 auto; /* по центру */
}

    .product-img-wrapper img {
        max-height: 100%;
        max-width: 100%;
        object-fit: contain;
    }

    /* Плавный переход для избранного */
    .toggle-wishlist i {
        transition: color 0.3s ease;
    }

    /* Hover эффект тени */
    .hover-shadow:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        transition: box-shadow 0.3s ease-in-out;
    }
</style>
