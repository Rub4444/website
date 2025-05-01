@extends('auth.layouts.master')

@isset($property)
    @section('title', 'Խմբագրել հատկություն ' . $property->name)
@else
    @section('title', 'Ստեղծել հատկություն')
@endisset

@section('content')
    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header text-white" style="background-color:#2E8B57;">
                @isset($property)
                    <h4>Խմբագրել Հատկություն <b>{{ $property->name }}</b></h4>
                @else
                    <h4>Ավելացնել Հատկություն</h4>
                @endisset
            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data"
                      @isset($property)
                      action="{{ route('properties.update', $property) }}"
                      @else
                      action="{{ route('properties.store') }}"
                    @endisset
                >
                    @isset($property)
                        @method('PUT')
                    @endisset
                    @csrf

                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-form-label">Անուն:</label>
                        <div class="col-sm-9">
                            @error('name')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            <input type="text" class="form-control" name="name" id="name"
                                   value="@isset($property){{ $property->name }}@endisset" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="name_en" class="col-sm-3 col-form-label">Անուն (en):</label>
                        <div class="col-sm-9">
                            @error('name_en')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            <input type="text" class="form-control" name="name_en" id="name_en"
                                   value="@isset($property){{ $property->name_en }}@endisset" required>
                        </div>
                    </div>

                    <div class="form-group text-center">
                        <button class="btn btn-success btn-lg">Պահպանել</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
