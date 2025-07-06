@extends('layouts.master')

@section('content')
<section class="py-4">
    <div class="container">
        <div class="table-responsive rounded shadow-sm bg-white p-3">
            <table class="table align-middle mb-0">
                <thead class="table-light d-none d-md-table-header-group">
                    <tr>
                        <th>@lang('main.product')</th>
                        <th>@lang('main.price')</th>
                        <th class="text-end">@lang('main.add_to_card')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($skus as $sku)
                        <tr class="align-middle">
                            <td class="py-3">
                                <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center">
                                    <a href="{{ route('sku', [$sku->product->category->code, $sku->product->code, $sku]) }}" class="me-md-3 mb-2 mb-md-0">
                                        <img src="{{ asset('storage/' . $sku->image) }}" class="rounded" alt="{{ $sku->product->__('name') }}"
                                             style="width: 80px; height: 80px; object-fit: contain;">
                                    </a>

                                    <div>
                                        <h6 class="mb-1 fs-6">{{ $sku->product->__('name') }}</h6>
                                        @foreach ($sku->propertyOptions as $option)
                                            <div class="text-muted small">{{ $option->property->name }}: {{ $option->name }}</div>
                                        @endforeach

                                        {{-- Цена и кнопка на мобилке --}}
                                        <div class="d-block d-md-none mt-2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="fw-bold text-success">{{ $sku->price }} {{ $currencySymbol }}</span>
                                                <form action="{{ route('basket-add', $sku) }}" method="POST" class="ms-3">
                                                    @csrf
                                                    @if($sku->isAvailable())
                                                        <button type="submit" class="btn btn-success btn-sm px-2 py-1">
                                                            <i class="bi bi-cart-plus"></i>
                                                        </button>
                                                    @else
                                                        <button type="button" class="btn btn-outline-danger btn-sm px-2 py-1" disabled>
                                                            @lang('main.available')
                                                        </button>
                                                    @endif
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Цена на десктопе --}}
                            <td class="d-none d-md-table-cell">
                                <span class="fw-bold text-success">{{ $sku->price }} {{ $currencySymbol }}</span>
                            </td>

                            {{-- Кнопка на десктопе --}}
                            <td class="text-end d-none d-md-table-cell">
                                <form action="{{ route('basket-add', $sku) }}" method="POST">
                                    @csrf
                                    @if($sku->isAvailable())
                                        <button type="submit" class="btn btn-success btn-sm px-3 py-1">
                                            <i class="bi bi-cart-plus me-1"></i> @lang('main.basket')
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-outline-danger btn-sm" disabled>
                                            @lang('main.available')
                                        </button>
                                    @endif
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-white bg-success p-3 rounded">
                                @lang('main.there_are_no_suitable_products')
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Кнопки снизу -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 gap-3">
            <a href="{{ route('index') }}" class="btn btn-outline-secondary w-100 w-md-auto">
                <i class="bi bi-arrow-left me-1"></i> @lang('basket.continue_shopping')
            </a>

            <a href="{{ route('shop') }}" class="btn btn-outline-primary w-100 w-md-auto">
                @lang('main.view_all_products')
            </a>
        </div>
    </div>
</section>
@endsection



{{-- <th scope="col" class="text-center">@lang('main.stock_status')</th> --}}

<!-- Availability -->
{{-- <td class="text-center">
    @if ($sku->count > 0)
        <span class="badge bg-success">@lang('main.in_stock')</span>
    @else
        <span class="badge bg-danger">@lang('main.out_off_stock')</span>
    @endif
</td> --}}
