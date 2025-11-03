@if ($paginator->hasPages())
    <nav class="pagination-container" role="navigation" aria-label="Pagination Navigation">
        <ul class="pagination-list">
            @if ($paginator->onFirstPage())
                <li class="pagination-item disabled" aria-disabled="true">
                    <span class="pagination-link pagination-prev">
                        ‹ Назад
                    </span>
                </li>
            @else
                <li class="pagination-item">
                    <a class="pagination-link pagination-prev" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                        ‹ Назад
                    </a>
                </li>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="pagination-item disabled" aria-disabled="true">
                        <span class="pagination-link pagination-ellipsis">{{ $element }}</span>
                    </li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="pagination-item active" aria-current="page">
                                <span class="pagination-link pagination-number">{{ $page }}</span>
                            </li>
                        @else
                            <li class="pagination-item">
                                <a class="pagination-link pagination-number" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <li class="pagination-item">
                    <a class="pagination-link pagination-next" href="{{ $paginator->nextPageUrl() }}" rel="next">
                        Вперед ›
                    </a>
                </li>
            @else
                <li class="pagination-item disabled" aria-disabled="true">
                    <span class="pagination-link pagination-next">
                        Вперед ›
                    </span>
                </li>
            @endif
        </ul>
        
        <div class="pagination-info">
            <p>
                Показано с 
                <span class="pagination-info-highlight">{{ $paginator->firstItem() }}</span>
                по
                <span class="pagination-info-highlight">{{ $paginator->lastItem() }}</span>
                из
                <span class="pagination-info-highlight">{{ $paginator->total() }}</span>
                результатов
            </p>
        </div>
    </nav>
@endif