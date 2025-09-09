@extends('auth.layouts.master')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header text-white rounded-top-4" style="background-color:#2E8B57;">
                    <h4 class="mb-0">
                        <i class="bi bi-folder-fill me-2"></i>Կատեգորիա — {{ $category->name }}
                    </h4>
                </div>
                <div class="card-body">
                    <table class="table table-hover table-bordered align-middle">
                        <tbody>
                            <tr class="table-light">
                                <th style="width: 30%">Դաշտ</th>
                                <th>Նշանակություն</th>
                            </tr>
                            <tr>
                                <td>ID</td>
                                <td>{{ $category->id }}</td>
                            </tr>
                            <tr>
                                <td>Կոդ</td>
                                <td>{{ $category->code }}</td>
                            </tr>
                            <tr>
                                <td>Անուն</td>
                                <td>{{ $category->name }}</td>
                            </tr>
                            <tr>
                                <td>Անուն EN</td>
                                <td>{{ $category->name_en }}</td>
                            </tr>
                            <tr>
                                <td>Նկարագրություն</td>
                                <td>{{ $category->description }}</td>
                            </tr>
                            <tr>
                                <td>Նկարագրություն EN</td>
                                <td>{{ $category->description_en }}</td>
                            </tr>
                            <tr>
                                <td>Նկար</td>
                                <td>
                                    @if ($category->image)
                                        <img src="{{ asset('storage/' . $category->image) }}" class="img-fluid rounded shadow-sm" style="max-height: 240px;">
                                    @else
                                        <span class="text-muted">Չկա նկար</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Քանակ ապրանքների</td>
                                <td><span class="badge bg-success fs-6">{{ $category->products->count() }}</span></td>
                            </tr>
                        </tbody>
                    </table>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary mt-3">
                        <i class="bi bi-arrow-left"></i> Վերադառնալ
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
