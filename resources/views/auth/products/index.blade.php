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
                        <th>Քանակ</th>
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
                        <td></td>
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
        <div class="card-footer text-center">
            <nav>
                <ul class="pagination justify-content-center">
                    <!-- Previous Button -->
                    <li class="page-item {{ $products->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $products->previousPageUrl() }}" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>

                    <!-- Page Numbers -->
                    @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                        <li class="page-item {{ $page == $products->currentPage() ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach

                    <!-- Next Button -->
                    <li class="page-item {{ $products->hasMorePages() ? '' : 'disabled' }}">
                        <a class="page-link" href="{{ $products->nextPageUrl() }}" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>
@endsection
