@extends('auth.layouts.master')

@section('title', 'Заказы')

@section('content')
<div class="container">
        <h1>Список заказов</h1>
        <table class="table">
            <tbody>
                <tr>
                    <th>
                        #
                    </th>
                    <th>
                        Имя
                    </th>
                    <th>
                        Телефон
                    </th>
                    <th>
                        Когда отправлен
                    </th>
                    <th>
                        Сумма
                    </th>
                    <th>
                        Действия
                    </th>
                </tr>
                @foreach($orders as $order)
                    <tr>
                        <td>{{ $order->id}}</td>
                        <td>{{ $order->name }}</td>
                        <td>{{ $order->phone }}</td>
                        <td>{{ $order->created_at->format('H:i d/m/Y') }}</td>
                        <td>{{ $order->sum}} {{$order->currency->symbol}}</td>
                        <td>
                            <div class="btn-group" role="group">

                                <a
                                @admin
                                    href="{{route('orders.show', $order)}}"
                                @else
                                    href="{{route('person.orders.show', $order)}}"
                                @endadmin
                                class="btn btn-success" type="button">Открыть</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{$orders->links()}}
    </div>
@endsection
