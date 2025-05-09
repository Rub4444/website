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
        <p class="alert alert-success">{{ session()->get('success') }}</p>
    @endif

    <!-- Suppliers Table -->
    <div class="card shadow-lg">
        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead>
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
                                <a class="btn btn-success" href="{{ route('merchants.show', $merchant) }}" data-toggle="tooltip" title="Բացել">
                                    <i class="fas fa-eye text-white"></i> Բացել
                                </a>

                                <!-- Edit Button -->
                                <a class="btn btn-warning" href="{{ route('merchants.edit', $merchant) }}" data-toggle="tooltip" title="Փոփոխել">
                                    <i class="fas fa-edit text-white"></i> Փոփոխել
                                </a>

                                <!-- Update Token Button -->
                                <a class="btn btn-primary" href="{{ route('merchants.update_token', $merchant) }}" data-toggle="tooltip" title="Թարմացնել տոկենը">
                                    <i class="fas fa-sync-alt text-white"></i> Թարմացնել տոկենը
                                </a>
                                <form action="{{ route('merchants.destroy', $merchant) }}" method="POST" style="display:inline;">
                                    <!-- Delete Button -->
                                    @csrf
                                    @method('DELETE')
                                    <input class="btn btn-danger" type="submit" value="Հեռացնել" data-toggle="tooltip" title="Հեռացնել">
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
                    @if ($merchants->onFirstPage())
                        <li class="pagination__list disabled">
                            <span class="pagination__item--arrow link">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22.51" height="20.443" viewBox="0 0 512 512">
                                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="48"
                                          d="M244 400L100 256l144-144M120 256h292"/>
                                </svg>
                            </span>
                        </li>
                    @else
                        <li class="pagination__list">
                            <a href="{{ $merchants->previousPageUrl() }}" class="pagination__item--arrow link">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22.51" height="20.443" viewBox="0 0 512 512">
                                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="48"
                                          d="M244 400L100 256l144-144M120 256h292"/>
                                </svg>
                            </a>
                        </li>
                    @endif

                    {{-- Номера страниц --}}
                    @foreach ($merchants->getUrlRange(1, $merchants->lastPage()) as $page => $url)
                        @if ($page == $merchants->currentPage())
                            <li class="pagination__list"><span class="pagination__item pagination__item--current">{{ $page }}</span></li>
                        @else
                            <li class="pagination__list"><a href="{{ $url }}" class="pagination__item link">{{ $page }}</a></li>
                        @endif
                    @endforeach

                    {{-- Кнопка "вперёд" --}}
                    @if ($merchants->hasMorePages())
                        <li class="pagination__list">
                            <a href="{{ $merchants->nextPageUrl() }}" class="pagination__item--arrow link">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22.51" height="20.443" viewBox="0 0 512 512">
                                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="48"
                                          d="M268 112l144 144-144 144M392 256H100"/>
                                </svg>
                            </a>
                        </li>
                    @else
                        <li class="pagination__list disabled">
                            <span class="pagination__item--arrow link">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22.51" height="20.443" viewBox="0 0 512 512">
                                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="48"
                                          d="M268 112l144 144-144 144M392 256H100"/>
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
