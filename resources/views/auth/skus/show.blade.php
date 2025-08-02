@extends('auth.layouts.master')

@section('title', 'Ապրանքային առաջարկ')

@section('content')
    <div class="col-md-12">
        <div class="card shadow-sm border-0 mt-3">
            <div class="card-body">
                <div class="card-header text-white" style="background-color:#2E8B57;">
                    <h2 class="mb-3">Ապրանքային առաջարկի ID - <span>{{ $sku->id }}</span></h2>
                    <h4 class="mb-4">Ապրանք՝ <strong>{{ $product->name }}</strong></h4>
                </div>
                {{-- @php
                    dd( $sku, $sku['price']);
                @endphp --}}
                <div class="table-responsive">
                    <table class="table table-striped table-bordered align-middle">
                        <thead>
                            <tr>
                                <th>Դաշտ</th>
                                <th>Արժեք</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>ID</td>
                                <td>{{ $sku->id }}</td>
                            </tr>
                            <tr>
                                <td>Անուն SKU</td>
                                <td>{{ $sku->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>Գին</td>
                                <td>{{ $sku->price}} դրամ</td>
                            </tr>
                            <tr>
                                <td>Քանակ</td>
                                <td>{{ $sku->count }} հատ</td>
                            </tr>
                            @foreach($sku->propertyOptions as $propertyOption)
                                <tr>
                                    <td>{{ $propertyOption->property->name }}</td>
                                    <td>{{ $propertyOption->name }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    <a href="{{ route('skus.index', $product) }}" class="btn btn-secondary">
                        ← Վերադառնալ ցուցակ
                    </a>
                    <a href="{{ route('skus.edit', [$product, $sku]) }}" class="btn btn-warning">
                        ✎ Խմբագրել
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
