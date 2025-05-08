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
                                                    <input placeholder="@lang('basket.name')" type="text" name="name" id="name" class="checkout__input--field border-radius-5">
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 mb-12">
                                            <div class="checkout__input--list">
                                                <input placeholder="@lang('basket.phone_number')" type="text" name="phone" id="phone" class="checkout__input--field border-radius-5">
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
                                    <option value="pickup">Վերցնել խանութից</option>
                                    <option value="courier">Առաքում հասցեով</option>
                                </select>
                            </div>

                            <div class="col-lg-12 mb-12 mt-3" id="address_block" style="display: none;">
                                <label for="address" class="form-label">Հասցե</label>
                                <input type="text" name="address" id="address" class="form-control" placeholder="Մուտքագրեք հասցեն">
                            </div>

                            <div id="map" style="width: 100%; height: 400px; margin-top: 20px;"></div>

                            <!-- Hidden latitude and longitude inputs -->
                            <input type="hidden" name="latitude" id="latitude">
                            <input type="hidden" name="longitude" id="longitude">

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

    @push('scripts')
    <script>
        let map, marker;


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
    @endpush



    @endsection
