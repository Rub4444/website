@extends('layouts.master')
@section('title', 'Իջևան Մարկետ')

@section('content')
<section class="shop-section py-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <button class="btn btn-outline-success d-lg-none" data-bs-toggle="offcanvas" data-bs-target="#filterOffcanvas">
                <i class="bi bi-sliders"></i> Ֆիլտրեր
            </button>
            <div class="btn-group d-lg-none" role="group">
                <button type="button" class="btn btn-outline-secondary view-toggle active" data-view="grid">
                    <i class="bi bi-grid-3x3-gap-fill"></i>
                </button>
                <button type="button" class="btn btn-outline-secondary view-toggle" data-view="list">
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
                            <div class="alert alert-info text-center">@lang('main.there_are_no_suitable_products')</div>
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
