@extends('auth.layouts.master')

@section('title', 'Պատվեր №' . $order->id)

@section('content')
<div class="py-5 bg-light">
    <div class="container">

        {{-- Карточка заказа --}}
        <div class="card shadow-lg rounded-4 border-0">
            <div class="card-body p-4">

                {{-- Заголовок --}}
                <h2 class="text-center fw-bold mb-4" style="color:#2E8B57;">
                    Պատվեր №{{ $order->id }}
                </h2>

                {{-- Статус заказа --}}
                <p class="text-center mb-4">
                    <strong>Կարգավիճակ՝</strong>
                    <span class="badge
                        {{ $order->status == 1 ? 'bg-warning' : ($order->status == 2 ? 'bg-success' : 'bg-danger') }} fs-6">
                        {{ $order->getStatusName() }}
                    </span>
                </p>

                {{-- Таблица товаров --}}
                <div class="table-responsive mb-4">
                    <table class="table table-hover table-striped align-middle text-center">
                        <thead class="table-light">
                            <tr>
                                <th class="text-start">Անվանում</th>
                                <th>Քանակ</th>
                                <th>Գին</th>
                                <th>Ընդհանուր արժեքը</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($skus as $sku)
                                <tr>
                                    <td class="text-start d-flex align-items-center">
                                        <img width="56px" class="me-2 rounded" src="{{ Storage::url($sku->product->image) }}">
                                        {{ $sku->product->name }}
                                    </td>
                                    <td><span class="badge bg-secondary">{{ $sku->pivot->count }}</span></td>
                                    <td>{{ $sku->pivot->price }} {{ $order->currency ? $order->currency->symbol : 'Դ' }}</td>
                                    <td>{{ $sku->pivot->price * $sku->pivot->count }} {{ $order->currency ? $order->currency->symbol : 'Դ' }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="3" class="text-end"><strong>Ընդհանուր գումար՝</strong></td>
                                <td><strong>{{ $order->sum }} {{ $order->currency ? $order->currency->symbol : 'Դ' }}</strong></td>
                            </tr>
                            @if($order->hasCoupon())
                                <tr>
                                    <td colspan="3" class="text-end">Օգտագործված է կտրոն՝</td>
                                    <td>
                                        <a href="{{ route('coupons.show', $order->coupon) }}" class="text-decoration-none">
                                            {{ $order->coupon->code }}
                                        </a>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                {{-- Информация о клиенте и заказе --}}
                <div class="row mb-4">
                    <div class="col-md-6 mb-2">
                        <p><strong>Հաճախորդ՝</strong> {{ $order->name }}</p>
                        <p><strong>Հեռախոսահամար՝</strong> {{ $order->phone }}</p>
                    </div>
                    <div class="col-md-6 mb-2">
                        <p><strong>Պատվերի տեսակը՝</strong>
                            {{ $order->delivery_type === 'delivery' ? 'Առաքում' : 'Վերցնել խանութից' }}
                        </p>
                        @if($order->cancellation_comment)
                            <p><strong>Չեղարկման մեկնաբանություն՝</strong> {{ $order->cancellation_comment }}</p>
                        @endif
                    </div>
                </div>

                {{-- Кнопки действий --}}
                @admin
                @if($order->status == 1 || $order->status == 2)
                    <div class="row mb-4 g-3">
                        <div class="col-md-6">
                            <form method="POST" action="{{ route('admin.orders.confirm', $order) }}">
                                @csrf
                                <button type="submit" class="btn btn-success w-100 d-flex align-items-center justify-content-center gap-2 rounded-pill shadow-sm">
                                    <i class="bi bi-check-circle"></i> Հաստատել պատվերը
                                </button>
                            </form>
                        </div>
                        <div class="col-md-6">
                            <form action="{{ route('admin.orders.cancel', $order) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <textarea name="cancellation_comment" class="form-control rounded-3 mb-2" rows="3" placeholder="Ավելացրեք պատճառը..."></textarea>
                                <button type="submit" class="btn btn-danger w-100 d-flex align-items-center justify-content-center gap-2 rounded-pill shadow-sm">
                                    <i class="bi bi-x-circle"></i> Չեղարկվել պատվերը
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
                @endadmin
            </div>
        </div>
    </div>
</div>
@endsection
