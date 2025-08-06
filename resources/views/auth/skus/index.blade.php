@extends('auth.layouts.master')

@section('title', 'Ապրանքային առաջարկներ')

@section('content')
    <div class="col-md-12">
        <div class="card-header text-white" style="background-color:#2E8B57;">
            <h3 class="mb-4 text-center">Ապրանքային առաջարկներ - <span>{{ $product->name }}</span></h3>
        </div>
        <div class="table-responsive">
            <div class="card border-0 rounded-4 shadow-sm mb-4">
                <div class="card-header bg-light rounded-top-4 fw-semibold text-secondary">
                    🔍 Որոնում
                </div>
                <div class="card-body">
                    <form method="GET" class="row mb-3 g-2">
                        <div class="col-md-6">
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Որոնել SKU անվամբ կամ հատկությամբ">
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-primary" type="submit">Որոնել</button>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('skus.index', $product) }}" class="btn btn-outline-secondary">Ջնջել ֆիլտրը</a>
                        </div>
                    </form>
                </div>
            </div>
            <br>
            <a class="btn btn-success" href="{{ route('skus.create', $product) }}">
                Ավելացնել առաջարկ (SKU)
            </a>
            <br>
            <table class="table table-hover table-bordered align-middle text-center">
                <thead class="">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">SKU Անուն</th>
                        <th scope="col">Առաջարկի հատկություններ</th>
                        <th scope="col">Գործողություններ</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach($skus as $sku)
                    {{-- @php
                        dd($sku->propertyOptions);
                    @endphp --}}
                        <tr>
                            <td>{{ $sku->id }}</td>
                            <td>{{ $sku->name ?? '-' }}</td>
                            <td>{{ $sku->propertyOptions->map->name->implode(', ') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a class="btn btn-outline-success" href="{{ route('skus.show', [$product, $sku]) }}">
                                        Դիտել
                                    </a>
                                    <a class="btn btn-outline-warning" href="{{ route('skus.edit', [$product, $sku]) }}">
                                        Խմբագրել
                                    </a>
                                    <form action="{{ route('skus.destroy', [$product, $sku]) }}" method="POST" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-outline-danger" onclick="return confirm('Վստա՞հ եք, որ ցանկանում եք ջնջել։')">
                                            Ջնջել
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- <div class="my-3">
            {{ $skus->links() }}
        </div> --}}
        <!-- Pagination -->
        <nav class="d-flex justify-content-center">
            {{ $skus->links('vendor.custom') }}
        </nav>

    </div>
@endsection
