@extends('layouts.master')

@section('title', 'Մեր մասին')

@section('content')
<div class="container py-5">
    <div class="row align-items-center">
        <div class="col-md-12">
            <h1 class="mb-4 text-success fw-bold">@lang('main.about-us')</h1>
            <p class="fs-5">
                <strong>IjevanMarket.am</strong> @lang('main.is_a_modern')
            </p>
            <p class="fs-5">
                @lang('main.our_goal')
            </p>
            <p class="fs-5">
                @lang('main.we_value')
            </p>

            <h2 class="mt-5 text-success">@lang('main.mission')</h2>
            <p class="fs-5">✅ @lang('main.to_provide')</p>

            <h2 class="mt-4 text-success">@lang('main.vision')</h2>
            <p class="fs-5">🌟 @lang('main.to_become')</p>

            <h2 class="mt-4 text-success">@lang('main.values')</h2>
            <ul class="fs-5">
                <li>🤝 <strong>@lang('main.honestly')</strong> — @lang('main.we_build')</li>
                <li>📦 <strong>@lang('main.quality_and_freshness')</strong> — @lang('main.we_carefully')</li>
                <li>🚚 <strong>@lang('main.fast_delivery')</strong> — @lang('main.you_receive')</li>
                <li>🛒 <strong>@lang('main.convenience')</strong> — @lang('main.easy_to_choose')</li>
            </ul>
        </div>
    </div>

    <hr class="my-5">

    <div class="row text-center">
        <div class="col-md-4">
            <i class="bi bi-people-fill fs-1 text-success mb-3"></i>
            <h4>@lang('main.our_team')</h4>
            <p>@lang('main.our_experienced')</p>
        </div>
        <div class="col-md-4">
            <i class="bi bi-award-fill fs-1 text-success mb-3"></i>
            <h4>@lang('main.quality_service')</h4>
            <p>@lang('main.we_prioritize')</p>
        </div>
        <div class="col-md-4">
            <i class="bi bi-truck fs-1 text-success mb-3"></i>
            <h4>@lang('main.fast_delivery')</h4>
            <p>@lang('main.we_ensure')</p>
        </div>
    </div>
</div>
@endsection
