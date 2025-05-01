@extends('auth.layouts.master')

@section('title', 'Պատվեր №' . $order->id)

@section('content')
    <div class="py-4">
        <div class="container">
            <div class="card shadow rounded-4">
                <div class="card-body">
                    <h2 class="mb-3 text-center" style="color:#2E8B57;">Պատվեր №{{ $order->id }}</h2>

                    <p><strong>Հաճախորդ՝</strong> {{ $order->name }}</p>
                    <p><strong>Հեռախոսահամար՝</strong> {{ $order->phone }}</p>

                    <div class="table-responsive">
                        <table class="table table-striped align-middle text-center">
                            <thead class="table-light">
                                <tr>
                                    <th>Անվանում</th>
                                    <th>Քանակ</th>
                                    <th>Գին</th>
                                    <th>Ընդհանուր արժեքը</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($skus as $sku)
                                <tr>
                                    <td class="text-start">
                                        <a href="{{ route('sku', [$sku->product->category->code, $sku->product->code, $sku]) }}">
                                            <img width="56px" class="me-2 rounded"
                                                 src="{{ Storage::url($sku->product->image) }}">
                                            {{ $sku->product->name }}
                                        </a>
                                    </td>
                                    <td><span class="badge bg-secondary">{{ $sku->pivot->count }}</span></td>
                                    <td>{{ $sku->pivot->price }} {{ $order->currency->symbol }}</td>
                                    <td>{{ $sku->pivot->price * $sku->pivot->count }} {{ $order->currency->symbol }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="3" class="text-end"><strong>Ընդհանուր գումար՝</strong></td>
                                <td><strong>{{ $order->sum }} {{ $order->currency->symbol }}</strong></td>
                            </tr>
                            @if($order->hasCoupon())
                                <tr>
                                    <td colspan="3" class="text-end">Օգտագործված է կտրոն՝</td>
                                    <td>
                                        <a href="{{ route('coupons.show', $order->coupon) }}">
                                            {{ $order->coupon->code }}
                                        </a>
                                    </td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>

                    <div class="text-center mt-4">
                        <a href="{{ route('home') }}" class="btn btn-outline-primary px-4 rounded-pill">
                            Վերադառնալ պատվերների ցանկին
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
