@extends('auth.layouts.master')

@section('title', 'Поставщики')

@section('content')
<div class="col-md-12">
    <!-- Add Supplier Button -->
    <div class="mt-4">
        <a class="btn btn-success" href="{{ route('merchants.create') }}">
            <i class="fas fa-plus text-white"></i> Добавить поставщика
        </a>
    </div>

    <!-- Success Message -->
    @if(session()->has('success'))
        <p class="alert alert-success">{{ session()->get('success') }}</p>
    @endif

    <!-- Suppliers Table -->
    <div class="card shadow-lg">
        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Название</th>
                        <th>Email</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($merchants as $merchant)
                    <tr>
                        <td>{{ $merchant->id }}</td>
                        <td>{{ $merchant->name }}</td>
                        <td>{{ $merchant->email }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <!-- View Button -->
                                <a class="btn btn-success" href="{{ route('merchants.show', $merchant) }}" data-toggle="tooltip" title="Открыть поставщика">
                                    <i class="fas fa-eye text-white"></i> Открыть
                                </a>

                                <!-- Edit Button -->
                                <a class="btn btn-warning" href="{{ route('merchants.edit', $merchant) }}" data-toggle="tooltip" title="Редактировать поставщика">
                                    <i class="fas fa-edit text-white"></i> Редактировать
                                </a>

                                <!-- Update Token Button -->
                                <a class="btn btn-primary" href="{{ route('merchants.update_token', $merchant) }}" data-toggle="tooltip" title="Обновить токен">
                                    <i class="fas fa-sync-alt text-white"></i> Обновить токен
                                </a>

                                <!-- Delete Button -->
                                <form action="{{ route('merchants.destroy', $merchant) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <input class="btn btn-danger" type="submit" value="Удалить" data-toggle="tooltip" title="Удалить поставщика">
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Custom Pagination -->
        <div class="card-footer text-center">
            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                    <!-- Previous Button -->
                    <li class="page-item {{ $merchants->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $merchants->previousPageUrl() }}" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>

                    <!-- Page Numbers -->
                    @foreach ($merchants->getUrlRange(1, $merchants->lastPage()) as $page => $url)
                        <li class="page-item {{ $page == $merchants->currentPage() ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach

                    <!-- Next Button -->
                    <li class="page-item {{ $merchants->hasMorePages() ? '' : 'disabled' }}">
                        <a class="page-link" href="{{ $merchants->nextPageUrl() }}" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>
@endsection
