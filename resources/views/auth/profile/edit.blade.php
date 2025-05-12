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
                    <label>@lang('main.select_delivery_address')</label>
                    <div class="mb-3">
                        <label>@lang('main.delivery_address')</label>
                        <input type="text" name="delivery_address" value="{{ old('delivery_address', $user->delivery_address) }}" class="form-control">
                    </div>

                    <div class="mt-3">
                        <label>@lang('main.search_the_address')</label>
                        <input id="search-input" type="text" class="form-control" placeholder="@lang('main.search_the_address')">

                        <button type="button" class="btn btn-outline-secondary mt-2" onclick="getMyLocation()">
                            @lang('basket.use_my_location')
                        </button>
                    </div>

                    <small class="text-muted">@lang('main.if_this_address_incorrect')</small>

                    <div id="map" style="height: 300px; border-radius: 8px; border: 1px solid #ced4da;" class="mt-3"></div>
                    <div id="current-address" class="text-muted small mt-2"></div>
                </div>

                <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude', $user->latitude) }}">
                <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude', $user->longitude) }}">

                <button type="submit" class="btn btn-primary">@lang('basket.confirm')</button>
            </form>
        </div>
    </div>
</div>

{{-- Google Maps API --}}
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA6h1Jk2Rsc910Guq2HV8y9yZSU-57D0PU&libraries=places&callback=initMap" async defer></script>

<script>
    let map, marker, searchBox, geocoder;

    function initMap() {
        const defaultCoords = {
            lat: parseFloat(document.getElementById('latitude').value) || 40.8785,
            lng: parseFloat(document.getElementById('longitude').value) || 45.1535
        };

        geocoder = new google.maps.Geocoder();

        map = new google.maps.Map(document.getElementById("map"), {
            center: defaultCoords,
            zoom: 14,
        });

        marker = new google.maps.Marker({
            position: defaultCoords,
            map: map,
            draggable: true
        });

        updateLatLngInputs(defaultCoords.lat, defaultCoords.lng);
        reverseGeocodeCoords(defaultCoords.lat, defaultCoords.lng);

        marker.addListener('dragend', function () {
            const pos = marker.getPosition();
            const lat = pos.lat();
            const lng = pos.lng();
            updateLatLngInputs(lat, lng);
            reverseGeocodeCoords(lat, lng);
        });

        map.addListener('click', function (e) {
            const lat = e.latLng.lat();
            const lng = e.latLng.lng();
            marker.setPosition({ lat, lng });
            updateLatLngInputs(lat, lng);
            reverseGeocodeCoords(lat, lng);
        });

        const input = document.getElementById("search-input");
        searchBox = new google.maps.places.SearchBox(input);

        map.addListener("bounds_changed", () => {
            searchBox.setBounds(map.getBounds());
        });

        searchBox.addListener("places_changed", () => {
            const places = searchBox.getPlaces();
            if (places.length === 0) return;

            const place = places[0];
            if (!place.geometry || !place.geometry.location) return;

            const location = place.geometry.location;
            map.setCenter(location);
            map.setZoom(15);
            marker.setPosition(location);

            const lat = location.lat();
            const lng = location.lng();
            updateLatLngInputs(lat, lng);

            if (place.formatted_address) {
                document.querySelector('input[name="delivery_address"]').value = place.formatted_address;
            }
        });
    }

    function updateLatLngInputs(lat, lng) {
        document.getElementById("latitude").value = lat;
        document.getElementById("longitude").value = lng;
    }

    function reverseGeocodeCoords(lat, lng) {
        const latlng = { lat: parseFloat(lat), lng: parseFloat(lng) };

        geocoder.geocode({ location: latlng }, function (results, status) {
            if (status === "OK" && results[0]) {
                const input = document.querySelector('input[name="delivery_address"]');
                if (input) {
                    input.value = results[0].formatted_address;
                }
            }
        });
    }

    function getMyLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function (position)
                {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    updateLatLngInputs(lat, lng);
                    map.setCenter({ lat, lng });
                    marker.setPosition({ lat, lng });
                    reverseGeocodeCoords(lat, lng);
                },
                function ()
                {
                    alert('Չհաջողվեց ստանալ տեղադրությունը։');
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );
        } else {
            alert('Ձեր զննիչը չի աջակցում տեղադրության ֆունկցիային։');
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const deliveryType = document.getElementById('delivery_type');
        const addressBlock = document.getElementById('address_block');
        const mapBlock = document.getElementById('map');

        function handleDeliveryChange() {
            const isCourier = deliveryType?.value === 'courier';
            if (addressBlock) addressBlock.style.display = isCourier ? 'block' : 'none';
            if (mapBlock) mapBlock.style.display = isCourier ? 'block' : 'none';
        }

        if (deliveryType && addressBlock && mapBlock) {
            deliveryType.addEventListener('change', handleDeliveryChange);
            handleDeliveryChange();
        }
    });
</script>
@endsection
