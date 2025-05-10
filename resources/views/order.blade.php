    @extends('layouts.master')
    @section('title', 'Իջևան Մարկետ')
    @section('content')
    <div class="checkout__page--area">
        <div class="container">
            <div class="checkout__page--inner d-flex">
                <div class="main checkout__main">
                    <main class="main__content_wrapper section--padding pt-0">
                        <form action="{{ route('basket-confirm') }}" method="POST">
                            <div class="checkout__content--step section__shipping--address">
                                <div class="section__header mb-25">
                                    <h2 class="section__header--title h3">@lang('basket.order_confirmed')</h2>
                                    <h4 class="section__header--title h4">@lang('basket.cost') {{$order->getFullSum()}} {{$currencySymbol}}</h4>
                                </div>
                                <div class="section__shipping--address__content">
                                    <div class="row">
                                        <div class="col-lg-6 mb-12">
                                            <div class="checkout__input--list">
                                                <label>
                                                    <input placeholder="@lang('basket.name')"  value="{{ old('name', auth()->user()?->name) }}" required type="text" name="name" id="name" class="checkout__input--field border-radius-5">
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 mb-12">
                                            <div class="checkout__input--list">
                                                <input placeholder="@lang('basket.phone_number')" value="{{ old('phone', auth()->user()?->phone) }}" required type="text" name="phone" id="phone" class="checkout__input--field border-radius-5">
                                            </div>
                                        </div>
                                        @guest
                                            <div class="col-lg-6 mb-12">
                                                <div class="checkout__input--list">
                                                    <input placeholder="@lang('basket.email')" type="text" name="email" id="email" class="checkout__input--field border-radius-5">
                                                </div>
                                            </div>
                                        @endguest
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 mb-12">
                                <label for="delivery_type" class="form-label">@lang('basket.choose_type')</label>
                                <select name="delivery_type" id="delivery_type" class="form-control">
                                    <option value="pickup">@lang('basket.pick_up_from_the_store')</option>
                                    <option value="courier">@lang('basket.delivery_to_the_address')</option>
                                </select>
                            </div>

                            <div class="col-lg-12 mb-12 mt-3" id="address_block" style="display: none;">
                                <label for="address" class="form-label">@lang('basket.address')</label>
                                <input type="text"  value="{{ old('delivery_address', auth()->user()?->delivery_address) }}" name="delivery_address" id="delivery_address" class="form-control" placeholder="@lang('basket.enter_the_address')">
                            </div>
                            <div class="mt-3">
                                <label>Փնտրել հասցեն</label>
                                <input type="text" id="search-input" placeholder="Որոնել հասցեն...">

                                <button type="button" class="btn btn-outline-secondary mt-2" onclick="getMyLocation()">
                                    @lang('basket.use_my_location')
                                </button>
                            </div>

                            <small class="text-muted">Եթե նշված տեղադրությունը սխալ է, շարժեք մարկերը քարտեզի վրա կամ փնտրեք հասցեն վերևից:</small>
                            <div id="map" style="width: 100%; height: 400px; margin-top: 20px;"></div>

                            <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude', auth()->user()?->latitude) }}">                            <input type="hidden" value="{{ old('longitude', auth()->user()?->longitude) }}" name="longitude" id="longitude">
                            <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', auth()->user()?->longitude) }}">


                            <div class="checkout__content--step__footer d-flex align-items-center">
                                @csrf
                                <input type="submit" class="btn btn-success" value="@lang('basket.confirm')">
                            </div>
                        </form>
                    </main>
                </div>
            </div>
        </div>
    </div>

    {{-- Google Maps API --}}
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA6h1Jk2Rsc910Guq2HV8y9yZSU-57D0PU&libraries=places&callback=initMap" async defer></script>

<script>
    let map, marker, geocoder, searchBox;
    let mapInitialized = false;

    function initMap() {
        if (mapInitialized) return;
        mapInitialized = true;

        const defaultLat = parseFloat(document.getElementById('latitude').value) || 40.8785;
        const defaultLng = parseFloat(document.getElementById('longitude').value) || 45.1535;

        const defaultCoords = { lat: defaultLat, lng: defaultLng };

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

        updateLatLngInputs(defaultLat, defaultLng);
        reverseGeocodeCoords(defaultLat, defaultLng);

        marker.addListener('dragend', function () {
            const pos = marker.getPosition();
            updateLatLngInputs(pos.lat(), pos.lng());
            reverseGeocodeCoords(pos.lat(), pos.lng());
        });

        map.addListener('click', function (e) {
            const lat = e.latLng.lat();
            const lng = e.latLng.lng();
            marker.setPosition({ lat, lng });
            updateLatLngInputs(lat, lng);
            reverseGeocodeCoords(lat, lng);
        });

        // Поиск по адресу
        const input = document.getElementById("search-input");
        if (input) {
            searchBox = new google.maps.places.SearchBox(input);

            map.addListener("bounds_changed", () => {
                searchBox.setBounds(map.getBounds());
            });

            searchBox.addListener("places_changed", () => {
                const places = searchBox.getPlaces();
                if (!places.length) return;

                const place = places[0];
                if (!place.geometry) return;

                const location = place.geometry.location;
                map.setCenter(location);
                map.setZoom(15);
                marker.setPosition(location);

                const lat = location.lat();
                const lng = location.lng();
                updateLatLngInputs(lat, lng);

                if (place.formatted_address) {
                    const addrInput = document.querySelector('input[name="delivery_address"]');
                    if (addrInput) addrInput.value = place.formatted_address;
                }
            });
        }
    }

    function updateLatLngInputs(lat, lng) {
        document.getElementById("latitude").value = lat;
        document.getElementById("longitude").value = lng;
    }

    function reverseGeocodeCoords(lat, lng) {
        if (!geocoder) return;

        geocoder.geocode({ location: { lat, lng } }, function (results, status) {
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
                function (position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    updateLatLngInputs(lat, lng);
                    map.setCenter({ lat, lng });
                    marker.setPosition({ lat, lng });
                    reverseGeocodeCoords(lat, lng);
                },
                function () {
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

            if (isCourier) {
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
