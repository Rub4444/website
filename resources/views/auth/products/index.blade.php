@extends('auth.layouts.master')

@section('title', 'Товары')

@section('content')
<div class="col-md-12">
   <!-- Add Product Button -->
   <div class="mt-4">
        <a class="btn btn-success" href="{{ route('products.create') }}">
            <i class="fas fa-plus text-white"></i> Ավելացնել Ապրանք
        </a>
    </div>
    <!-- Card for the Table -->
    <div class="card shadow-lg">
        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Կոդ</th>
                        <th>Անուն</th>
                        <th>Կատեգորիա</th>
                        {{-- <th>Քանակ</th> --}}
                        <th>Գործողություններ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>{{ $product->code }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->category ? $product->category->name : 'Без категории' }}</td>
                        {{-- <td></td> --}}
                        <td>
                            <div class="btn-group" role="group">
                                <!-- View Button -->
                                <a class="btn btn-success" href="{{ route('products.show', $product) }}" data-toggle="tooltip" title="Открыть товар">
                                    <i class="fas fa-eye text-white"></i> Բացել
                                </a>

                                <!-- Skus Button -->
                                <a class="btn btn-info" href="{{ route('skus.index', $product) }}" data-toggle="tooltip" title="Смотреть SKU">
                                    <i class="fas fa-box text-white"></i> ՍԿՈՒՍ
                                </a>

                                <!-- Edit Button -->
                                <a class="btn btn-warning" href="{{ route('products.edit', $product) }}" data-toggle="tooltip" title="Редактировать товар">
                                    <i class="fas fa-edit text-white"></i> Փոփոխել
                                </a>

                                <form action="{{ route('products.destroy', $product) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Հեռացնել</button>
                                </form>

                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="pagination__area bg__gray--color">
            <nav class="pagination justify-content-center">
                <ul class="pagination__wrapper d-flex align-items-center justify-content-center">
                    {{-- Кнопка "назад" --}}
                    @if ($products->onFirstPage())
                        <li class="pagination__list disabled">
                            <span class="pagination__item--arrow link">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22.51" height="20.443" viewBox="0 0 512 512">
                                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="48" d="M244 400L100 256l144-144M120 256h292"/>
                                </svg>
                            </span>
                        </li>
                    @else
                        <li class="pagination__list">
                            <a href="{{ $products->previousPageUrl() }}" class="pagination__item--arrow link">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22.51" height="20.443" viewBox="0 0 512 512">
                                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="48" d="M244 400L100 256l144-144M120 256h292"/>
                                </svg>
                            </a>
                        </li>
                    @endif

                    {{-- Номера страниц --}}
                    @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                        @if ($page == $products->currentPage())
                            <li class="pagination__list"><span class="pagination__item pagination__item--current">{{ $page }}</span></li>
                        @else
                            <li class="pagination__list"><a href="{{ $url }}" class="pagination__item link">{{ $page }}</a></li>
                        @endif
                    @endforeach

                    {{-- Кнопка "вперёд" --}}
                    @if ($products->hasMorePages())
                        <li class="pagination__list">
                            <a href="{{ $products->nextPageUrl() }}" class="pagination__item--arrow link">
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
@endsection
