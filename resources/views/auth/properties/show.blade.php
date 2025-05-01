@extends('auth.layouts.master')

@section('title', 'Հատկություն ' . $property->name)

@section('content')
    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header text-white" style="background-color:#2E8B57;">
                <h4>Հատկություն <b>{{ $property->name }}</b></h4>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <tbody>
                    <tr>
                        <th>Դաշտ</th>
                        <th>Արժեք</th>
                    </tr>
                    <tr>
                        <td>ID</td>
                        <td>{{ $property->id }}</td>
                    </tr>
                    <tr>
                        <td>Անուն</td>
                        <td>{{ $property->name }}</td>
                    </tr>
                    <tr>
                        <td>Անուն (en)</td>
                        <td>{{ $property->name_en }}</td>
                    </tr>
                    {{-- Uncomment and update the products count if needed --}}
                    {{-- <tr> --}}
                    {{--    <td>Ապրանքների քանակ</td> --}}
                    {{--    <td>{{ $property->products->count() }}</td> --}}
                    {{-- </tr> --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
