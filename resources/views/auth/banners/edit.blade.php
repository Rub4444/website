@extends('auth.layouts.master')

@section('content')
<div class="container">
    <h1>Редактировать баннер</h1>

    <form action="{{ route('banners.update', $banner) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="image" class="form-label">Изображение (Desktop)</label>
            <input type="file" name="image" id="image" class="form-control">
            @if($banner->image)
                <img src="{{ Storage::url($banner->image) }}" width="200" class="mt-2">
            @endif
        </div>

        <div class="mb-3">
            <label for="img_mobile" class="form-label">Изображение (Mobile)</label>
            <input type="file" name="img_mobile" id="img_mobile" class="form-control">
            @if($banner->img_mobile)
                <img src="{{ Storage::url($banner->img_mobile) }}" width="100" class="mt-2">
            @endif
        </div>

        <div class="mb-3">
            <label for="title" class="form-label">Заголовок</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ $banner->title }}">
        </div>

        <div class="mb-3">
            <label for="link" class="form-label">Ссылка</label>
            <input type="url" name="link" id="link" class="form-control" value="{{ $banner->link }}">
        </div>

        <button type="submit" class="btn btn-success">Обновить</button>
    </form>
</div>
@endsection
