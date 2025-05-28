@extends('auth.layouts.master')

@section('title', 'Купоны')

@section('content')
<div class="container-fluid mt-4">
    <!-- Add Coupon Button -->
    <div class="d-flex justify-content-end mb-3">
        <a class="btn btn-success d-flex align-items-center" href="{{ route('coupons.create') }}">
            <i class="fas fa-plus text-white me-2"></i> Ավելացնել կուպոն
        </a>
    </div>

    <!-- Coupons Table -->
    <div class="card shadow rounded-4">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Կոդ</th>
                            <th>Նկարագրություն</th>
                            <th>Գործողություններ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($coupons as $coupon)
                        <tr>
                            <td>{{ $coupon->id }}</td>
                            <td>{{ $coupon->code }}</td>
                            <td>{{ $coupon->description }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <!-- Open Button -->
                                    <a class="btn btn-success btn-sm me-1 d-flex align-items-center" href="{{ route('coupons.show', $coupon) }}" title="Բացել">
                                        <i class="fas fa-eye text-white me-1"></i> Բացել
                                    </a>

                                    <!-- Edit Button -->
                                    <a class="btn btn-warning btn-sm me-1 d-flex align-items-center" href="{{ route('coupons.edit', $coupon) }}" title="Փոփոխել">
                                        <i class="fas fa-edit text-white me-1"></i> Փոփոխել
                                    </a>

                                    <!-- Delete Button -->
                                    <form action="{{ route('coupons.destroy', $coupon) }}" method="POST" onsubmit="return confirm('Համոզված ե՞ք, որ ցանկանում եք հեռացնել այս կուպոնը:');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm d-flex align-items-center" title="Հեռացնել">
                                            <i class="fas fa-trash text-white me-1"></i> Հեռացնել
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
           <!-- Custom Pagination -->
@if ($coupons->hasPages())
<div class="d-flex justify-content-center mt-4">
    <nav>
        <ul class="pagination pagination-lg">
            {{-- Previous Page Link --}}
            @if ($coupons->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link bg-light border-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-chevron-left">
                            <path fill-rule="evenodd" d="M15.354 1.646a.5.5 0 0 1 0 .708L8.707 9l6.647 6.646a.5.5 0 0 1-.708.708l-7-7a.5.5 0 0 1 0-.708l7-7a.5.5 0 0 1 .708 0z"/>
                        </svg>
                    </span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link bg-white border-0" href="{{ $coupons->previousPageUrl() }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-chevron-left">
                            <path fill-rule="evenodd" d="M15.354 1.646a.5.5 0 0 1 0 .708L8.707 9l6.647 6.646a.5.5 0 0 1-.708.708l-7-7a.5.5 0 0 1 0-.708l7-7a.5.5 0 0 1 .708 0z"/>
                        </svg>
                    </a>
                </li>
            @endif

            {{-- Page Numbers --}}
            @foreach ($coupons->getUrlRange(1, $coupons->lastPage()) as $page => $url)
                @if ($page == $coupons->currentPage())
                    <li class="page-item active">
                        <span class="page-link border-0">{{ $page }}</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link bg-white border-0" href="{{ $url }}">{{ $page }}</a>
                    </li>
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($coupons->hasMorePages())
                <li class="page-item">
                    <a class="page-link bg-white border-0" href="{{ $coupons->nextPageUrl() }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-chevron-right">
                            <path fill-rule="evenodd" d="M0.646 1.646a.5.5 0 0 1 .708 0l7 7a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708-.708L7.293 9 0.646 2.354a.5.5 0 0 1 0-.708z"/>
                        </svg>
                    </a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link bg-light border-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-chevron-right">
                            <path fill-rule="evenodd" d="M0.646 1.646a.5.5 0 0 1 .708 0l7 7a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708-.708L7.293 9 0.646 2.354a.5.5 0 0 1 0-.708z"/>
                        </svg>
                    </span>
                </li>
            @endif
        </ul>
    </nav>
</div>
@endif

        </div>
    </div>
</div>
@endsection
