@extends('auth.layouts.master')

@section('title', 'Հատկությունների տարբերակներ')

@section('content')
<div class="col-md-12">
    <!-- Add Property Option Button -->
    <div class="mt-4">
        <a class="btn btn-success" href="{{ route('property-options.create', $property) }}">
            <i class="fas fa-plus text-white"></i> Ստեղծել հատկության տարբերակ
        </a>
    </div>

    <!-- Property Options Table -->
    <div class="card shadow-lg mt-4">
        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Հատկություն</th>
                        <th>Անուն</th>
                        <th>Գործողություններ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($propertyOptions as $propertyOption)
                    <tr>
                        <td>{{ $propertyOption->id }}</td>
                        <td>{{ $property->name }}</td>
                        <td>{{ $propertyOption->name }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <!-- View Button -->
                                <a class="btn btn-success" href="{{ route('property-options.show', [$property, $propertyOption]) }}" data-toggle="tooltip" title="Բացել տարբերակը">
                                    <i class="fas fa-eye text-white"></i> Բացել
                                </a>

                                <!-- Edit Button -->
                                <a class="btn btn-warning" href="{{ route('property-options.edit', [$property, $propertyOption]) }}" data-toggle="tooltip" title="Խմբագրել տարբերակը">
                                    <i class="fas fa-edit text-white"></i> Խմբագրել
                                </a>

                                <!-- Delete Button -->
                                <form action="{{ route('property-options.destroy', [$property, $propertyOption]) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <input class="btn btn-danger" type="submit" value="Հեռացնել" data-toggle="tooltip" title="Հեռացնել տարբերակը">
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
                    <li class="page-item {{ $propertyOptions->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $propertyOptions->previousPageUrl() }}" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>

                    <!-- Page Numbers -->
                    @foreach ($propertyOptions->getUrlRange(1, $propertyOptions->lastPage()) as $page => $url)
                        <li class="page-item {{ $page == $propertyOptions->currentPage() ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach

                    <!-- Next Button -->
                    <li class="page-item {{ $propertyOptions->hasMorePages() ? '' : 'disabled' }}">
                        <a class="page-link" href="{{ $propertyOptions->nextPageUrl() }}" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>
@endsection
