@if ($paginator->hasPages())

    @if (request()->route()->getPrefix() == '/admin')

        <nav class="pagination is-small is-right">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <a class="pagination-previous" title="Esta es la primer pagina" disabled><i class="fa fa-chevron-left"></i></a>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="pagination-previous"><i class="fa fa-chevron-left"></i></a>
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="pagination-next"><i class="fa fa-chevron-right"></i></a>
            @else
                <a class="pagination-next" title="Esta es la ultima pagina" disabled><i class="fa fa-chevron-right"></i></a>
            @endif

            <ul class="pagination-list">
                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <li><span class="pagination-ellipsis">{{ $element }}</span></li>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li><a class="pagination-link is-current">{{ $page }}</a></li>
                            @else
                                <li><a href="{{ $url }}" class="pagination-link">{{ $page }}</a></li>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </ul>
        </nav>

    @else

        <ul class="pagination">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="disabled"><span>&laquo;</span></li>
            @else
                <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo;</a></li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="disabled"><span>{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="active"><span>{{ $page }}</span></li>
                        @else
                            <li><a href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li><a href="{{ $paginator->nextPageUrl() }}" rel="next">&raquo;</a></li>
            @else
                <li class="disabled"><span>&raquo;</span></li>
            @endif
        </ul>

    @endif

@endif
