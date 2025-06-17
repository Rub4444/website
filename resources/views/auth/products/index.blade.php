@extends('auth.layouts.master')

@section('title', 'Ապրանքներ')

@section('content')
<div class="col-md-12 mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Ապրանքների ցանկ</h4>
    <a href="{{ route('products.create') }}" class="btn btn-success btn-sm rounded-2">
        <i class="fas fa-plus me-1"></i> Ավելացնել Ապրանք
    </a>
</div>

<div class="card mb-4">
    <div class="card-header">
        <strong>Импорт товаров</strong>
    </div>
    <div class="card-body">
        <form action="{{ route('products.import') }}" method="POST" enctype="multipart/form-data" class="row g-3">
            @csrf
            <div class="col-md-4">
                <label for="category_id" class="form-label">Category ID</label>
                <input type="number" name="category_id" id="category_id" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label for="property_id" class="form-label">Property ID (через запятую)</label>
                <input type="text" name="property_id" id="property_id" class="form-control" placeholder="например: 1,2,3" required>
            </div>
            <div class="col-md-4">
                <label for="file" class="form-label">Файл</label>
                <input type="file" name="file" id="file" class="form-control" required>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Импортировать</button>
            </div>
        </form>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">
        <strong>Поиск товаров</strong>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('products.index') }}" class="d-flex gap-2 align-items-center">
            <input type="text" name="search" placeholder="Поиск по названию" value="{{ $search ?? '' }}" class="form-control" />
            <button type="submit" class="btn btn-primary">Найти</button>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Отмена</a>
        </form>
    </div>
</div>


    <div class="card shadow-sm rounded-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Կոդ</th>
                            <th>Անուն</th>
                            <th>Կատեգորիա</th>
                            <th class="text-end">Գործողություններ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>{{ $product->code }}</td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->category ? $product->category->name : 'Առանց կատեգորիայի' }}</td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('products.show', $product) }}" class="btn btn-outline-success rounded-2" title="Բացել">
                                        <i class="fas fa-eye text-white"></i>
                                    </a>
                                    <a href="{{ route('skus.index', $product) }}" class="btn btn-outline-info rounded-2" title="ՍԿՈՒՍ">
                                        <i class="fas fa-box text-white"></i>
                                    </a>
                                    <a href="{{ route('products.edit', $product) }}" class="btn btn-outline-warning rounded-2" title="Փոփոխել">
                                        <i class="fas fa-edit text-white" ></i>
                                    </a>
                                    <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Վստա՞հ եք, որ ցանկանում եք հեռացնել այս ապրանքը։')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger rounded-2" title="Հեռացնել">
                                            <i class="fas fa-trash text-white"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Ապրանքներ չկան։</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($products->hasPages())
        <div class="card-footer bg-white d-flex justify-content-center">
            {{ $products->links('pagination::bootstrap-4') }}
        </div>
        @endif
    </div>
</div>
@endsection
