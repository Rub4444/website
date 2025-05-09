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

                                <form action="{{ route('categories.destroy', $category) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Հեռացնել</button>
                                </form>

                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="pagination__area bg__gray--color">
            <nav class="pagination justify-content-center">
                <ul class="pagination__wrapper d-flex align-items-center justify-content-center">
                    {{-- Кнопка "назад" --}}
                    @if ($categories->onFirstPage())
                        <li class="pagination__list disabled">
                            <span class="pagination__item--arrow link">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22.51" height="20.443" viewBox="0 0 512 512">
                                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="48" d="M244 400L100 256l144-144M120 256h292"/>
                                </svg>
                            </span>
                        </li>
                    @else
                        <li class="pagination__list">
                            <a href="{{ $categories->previousPageUrl() }}" class="pagination__item--arrow link">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22.51" height="20.443" viewBox="0 0 512 512">
                                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="48" d="M244 400L100 256l144-144M120 256h292"/>
                                </svg>
                            </a>
                        </li>
                    @endif

                    {{-- Номера страниц --}}
                    @foreach ($categories->getUrlRange(1, $categories->lastPage()) as $page => $url)
                        @if ($page == $categories->currentPage())
                            <li class="pagination__list"><span class="pagination__item pagination__item--current">{{ $page }}</span></li>
                        @else
                            <li class="pagination__list"><a href="{{ $url }}" class="pagination__item link">{{ $page }}</a></li>
                        @endif
                    @endforeach

                    {{-- Кнопка "вперёд" --}}
                    @if ($categories->hasMorePages())
                        <li class="pagination__list">
                            <a href="{{ $categories->nextPageUrl() }}" class="pagination__item--arrow link">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22.51" height="20.443" viewBox="0 0 512 512">
                                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="48" d="M268 112l144 144-144 144M392 256H100"/>
                                </svg>
                            </a>
                        </li>
                    @else
                        <li class="pagination__list disabled">
                            <span class="pagination__item--arrow link">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22.51" height="20.443" viewBox="0 0 512 512">
                                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="48" d="M268 112l144 144-144 144M392 256H100"/>
                                </svg>
                            </span>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>


    </div>
</div>

@endsection
