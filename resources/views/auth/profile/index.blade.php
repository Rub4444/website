@extends('auth.layouts.master')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4">
                    <h3 class="card-title mb-4 d-flex align-items-center" style="color: #2E8B57;">
                        <div class="me-3">
                            <i class="bi bi-person-circle me-2 fs-2"></i>
                            @lang('main.my_account')
                        </div>

                        <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary w-50">
                            <i class="bi bi-pencil-square me-2"></i>@lang('basket.edit_my_profile')
                        </a>
                    </h3>

                    <ul class="list-group list-group-flush mb-4">
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="fw-semibold">@lang('basket.name')</span>
                            <span>{{ $user->name }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="fw-semibold">@lang('basket.email')</span>
                            <span>{{ $user->email }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="fw-semibold">@lang('basket.phone_number')</span>
                            <span>{{ $user->phone ?? '' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="fw-semibold">@lang('main.delivery_city')</span>
                            <span>{{ $user->delivery_city ?? '' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="fw-semibold">@lang('main.delivery_street')</span>
                            <span>{{ $user->delivery_street ?? '' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="fw-semibold">@lang('main.delivery_home')</span>
                            <span>{{ $user->delivery_home ?? '' }}</span>
                        </li>
                    </ul>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection
