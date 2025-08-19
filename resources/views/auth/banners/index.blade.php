@extends('auth.layouts.master')


@section('content')
<div class="container">
    <h1>Баннеры</h1>
    <a href="{{ route('banners.create') }}" class="btn btn-success mb-3">Добавить баннер</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Превью</th>
                <th>Заголовок</th>
                <th>Ссылка</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
        @foreach($banners as $banner)
            <tr>
                <td><img src="{{ Storage::url($banner->image) }}" width="200" class="rounded"></td>
                <td>{{ $banner->title }}</td>
                <td>{{ $banner->link }}</td>
                <td>
                    <form method="POST" action="{{ route('banners.destroy', $banner) }}">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">Удалить</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
