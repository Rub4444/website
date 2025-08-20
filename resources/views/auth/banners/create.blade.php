@extends('auth.layouts.master')


@section('content')
<div class="container">
    <h1>Добавить баннер</h1>

    <form action="{{ route('banners.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="mb-3">
        <label for="image" class="form-label">Изображение (Desktop)</label>
        <input type="file" name="image" id="image" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="img_mobile" class="form-label">Изображение (Mobile)</label>
        <input type="file" name="img_mobile" id="img_mobile" class="form-control">
    </div>

    <div class="mb-3">
        <label for="title" class="form-label">Заголовок</label>
        <input type="text" name="title" id="title" class="form-control">
    </div>

    <div class="mb-3">
        <label for="link" class="form-label">Ссылка</label>
        <input type="url" name="link" id="link" class="form-control">
    </div>

    <button type="submit" class="btn btn-success">Сохранить</button>
</form>

</div>
@endsection
