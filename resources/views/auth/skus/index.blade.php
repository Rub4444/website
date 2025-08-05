@extends('auth.layouts.master')

@section('title', 'Ապրանքային առաջարկներ')

@section('content')
    <div class="col-md-12">
        <div class="card-header text-white" style="background-color:#2E8B57;">
            <h3 class="mb-4 text-center">Ապրանքային առաջարկներ - <span>{{ $product->name }}</span></h3>
        </div>
        <div class="table-responsive">
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
        <!-- Products Listing -->
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4 mb-5 align-items-stretch">
            @foreach($skus as $sku)
                <div class="col-6 col-xss-6 col-sm-4 col-lg-3 col-xxl-2 my-2 p-1 p-lg-2">
                    @include('card', compact('sku'))
                </div>
            @endforeach
        </div>

    </div>
@endsection
