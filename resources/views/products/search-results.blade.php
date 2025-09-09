@extends('layouts.master')

@section('content')
<div class="container py-4">
    @if($skus->count())
        <!-- Заголовок поиска -->
        <div class="mb-4">
            <p class="text-muted">@lang('main.search_result') <<{{ $query }}>> ({{ $skus->count() }})</p>
        </div>
        <!-- Сетка карточек -->
        <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 g-3">
            @foreach($skus as $sku)
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm rounded-4 hover-shadow">
                        @include('card', compact('sku'))
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Пагинация -->
        <div class="d-flex justify-content-center mt-4">
            {{ $skus->links('vendor.custom') }}
        </div>

    @else
        <!-- Нет товаров -->
        <div class="d-flex justify-content-center align-items-center" style="min-height: 50vh;">
            <div class="text-center bg-warning-subtle p-4 rounded-4 shadow">
                <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="#856404" class="bi bi-exclamation-triangle mb-3" viewBox="0 0 16 16">
                    <path d="M7.938 2.016a.13.13 0 0 1 .125 0l6.857 3.964c.104.06.18.165.18.286v7.468a.285.285 0 0 1-.18.286l-6.857 3.964a.13.13 0 0 1-.125 0L1.08 14.02A.285.285 0 0 1 .9 13.734V6.266c0-.121.076-.226.18-.286L7.938 2.016zm.82 10.06a.823.823 0 1 1-1.647 0 .823.823 0 0 1 1.647 0zm-.823-6.579a.905.905 0 0 1 .9.899v3.242a.9.9 0 0 1-1.8 0V6.396a.905.905 0 0 1 .9-.899z"/>
                </svg>
                <h4 class="fw-bold mb-2" style="color: #856404;">@lang('main.no_products_found')</h4>
                <p class="text-muted">@lang('main.sorry_but_we_cant_find')</p>
            </div>
        </div>
    @endif
</div>

<style>
/* Hover-эффект на карточках */
.hover-shadow:hover {
    transform: translateY(-3px);
    transition: 0.3s ease-in-out;
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
}
</style>
@endsection
