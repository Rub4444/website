@extends('auth.layouts.master')

@section('title', 'Ապրանքներ')

@section('content')
<div class="container-fluid mt-4">

    <!-- Верхняя панель -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Ապրանքներ</h4>
        <a href="{{ route('products.create') }}" class="btn btn-success btn-lg rounded-pill px-4 py-2 shadow-sm d-flex align-items-center gap-2">
            <i class="fas fa-plus text-white"></i> <span>Ավելացնել Ապրանք</span>
        </a>
    </div>

    <!-- Импорт -->
    <div class="card border-0 rounded-4 shadow-sm mb-4">
        <div class="card-header bg-light rounded-top-4 fw-semibold text-secondary">
            📦 Импорт товаров
        </div>
        <div class="card-body">
            <form action="{{ route('products.import') }}" method="POST" enctype="multipart/form-data" class="row g-3">
                @csrf
                <div class="col-md-4">
                    <label for="category_id" class="form-label fw-semibold">ID Категории</label>
                    <input type="number" name="category_id" id="category_id" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label for="property_id" class="form-label fw-semibold">ID Свойств (через запятую)</label>
                    <input type="text" name="property_id" id="property_id" class="form-control" placeholder="например: 1,2,3" required>
                </div>
                <div class="col-md-4">
                    <label for="file" class="form-label fw-semibold">Файл</label>
                    <input type="file" name="file" id="file" class="form-control" required>
                </div>
                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-primary px-4 shadow-sm">Импортировать</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Поиск -->
    <div class="card border-0 rounded-4 shadow-sm mb-4">
        <div class="card-header bg-light rounded-top-4 fw-semibold text-secondary">
            🔍 Որոնում
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('products.index') }}" class="row g-2 align-items-center">
                <div class="col-md-6">
                    <input type="text" name="search" placeholder="Որոնել ըստ անվանման" value="{{ $search ?? '' }}" class="form-control shadow-sm" />
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary px-4 shadow-sm">Որոնել</button>
                </div>
                <div class="col-auto">
                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary px-4 shadow-sm">Ջնջել ֆիլտրը</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Таблица -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-secondary text-uppercase small">
                        <tr>
                            <th style="width: 5%;">#</th>
                            <th style="width: 20%;">Կոդ</th>
                            <th>Անուն</th>
                            <th>Կատեգորիա</th>
                            <th class="text-end" style="width: 22%;">Գործողություններ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr>
                            <td class="fw-semibold">{{ $product->id }}</td>
                            <td><code>{{ $product->code }}</code></td>
                            <td class="text-truncate" style="max-width: 250px;">{{ $product->name }}</td>
                            <td>{{ $product->category->name ?? 'Առանց կատ.' }}</td>
                            <td class="text-end">
                                <div class="btn-group shadow-sm" role="group">
                                    <a href="{{ route('products.show', $product) }}"
                                    class="btn btn-success btn-lg rounded-pill d-flex align-items-center gap-2"
                                    title="Դիտել">
                                        <i class="fas fa-eye text-white"></i>
                                    </a>
                                    <a href="{{ route('skus.index', $product) }}"
                                    class="btn btn-info btn-lg rounded-pill d-flex align-items-center gap-2"
                                    title="ՍԿՈՒՍ">
                                        <i class="fas fa-box text-white"></i>
                                    </a>
                                    <a href="{{ route('products.edit', $product) }}"
                                    class="btn btn-warning btn-lg rounded-pill d-flex align-items-center gap-2"
                                    title="Փոփոխել">
                                        <i class="fas fa-edit text-white"></i>
                                    </a>
                                    <form action="{{ route('products.destroy', $product) }}"
                                        method="POST"
                                        onsubmit="return confirm('Վստա՞հ եք, որ ցանկանում եք հեռացնել այս ապրանքը։')"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-danger btn-lg rounded-pill d-flex align-items-center gap-2"
                                                title="Ջնջել">
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
            {{ $products->links('vendor.custom') }}
        </div>
        @endif
    </div>

</div>
@endsection
<style>
    code {
        font-size: 0.85rem;
        color: #6c757d;
    }
    .table td, .table th {
        vertical-align: middle;
    }
    .btn i {
        color: white !important;
        font-size: 1.2rem;
    }
</style>
