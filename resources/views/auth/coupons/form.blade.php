@extends('auth.layouts.master')

@isset($coupon)
    @section('title', 'Խմբագրել կտրոն ' . $coupon->id)
@else
    @section('title', 'Ստեղծել կտրոն')
@endisset

@section('content')
    <div class="container mt-5">
        <div class="card shadow rounded-4">
            <div class="card-body p-5">
                <h2 class="mb-4">
                    @isset($coupon)
                        Խմբագրել Կուպոնը <strong>#{{ $coupon->id }}</strong>
                    @else
                        Ավելացնել Նոր Կուպոն
                    @endisset
                </h2>

                <form method="POST"
                      @isset($coupon)
                          action="{{ route('coupons.update', $coupon) }}"
                      @else
                          action="{{ route('coupons.store') }}"
                      @endisset
                >
                    @csrf
                    @isset($coupon)
                        @method('PUT')
                    @endisset

                    <div class="mb-3">
                        <label for="code" class="form-label">Կուպոն կոդ</label>
                        @include('auth.layouts.error', ['fieldName' => 'code'])
                        <input type="text" class="form-control" name="code" id="code"
                               value="{{ old('code', $coupon->code ?? '') }}">
                    </div>

                    <div class="mb-3">
                        <label for="value" class="form-label">Արժեք</label>
                        @include('auth.layouts.error', ['fieldName' => 'value'])
                        <input type="text" class="form-control" name="value" id="value"
                               value="{{ old('value', $coupon->value ?? '') }}">
                    </div>

                    <div class="mb-3">
                        <label for="currency_id" class="form-label">Արժույթ</label>
                        @include('auth.layouts.error', ['fieldName' => 'currency_id'])
                        <select name="currency_id" id="currency_id" class="form-select">
                            <option value="">Առանց արժույթի</option>
                            @foreach($currencies as $currency)
                                <option value="{{ $currency->id }}"
                                        @selected(isset($coupon) && $coupon->currency_id == $currency->id)>
                                    {{ $currency->code }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    @foreach ([
                        'type' => 'Աբսոլյուտ արժեք',
                        'only_once' => 'Կտրոնը կարող է օգտագործվել միայն մեկ անգամ',
                    ] as $field => $label)
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="{{ $field }}" id="{{ $field }}"
                                   @checked(isset($coupon) && $coupon->$field === 1)>
                            <label class="form-check-label" for="{{ $field }}">
                                {{ $label }}
                            </label>
                        </div>
                    @endforeach

                    <div class="mb-3">
                        <label for="expired_at" class="form-label">Վավեր է մինչև</label>
                        @include('auth.layouts.error', ['fieldName' => 'expired_at'])
                        <input type="date" class="form-control" name="expired_at" id="expired_at"
                               value="{{ old('expired_at', isset($coupon) && $coupon->expired_at ? $coupon->expired_at->format('Y-m-d') : '') }}">
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label">Նկարագրություն</label>
                        @include('auth.layouts.error', ['fieldName' => 'description'])
                        <textarea name="description" id="description" class="form-control" rows="5">{{ old('description', $coupon->description ?? '') }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-success w-100 py-2 rounded-3">Պահպանել</button>
                </form>
            </div>
        </div>
    </div>
@endsection
