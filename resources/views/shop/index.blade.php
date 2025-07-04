@extends('layouts.master')
@section('title', 'Իջևան Մարկետ')

@section('content')
<section class="shop-section py-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <button class="btn btn-outline-success d-lg-none" data-bs-toggle="offcanvas" data-bs-target="#filterOffcanvas">
                <i class="bi bi-sliders"></i> Ֆիլտրեր
            </button>
            <div class="btn-group d-lg-none" role="group" aria-label="View toggle">
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
                    <div class="btn-group mb-3" role="group">
                        <button type="button" class="btn btn-outline-secondary view-toggle active" data-view="grid">
                            <i class="bi bi-grid-3x3-gap-fill"></i>
                        </button>
                        <button type="button" class="btn btn-outline-secondary view-toggle" data-view="list">
                            <i class="bi bi-list-ul"></i>
                        </button>
                    </div>
                    @include('shop._filters')
                </div>
            </div>

            <!-- Продукты -->
            <div class="col-lg-9">
                <div class="row g-3" id="product-list">
                    @forelse($skus as $sku)
                        <div class="col-6 col-sm-6 col-md-4 col-lg-4 product-item">
                            @include('card', compact('sku'))
                        </div>
                    @empty
                       <div class="col-12">
                            <div class="text-center" style=" position: relative;padding: 1rem 1rem;margin-bottom: 1rem;border: 1px solid transparent;border-radius: 0.25rem; background-color:#6bc391;color:white;">@lang('main.there_are_no_suitable_products')</div>
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

<!-- Offcanvas Filters -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="filterOffcanvas" aria-labelledby="filterOffcanvasLabel">
    <div class="offcanvas-header">
        <h5 id="filterOffcanvasLabel">Ֆիլտրեր</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        @include('shop._filters')
    </div>
</div>
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
                    item.classList.remove('col-sm-6', 'col-md-4', 'col-lg-4');
                    item.classList.add('col-12');

                    card?.classList.add('flex-row', 'align-items-center');
                    description?.classList.remove('d-none');
                } else {
                    item.classList.remove('col-12');
                    item.classList.add('col-sm-6', 'col-md-4', 'col-lg-4');

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

@endsection
<style>
    /* Основные стили страницы */
.shop-section {
    background-color: #f8f9fa; /* светлый фон */
}

/* Панель фильтров */
.bg-light {
    background-color: #fff !important;
}

.shadow-sm {
    box-shadow: 0 .125rem .25rem rgba(0,0,0,.075) !important;
}

/* Карточки товаров */
.card {
    border: none;
    border-radius: 1rem;
    transition: box-shadow 0.3s ease;
}

.card:hover {
    box-shadow: 0 0.5rem 1rem rgba(25, 135, 84, 0.3);
}

/* Кнопки переключения вида */
.btn-group .btn {
    border-radius: 0.5rem;
    transition: background-color 0.3s ease, color 0.3s ease;
}

.btn-group .btn.active,
.btn-group .btn:hover {
    background-color: #198754;
    color: #fff;
    border-color: #198754;
}

/* Оффканвас */
.offcanvas {
    background-color: #fff;
}

/* Заголовок оффканвас */
.offcanvas-header h5 {
    font-weight: 600;
}

/* Кнопки фильтров и поиска */
.btn-outline-success {
    border-color: #198754;
    color: #198754;
    transition: all 0.3s ease;
}

.btn-outline-success:hover,
.btn-outline-success:focus {
    background-color: #198754;
    color: #fff;
    border-color: #198754;
}

/* Кнопка закрытия оффканвас */
.btn-close {
    outline: none;
}

/* Для мобильных фильтров */
@media (max-width: 991.98px) {
    .btn-group.d-lg-none {
        margin-left: auto;
    }
}

</style>
