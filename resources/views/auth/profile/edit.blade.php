@extends('auth.layouts.master')

@section('content')
<div class="container py-4">
    <div class="card shadow rounded-4">
        <div class="card-body">
            <h3 class="card-title mb-4 d-flex align-items-center" style="color: #2E8B57;">
                <i class="bi bi-pencil-square me-2 fs-2"></i>
                @lang('basket.edit_my_profile')
            </h3>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Закрыть"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf

                <div class="mb-3">
                    <label>@lang('basket.name')</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>@lang('basket.email')</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>@lang('basket.phone_number')</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-control">
                </div>
                <div class="mb-3">
                    <label>@lang('main.delivery_city')</label>
                    <input type="text" name="delivery_city" value="{{ old('delivery_city', $user->delivery_city) }}" class="form-control">
                </div>

                <div class="mb-3">
                    <label>@lang('main.delivery_street')</label>
                    <input type="text" name="delivery_street" value="{{ old('delivery_street', $user->delivery_street) }}" class="form-control">
                </div>

                <div class="mb-3">
                    <label>@lang('main.delivery_home')</label>
                    <input type="text" name="delivery_home" value="{{ old('delivery_home', $user->delivery_home) }}" class="form-control">
                </div>

                <button type="submit" class="btn btn-primary">@lang('basket.confirm')</button>
            </form>
        </div>
    </div>
</div>
@endsection
