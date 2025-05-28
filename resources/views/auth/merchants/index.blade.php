@extends('auth.layouts.master')

@section('title', 'Поставщики')

@section('content')
<div class="col-md-12">
    <!-- Add Supplier Button -->
    <div class="mt-4">
        <a class="btn btn-success" href="{{ route('merchants.create') }}">
            <i class="fas fa-plus text-white"></i> Ավելացնել Մատակարար
        </a>
    </div>

    <!-- Success Message -->
    @if(session()->has('success'))
        <p class="alert alert-success mt-3">{{ session()->get('success') }}</p>
    @endif

    <!-- Suppliers Table -->
    <div class="card shadow-lg mt-4">
        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead class="table-dark text-white">
                    <tr>
                        <th>#</th>
                        <th>Անուն</th>
                        <th>Էլ-հասցե</th>
                        <th>Գործողություններ</th>
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
                                <a class="btn btn-success text-white" href="{{ route('merchants.show', $merchant) }}" data-bs-toggle="tooltip" title="Բացել">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <!-- Edit Button -->
                                <a class="btn btn-warning text-white" href="{{ route('merchants.edit', $merchant) }}" data-bs-toggle="tooltip" title="Փոփոխել">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <!-- Update Token Button -->
                                <a class="btn btn-primary text-white" href="{{ route('merchants.update_token', $merchant) }}" data-bs-toggle="tooltip" title="Թարմացնել տոկենը">
                                    <i class="fas fa-sync-alt"></i>
                                </a>

                                <!-- Delete Button -->
                                <form action="{{ route('merchants.destroy', $merchant) }}" method="POST" onsubmit="return confirm('Համոզվա՞ծ եք, որ ցանկանում եք հեռացնել մատակարարը։');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger text-white" data-bs-toggle="tooltip" title="Հեռացնել">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Stylish Pagination -->
        <div class="d-flex justify-content-center mt-4 mb-3">
            <nav>
                <ul class="pagination pagination-lg flex-wrap gap-2">
                    {{-- Previous --}}
                    @if ($merchants->onFirstPage())
                        <li class="page-item disabled">
                            <span class="page-link bg-dark text-white border-0 rounded-pill px-3">
                                <i class="fas fa-angle-left"></i>
                            </span>
                        </li>
                    @else
                        <li class="page-item">
                            <a href="{{ $merchants->previousPageUrl() }}" class="page-link bg-dark text-white border-0 rounded-pill px-3">
                                <i class="fas fa-angle-left"></i>
                            </a>
                        </li>
                    @endif

                    {{-- Page Numbers --}}
                    @foreach ($merchants->getUrlRange(1, $merchants->lastPage()) as $page => $url)
                        @if ($page == $merchants->currentPage())
                            <li class="page-item active">
                                <span class="page-link bg-primary text-white border-0 rounded-pill px-3">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a href="{{ $url }}" class="page-link bg-dark text-white border-0 rounded-pill px-3">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach

                    {{-- Next --}}
                    @if ($merchants->hasMorePages())
                        <li class="page-item">
                            <a href="{{ $merchants->nextPageUrl() }}" class="page-link bg-dark text-white border-0 rounded-pill px-3">
                                <i class="fas fa-angle-right"></i>
                            </a>
                        </li>
                    @else
                        <li class="page-item disabled">
                            <span class="page-link bg-dark text-white border-0 rounded-pill px-3">
                                <i class="fas fa-angle-right"></i>
                            </span>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>
    </div>
</div>
@endsection
