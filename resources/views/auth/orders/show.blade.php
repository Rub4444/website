@extends('auth.layouts.master')

@section('title', 'Պատվեր №' . $order->id)

@section('content')
    <div class="py-4">
        <div class="container">
            <div class="card shadow rounded-4">
                <div class="card-body">
                    <h2 class="mb-3 text-center" style="color:#2E8B57;">Պատվեր №{{ $order->id }}</h2>
                    @admin
                        @if($order->status == 1) {{-- 1 = в обработке --}}
                            <div class="row">
                                <!-- Подтверждение заказа -->
                                <div class="col-lg-6 mb-3">
                                    <form method="POST" action="{{ route('admin.orders.confirm', $order) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-success w-100">
                                            <i class="bi bi-check-circle"></i> Հաստատել պատվերը
                                        </button>
                                    </form>
                                </div>

                                <!-- Отмена заказа -->
                                <div class="col-lg-6 mb-3">
                                    <form action="{{ route('admin.orders.cancel', $order) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="mb-2">
                                            <label for="cancellation_comment" class="form-label">Մեկնաբանություն՝</label>
                                            <textarea name="cancellation_comment" id="cancellation_comment" class="form-control" rows="3" ></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-danger w-100">
                                            <i class="bi bi-x-circle"></i> Չեղարկվել պատվերը
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    @endadmin


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
                                        <a href="{{ route('coupons.show', $order->coupon) }}">
                                            {{ $order->coupon->code }}
                                        </a>
                                    </td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                    <p><strong>Հաճախորդ՝</strong> {{ $order->name }}</p>
                    <p><strong>Հեռախոսահամար՝</strong> {{ $order->phone }}</p>
                    <!-- Карта -->
                    <p><strong>Պատվերի տեսակը՝</strong> {{ $order->delivery_type === 'delivery' ? 'Առաքում' : 'Վերցնել խանութից' }}</p>
                    <p><strong>Կարգավիճակ՝</strong>
                        @switch($order->status)
                            @case(1)
                                <span class="text-warning">Ընթացքի մեջ</span>
                                @break
                            @case(2)
                                @if($order->delivery_type === 'delivery')
                                    <span class="text-primary">Հաստատված է, առաքիչը ճանապարհին է</span>
                                @else
                                    <span class="text-primary">Հաստատված է, կարող եք մոտենալ</span>
                                @endif
                                @break
                            @case(3)
                                <span class="text-danger">Պատվերը չեղարկվել է</span>
                                <p><strong>Մեկնաբանություն՝</strong> {{ $order->cancellation_comment }}</p>
                                @break
                            @default
                                <span class="text-muted">Անհայտ կարգավիճակ</span>
                        @endswitch
                    </p>

                    @if($order->address)<p><strong>Հասցե՝</strong> {{ $order->address }}</p>@endif

                    <div id="map" style="width: 100%; height: 400px; margin-top: 20px;"></div>

                    <!-- Ссылка на Google Maps -->
                    @if($order->latitude && $order->longitude)
                        <div class="text-center mt-3">
                            <a href="https://www.google.com/maps?q={{ $order->latitude }},{{ $order->longitude }}" target="_blank" class="btn btn-outline-secondary">
                                Տեսնել Google Maps-ի միջոցով
                            </a>
                        </div>
                        <div class="text-center mt-4">
                            <a href="{{ route('home') }}" class="btn btn-outline-primary px-4 rounded-pill">
                                Վերադառնալ պատվերների ցանկին
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @if($order->latitude && $order->longitude)
        <script>
            function initMap() {
                const lat = {{ $order->latitude ?? 40.8785 }}; // Если координаты есть, то используются они
                const lng = {{ $order->longitude ?? 45.1535 }}; // Если нет, то значения по умолчанию

                const map = new google.maps.Map(document.getElementById("map"), {
                    center: { lat: lat, lng: lng },
                    zoom: 15
                });

                const marker = new google.maps.Marker({
                    position: { lat: lat, lng: lng },
                    map: map
                });
            }

            // document.addEventListener('DOMContentLoaded', function () {
                initMap();  // Инициализация карты
            // });
        </script>

        <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA6h1Jk2Rsc910Guq2HV8y9yZSU-57D0PU&callback=initMap">
        </script>
    @endif

@endsection
