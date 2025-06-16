@extends('layouts.master')

@section('title', 'Իջևան Մարկետ')

@section('content')

    @if (($category->code ?? $skus->product->category->code) === 'alkvohvolayin-khmichqner')
        @include('partials.age-check-modal')
    @endif

    <div class="container py-4">
        <!-- Категория: Заголовок -->
        <div class="text-center mb-5">
            <h1 class="fw-bold text-success display-5">{{ $category->__('name') }}</h1>
            <p class="text-muted fs-5">{{ $category->__('description') }}</p>
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
