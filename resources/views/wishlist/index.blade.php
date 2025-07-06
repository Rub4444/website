@extends('layouts.master')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="table-responsive rounded shadow-sm bg-white p-3">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th scope="col">@lang('main.product')</th>
                        <th scope="col">@lang('main.price')</th>
                        <th scope="col" class="text-end">@lang('main.add_to_card')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($skus as $sku)
                    <tr>
                        <!-- Product Info -->
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @auth
                                    @php $isInWishlist = Auth::user()->hasInWishlist($sku->id); @endphp
                                    <button class="btn btn-outline-danger btn-sm rounded-circle d-flex align-items-center justify-content-center"
                                            data-id="{{ $sku->id }}"
                                            aria-pressed="{{ $isInWishlist ? 'true' : 'false' }}"
                                            style="width: 30px; height: 30px;">
                                        <i class="bi {{ $isInWishlist ? 'bi-heart-fill text-danger' : 'bi-heart' }} fs-6"></i>
                                    </button>
                                @endauth

                                <a href="{{ route('sku', [$sku->product->category->code, $sku->product->code, $sku]) }}">
                                    <img src="{{ asset('storage/' . $sku->image) }}" class="rounded border"
                                         width="55" height="55" alt="{{ $sku->product->__('name') }}"
                                         style="object-fit: contain;">
                                </a>

                                <div class="ms-2">
                                    <div class="fw-semibold small text-truncate" style="max-width: 160px;">
                                        {{ $sku->product->__('name') }}
                                    </div>
                                    @foreach ($sku->propertyOptions as $option)
                                        <small class="text-muted d-block">
                                            {{ $option->property->name }}: {{ $option->name }}
                                        </small>
                                    @endforeach
                                </div>
                            </div>
                        </td>

                        <!-- Price -->
                        <td>
                            <span class="fw-semibold text-success small">{{ $sku->price }} {{ $currencySymbol }}</span>
                        </td>

                        <!-- Add to Cart -->
                        <td class="text-end">
                            <form action="{{ route('basket-add', $sku) }}" method="POST" class="d-inline-block">
                                @csrf
                                @if($sku->isAvailable())
                                    <button type="submit" class="btn btn-success btn-sm px-2 py-1" style="font-size: 0.75rem;">
                                        <i class="bi bi-cart-plus"></i>
                                    </button>
                                @else
                                    <button type="button" class="btn btn-outline-danger btn-sm px-2 py-1" style="font-size: 0.75rem;" disabled>
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

        <!-- Footer buttons -->
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
