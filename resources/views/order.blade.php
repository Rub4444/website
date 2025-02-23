@extends('layouts.master')
@section('title', 'Իջևան Մարկետ')
@section('content')
<div class="starter-template">
    <h1>Confirm Order</h1>
    <div class="container">
        <div class="row justify-content-center">
            <p>{{$order->calculateFullSum()}}AMD</p>
            <form action="{{route('basket-confirm')}}" method="POST">
                <div>
                    <p>Input name, phone number</p>
                    <div class="container">
                        <div class="form-group">
                            <label for="name">Name: </label>
                            <div>
                                <input type="text" name="name" id="name" class="form-control">
                            </div>
                        </div>
                        <br>
                        <br>
                        <div class="form-group">
                            <label for="phone">Phone Number: </label>
                            <div>
                                <input type="text" name="phone" id="phone" class="form-control">
                            </div>
                        </div>
                        <br>
                        @csrf
                        <input type="submit" class="btn btn-success" value="Confirm">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
