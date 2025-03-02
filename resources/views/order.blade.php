@extends('layouts.master')
@section('title', 'Իջևան Մարկետ')
@section('content')
<div class="checkout__page--area">
    <div class="container">
        <div class="checkout__page--inner d-flex">
            <div class="main checkout__mian">
                <main class="main__content_wrapper section--padding pt-0">
                    <form action="{{route('basket-confirm')}}" method="POST">
                        <div class="checkout__content--step section__shipping--address">
                            <div class="section__header mb-25">
                                <h2 class="section__header--title h3">Confirm Order</h2>
                            </div>
                            <div class="section__shipping--address__content">
                                <div class="row">
                                    <div class="col-lg-6 mb-12">
                                        <div class="checkout__input--list ">
                                            <label>
                                                <input placeholder="Name" type="text" name="name" id="name" class="checkout__input--field border-radius-5">
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 mb-12">
                                        <div class="checkout__input--list ">
                                            <input placeholder="Phone Number" type="text" name="phone" id="phone" class="checkout__input--field border-radius-5">
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
