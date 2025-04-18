@extends('auth.layouts.master')

@section('content')
<div class="col-md-12">
    {{-- <h1 class="mb-4 text-center">Կատեգորիաներ</h1> --}}
    <!-- Add Category Button -->
    <div class="mt-4 ">
        <a class="btn btn-success" type="button" href="{{route('categories.create')}}">
            <i class="fas fa-plus text-white"></i> Ավելացնել կատեգորիա
        </a>
    </div>
    <!-- Card for the Table -->
    <div class="card shadow-lg">
        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Կոդ</th>
                        <th>Անվանում</th>
                        <th>Գործողություններ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                    <tr>
                        <td>{{$category->id}}</td>
                        <td>{{$category->code}}</td>
                        <td>{{$category->name}}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <!-- View Button -->
                                <a class="btn btn-success" type="button" href="{{ route('categories.show', $category) }}" data-toggle="tooltip" title="Открыть категорию">
                                    <i class="fas fa-eye text-white"></i> Բացել
                                </a>

                                <!-- Edit Button -->
                                <a class="btn btn-warning" type="button" href="{{ route('categories.edit', $category) }}" data-toggle="tooltip" title="Редактировать категорию">
                                    <i class="fas fa-edit text-white"></i> Խմբագրել
                                </a>

                                <!-- Delete Button - Open Modal -->
                                <button class="btn btn-danger" type="button" data-toggle="modal" data-target="#deleteModal{{$category->id}}" data-toggle="tooltip" title="Удалить категорию">
                                    <i class="fas fa-trash-alt text-white"></i> Ջնջել
                                </button>
                            </div>

                            <!-- Modal for Deletion -->
                            <div class="modal fade" id="deleteModal{{$category->id}}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel{{$category->id}}" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteModalLabel{{$category->id}}">Ջնջել կատեգորիան</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Закрыть">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            Համոզված եք, որ ցանկանում եք հեռացնել կատեգորիան «{{$category->name}}»?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Չեղարկել</button>
                                            <form action="{{route('categories.destroy', $category)}}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Ջնջել</button>
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
                    <li class="page-item {{ $categories->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $categories->previousPageUrl() }}" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>

                    <!-- Page Numbers -->
                    @foreach ($categories->getUrlRange(1, $categories->lastPage()) as $page => $url)
                        <li class="page-item {{ $page == $categories->currentPage() ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach

                    <!-- Next Button -->
                    <li class="page-item {{ $categories->hasMorePages() ? '' : 'disabled' }}">
                        <a class="page-link" href="{{ $categories->nextPageUrl() }}" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

    </div>
</div>

@endsection
