@extends('auth.layouts.master')

@section('title', 'Հատկության տարբերակ ' . $propertyOption->name)

@section('content')
<div class="col-md-12">
    <div class="card-header text-white" style="background-color:#2E8B57;">
        <h3 class="text-center">Հատկության տարբերակ {{ $propertyOption->name }}</h3>
    </div>
    <div class="card shadow-lg mt-4">
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>Դաշտ</th>
                        <th>Գին</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>ID</td>
                        <td>{{ $propertyOption->id }}</td>
                    </tr>
                    <tr>
                        <td>Հատկություն</td>
                        <td>{{ $propertyOption->property->name }}</td>
                    </tr>
                    <tr>
                        <td>Անուն</td>
                        <td>{{ $propertyOption->name }}</td>
                    </tr>
                    <tr>
                        <td>Անուն en</td>
                        <td>{{ $propertyOption->name_en }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
