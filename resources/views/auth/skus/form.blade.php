@extends('auth.layouts.master')

@isset($sku)
    @section('title', 'Խմբագրել Sku ' . $sku->name)
@else
    @section('title', 'Ստեղծել Sku')
@endisset

@section('content')
<div class="container mt-4">
    <div class="card shadow rounded-4">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">
                @isset($sku)
                    Խմբագրել SKU <b>{{ $sku->name }}</b>
                @else
                    Ավելացնել SKU
                @endisset
            </h4>
        </div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data"
                  @isset($sku)
                      action="{{ route('skus.update', [$product, $sku]) }}"
                  @else
                      action="{{ route('skus.store', $product) }}"
                  @endisset>
                @csrf
                @isset($sku)
                    @method('PUT')
                @endisset

                {{-- Price --}}
                <div class="mb-3">
                    <label for="price" class="form-label">Գինը:</label>
                    @include('auth.layouts.error', ['fieldName' => 'price'])
                    <input type="number" class="form-control" name="price"
                           value="{{ old('price', isset($sku) ? $sku->price : '') }}" required>
                </div>

                {{-- Count --}}
                <div class="mb-3">
                    <label for="count" class="form-label">Քանակը:</label>
                    @include('auth.layouts.error', ['fieldName' => 'count'])
                    <input type="number" class="form-control" name="count"
                           value="{{ old('count', $sku->count ?? '') }}" required>
                </div>

                {{-- Properties --}}
                @foreach ($product->properties as $property)
                    <div class="mb-3">
                        <label for="property_id[{{ $property->id }}]" class="form-label">{{ $property->name }}:</label>
                        <select name="property_id[{{ $property->id }}]" class="form-select" required>
                            @foreach($property->propertyOptions as $propertyOption)
                                <option value="{{ $propertyOption->id }}"
                                    @isset($sku)
                                        @if($sku->propertyOptions->contains($propertyOption->id)) selected @endif
                                    @endisset
                                >
                                    {{ $propertyOption->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endforeach

                {{-- Image Upload --}}
                <div class="mb-3">
                    <label for="image" class="form-label">Նկար:</label>
                    <input type="file" name="image" class="form-control">
                </div>

                {{-- Image Preview --}}
                @if (isset($sku) && $sku->image)
                    <div class="mb-3">
                        <label class="form-label">Ներկա Նկարը:</label><br>
                        <img src="{{ Storage::url($sku->image) }}" alt="SKU Image" class="img-thumbnail" style="max-width: 150px;">
                    </div>
                @endif

                {{-- Submit --}}
                <div class="d-grid">
                    <button type="submit" class="btn btn-success btn-lg">Պահպանել</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
