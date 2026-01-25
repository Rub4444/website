@extends('auth.layouts.master')

@section('title', __('basket.my_profile'))

@section('content')
    <main class="main__content_wrapper">
        <!-- my account section start -->
        <section class="my__account--section section--padding">
            <div class="container">
                <div class="my__account--section__inner border-radius-10 d-flex">
                    <div class="account__left--sidebar">
                        <ul class="account__menu">
                            <li class="account__menu--list active"><a href="my-account.html">@lang('basket.orders')</a></li>
                            {{-- <li class="account__menu--list"><a href="my-account-2.html">@lang('main.addresses')</a></li> --}}
                            <li class="account__menu--list"><a href="{{route('basket')}}">@lang('main.basket')</a></li>

                        </ul>
                    </div>
                    <div class="account__wrapper">
                        <div class="account__content">
                            <div class="account__table--area">
                                {{-- <div class="mb-3">
                                    @if($showAll)
                                        <a href="{{ route('home') }}" class="btn btn-secondary">
                                            @lang('order.show_recent_orders')
                                        </a>
                                    @else
                                        <a href="{{ route('home', ['show_all' => 1]) }}" class="btn btn-primary">
                                            @lang('order.show_all_orders')
                                        </a>
                                    @endif
                                </div> --}}

                                <table class="account__table">
                                    <thead class="account__table--header">
                                        <tr class="account__table--header__child">
                                            @admin<th class="account__table--header__child--items">#</th>@endadmin
                                            <th class="account__table--header__child--items">@lang('basket.name')</th>
                                            <th class="account__table--header__child--items">@lang('basket.phone_number')</th>
                                            <th class="account__table--header__child--items">@lang('basket.when_send')</th>
                                            <th class="account__table--header__child--items">@lang('basket.cost')</th>
                                            <th class="account__table--header__child--items">@lang('order.status')</th>
                                            <th class="account__table--header__child--items">@lang('basket.actions')</th>
                                        </tr>
                                    </thead>
                                    <tbody class="account__table--body mobile__none">
                                        @foreach($orders as $order)
                                            <tr class="account__table--body__child">
                                                @admin<td class="account__table--body__child--items">{{ $order->id}}</td>@endadmin
                                                <td class="account__table--body__child--items">{{ $order->name }}</td>
                                                <td class="account__table--body__child--items">{{ $order->phone }}</td>
                                                <td class="account__table--body__child--items">{{ $order->created_at->format('H:i d/m/Y') }}</td>
                                                <td class="account__table--body__child--items">{{ $order->sum}} ֏</td>
                                                <td class="account__table--body__child--items">{{ $order->getStatusName() }}</td>
                                                <td class="account__table--body__child--items">
                                                    <div class="btn-group" role="group">
                                                        <a
                                                        @admin
                                                            href="{{route('orders.show', $order)}}"
                                                        @else
                                                            href="{{route('person.orders.show', $order)}}"
                                                        @endadmin
                                                        class="btn btn-success" type="button">@lang('basket.open')</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tbody class="account__table--body mobile__block">
                                        @foreach($orders as $order)
                                            <tr class="account__table--body__child">
                                                @admin
                                                <td class="account__table--body__child--items">
                                                    <strong>#</strong>
                                                    <span>{{ $order->id}}</span>
                                                </td>
                                                @endadmin
                                                <td class="account__table--body__child--items">
                                                    <strong>@lang('basket.name')</strong>
                                                    <span>{{ $order->name }}</span>
                                                </td>
                                                <td class="account__table--body__child--items">
                                                    <strong>@lang('basket.phone_number')</strong>
                                                    <span>{{ $order->phone }}</span>
                                                </td>
                                                <td class="account__table--body__child--items">
                                                    <strong>@lang('basket.when_send')</strong>
                                                    <span>{{ $order->created_at->format('H:i d/m/Y') }}</span>
                                                </td>
                                                <td class="account__table--body__child--items">
                                                    <strong>@lang('basket.cost')</strong>
                                                    <span>{{ $order->sum}} ֏</span>
                                                </td>
                                                <td class="account__table--body__child--items">
                                                    <strong>@lang('order.status')</strong>
                                                    <span>{{ $order->getStatusName()}}</span>
                                                </td>
                                                <td class="account__table--body__child--items">
                                                    <strong>@lang('basket.actions')</strong>
                                                    <span>
                                                        <div class="btn-group" role="group">
                                                            <a
                                                            @admin
                                                                href="{{route('orders.show', $order)}}"
                                                            @else
                                                                href="{{route('person.orders.show', $order)}}"
                                                            @endadmin
                                                            class="btn btn-success" type="button">@lang('basket.open')</a>
                                                        </div>
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="pagination__area bg__gray--color">
                                    <nav class="pagination justify-content-center">
                                        <ul class="pagination__wrapper d-flex align-items-center justify-content-center">
                                            {{-- Кнопка "назад" --}}
                                            @if ($orders->onFirstPage())
                                                <li class="pagination__list disabled">
                                                    <span class="pagination__item--arrow link">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="22.51" height="20.443" viewBox="0 0 512 512">
                                                            <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="48" d="M244 400L100 256l144-144M120 256h292"/>
                                                        </svg>
                                                    </span>
                                                </li>
                                            @else
                                                <li class="pagination__list">
                                                    <a href="{{ $orders->previousPageUrl() }}" class="pagination__item--arrow link">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="22.51" height="20.443" viewBox="0 0 512 512">
                                                            <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="48" d="M244 400L100 256l144-144M120 256h292"/>
                                                        </svg>
                                                    </a>
                                                </li>
                                            @endif

                                            {{-- Вывод номеров страниц --}}
                                            @foreach ($orders->links()->elements[0] as $page => $url)
                                                @if ($page == $orders->currentPage())
                                                    <li class="pagination__list"><span class="pagination__item pagination__item--current">{{ $page }}</span></li>
                                                @else
                                                    <li class="pagination__list"><a href="{{ $url }}" class="pagination__item link">{{ $page }}</a></li>
                                                @endif
                                            @endforeach

                                            {{-- Кнопка "вперёд" --}}
                                            @if ($orders->hasMorePages())
                                                <li class="pagination__list">
                                                    <a href="{{ $orders->nextPageUrl() }}" class="pagination__item--arrow link">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="22.51" height="20.443" viewBox="0 0 512 512">
                                                            <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="48" d="M268 112l144 144-144 144M392 256H100"/>
                                                        </svg>
                                                    </a>
                                                </li>
                                            @else
                                                <li class="pagination__list disabled">
                                                    <span class="pagination__item--arrow link">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="22.51" height="20.443" viewBox="0 0 512 512">
                                                            <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="48" d="M268 112l144 144-144 144M392 256H100"/>
                                                        </svg>
                                                    </span>
                                                </li>
                                            @endif
                                        </ul>
                                    </nav>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- my account section end -->
    </main>
@endsection
