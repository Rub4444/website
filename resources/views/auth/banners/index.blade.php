@extends('auth.layouts.master')

@section('content')
    <div class="container">
        <h1>Բաններներ</h1>
        <a href="{{ route('banners.create') }}" class="btn btn-success mb-3">Ավելացնել Բաններ</a>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Տես․</th>
                    <th>Վերնագիր</th>
                    <th>Հղում</th>
                    <th>Գործողություն</th>
                </tr>
            </thead>
            <tbody>
            @foreach($banners as $banner)
                <tr>
                    <td><img src="{{ Storage::url($banner->image) }}" width="200" class="rounded"></td>
                    <td>{{ $banner->title }}</td>
                    <td>{{ $banner->link }}</td>
                    <td>
                        <form method="POST" action="{{ route('banners.destroy', $banner) }}">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">Հեռացնել</button>
                        </form>
                        <br>
                        <a href="{{ route('banners.edit', $banner) }}" class="btn btn-primary btn-sm mb-1">Փոփոխել</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
