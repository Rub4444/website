@extends('auth.layouts.master')

@section('title', 'Товары')

@section('content')
<div class="col-md-12">
   <!-- Add Product Button -->
   <div class="mt-4">
        <a class="btn btn-success" href="{{ route('products.create') }}">
            <i class="fas fa-plus text-white"></i> Добавить товар
        </a>
    </div>
    <!-- Card for the Table -->
    <div class="card shadow-lg">
        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Код</th>
                        <th>Название</th>
                        <th>Категория</th>
                        <th>Кол-во товарных предложений</th>
                        <th>Действия</th>
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
                                    <i class="fas fa-eye text-white"></i> Открыть
                                </a>

                                <!-- Skus Button -->
                                <a class="btn btn-info" href="{{ route('skus.index', $product) }}" data-toggle="tooltip" title="Смотреть SKU">
                                    <i class="fas fa-box text-white"></i> Skus
                                </a>

                                <!-- Edit Button -->
                                <a class="btn btn-warning" href="{{ route('products.edit', $product) }}" data-toggle="tooltip" title="Редактировать товар">
                                    <i class="fas fa-edit text-white"></i> Редактировать
                                </a>

                                <!-- Delete Button - Open Modal -->
                                <button class="btn btn-danger" data-toggle="modal" data-target="#deleteModal{{ $product->id }}" data-toggle="tooltip" title="Удалить товар">
                                    <i class="fas fa-trash-alt text-white"></i> Удалить
                                </button>
                            </div>

                            <!-- Modal for Deletion -->
                            <div class="modal fade" id="deleteModal{{ $product->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel{{ $product->id }}" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteModalLabel{{ $product->id }}">Удалить товар</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Закрыть">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            Вы уверены, что хотите удалить товар «{{ $product->name }}»?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                                            <form action="{{ route('products.destroy', $product) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Удалить</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
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
