@extends('auth.layouts.master')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow border-0 rounded-4">
                <div class="card-header text-white rounded-top-4" style="background-color:#2E8B57;">
                    <h4 class="mb-0">
                        @isset($category)
                            Փոփոխել Կատեգորիա <b>{{ $category->name }}</b>
                        @else
                            Ավելացնել Կատեգորիա
                        @endisset
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data"
                        @isset($category)
                            action="{{ route('categories.update', $category) }}"
                        @else
                            action="{{ route('categories.store') }}"
                        @endisset
                    >
                        @csrf
                        @isset($category)
                            @method('PUT')
                        @endisset

                        <div class="mb-3">
                            <label for="code" class="form-label">Կոդ</label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror" name="code" id="code"
                                   value="{{ old('code', isset($category) ? $category->code : '') }}">
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Անուն</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name"
                                   value="{{ old('name', isset($category) ? $category->name : '') }}">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="name_en" class="form-label">Անուն en</label>
                            <input type="text" class="form-control @error('name_en') is-invalid @enderror" name="name_en" id="name_en"
                                   value="{{ old('name_en', isset($category) ? $category->name_en : '') }}">
                            @error('name_en')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Նկարագրություն</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" name="description" id="description" rows="4">{{ old('description', isset($category) ? $category->description : '') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description_en" class="form-label">Նկարագրություն en</label>
                            <textarea class="form-control @error('description_en') is-invalid @enderror" name="description_en" id="description_en" rows="4">{{ old('description_en', isset($category) ? $category->description_en : '') }}</textarea>
                            @error('description_en')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="image" class="form-label d-block">Նկար</label>
                            @isset($category->image)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $category->image) }}" alt="Категория" width="120" class="rounded shadow-sm">
                                    <p class="text-muted mt-1">Տեղադրված նկար</p>
                                </div>
                            @endisset
                            <input class="form-control" type="file" name="image" id="image">
                        </div>

                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle me-1"></i>Պահպանել
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
