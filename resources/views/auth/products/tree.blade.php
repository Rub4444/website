@extends('auth.layouts.master')

@section('title', 'Ապրանքներ — Դերև')

@section('content')
<div class="col-md-12 mt-4">

    <h3 class="mb-4 fw-bold" style="color: var(--theme-color);">Ապրանքների կատեգորիաների ծառ</h3>

    @foreach($categories as $category)
    <div class="card mb-3 shadow-sm rounded-4">
        <div class="card-header d-flex align-items-center justify-content-between"
             style="cursor: pointer; background-color: var(--theme-color4);"
             data-bs-toggle="collapse"
             href="#category-{{ $category->id }}"
             role="button"
             aria-expanded="false"
             aria-controls="category-{{ $category->id }}">
            <h5 class="mb-0" style="color: var(--theme-color5);">
                <i class="fas fa-chevron-right me-2 rotate-icon"></i>
                {{ $category->name }}
            </h5>
            <span class="badge rounded-pill" style="background-color: var(--secondary-color); color: white;">
                {{ $category->products->count() }}
            </span>
        </div>

        <div class="collapse" id="category-{{ $category->id }}">
            <div class="card-body p-0">
                @if($category->products->isEmpty())
                    <p class="text-muted fst-italic px-3 py-2 mb-0">Ապրանքներ չկան։</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead style="background-color: var(--theme-color4);">
                                <tr>
                                    <th style="width: 5%; color: var(--theme-color5);">#</th>
                                    <th style="width: 20%; color: var(--theme-color5);">Կոդ</th>
                                    <th style="color: var(--theme-color5);">Անուն</th>
                                    <th class="text-end" style="width: 20%; color: var(--theme-color5);">Գործողություններ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($category->products as $product)
                                <tr class="align-middle">
                                    <td style="color: var(--theme-color5);">{{ $product->id }}</td>
                                    <td><code style="color: var(--secondary-color2);">{{ $product->code }}</code></td>
                                    <td style="color: var(--theme-color5);">{{ $product->name }}</td>
                                    <td class="text-end">
                                        <div class="btn-group btn-group-sm" role="group" aria-label="Actions">
                                            <a href="{{ route('products.show', $product) }}" class="btn btn-outline-success" title="Բացել" style="border-color: var(--theme-color2); color: var(--theme-color2);">
                                                <i class="fas fa-eye text-white"></i>
                                            </a>
                                            <a href="{{ route('skus.index', $product) }}" class="btn btn-outline-info" title="ՍԿՈՒՍ" style="border-color: var(--theme-color3); color: var(--theme-color3);">
                                                <i class="fas fa-box text-white"></i>
                                            </a>
                                            <a href="{{ route('products.edit', $product) }}" class="btn btn-outline-warning" title="Փոփոխել" style="border-color: var(--secondary-color); color: var(--secondary-color);">
                                                <i class="fas fa-edit text-white"></i>
                                            </a>
                                            <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Վստա՞հ եք, որ ցանկանում եք հեռացնել այս ապրանքը։')" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="Հեռացնել" style="border-color: #dc3545; color: #dc3545;">
                                                    <i class="fas fa-trash text-white"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @endforeach

</div>

<style>
    .rotate-icon {
        transition: transform 0.3s ease;
        color: var(--theme-color2);
        font-size: 1.2rem;
    }
    a[aria-expanded="true"] .rotate-icon {
        transform: rotate(90deg);
    }

    table.table-hover tbody tr:hover {
        background-color: var(--theme-color4);
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .btn-outline-success:hover {
        background-color: var(--theme-color2);
        color: #fff;
        border-color: var(--theme-color2);
    }
    .btn-outline-info:hover {
        background-color: var(--theme-color3);
        color: #fff;
        border-color: var(--theme-color3);
    }
    .btn-outline-warning:hover {
        background-color: var(--secondary-color);
        color: #212529;
        border-color: var(--secondary-color);
    }
    .btn-outline-danger:hover {
        background-color: #dc3545;
        color: #fff;
        border-color: #dc3545;
    }

    /* Адаптивность */
    @media (max-width: 576px) {
        .card-header h5 {
            font-size: 1rem;
        }
        .btn-group .btn {
            width: 30px;
            height: 30px;
        }
        table.table thead tr th, table.table tbody tr td {
            font-size: 0.85rem;
            padding: 0.3rem 0.5rem;
        }
    }
</style>

<script>
    document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(el => {
        el.addEventListener('click', () => {
            const icon = el.querySelector('.rotate-icon');
            if (!icon) return;

            if (el.getAttribute('aria-expanded') === 'true') {
                icon.style.transform = 'rotate(0deg)';
                el.setAttribute('aria-expanded', 'false');
            } else {
                icon.style.transform = 'rotate(90deg)';
                el.setAttribute('aria-expanded', 'true');
            }
        });
    });
</script>
@endsection
