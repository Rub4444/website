@extends('auth.layouts.master')

@section('title', 'Свойства')

@section('content')
<div class="col-md-12">
   <!-- Add Property Button -->
   <div class="mt-4">
        <a class="btn btn-success" href="{{ route('properties.create') }}">
            <i class="fas fa-plus text-white"></i> Ավելացնել հատկանիշ
        </a>
    </div>
    <!-- Card for the Table -->
    <div class="card shadow-lg">
        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Անուն</th>
                        <th>Գործողություններ</th>
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
                                    <i class="fas fa-eye text-white"></i> Բացել
                                </a>

                                <!-- Edit Button -->
                                <a class="btn btn-warning" href="{{ route('properties.edit', $property) }}" data-toggle="tooltip" title="Редактировать свойство">
                                    <i class="fas fa-edit text-white"></i> Փոփոխել
                                </a>

                                <!-- Property Options Button -->
                                <a class="btn btn-primary" href="{{ route('property-options.index', $property) }}" data-toggle="tooltip" title="Значение свойства">
                                    <i class="fas fa-cogs text-white"></i> Նշանակությունը
                                </a>

                                <form action="{{ route('properties.destroy', $property) }}" method="POST">
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
                    @if ($properties->onFirstPage())
                        <li class="pagination__list disabled">
                            <span class="pagination__item--arrow link">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22.51" height="20.443" viewBox="0 0 512 512">
                                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="48" d="M244 400L100 256l144-144M120 256h292"/>
                                </svg>
                            </span>
                        </li>
                    @else
                        <li class="pagination__list">
                            <a href="{{ $properties->previousPageUrl() }}" class="pagination__item--arrow link">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22.51" height="20.443" viewBox="0 0 512 512">
                                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="48" d="M244 400L100 256l144-144M120 256h292"/>
                                </svg>
                            </a>
                        </li>
                    @endif

                    {{-- Номера страниц --}}
                    @foreach ($properties->getUrlRange(1, $properties->lastPage()) as $page => $url)
                        @if ($page == $properties->currentPage())
                            <li class="pagination__list"><span class="pagination__item pagination__item--current">{{ $page }}</span></li>
                        @else
                            <li class="pagination__list"><a href="{{ $url }}" class="pagination__item link">{{ $page }}</a></li>
                        @endif
                    @endforeach

                    {{-- Кнопка "вперёд" --}}
                    @if ($properties->hasMorePages())
                        <li class="pagination__list">
                            <a href="{{ $properties->nextPageUrl() }}" class="pagination__item--arrow link">
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
