@extends('auth.layouts.master')

@section('title', 'Свойства')

@section('content')
<div class="col-md-12">
   <!-- Add Property Button -->
   <div class="mt-4">
        <a class="btn btn-success" href="{{ route('properties.create') }}">
            <i class="fas fa-plus text-white"></i> Добавить свойство
        </a>
    </div>
    <!-- Card for the Table -->
    <div class="card shadow-lg">
        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Название</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($properties as $property)
                    <tr>
                        <td>{{ $property->id }}</td>
                        <td>{{ $property->name }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <!-- View Button -->
                                <a class="btn btn-success" href="{{ route('properties.show', $property) }}" data-toggle="tooltip" title="Открыть свойство">
                                    <i class="fas fa-eye text-white"></i> Открыть
                                </a>

                                <!-- Edit Button -->
                                <a class="btn btn-warning" href="{{ route('properties.edit', $property) }}" data-toggle="tooltip" title="Редактировать свойство">
                                    <i class="fas fa-edit text-white"></i> Редактировать
                                </a>

                                <!-- Property Options Button -->
                                <a class="btn btn-primary" href="{{ route('property-options.index', $property) }}" data-toggle="tooltip" title="Значение свойства">
                                    <i class="fas fa-cogs text-white"></i> Значение свойства
                                </a>

                                <!-- Delete Button - Open Modal -->
                                <button class="btn btn-danger" data-toggle="modal" data-target="#deleteModal{{ $property->id }}" data-toggle="tooltip" title="Удалить свойство">
                                    <i class="fas fa-trash-alt text-white"></i> Удалить
                                </button>
                            </div>

                            <!-- Modal for Deletion -->
                            <div class="modal fade" id="deleteModal{{ $property->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel{{ $property->id }}" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteModalLabel{{ $property->id }}">Удалить свойство</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Закрыть">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            Вы уверены, что хотите удалить свойство «{{ $property->name }}»?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                                            <form action="{{ route('properties.destroy', $property) }}" method="POST">
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

        <!-- Custom Pagination -->
        <div class="card-footer text-center">
            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                    <!-- Previous Button -->
                    <li class="page-item {{ $properties->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $properties->previousPageUrl() }}" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>

                    <!-- Page Numbers -->
                    @foreach ($properties->getUrlRange(1, $properties->lastPage()) as $page => $url)
                        <li class="page-item {{ $page == $properties->currentPage() ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach

                    <!-- Next Button -->
                    <li class="page-item {{ $properties->hasMorePages() ? '' : 'disabled' }}">
                        <a class="page-link" href="{{ $properties->nextPageUrl() }}" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>
@endsection
