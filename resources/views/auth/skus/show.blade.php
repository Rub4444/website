@extends('auth.layouts.master')

@section('title', 'Sku ')

@section('content')
    <div class="col-md-12">
        <h2>Sku ID - {{ $sku->id }}</h2>
        <h2>Product - {{ $product->name }}</h2>
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
                <td>{{ $sku->id }}</td>
            </tr>
            <tr>
                <td>Цена</td>
                <td>{{ $sku->price }}</td>
            </tr>
            <tr>
                <td>Кол-во</td>
                <td>{{ $sku->count }}</td>
            </tr>
            </tbody>
        </table>
    </div>
@endsection
