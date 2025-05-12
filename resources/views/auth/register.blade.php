@extends('auth.layouts.master')

@section('content')
<div class="container d-flex align-items-center justify-content-center mt-2">
    <div class="col-12 col-md-6">
        <div class="card shadow-lg rounded-4">
            <div class="card-header text-center bg-success text-white rounded-top-4 py-3">
                <h4><i class="bi bi-person-plus-fill me-2"></i>@lang('main.registration')</h4>
            </div>

            <div class="card-body p-4">
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">@lang('basket.name')</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-person"></i></span>
                            <input id="name" type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   name="name" value="{{ old('name') }}" required autofocus>
                        </div>
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">@lang('basket.email')</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-envelope-at"></i></span>
                            <input id="email" type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   name="email" value="{{ old('email') }}" required>
                        </div>
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">@lang('main.password')</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-lock"></i></span>
                            <input id="password" type="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   name="password" required>
                        </div>
                        @error('password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password-confirm" class="form-label">@lang('main.repeat_password')</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-lock-fill"></i></span>
                            <input id="password-confirm" type="password"
                                   class="form-control" name="password_confirmation" required>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle-fill me-1"></i> @lang('main.registration')
                        </button>
                        <a class="btn btn-link text-decoration-none text-center" href="{{ route('login') }}">
                            @lang('main.already_have_account')
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection
