@extends('auth.layouts.master')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4">
                    <h3 class="card-title mb-4 d-flex align-items-center" style="color: #2E8B57;">
                        <div class="me-3">
                            <i class="bi bi-person-circle me-2 fs-2"></i>
                            @lang('main.my_account')
                        </div>

                        <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary w-50">
                            <i class="bi bi-pencil-square me-2"></i>@lang('basket.edit_my_profile')
                        </a>
                    </h3>

                    <ul class="list-group list-group-flush mb-4">
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="fw-semibold">@lang('basket.name')</span>
                            <span>{{ $user->name }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="fw-semibold">@lang('basket.email')</span>
                            <span>{{ $user->email }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="fw-semibold">@lang('basket.phone_number')</span>
                            <span>{{ $user->phone ?? '' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="fw-semibold">@lang('main.addresses')</span>
                            <span>{{ $user->delivery_address ?? '' }}</span>
                        </li>
                    </ul>

                    <!-- Добавляем блок с картой -->
                    <div class="mb-4">
                        {{-- <h5>@lang('main.location_on_map')</h5> --}}
                        <div id="map" style="height: 400px; border-radius: 8px; border: 1px solid #ced4da;"></div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
{{-- Подключаем Google Maps API --}}
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA6h1Jk2Rsc910Guq2HV8y9yZSU-57D0PU&callback=initMap" async defer></script>

<script>
    // Функция инициализации карты
    function initMap() {
        const defaultCoords = {
            lat: {{ $user->latitude ?? 40.8788 }}, // Если в базе данных нет координат, используем значение по умолчанию
            lng: {{ $user->longitude ?? 45.1485 }} // Аналогично для долготы
        };

        // Создаем карту
        const map = new google.maps.Map(document.getElementById('map'), {
            center: defaultCoords,
            zoom: 14
        });

        // Создаем маркер
        const marker = new google.maps.Marker({
            position: defaultCoords,
            map: map,
            title: 'Местоположение',
        });
    }

    // Инициализируем карту
    google.maps.event.addDomListener(window, 'load', initMap);
</script>
@endsection
