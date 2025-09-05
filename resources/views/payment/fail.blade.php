@extends('layouts.master')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm border-0 text-center p-4">
                <!-- Иконка ошибки -->
                <div class="mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="#dc3545" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM4.646 4.646a.5.5 0 1 0-.708.708L7.293 8l-3.355 3.354a.5.5 0 1 0 .708.708L8 8.707l3.354 3.355a.5.5 0 0 0 .708-.708L8.707 8l3.355-3.354a.5.5 0 0 0-.708-.708L8 7.293 4.646 4.646z"/>
                    </svg>
                </div>

                <!-- Заголовок -->
                <h2 class="mb-3 text-danger">@lang('order.title')</h2>

                <!-- Сообщение об ошибке -->
                <p class="mb-4">
                    <strong>@lang('order.reason'):</strong> {{ $data['ResponseMessage'] ?? 'Неизвестная ошибка' }}
                </p>

                <!-- Дополнительная информация -->
                <ul class="list-group list-group-flush mb-4 text-start">
                    @if(isset($data['ResponseCode']))
                        <li class="list-group-item"><strong>Код ответа:</strong> {{ $data['ResponseCode'] }}</li>
                    @endif
                    @if(isset($data['OrderID']))
                        <li class="list-group-item"><strong>OrderID:</strong> {{ $data['OrderID'] }}</li>
                    @endif
                    @if(isset($data['PaymentID']))
                        <li class="list-group-item"><strong>PaymentID:</strong> {{ $data['PaymentID'] }}</li>
                    @endif
                </ul>

                <!-- Кнопка повторной оплаты -->
                <a href="{{ url('/payment/pay') }}" class="btn btn-danger btn-lg">
                    @lang('index')
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
