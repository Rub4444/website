@extends('auth.layouts.master')

@section('content')
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="card shadow-lg border-0 p-4" style="max-width: 500px; width: 100%;">
            <div class="card-body text-center">
                <h4 class="card-title mb-3">✅ @lang('main.confirmed_email')</h4>

                @if (session('message'))
                    <div class="alert alert-success">
                        {{ session('message') }}
                    </div>
                @else
                    <div class="alert alert-warning">
                        @lang('main.for_continue_please')
                    </div>
                @endif

                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button id="verifyBtn" type="submit" class="btn btn-primary w-100">
                        @lang('main.send_again')
                    </button>
                </form>

                <p class="mt-3 text-muted small">
                    @lang('main.email_in_spam')
                </p>
            </div>
        </div>
    </div>

    <script>
        const btn = document.getElementById('verifyBtn');
        btn.addEventListener('click', () => {
            btn.disabled = true;
            btn.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> @lang('main.sending')...`;
        });
    </script>
@endsection
