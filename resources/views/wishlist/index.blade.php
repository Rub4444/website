@extends('layouts.master')
@section('title', 'Ցանկությունների ցանկ')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Ցանկությունների ցանկ</h2>

    <div class="row g-3" id="wishlist-container">
        @forelse($skus as $sku)
            {{-- @foreach($bestSkus as $bestSku) --}}
                <div class="col-6 col-xss-6 col-sm-4 col-lg-3 col-xxl-2 my-2 p-1 p-lg-2">
                    @include('card', compact('sku'))
                </div>
            {{-- @endforeach --}}
        @empty
            <div class="alert alert-info text-center">Ցանկությունների ցուցակը դատարկ է։</div>
        @endforelse
    </div>
    @if($recommendedSkus->isNotEmpty())
        <hr class="my-4">
        <h3 class="mb-3">Առաջարկում ենք նաև</h3>
        <div class="row g-3">
            @foreach($recommendedSkus as $sku)
                <div class="col-6 col-xss-6 col-sm-4 col-lg-3 col-xxl-2 my-2 p-1 p-lg-2">
                    @include('card', compact('sku'))
                </div>
            @endforeach
        </div>
    @endif

</div>

@endsection
