@extends('auth.layouts.master')


@section('content')
<div class="container">
    <h1>Добавить баннер</h1>

    <form method="POST" action="{{ route('banners.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label">Заголовок</label>
            <input type="text" name="title" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Ссылка (опционально)</label>
            <input type="url" name="link" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Изображение</label>
            <input type="file" name="image" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Сохранить</button>
    </form>
</div>
@endsection
