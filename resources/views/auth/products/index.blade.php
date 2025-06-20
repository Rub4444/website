@extends('auth.layouts.master')

@section('title', 'Ապրանքներ')

@section('content')
<div class="col-md-12 mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('products.create') }}" class="btn btn-success btn-sm rounded-pill px-4 shadow-sm">
            <i class="fas fa-plus me-2"></i> Ավելացնել Ապրանք
        </a>
    </div>

    <div class="card mb-4 shadow-sm rounded-4 border-0">
        <div class="card-header bg-light fw-semibold text-secondary">
            Импорт товаров
        </div>
        <div class="card-body">
            <form action="{{ route('products.import') }}" method="POST" enctype="multipart/form-data" class="row g-3">
                @csrf
                <div class="col-md-4">
                    <label for="category_id" class="form-label fw-semibold">Category ID</label>
                    <input type="number" name="category_id" id="category_id" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label for="property_id" class="form-label fw-semibold">Property ID (через запятую)</label>
                    <input type="text" name="property_id" id="property_id" class="form-control" placeholder="например: 1,2,3" required>
                </div>
                <div class="col-md-4">
                    <label for="file" class="form-label fw-semibold">Файл</label>
                    <input type="file" name="file" id="file" class="form-control" required>
                </div>
                <div class="col-12 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary px-4 shadow-sm">Импортировать</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card mb-4 shadow-sm rounded-4 border-0">
        <div class="card-header bg-light fw-semibold text-secondary">
            Поиск товаров
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('products.index') }}" class="d-flex gap-2 align-items-center">
                <input type="text" name="search" placeholder="Поиск по названию" value="{{ $search ?? '' }}" class="form-control shadow-sm" />
                <button type="submit" class="btn btn-primary px-4 shadow-sm">Найти</button>
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary px-4 shadow-sm">Отмена</a>
            </form>
        </div>
    </div>

    <div class="card shadow rounded-4 border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-secondary text-uppercase small">
                        <tr>
                            <th style="width: 5%;">#</th>
                            <th style="width: 20%;">Կոդ</th>
                            <th>Անուն</th>
                            <th>Կատեգորիա</th>
                            <th class="text-end" style="width: 20%;">Գործողություններ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr>
                            <td class="fw-semibold">{{ $product->id }}</td>
                            <td><code class="text-muted">{{ $product->code }}</code></td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->category ? $product->category->name : 'Առանց կատեգորիայի' }}</td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm shadow" role="group">
                                    <a href="{{ route('products.show', $product) }}" class="btn btn-outline-success rounded-pill" title="Բացել">
                                        <i class="fas fa-eye text-white"></i>
                                    </a>
                                    <a href="{{ route('skus.index', $product) }}" class="btn btn-outline-info rounded-pill" title="ՍԿՈՒՍ">
                                        <i class="fas fa-box text-white"></i>
                                    </a>
                                    <a href="{{ route('products.edit', $product) }}" class="btn btn-outline-warning rounded-pill" title="Փոփոխել">
                                        <i class="fas fa-edit text-white"></i>
                                    </a>
                                    <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Վստա՞հ եք, որ ցանկանում եք հեռացնել այս ապրանքը։')" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger rounded-pill" title="Հեռացնել">
                                            <i class="fas fa-trash text-white"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted fst-italic py-4">Ապրանքներ չկան։</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($products->hasPages())
        <div class="card-footer bg-white d-flex justify-content-center border-0">
            {{ $products->links('pagination::bootstrap-4') }}
        </div>
        @endif
    </div>

</div>

<style>
    /* Акценты цветов по твоим переменным, можно поменять под свои */
    h4.text-primary {
        color: var(--theme-color2);
    }
    .btn-success {
        background-color: var(--theme-color2);
        border-color: var(--theme-color2);
        transition: background-color 0.3s ease;
    }
    .btn-success:hover {
        background-color: var(--theme-color5);
        border-color: var(--theme-color5);
    }
    .btn-primary {
        background-color: var(--secondary-color);
        border-color: var(--secondary-color);
        transition: background-color 0.3s ease;
    }
    .btn-primary:hover {
        background-color: var(--secondary-color2);
        border-color: var(--secondary-color2);
    }
    .btn-outline-success {
        border-color: var(--theme-color2);
        color: var(--theme-color2);
    }
    .btn-outline-success:hover {
        background-color: var(--theme-color2);
        color: #fff;
    }
    .btn-outline-info {
        border-color: var(--theme-color3);
        color: var(--theme-color3);
    }
    .btn-outline-info:hover {
        background-color: var(--theme-color3);
        color: #fff;
    }
    .btn-outline-warning {
        border-color: var(--secondary-color);
        color: var(--secondary-color);
    }
    .btn-outline-warning:hover {
        background-color: var(--secondary-color);
        color: #212529;
    }
    .btn-outline-danger {
        border-color: #dc3545;
        color: #dc3545;
    }
    .btn-outline-danger:hover {
        background-color: #dc3545;
        color: #fff;
    }
    /* Код внутри таблицы */
    code {
        font-size: 0.9rem;
        font-weight: 600;
    }
</style>
@endsection
