@extends('auth.layouts.master')

@section('content')
<div class="container">
    <h2>Информация о профиле</h2>

    <p><strong>Имя:</strong> {{ $user->name }}</p>
    <p><strong>Email:</strong> {{ $user->email }}</p>
    <p><strong>Телефон:</strong> {{ $user->phone ?? 'Не указано' }}</p>

    <a href="{{ route('profile.edit') }}" class="btn btn-primary">Редактировать</a>
</div>
@endsection
