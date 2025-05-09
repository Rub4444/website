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

        <div class="pagination__area bg__gray--color">
            <nav class="pagination justify-content-center">
                <ul class="pagination__wrapper d-flex align-items-center justify-content-center">

                    {{-- Кнопка "назад" --}}
                    @if ($propertyOptions->onFirstPage())
                        <li class="pagination__list disabled">
                            <span class="pagination__item--arrow link">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22.51" height="20.443" viewBox="0 0 512 512">
                                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="48"
                                          d="M244 400L100 256l144-144M120 256h292" />
                                </svg>
                            </span>
                        </li>
                    @else
                        <li class="pagination__list">
                            <a href="{{ $propertyOptions->previousPageUrl() }}" class="pagination__item--arrow link">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22.51" height="20.443" viewBox="0 0 512 512">
                                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="48"
                                          d="M244 400L100 256l144-144M120 256h292" />
                                </svg>
                            </a>
                        </li>
                    @endif

                    {{-- Номера страниц --}}
                    @foreach ($propertyOptions->getUrlRange(1, $propertyOptions->lastPage()) as $page => $url)
                        @if ($page == $propertyOptions->currentPage())
                            <li class="pagination__list">
                                <span class="pagination__item pagination__item--current">{{ $page }}</span>
                            </li>
                        @else
                            <li class="pagination__list">
                                <a href="{{ $url }}" class="pagination__item link">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach

                    {{-- Кнопка "вперёд" --}}
                    @if ($propertyOptions->hasMorePages())
                        <li class="pagination__list">
                            <a href="{{ $propertyOptions->nextPageUrl() }}" class="pagination__item--arrow link">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22.51" height="20.443" viewBox="0 0 512 512">
                                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="48"
                                          d="M268 112l144 144-144 144M392 256H100" />
                                </svg>
                            </a>
                        </li>
                    @else
                        <li class="pagination__list disabled">
                            <span class="pagination__item--arrow link">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22.51" height="20.443" viewBox="0 0 512 512">
                                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="48"
                                          d="M268 112l144 144-144 144M392 256H100" />
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
