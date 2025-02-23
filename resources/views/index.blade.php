@extends('layouts.master')
@section('title', 'Իջևան Մարկետ')
@section('content')
<form method="GET" action="{{ route('index') }}" class="p-3 border rounded shadow-sm bg-light">
    <div class="row align-items-center">
        <!-- Price filter -->
        <div class="col-md-4 d-flex">
            <div class="form-group me-2 w-50">
                <input type="number" name="price_from" id="price_from" class="form-control form-control-sm"
                       value="{{ request()->price_from }}" placeholder="Price From">
            </div>
            <div class="form-group w-50">
                <input type="number" name="price_to" id="price_to" class="form-control form-control-sm"
                       value="{{ request()->price_to }}" placeholder="Price To">
            </div>
        </div>

        <!-- Checkboxes -->
        <div class="col-md-4 d-flex justify-content-center">
            <div class="form-check form-check-inline">
                <input type="checkbox" name="hit" id="hit" class="form-check-input"
                       @if(request()->has('hit')) checked @endif>
                <label for="hit" class="form-check-label">Hit</label>
            </div>
            <div class="form-check form-check-inline">
                <input type="checkbox" name="new" id="new" class="form-check-input"
                       @if(request()->has('new')) checked @endif>
                <label for="new" class="form-check-label">New</label>
            </div>
            <div class="form-check form-check-inline">
                <input type="checkbox" name="recommend" id="recommend" class="form-check-input"
                       @if(request()->has('recommend')) checked @endif>
                <label for="recommend" class="form-check-label">Rec</label>
            </div>
        </div>

        <!-- Buttons -->
        <div class="col-md-4 d-flex justify-content-end">
            <button type="submit" class="btn btn-primary btn-sm me-2">
                <i class="fas fa-filter"></i> Apply
            </button>
            <a href="{{ route('index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-sync"></i> Reset
            </a>
        </div>
    </div>
</form>



    <div class="row">
        @foreach($products as $product)
            @include('card', compact('product'))
        @endforeach
    </div>
    {{$products->links()}}
@endsection

