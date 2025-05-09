@extends('auth.layouts.master')

@isset($sku)
    @section('title', 'Խմբագրել Sku ' . $sku->name)
@else
    @section('title', 'Ստեղծել Sku')
@endisset

@section('content')
    <div class="col-md-12">
        @isset($sku)
            <h1>Խմբագրել Sku <b>{{ $sku->name }}</b></h1>
        @else
            <h1>Ավելացնել Sku</h1>
        @endisset

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

           <!-- Price Input -->
            <div class="input-group row mb-3">
                <label for="price" class="col-sm-2 col-form-label">Գինը:</label>
                <div class="col-sm-2">
                    @include('auth.layouts.error', ['fieldName' => 'price'])
                    <input type="number" class="form-control" name="price"
                        value="{{ old('price', isset($sku) ? $sku->price : '') }}">
                </div>
            </div>

            <!-- Quantity Input -->
            <div class="input-group row mb-3">
                <label for="count" class="col-sm-2 col-form-label">Քանակը:</label>
                <div class="col-sm-2">
                    @include('auth.layouts.error', ['fieldName' => 'count'])
                    <input type="number" class="form-control" name="count"
                           value="{{ old('count', $sku->count ?? '') }}">
                </div>
            </div>

           <!-- Properties -->
            {{-- @foreach ($product->properties as $property)
            <div class="input-group row mb-3">
                <label class="col-sm-2 col-form-label">{{ $property->name }}:</label>
                <div class="col-sm-6">
                    <select name="property_id[{{ $property->id }}]" class="form-control">
                        @foreach ($property->propertyOptions as $propertyOption)
                            <option value="{{ $propertyOption->id }}"
                                @if (old("property_id.{$property->id}", isset($sku) ? $sku->propertyOptions->pluck('id', 'property_id')[$property->id] ?? null : null) == $propertyOption->id)
                                    selected
                                @endif>
                                {{ $propertyOption->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            @endforeach --}}
            @foreach ($product->properties as $property)

                    <div class="input-group row">
                        <label for="property_id[{{ $property->id }}]" class="col-sm-2 col-form-label">{{ $property->name }}: </label>
                        <div class="col-sm-6">
                            <select name="property_id[{{ $property->id }}]" class="form-control">
                                @foreach($property->propertyOptions as $propertyOption)
                                    <option value="{{ $propertyOption->id }}"
                                        @isset($skus)
                                        @if($skus->propertyOptions->contains($propertyOption->id))
                                            selected
                                        @endif
                                        @endisset
                                    >{{ $propertyOption->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endforeach



            <!-- Submit -->
            <div class="row">
                <div class="col-sm-10">
                    <button class="btn btn-success">Պահպանել</button>
                </div>
            </div>
        </form>
    </div>
@endsection
