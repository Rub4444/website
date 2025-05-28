@extends('auth.layouts.master')

@section('title', 'Свойства')

@section('content')
<div class="col-md-12">
   <!-- Add Property Button -->
   <div class="mt-4 mb-3">
        <a class="btn btn-success" href="{{ route('properties.create') }}">
            <i class="fas fa-plus text-white"></i> Ավելացնել հատկանիշ
        </a>
    </div>

    <!-- Сделаем контейнер с горизонтальным скроллом -->
    <div class="card shadow-lg">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead>
                        <tr>
                            <th style="min-width: 50px;">#</th>
                            <th>Անուն</th>
                            <th style="min-width: 280px;">Գործողություններ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($properties as $property)
                        <tr>
                            <td>{{ $property->id }}</td>
                            <td>{{ $property->name }}</td>
                            <td>
                                <div class="d-flex flex-wrap gap-2">
                                    <!-- View Button -->
                                    <a class="btn btn-success d-flex align-items-center gap-2 flex-grow-1 flex-md-grow-0" href="{{ route('properties.show', $property) }}" data-toggle="tooltip" title="Открыть свойство">
                                        <i class="fas fa-eye text-white"></i> Բացել
                                    </a>

                                    <!-- Edit Button -->
                                    <a class="btn btn-warning d-flex align-items-center gap-2 flex-grow-1 flex-md-grow-0" href="{{ route('properties.edit', $property) }}" data-toggle="tooltip" title="Редактировать свойство">
                                        <i class="fas fa-edit text-white"></i> Փոփոխել
                                    </a>

                                    <!-- Property Options Button -->
                                    <a class="btn btn-primary d-flex align-items-center gap-2 flex-grow-1 flex-md-grow-0" href="{{ route('property-options.index', $property) }}" data-toggle="tooltip" title="Значение свойства">
                                        <i class="fas fa-cogs text-white"></i> Նշանակությունը
                                    </a>

                                    <!-- Delete Button -->
                                    <form action="{{ route('properties.destroy', $property) }}" method="POST" onsubmit="return confirm('Վստա՞հ եք, որ ցանկանում եք հեռացնել այս հատկությունը։')" style="flex-grow: 1; min-width: 120px;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger d-flex align-items-center gap-2 w-10">
                                            <i class="fas fa-trash text-white"></i> Հեռացնել
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div> <!-- /.table-responsive -->
        </div>

        <div class="pagination__area bg__gray--color py-3">
            <nav class="pagination justify-content-center">
                <ul class="pagination__wrapper d-flex align-items-center justify-content-center list-unstyled mb-0 flex-wrap gap-1">
                    {{-- Кнопка "назад" --}}
                    @if ($properties->onFirstPage())
                        <li class="pagination__list disabled me-2">
                            <span class="pagination__item--arrow link" aria-disabled="true" aria-label="Previous">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22.51" height="20.443" viewBox="0 0 512 512">
                                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="48" d="M244 400L100 256l144-144M120 256h292"/>
                                </svg>
                            </span>
                        </li>
                    @else
                        <li class="pagination__list me-2">
                            <a href="{{ $properties->previousPageUrl() }}" class="pagination__item--arrow link" aria-label="Previous">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22.51" height="20.443" viewBox="0 0 512 512">
                                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="48" d="M244 400L100 256l144-144M120 256h292"/>
                                </svg>
                            </a>
                        </li>
                    @endif

                    {{-- Номера страниц --}}
                    @foreach ($properties->getUrlRange(1, $properties->lastPage()) as $page => $url)
                        @if ($page == $properties->currentPage())
                            <li class="pagination__list me-2"><span class="pagination__item pagination__item--current px-3 py-1 rounded bg-primary text-white">{{ $page }}</span></li>
                        @else
                            <li class="pagination__list me-2"><a href="{{ $url }}" class="pagination__item link px-3 py-1 rounded text-decoration-none text-dark">{{ $page }}</a></li>
                        @endif
                    @endforeach

                    {{-- Кнопка "вперёд" --}}
                    @if ($properties->hasMorePages())
                        <li class="pagination__list">
                            <a href="{{ $properties->nextPageUrl() }}" class="pagination__item--arrow link" aria-label="Next">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22.51" height="20.443" viewBox="0 0 512 512">
                                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="48" d="M268 112l144 144-144 144M392 256H100"/>
                                </svg>
                            </a>
                        </li>
                    @else
                        <li class="pagination__list disabled">
                            <span class="pagination__item--arrow link" aria-disabled="true" aria-label="Next">
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
