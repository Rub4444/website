@extends('layouts.master')

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

@endsection
