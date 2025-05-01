@extends('auth.layouts.master')

@isset($merchant)
    @section('title', 'Խմբագրել մատակարարին՝ ' . $merchant->name)
@else
    @section('title', 'Ավելացնել մատակարար')
@endisset

@section('content')
    <div class="container mt-5">
        <div class="card shadow rounded-4">
            <div class="card-body p-4">
                @isset($merchant)
                    <h2 class="mb-4 text-center" style="color:#2E8B57;">Խմբագրել մատակարարին՝ <b>{{ $merchant->name }}</b></h2>
                @else
                    <h2 class="mb-4 text-center text-success">Ավելացնել նոր մատակարար</h2>
                @endisset

                <form method="POST" enctype="multipart/form-data"
                      @isset($merchant)
                          action="{{ route('merchants.update', $merchant) }}"
                      @else
                          action="{{ route('merchants.store') }}"
                      @endisset
                >
                    @csrf
                    @isset($merchant)
                        @method('PUT')
                    @endisset

                    <div class="mb-3">
                        <label for="name" class="form-label">Մատակարարի անունը</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               name="name" id="name"
                               value="{{ old('name', $merchant->name ?? '') }}"
                               placeholder="Մուտքագրեք անունը">
                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Էլ․ հասցե</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                               name="email" id="email"
                               value="{{ old('email', $merchant->email ?? '') }}"
                               placeholder="Մուտքագրեք էլ․ հասցեն">
                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="text-center mt-4">
                        <button class="btn btn-outline-primary rounded-pill px-4">
                            Պահպանել
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
