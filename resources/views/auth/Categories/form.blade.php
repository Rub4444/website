@extends('auth.layouts.master')

@section('content')
    <div class="col-md-12">
        @isset($category)
            <h1>Редактировать Категорию <b>{{ $category->name }}</b></h1>
        @else
            <h1>Добавить Категорию</h1>
        @endisset
        <form method="POST" enctype="multipart/form-data"
                    @isset($category)
                        action="{{ route('categories.update', $category) }}"
                    @else
                        action="{{ route('categories.store') }}"
                    @endisset
                >
            <div>
                @isset($category)
                    @method('PUT')
                @endisset
                @csrf
                <div class="input-group row">
                    <label for="code" class="col-sm-2 col-form-label">Код: </label>
                    <div class="col-sm-6">
                        @error('code')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        <input type="text" class="form-control" name="code" id="code"
                               value="{{ old('code', isset($category) ? $category->code : null) }}">
                    </div>
                </div>
                <br>
                <div class="input-group row">
                    <label for="name" class="col-sm-2 col-form-label">Название: </label>
                    <div class="col-sm-6">
                        @error('name')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        <input type="text" class="form-control" name="name" id="name"
                               value="{{ old('name', isset($category) ? $category->name : null) }}">
                    </div>
                </div>
                <br>
                <div class="input-group row">
                    <label for="name_en" class="col-sm-2 col-form-label">Название en: </label>
                    <div class="col-sm-6">
                        @error('name_en')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        <input type="text" class="form-control" name="name_en" id="name_en"
                               value="{{ old('name_en', isset($category) ? $category->name_en : null) }}">
                    </div>
                </div>
                <br>
                <div class="input-group row">
                    <label for="description" class="col-sm-2 col-form-label">Описание: </label>
                    <div class="col-sm-6">
                        @error('description')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        <textarea name="description" id="description" cols="72" rows="7">{{ old('description', isset($category) ? $category->description : null) }}</textarea>
                    </div>
                </div>
                <br>
                <div class="input-group row">
                    <label for="description_en" class="col-sm-2 col-form-label">Описание en: </label>
                    <div class="col-sm-6">
                        @error('description_en')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        <textarea name="description_en" id="description_en" cols="72" rows="7">{{ old('description_en', isset($category) ? $category->description_en : null) }}</textarea>
                    </div>
                </div>
                <br>
                <div class="input-group row">
                    <label for="image" class="col-sm-2 col-form-label">Картинка: </label>
                    <div class="col-sm-10">
                        @isset($category->image)
                            <div>
                                <img src="{{ asset('storage/' . $category->image) }}" alt="Категория" width="100">
                                <br>
                                <span>Текущее изображение</span>
                            </div>
                        @endisset
                        <label class="btn btn-default btn-file">
                            Загрузить <input type="file" style="display: none;" name="image" id="image">
                        </label>
                    </div>
                </div>
                <br>
                <button class="btn btn-success">Сохранить</button>
            </div>
        </form>
    </div>
@endsection
