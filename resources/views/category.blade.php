{{-- @extends('layouts.master')

@section('title', 'Իջևան Մարկետ')

@section('content')

    @if (in_array(($category->code ?? $skus->product->category->code), ['alkvohvolayin-khmichqner', 'cxaxotner']))
        @include('partials.age-check-modal')
    @endif

    <div class="container py-4">
    <!-- Категория: Заголовок -->
    <div class="mb-4">
        <h4 class="fw-bold text-success mb-1">{{ $category->__('name') }}</h4>
        <p class="text-muted small">{{ $category->__('description') }}</p>
    </div>

    <!-- Список товаров -->
    <div class="row g-4">
        @foreach($category->products->map->skus->flatten() as $sku)
            <div class="col-6 col-sm-6 col-md-4 col-lg-3">
                @include('card', compact('sku'))
            </div>
        @endforeach
    </div>
</div>

@endsection --}}
@extends('layouts.master')

@section('title', 'Իջևան Մարկետ')

@section('content')

@if (in_array(($category->code ?? $skus->product->category->code), ['alkvohvolayin-khmichqner', 'cxaxotner']))
    @include('partials.age-check-modal')
@endif

<section class="shop-section ">
    <nav aria-label="breadcrumb" class="mb-3 bg-light p-2 rounded">
        <ol class="breadcrumb mb-0" style="padding-left: 6rem;">
            <li class="breadcrumb-item">
                <a href="{{ route('index') }}">@lang('main.home')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('shop') }}">@lang('main.shop')</a>
            </li>
            <li class="breadcrumb-item active text-success" aria-current="page">
                {{ $category->__('name') }}
            </li>
        </ol>
    </nav>
    <div class="container">

        <!-- 🔹 Мобильная панель действий -->
        <div class="d-flex justify-content-between align-items-center mb-3 d-lg-none">
            <!-- Кнопка фильтров -->
            <button class="btn btn-outline-success" data-bs-toggle="offcanvas" data-bs-target="#filterOffcanvas">
                <i class="bi bi-sliders"></i> Ֆիլտրեր
            </button>

            <!-- Переключение вида -->
            <div class="btn-group" role="group" aria-label="View toggle">
                <button type="button" class="btn btn-outline-secondary view-toggle active" data-view="grid" aria-pressed="true" title="Ցանցային տեսք">
                    <i class="bi bi-grid-3x3-gap-fill"></i>
                </button>
                <button type="button" class="btn btn-outline-secondary view-toggle" data-view="list" aria-pressed="false" title="Ցանկային տեսք">
                    <i class="bi bi-list-ul"></i>
                </button>
            </div>
        </div>

        <div class="row">
            <!-- Sidebar фильтры -->
            <div class="col-lg-3 d-none d-lg-block">
                <div class="p-3 bg-light rounded shadow-sm">
                    @include('shop._filters')
                </div>
            </div>

            <!-- Продукты -->
            <div class="col-lg-9">
                <div class="row g-3" id="product-list">
                    @forelse($skus as $sku)
                        <div class="col-6 col-sm-6 col-md-4 col-lg-3 product-item">
                            @include('card', compact('sku'))
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="text-center alert alert-success">
                                @lang('main.there_are_no_suitable_products')
                            </div>
                        </div>
                    @endforelse
                </div>

                <div class="mt-4">
                    {{ $skus->withQueryString()->links('vendor.custom') }}
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Offcanvas Filters (для мобилок) -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="filterOffcanvas" aria-labelledby="filterOffcanvasLabel">
    <div class="offcanvas-header">
        <h5 id="filterOffcanvasLabel">Ֆիլտրեր</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        @include('shop._filters')
    </div>
</div>

@endsection
<script>
document.addEventListener('DOMContentLoaded', function () {
    const buttons = document.querySelectorAll('.view-toggle');
    const items = document.querySelectorAll('.product-item');
    const savedView = localStorage.getItem('view') || 'grid';

    function applyView(view) {
        buttons.forEach(btn => btn.classList.remove('active'));
        document.querySelector(`.view-toggle[data-view="${view}"]`)?.classList.add('active');

        items.forEach(item => {
            const card = item.querySelector('.card');
            const description = item.querySelector('.description-for-list');

            if (view === 'list') {
                item.classList.remove('col-sm-6', 'col-md-4', 'col-lg-3');
                item.classList.add('col-12');

                card?.classList.add('flex-row', 'align-items-center');
                description?.classList.remove('d-none');
            } else {
                item.classList.remove('col-12');
                item.classList.add('col-sm-6', 'col-md-4', 'col-lg-3');

                card?.classList.remove('flex-row', 'align-items-center');
                description?.classList.add('d-none');
            }
        });
    }

    applyView(savedView);

    buttons.forEach(button => {
        button.addEventListener('click', () => {
            const view = button.dataset.view;
            localStorage.setItem('view', view);
            applyView(view);
        });
    });
});
</script>
