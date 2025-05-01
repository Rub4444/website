@extends('auth.layouts.master')

@section('title', 'Կտրոն ' . $coupon->code)

@section('content')
    <div class="container mt-5">
        <div class="card shadow rounded-4">
            <div class="card-body p-4">
                <h2 class="mb-4 text-center" style="color:#2E8B57;">{{ $coupon->code }}</h2>
                <table class="table table-hover table-bordered align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>Դաշտ</th>
                            <th>Արժեք</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>ID</td>
                            <td>{{ $coupon->id }}</td>
                        </tr>
                        <tr>
                            <td>Կուպոնի կոդ</td>
                            <td>{{ $coupon->code }}</td>
                        </tr>
                        <tr>
                            <td>Նկարագրություն</td>
                            <td>{{ $coupon->description }}</td>
                        </tr>
                        @isset($coupon->currency)
                            <tr>
                                <td>Արժույթ</td>
                                <td>{{ $coupon->currency->code }}</td>
                            </tr>
                        @endisset
                        <tr>
                            <td>Աբսոլյուտ արժեք</td>
                            <td>
                                @if($coupon->isAbsolute())
                                    Այո
                                @else
                                    Ոչ
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Զեղչ</td>
                            <td>
                                {{ $coupon->value }} @if($coupon->isAbsolute()) {{ $coupon->currency->code }} @else % @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Միայն մեկ անգամ օգտագործման</td>
                            <td>
                                @if($coupon->isOnlyOnce())
                                    Այո
                                @else
                                    Ոչ
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Օգտագործումների քանակը</td>
                            <td>{{ $coupon->orders->count() }}</td>
                        </tr>
                        <tr>
                            <td>Վավեր է մինչև</td>
                            <td>{{ $coupon->expired_at->format('d.m.Y') }}</td>
                        </tr>
                    </tbody>
                </table>
                <div class="text-center mt-4">
                    <a href="{{ route('coupons.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Վերադառնալ</a>
                </div>
            </div>
        </div>
    </div>
@endsection
