@extends('layouts.master')

@section('title', 'Իջևան Մարկետ')

@section('content')
<div class="container my-4">
    <!-- Заголовок категории -->
    <div class="text-center mb-4" style="color: #2E8B57;">
        <h3 class="display-5 fw-bold">

            {{ $category->__('name') }}
        </h3>
        <p class="text-muted">{{ $category->__('description') }}</p>
    </div>

    <!-- Список товаров -->
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
        @foreach($category->products->map->skus->flatten() as $sku)
            @include('card', compact('sku'))
        @endforeach
    </div>
</div>
@endsection
