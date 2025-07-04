<div class="col">
    <div class="card h-100 shadow-sm border-0 rounded-4 text-center py-4 d-flex flex-column align-items-center justify-content-between position-relative">

        {{-- Картинка с бейджами --}}
        <a href="{{ route('sku', [$sku->product->category->code, $sku->product->code, $sku]) }}" class="text-decoration-none position-relative">
            <div class="product-img-wrapper mb-3 position-relative d-inline-block">
                <img src="{{ $sku->image ? asset('storage/' . $sku->image) : asset('img/no-image.png') }}"
                     class="rounded-circle border"
                     style="width: 120px; height: 120px; object-fit: cover;"
                     alt="{{ $sku->product->__('name') }}">

                {{-- Бейджи --}}
                <div class="badges-wrapper position-absolute top-0 start-0 translate-middle m-1 d-flex flex-column gap-1">
                    @if($sku->product->isNew())
                        <span class="badge rounded-pill bg-success badge-custom">New</span>
                    @endif
                    @if($sku->product->isRecommend())
                        <span class="badge rounded-pill bg-primary badge-custom">Recommend</span>
                    @endif
                    @if($sku->product->isHit())
                        <span class="badge rounded-pill bg-danger badge-custom">Hit</span>
                    @endif
                </div>
            </div>
        </a>

        {{-- Название --}}
        <a href="{{ route('sku', [$sku->product->category->code, $sku->product->code, $sku]) }}"
           class="text-dark text-decoration-none">
            <h6 class="fw-semibold mb-1">
                {{ $sku->product->__('name') }}
            </h6>
        </a>

        {{-- Цена --}}
        <p class="fw-bold text-success mb-3">{{ $sku->price }} {{ $currencySymbol }}</p>

        {{-- Кнопка корзины --}}
        <form action="{{ route('basket-add', $sku) }}" method="POST" class="w-100 px-4">
            @csrf
            @if($sku->isAvailable())
                <button type="submit" class="btn btn-primary rounded-pill w-100 py-2">
                    <i class="bi bi-cart-plus text-white me-2"></i> @lang('main.basket')
                </button>
            @else
                <button type="button" class="btn btn-outline-danger rounded-pill w-100 py-2" disabled>
                    @lang('main.available')
                </button>
            @endif
        </form>
    </div>
</div>

<style>
    .badges-wrapper {
    z-index: 20;
}

.badge-custom {
    font-size: 0.95rem;
    padding: 0.25em 0.5em;
    color: white;
    box-shadow: 0 0 4px rgba(0,0,0,0.15);
    user-select: none;
}

    .product-img-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .card:hover {
        transform: translateY(-4px);
        transition: 0.3s ease;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1) !important;
    }

    .btn i {
        pointer-events: none;
    }
</style>
