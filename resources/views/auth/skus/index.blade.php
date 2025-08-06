@extends('auth.layouts.master')

@section('title', 'Ապրանքային առաջարկներ')

@section('content')
    <div class="col-md-12">
        <div class="card-header text-white" style="background-color:#2E8B57;">
            <h3 class="mb-4 text-center">Ապրանքային առաջարկներ - <span>{{ $product->name }}</span></h3>
        </div>
        {{-- <div class="table-responsive"> --}}
            <div class="container-fluid px-3">
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
                <a class="btn btn-success" href="{{ route('skus.create', $product) }}">
                    Ավելացնել առաջարկ (SKU)
                </a>
                <div class="table-responsive rounded shadow-sm border">
                    <table class="table table-hover table-bordered align-middle text-center mb-0">
                        <thead class="table-success text-dark">
                            <tr class="align-middle">
                                <th scope="col" class="py-3">#</th>
                                <th scope="col">SKU Անուն</th>
                                <th scope="col">Առաջարկի հատկություններ</th>
                                <th scope="col">Գործողություններ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($skus as $sku)
                                <tr>
                                    <td class="fw-semibold">{{ $sku->id }}</td>
                                    <td>{{ $sku->name ?? '—' }}</td>
                                    <td>
                                        <span class="badge bg-light text-dark px-2">
                                            {{ $sku->propertyOptions->map(fn($opt) => $opt->property->name . ': ' . $opt->name)->implode(', ') }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-1 flex-wrap">
                                            <a class="btn btn-sm btn-outline-success" href="{{ route('skus.show', [$product, $sku]) }}">
                                                Դիտել
                                            </a>
                                            <a class="btn btn-sm btn-outline-warning" href="{{ route('skus.edit', [$product, $sku]) }}">
                                                Խմբագրել
                                            </a>
                                            <form action="{{ route('skus.destroy', [$product, $sku]) }}" method="POST" onsubmit="return confirm('Վստա՞հ եք, որ ցանկանում եք ջնջել։')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    Ջնջել
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-muted py-4">Առաջարկներ չկան։</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        {{-- </div> --}}

        {{-- <div class="my-3">
            {{ $skus->links() }}
        </div> --}}
        <!-- Pagination -->
        <nav class="d-flex justify-content-center">
            {{ $skus->links('vendor.custom') }}
        </nav>

    </div>
@endsection
