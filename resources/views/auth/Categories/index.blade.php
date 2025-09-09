@extends('auth.layouts.master')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Կատեգորիաներ</h2>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-success">
            <i class="fas fa-plus me-1 text-white"></i> Ավելացնել կատեգորիա
        </a>
    </div>

    <div class="card shadow rounded-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Կոդ</th>
                            <th scope="col">Անվանում</th>
                            <th scope="col" class="text-end">Գործողություններ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($AllCategories as $category)
                        <tr>
                            <td>{{ $category->id }}</td>
                            <td>{{ $category->code }}</td>
                            <td>{{ $category->name }}</td>
                            <td class="text-end">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.categories.show', $category) }}" class="btn btn-outline-success btn-sm" data-bs-toggle="tooltip" title="Դիտել">
                                        <i class="fas fa-eye text-white"></i>
                                    </a>
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-outline-warning btn-sm" data-bs-toggle="tooltip" title="Խմբագրել">
                                        <i class="fas fa-edit text-white"></i>
                                    </a>
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Վստա՞հ եք, որ ցանկանում եք հեռացնել։')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" data-bs-toggle="tooltip" title="Ջնջել">
                                            <i class="fas fa-trash text-white"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $AllCategories->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection
