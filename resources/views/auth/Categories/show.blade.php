@extends('auth.layouts.master')

@section('content')
<div class="col-md-12">
        <h1>Категория {{ $category->name }}</h1>
        <table class="table">
            <tbody>
            <tr>
                <th>
                    Поле
                </th>
                <th>
                    Значение
                </th>
            </tr>
            <tr>
                <td>ID</td>
                <td>{{ $category->id }}</td>
            </tr>
            <tr>
                <td>Код</td>
                <td>{{ $category->code }}</td>
            </tr>
            <tr>
                <td>Название</td>
                <td>{{ $category->name }}</td>
            </tr>
            <tr>
                <td>Название EN</td>
                <td>{{ $category->name_en }}</td>
            </tr>
            <tr>
                <td>Описание</td>
                <td>{{ $category->description }}</td>
            </tr>
            <tr>
                <td>Описание EN</td>
                <td>{{ $category->description_en }}</td>
            </tr>
            <tr>
                <td>Картинка</td>
                <!-- <td><img src="{{ asset('storage/categories/' . $category->image) }}"
                         height="240px"></td> -->
                <td>
                    @if ($category->image)
                    <img src="{{ asset('storage/' . $category->image) }}" height="240px">
                    @else
                        Нет изображения
                    @endif
                </td>

            </tr>
            <tr>
                <td>Кол-во товаров</td>
                <td>{{ $category->products->count() }}</td>
            </tr>
            </tbody>
        </table>
    </div>
@endsection
