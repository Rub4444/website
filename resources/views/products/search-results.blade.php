@extends('layouts.master')

@section('content')
    {{-- <h1>Որոնման արդյունքներ "{{ $query }}"</h1> --}}

    @if($skus->count())
        <div class="row">
            @foreach($skus as $sku)
                @include('card', compact('sku'))
            @endforeach
        </div>

        <!-- Pagination -->
        {{-- <nav class="d-flex justify-content-center">
            {{ $skus->links('vendor.custom') }}
        </nav> --}}
   @else
        <div class="container my-5">
            <div class="alert alert-warning text-center shadow-lg p-4 rounded-4" role="alert" style="background: linear-gradient(135deg, #fff3cd, #ffeeba); border: none;">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="#856404" class="bi bi-exclamation-triangle mb-3" viewBox="0 0 16 16">
                    <path d="M7.938 2.016a.13.13 0 0 1 .125 0l6.857 3.964c.104.06.18.165.18.286v7.468a.285.285 0 0 1-.18.286l-6.857 3.964a.13.13 0 0 1-.125 0L1.08 14.02A.285.285 0 0 1 .9 13.734V6.266c0-.121.076-.226.18-.286L7.938 2.016zm.82 10.06a.823.823 0 1 1-1.647 0 .823.823 0 0 1 1.647 0zm-.823-6.579a.905.905 0 0 1 .9.899v3.242a.9.9 0 0 1-1.8 0V6.396a.905.905 0 0 1 .9-.899z"/>
                </svg>
                <h4 class="mb-2 fw-bold" style="color: #856404;">@lang('main.no_products_found')</h4>
                <p class="mb-0" style="color: #856404;">@lang('main.sorry_but_we_cant_find')</p>
            </div>
        </div>
    @endif

@endsection
