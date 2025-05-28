@extends('auth.layouts.master')

@section('title', 'Հատկությունների տարբերակներ')

@section('content')
<div class="container my-4">
    <!-- Добавить кнопку создания -->
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('property-options.create', $property) }}" class="btn btn-success">
            <i class="fas fa-plus text-white"></i> Ստեղծել հատկության տարբերակ
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-3 p-md-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Հատկություն</th>
                            <th>Անուն</th>
                            <th class="text-center">Գործողություններ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($propertyOptions as $propertyOption)
                            <tr>
                                <td>{{ $propertyOption->id }}</td>
                                <td>{{ $property->name }}</td>
                                <td>{{ $propertyOption->name }}</td>
                                <td class="text-center">
                                    <div class="btn-group" role="group" aria-label="Actions">
                                        <a href="{{ route('property-options.show', [$property, $propertyOption]) }}"
                                           class="btn btn-sm btn-success" title="Բացել տարբերակը">
                                            <i class="fas fa-eye text-white"></i>
                                        </a>
                                        <a href="{{ route('property-options.edit', [$property, $propertyOption]) }}"
                                           class="btn btn-sm btn-warning" title="Խմբագրել տարբերակը">
                                            <i class="fas fa-edit text-white"></i>
                                        </a>
                                        <form action="{{ route('property-options.destroy', [$property, $propertyOption]) }}" method="POST" class="d-inline" onsubmit="return confirm('Հաստատում եք հեռացումը?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Հեռացնել տարբերակը">
                                                <i class="fas fa-trash-alt text-white"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Пагинация -->
            <nav class="mt-3 d-flex justify-content-center" aria-label="Page navigation">
                {{ $propertyOptions->links('pagination::bootstrap-5') }}
            </nav>
        </div>
    </div>
</div>
@endsection
