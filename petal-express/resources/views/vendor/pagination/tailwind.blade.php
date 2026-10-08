@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination" class="flex flex-wrap items-center justify-center gap-2">
        @if ($paginator->onFirstPage())
            <span class="inline-flex h-10 items-center rounded-full border border-gray-200 px-4 text-sm text-gray-400">Previous</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex h-10 items-center rounded-full border border-gray-200 bg-white px-4 text-sm font-medium text-gray-700 hover:text-accent">Previous</a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="px-1 text-sm text-gray-500">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="inline-flex h-10 min-w-10 items-center justify-center rounded-full bg-accent px-3 text-sm font-semibold text-white" aria-current="page">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="inline-flex h-10 min-w-10 items-center justify-center rounded-full border border-gray-200 bg-white px-3 text-sm text-gray-700 hover:text-accent">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex h-10 items-center rounded-full border border-gray-200 bg-white px-4 text-sm font-medium text-gray-700 hover:text-accent">Next</a>
        @else
            <span class="inline-flex h-10 items-center rounded-full border border-gray-200 px-4 text-sm text-gray-400">Next</span>
        @endif
    </nav>
@endif
