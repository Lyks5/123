<div class="flex justify-center">
    <div class="border flex">
        {{-- Первая страница --}}
        @if ($paginator->onFirstPage())
            <span class="p-2 w-12 h-12 text-gray-500 bg-gray-100 cursor-not-allowed flex items-center justify-center">
                <<
            </span>
        @else
            <a href="{{ $paginator->url(1) }}" class="p-2 w-12 h-12 text-gray-800 bg-white hover:border border-gray-800 hover:bg-gray-800 hover:text-white flex items-center justify-center">
                <<
            </a>
        @endif

        {{-- Предыдущая страница --}}
        @if ($paginator->onFirstPage())
            <span class="p-2 w-12 h-12 text-gray-500 bg-gray-100 cursor-not-allowed flex items-center justify-center border-l">
                «
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="p-2 w-12 h-12 text-gray-800 bg-white hover:border border-gray-800 hover:bg-gray-800 hover:text-white flex items-center justify-center border-l">
                «
            </a>
        @endif

        {{-- Номера страниц --}}
        @php
            $start = max(1, $paginator->currentPage() - 2);
            $end = min($paginator->lastPage(), $paginator->currentPage() + 2);
        @endphp

        @for ($page = $start; $page <= $end; $page++)
            @if ($page == $paginator->currentPage())
                <span class="p-2 w-12 h-12 text-white bg-gray-800 flex items-center justify-center border-l">
                    {{ $page }}
                </span>
            @else
                <a href="{{ $paginator->url($page) }}" class="p-2 w-12 h-12 text-gray-800 bg-white hover:border border-gray-800 hover:bg-gray-800 hover:text-white flex items-center justify-center border-l">
                    {{ $page }}
                </a>
            @endif
        @endfor

        {{-- Следующая страница --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="p-2 w-12 h-12 text-gray-800 bg-white hover:border border-gray-800 hover:bg-gray-800 hover:text-white flex items-center justify-center border-l">
                »
            </a>
        @else
            <span class="p-2 w-12 h-12 text-gray-500 bg-gray-100 cursor-not-allowed flex items-center justify-center border-l">
                »
            </span>
        @endif

        {{-- Последняя страница --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->url($paginator->lastPage()) }}" class="p-2 w-12 h-12 text-gray-800 bg-white hover:border border-gray-800 hover:bg-gray-800 hover:text-white flex items-center justify-center border-l">
                >>
            </a>
        @else
            <span class="p-2 w-12 h-12 text-gray-500 bg-gray-100 cursor-not-allowed flex items-center justify-center border-l">
                >>
            </span>
        @endif
    </div>
</div>