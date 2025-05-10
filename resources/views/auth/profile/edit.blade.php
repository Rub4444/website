@extends('auth.layouts.master')

@section('content')
<div class="container py-4">
    <div class="card shadow rounded-4">
        <div class="card-body">
            <h3 class="card-title mb-4 d-flex align-items-center" style="color: #2E8B57;">
                <i class="bi bi-pencil-square me-2 fs-2"></i>
                @lang('basket.edit_my_profile')
            </h3>
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Закрыть"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf

                <div class="mb-3">
                    <label>@lang('basket.name')</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>@lang('basket.email')</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>@lang('basket.phone_number')</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Адрес доставки</label>
                    <input type="text" name="delivery_address" value="{{ old('delivery_address', $user->delivery_address) }}" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Выберите местоположение на карте</label>
                    <button type="button" class="btn btn-outline-secondary mt-2" onclick="getMyLocation()">
                        @lang('basket.use_my_location')
                    </button>
                    <div id="map" style="height: 300px; border-radius: 8px; border: 1px solid #ced4da;"></div>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude', $user->latitude) }}" class="form-control" readonly>
                    </div>
                    <div class="col">
                        <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude', $user->longitude) }}" class="form-control" readonly>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">@lang('basket.confirm')</button>
            </form>
        </div>
    </div>
</div>

{{-- Подключение Google Maps API --}}
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA6h1Jk2Rsc910Guq2HV8y9yZSU-57D0PU&callback=initMap" async defer></script>

<script>
        var map, marker, searchBox;


        function initMap() {
            const defaultCoords = { lat: 40.8785, lng: 45.1535 };

            map = new google.maps.Map(document.getElementById("map"), {
                center: defaultCoords,
                zoom: 14,
            });

            marker = new google.maps.Marker({
                position: defaultCoords,
                map: map,
                draggable: true
            });

            // Обновить координаты при перетаскивании маркера
            marker.addListener('dragend', function () {
                const pos = marker.getPosition();
                updateLatLngInputs(pos.lat(), pos.lng());
            });

            // Обновить координаты при клике по карте
            map.addListener('click', function (e) {
                const clickedLat = e.latLng.lat();
                const clickedLng = e.latLng.lng();

                marker.setPosition({ lat: clickedLat, lng: clickedLng });
                updateLatLngInputs(clickedLat, clickedLng);
            });

            // Установить координаты по умолчанию
            updateLatLngInputs(defaultCoords.lat, defaultCoords.lng);
        }

        function getMyLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function (position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;

                    document.getElementById('latitude').value = lat;
                    document.getElementById('longitude').value = lng;

                    initMap(lat, lng);
                },
                function () {
                    alert('Չհաջողվեց ստանալ տեղադրությունը։');
                }
            );
        } else {
            alert('Ձեր զննիչը չի աջակցում տեղադրության ֆունկցիային։');
        }
        }

        function updateLatLngInputs(lat, lng) {
            document.getElementById("latitude").value = lat;
            document.getElementById("longitude").value = lng;
        }


                // document.addEventListener('DOMContentLoaded', function () {
                //     const deliveryType = document.getElementById('delivery_type');
                //     const addressBlock = document.getElementById('address_block');

                //     if (deliveryType && addressBlock) {
                //         deliveryType.addEventListener('change', function () {
                //         addressBlock.style.display = this.value === 'courier' ? 'block' : 'none';
                //         });

                //         if (deliveryType.value === 'courier') {
                //         addressBlock.style.display = 'block';
                //         }
                //     }

                // });
                document.addEventListener('DOMContentLoaded', function () {
                const deliveryType = document.getElementById('delivery_type');
                const addressBlock = document.getElementById('address_block');
                const mapBlock = document.getElementById('map');

                function handleDeliveryChange() {
                    const isCourier = deliveryType.value === 'courier';
                    addressBlock.style.display = isCourier ? 'block' : 'none';
                    mapBlock.style.display = isCourier ? 'block' : 'none';

                    if (isCourier && !mapInitialized) {
                        initMap();
                    }
                }

                if (deliveryType && addressBlock && mapBlock) {
                    deliveryType.addEventListener('change', handleDeliveryChange);
                    handleDeliveryChange(); // при загрузке
                }
        });
      </script>
@endsection
