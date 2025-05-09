@extends('layouts.master')
@section('title', 'Իջևան Մարկետ')
@section('content')

<h1>{{$category->__('name')}} <i class="{{ $category->icon }}"></i></h1>
<p>{{$category->__('description')}}</p>

<div class="row">
    @foreach($category->products->map->skus->flatten() as $sku)
        @include('card', compact('sku'))
    @endforeach
</div>

@endsection

