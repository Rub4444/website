@extends('layouts.master')
@section('content')

<h1>❌ Оплата не удалась</h1>

<p><strong>Причина:</strong> {{ $data['ResponseMessage'] ?? 'Неизвестная ошибка' }}</p>

@if(isset($data['ResponseCode']))
    <p><strong>Код ответа:</strong> {{ $data['ResponseCode'] }}</p>
@endif

@if(isset($data['OrderID']))
    <p><strong>OrderID:</strong> {{ $data['OrderID'] }}</p>
@endif

@if(isset($data['PaymentID']))
    <p><strong>PaymentID:</strong> {{ $data['PaymentID'] }}</p>
@endif

<a href="{{ url('/payment/pay') }}">🔁 Попробовать снова</a>

@endsection
