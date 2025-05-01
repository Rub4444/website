@extends('auth.layouts.master')

@isset($propertyOption)
    @section('title', 'Խմբագրել հատկության տարբերակ ' . $propertyOption->name)
@else
    @section('title', 'Ստեղծել հատկության տարբերակ')
@endisset

@section('content')
    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header text-white" style="background-color:#2E8B57;">
                @isset($propertyOption)
                    <h4>Խմբագրել հատկության տարբերակ <b>{{ $propertyOption->name }}</b></h4>
                @else
                    <h4>Ստեղծել հատկության տարբերակ</h4>
                @endisset
            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data"
                      @isset($propertyOption)
                      action="{{ route('property-options.update', [$property, $propertyOption]) }}"
                      @else
                      action="{{ route('property-options.store', $property) }}"
                    @endisset
                >
                    <div>
                        @isset($propertyOption)
                            @method('PUT')
                        @endisset
                        @csrf

                        <div>
                            <h2>Հատկություն՝ {{ $property->name }}</h2>
                        </div>

                        <div class="input-group row mb-3">
                            <label for="name" class="col-sm-2 col-form-label">Անուն: </label>
                            <div class="col-sm-6">
                                @error('name')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                                <input type="text" class="form-control" name="name" id="name"
                                       value="@isset($propertyOption){{ $propertyOption->name }}@endisset">
                            </div>
                        </div>

                        <div class="input-group row mb-3">
                            <label for="name_en" class="col-sm-2 col-form-label">Անուն (en): </label>
                            <div class="col-sm-6">
                                @error('name_en')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                                <input type="text" class="form-control" name="name_en" id="name_en"
                                       value="@isset($propertyOption){{ $propertyOption->name_en }}@endisset">
                            </div>
                        </div>

                        <button class="btn btn-success">Պահպանել</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
