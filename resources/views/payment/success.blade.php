@extends('layouts.master')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm border-0 text-center p-4">
                <!-- Иконка успешной оплаты -->
                <div class="mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="#2E8B57" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM6.97 11.03a.75.75 0 0 0 1.08 0l3.992-3.992a.75.75 0 1 0-1.06-1.06L7.5 9.439 5.992 7.93a.75.75 0 0 0-1.06 1.06l2.038 2.04z"/>
                    </svg>
                </div>

                <!-- Заголовок -->
                <h2 class="mb-3 text-success">@lang('order.success_title')</h2>

                <!-- Сообщение -->
                <p class="mb-4">@lang('order.success_message')</p>

                <!-- Кнопка возврата в магазин -->
                <a href="{{ url('/') }}" class="btn btn-success btn-lg">@lang('order.return_button')</a>
            </div>
        </div>
    </div>
</div>
@endsection
