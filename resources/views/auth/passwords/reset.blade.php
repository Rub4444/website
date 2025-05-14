@extends('layouts.master')

@section('title')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-4">
                    <h4 class="mb-4 text-center fw-bold">
                        <i class="bi bi-shield-lock me-2"></i>
                        @lang('main.reset_password')
                    </h4>

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('Email Address') }}</label>
                            <input id="email" type="email"
                                class="form-control @error('email') is-invalid @enderror"
                                name="email"
                                value="{{ $email ?? old('email') }}"
                                required autocomplete="email" autofocus>

                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">{{ __('New Password') }}</label>
                            <input id="password" type="password"
                                class="form-control @error('password') is-invalid @enderror"
                                name="password"
                                required autocomplete="new-password">

                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password-confirm" class="form-label">@lang('main.password_confirm')</label>
                            <input id="password-confirm" type="password"
                                class="form-control"
                                name="password_confirmation"
                                required autocomplete="new-password">
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                @lang('main.reset_password')
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
