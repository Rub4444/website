@extends('layouts.master')
@section('title', 'Իջևան Մարկետ')
@section('content')
<div class="checkout__page--area">
    <div class="container">
        <div class="checkout__page--inner d-flex">
            <div class="main checkout__main">
                <main class="main__content_wrapper section--padding pt-0 ">
                    <form action="{{route('basket-confirm')}}" method="POST">
                        <div class="checkout__content--step section__shipping--address">
                            <div class="section__header mb-25 text-center">
                                {{-- <h2 class="section__header--title h3">@lang('basket.confirm') - {{$order->getFullSum()}} {{$currencySymbol}}</h2> --}}
                                <h2 class="section__header--title h3">
                                    @lang('basket.confirm') - <span id="fullSum">{{$order->getFullSum()}}</span> {{$currencySymbol}}
                                </h2>
                            </div>
                            <div class="section__shipping--address__content">
                                <div class="row">
                                    <div class="col-lg-12 mb-12">
                                        <div class="checkout__input--list ">
                                            <label>
                                                <input required value="{{ old('name', auth()->user()->name ?? '') }}" placeholder="@lang('basket.name')" type="text" name="name" id="name" class="checkout__input--field border-radius-5">
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mb-12">
                                        <div class="checkout__input--list ">
                                            <input required value="{{ old('phone', auth()->user()->phone ?? '') }}" placeholder="@lang('basket.phone_number')" type="text" name="phone" id="phone" class="checkout__input--field border-radius-5">
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mb-12">
                                        <div class="checkout__input--list">
                                            <label for="delivery_type">@lang('basket.choose_delivery_type')</label>
                                            <select name="delivery_type" id="delivery_type" class="checkout__input--field border-radius-5">
                                                <option value="pickup">@lang('basket.pickup')</option>
                                                <option value="delivery">@lang('basket.delivery')</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div id="address_fields" style="display: none;">
                                        <div class="col-lg-12 mb-12">
                                            <div class="checkout__input--list ">
                                                <input value="Իջևան" readonly  style="background-color: #e9ecef;"  placeholder="@lang('main.delivery_city')" type="text" name="delivery_city" id="delivery_city" class="checkout__input--field border-radius-5">
                                            </div>
                                        </div>
                                        <div class="col-lg-12 mb-12">
                                            <div class="checkout__input--list ">
                                                <input required value="{{ old('delivery_street', auth()->user()->delivery_street ?? '') }}"  placeholder="@lang('main.delivery_street')" type="text" name="delivery_street" id="delivery_street" class="checkout__input--field border-radius-5">
                                            </div>
                                        </div>
                                        <div class="col-lg-12 mb-12">
                                            <div class="checkout__input--list ">
                                                <input required value="{{ old('delivery_home', auth()->user()->delivery_home ?? '') }}"  placeholder="@lang('main.delivery_home')" type="text" name="delivery_home" id="delivery_home" class="checkout__input--field border-radius-5">
                                            </div>
                                        </div>
                                    </div>
                                    @guest
                                        <div class="col-lg-12 mb-12">
                                            <div class="checkout__input--list ">
                                                <input placeholder="@lang('basket.email')" type="text" name="email" id="email" class="checkout__input--field border-radius-5">
                                            </div>
                                        </div>
                                    @endguest
                                </div>
                            </div>
                        </div>
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
@endsection
<script>
document.addEventListener('DOMContentLoaded', function () {
    const select = document.getElementById('delivery_type');
    const addressFields = document.getElementById('address_fields');
    const deliveryCity = document.getElementById('delivery_city');
    const fullSumEl = document.getElementById('fullSum');
    const baseSum = {{$order->getFullSum()}};
    const deliveryFee = 500;

    function toggleAddressFields() {
        if (select.value === 'delivery') {
            addressFields.style.display = 'block';
            deliveryCity.value = 'Ijevan';
            deliveryCity.setAttribute('readonly', 'readonly');
            deliveryCity.style.backgroundColor = '#e9ecef';
            document.getElementById('delivery_street').setAttribute('required', 'required');
            document.getElementById('delivery_home').setAttribute('required', 'required');

            fullSumEl.textContent = baseSum + deliveryFee; // добавляем 500 драм
        } else {
            addressFields.style.display = 'none';
            deliveryCity.value = '';
            deliveryCity.removeAttribute('readonly');
            deliveryCity.style.backgroundColor = '';
            document.getElementById('delivery_street').removeAttribute('required');
            document.getElementById('delivery_home').removeAttribute('required');

            fullSumEl.textContent = baseSum; // базовая сумма
        }
    }

    select.addEventListener('change', toggleAddressFields);
    toggleAddressFields(); // init on load
});
</script>
