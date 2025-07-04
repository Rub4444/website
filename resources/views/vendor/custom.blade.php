@if ($paginator->hasPages())
    <div class="pagination__area bg__gray--color my-4">
        <nav class="pagination justify-content-center">
            <ul class="pagination__wrapper d-flex align-items-center justify-content-center">

                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <li class="pagination__list disabled">
                        <span class="pagination__item--arrow link">
                            <!-- SVG left -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="20" viewBox="0 0 512 512">
                                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="48"
                                      d="M244 400L100 256l144-144M120 256h292"/>
                            </svg>
                        </span>
                    </li>
                @else
                    <li class="pagination__list">
                        <a href="{{ $paginator->previousPageUrl() }}" class="pagination__item--arrow link">
                            <!-- SVG left -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="20" viewBox="0 0 512 512">
                                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="48"
                                      d="M244 400L100 256l144-144M120 256h292"/>
                            </svg>
                        </a>
                    </li>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <li class="pagination__list disabled"><span class="pagination__item">{{ $element }}</span></li>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li class="pagination__list">
                                    <span class="pagination__item pagination__item--current">{{ $page }}</span>
                                </li>
                            @else
                                <li class="pagination__list">
                                    <a href="{{ $url }}" class="pagination__item link">{{ $page }}</a>
                                </li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <li class="pagination__list">
                        <a href="{{ $paginator->nextPageUrl() }}" class="pagination__item--arrow link">
                            <!-- SVG right -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="20" viewBox="0 0 512 512">
                                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="48"
                                      d="M268 112l144 144-144 144M392 256H100"/>
                            </svg>
                        </a>
                    </li>
                @else
                    <li class="pagination__list disabled">
                        <span class="pagination__item--arrow link">
                            <!-- SVG right -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="20" viewBox="0 0 512 512">
                                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="48"
                                      d="M268 112l144 144-144 144M392 256H100"/>
                            </svg>
                        </span>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
@endif
<style>
    .pagination__area {
        padding: 1rem 0;
        background-color: #f9f9f9;
        border-radius: 12px;
    }

    .pagination__wrapper {
        list-style: none;
        padding: 0;
        margin: 0;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .pagination__list {
        display: flex;
        align-items: center;
    }

    .pagination__item,
    .pagination__item--arrow {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        font-weight: 500;
        border-radius: 8px;
        background-color: #fff;
        border: 1px solid #dee2e6;
        color: #333;
        text-decoration: none;
        transition: all 0.2s ease-in-out;
    }

    .pagination__item:hover,
    .pagination__item--arrow:hover {
        background-color: #e9ecef;
        color: #000;
    }

    .pagination__item--current {
        background-color: #198754;
        color: white;
        border-color: #198754;
    }

    .disabled .pagination__item--arrow,
    .disabled .pagination__item {
        pointer-events: none;
        opacity: 0.4;
    }
    .pagination__wrapper {
        flex-wrap: wrap;
        max-width: 100%;
    }
</style>
