@extends('layouts.master')
@section('title', 'Իջևան Մարկետ')
@section('content')
<div class="checkout__page--area">
    <div class="container">
        <div class="checkout__page--inner d-flex">
            <div class="main checkout__main">
                <main class="main__content_wrapper section--padding pt-0">
                    <form action="{{route('basket-confirm')}}" method="POST">
                        <div class="checkout__content--step section__shipping--address">
                            <div class="section__header mb-25">
                                <h2 class="section__header--title h3">Հաստատել Պատվերը՝</h2>
                                <h4 class="section__header--title h4">{{$order->getFullSum()}} {{$currencySymbol}}</h4>
                            </div>
                            <div class="section__shipping--address__content">
                                <div class="row">
                                    <div class="col-lg-6 mb-12">
                                        <div class="checkout__input--list ">
                                            <label>
                                                <input required placeholder="Name" type="text" name="name" id="name" class="checkout__input--field border-radius-5">
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 mb-12">
                                        <div class="checkout__input--list ">
                                            <input required placeholder="Phone Number" type="text" name="phone" id="phone" class="checkout__input--field border-radius-5">
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mb-12">
                                        <div class="checkout__input--list">
                                            <label for="delivery_type">Ընտրեք առաքման եղանակը</label>
                                            <select name="delivery_type" id="delivery_type" class="checkout__input--field border-radius-5">
                                                <option value="pickup">Խանութից վերցնել</option>
                                                <option value="delivery">Առաքում հասցեով</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div id="address_fields" style="display: none;">
                                        <div class="col-lg-6 mb-12">
                                            <div class="checkout__input--list ">
                                                <input value="Իջևան" readonly  style="background-color: #e9ecef;"  placeholder="City" type="text" name="delivery_city" id="delivery_city" class="checkout__input--field border-radius-5">
                                            </div>
                                        </div>
                                        <div class="col-lg-6 mb-12">
                                            <div class="checkout__input--list ">
                                                <input required placeholder="Street" type="text" name="delivery_street" id="delivery_street" class="checkout__input--field border-radius-5">
                                            </div>
                                        </div>
                                        <div class="col-lg-6 mb-12">
                                            <div class="checkout__input--list ">
                                                <input required placeholder="Home" type="text" name="delivery_home" id="delivery_home" class="checkout__input--field border-radius-5">
                                            </div>
                                        </div>
                                    </div>
                                    @guest
                                        <div class="col-lg-6 mb-12">
                                            <div class="checkout__input--list ">
                                                <input placeholder="Email" type="text" name="email" id="email" class="checkout__input--field border-radius-5">
                                            </div>
                                        </div>
                                    @endguest
                                </div>
                            </div>
                        </div>
                        <div class="checkout__content--step__footer d-flex align-items-center">
                            @csrf
                            <input type="submit" class="btn btn-success" value="Confirm">
                        </div>
                    </form>
                </main>
            </div>
        </div>
    </div>
</div>
@endsection
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const select = document.getElementById('delivery_type');
        const addressFields = document.getElementById('address_fields');
        const deliveryCity = document.getElementById('delivery_city');

        function toggleAddressFields()
        {
            if (select.value === 'delivery') {
                addressFields.style.display = 'block';
                deliveryCity.value = 'Ijevan';
                deliveryCity.setAttribute('readonly', 'readonly');
                deliveryCity.style.backgroundColor = '#e9ecef';

                document.getElementById('delivery_street').setAttribute('required', 'required');
                document.getElementById('delivery_home').setAttribute('required', 'required');
            } else {
                addressFields.style.display = 'none';
                deliveryCity.value = '';
                deliveryCity.removeAttribute('readonly');
                deliveryCity.style.backgroundColor = '';
                document.getElementById('delivery_street').removeAttribute('required');
                document.getElementById('delivery_home').removeAttribute('required');
            }
        }

        select.addEventListener('change', toggleAddressFields);
        toggleAddressFields(); // init on load
    });
</script>
