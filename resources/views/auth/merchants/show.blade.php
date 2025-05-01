@extends('auth.layouts.master')

@section('title', 'Մատակարար ' . $merchant->name)

@section('content')
    <div class="container mt-5">
        <div class="card shadow rounded-4">
            <div class="card-body">
                <h2 class="mb-4 text-center" style="color:#2E8B57;">Մատակարար՝ {{ $merchant->name }}</h2>

                <table class="table table-bordered text-center align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Դաշտ</th>
                            <th>Արժեք</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>ID</strong></td>
                            <td>{{ $merchant->id }}</td>
                        </tr>
                        <tr>
                            <td><strong>Անուն</strong></td>
                            <td>{{ $merchant->name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Էլ․ հասցե</strong></td>
                            <td>{{ $merchant->email }}</td>
                        </tr>
                    </tbody>
                </table>

                <div class="text-center mt-4">
                    <a href="{{ route('merchants.edit', $merchant) }}" class="btn btn-outline-secondary rounded-pill px-4">
                        Խմբագրել
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
