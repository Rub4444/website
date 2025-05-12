@extends('auth.layouts.master')

@section('content')
<div class="container d-flex align-items-center justify-content-center mt-2">
    <div class="col-12 col-md-6">
        <div class="card shadow-lg rounded-4">
            <div class="card-header text-center text-white bg-success rounded-top-4 py-3">
                <h4><i class="bi bi-person-circle me-2"></i>@lang('main.login')</h4>
            </div>

            <div class="card-body p-4">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">@lang('basket.email')</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                            <input id="email" type="email"
                                class="form-control @error('email') is-invalid @enderror"
                                name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
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
                                name="password" required autocomplete="current-password">
                        </div>
                        @error('password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3 form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember"
                               {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">
                            @lang('main.remember_me')
                        </label>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-box-arrow-in-right me-1"></i>@lang('main.login')
                        </button>
                        <a class="btn btn-link text-decoration-none text-center" href="{{ route('register') }}">
                            @lang('main.registration')
                        </a>
                        <div class="text-center mt-2">
                            @if (Route::has('password.request'))
                                <a class="text-decoration-none" href="{{ route('password.request') }}">
                                    @lang('main.forgot_password')
                                </a>
                            @endif
                        </div>


                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
