@extends('layouts.master')

@section('title')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-4">
                    <h4 class="mb-4 text-center fw-bold" style="color:black;">
                        <i class="bi bi-lock-fill me-2"></i>
                        @lang('main.password_confirm')
                    </h4>

                    <p class="text-muted text-center mb-4">
                        @lang('main.please_confirm')
                    </p>

                    <form method="POST" action="{{ route('password.confirm') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="password" class="form-label">@lang('main.password')</label>
                            <input id="password" type="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   name="password"
                                   required autocomplete="current-password">

                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-lg">
                                <i class="bi bi-shield-lock me-1"></i>
                                @lang('main.password_confirm')
                            </button>
                        </div>

                        @if (Route::has('password.request'))
                            <div class="text-center">
                                <a class="text-decoration-none" href="{{ route('password.request') }}">
                                    <i class="bi bi-question-circle me-1"></i>
                                    @lang('main.forgot_password')
                                </a>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
