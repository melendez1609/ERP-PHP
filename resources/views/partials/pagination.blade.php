@if ($paginator->hasPages())
    <nav class="custom-pagination">
        {{-- Botón Anterior --}}
        @if ($paginator->onFirstPage())
            <span class="page-link disabled">&laquo; Anterior</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="page-link" rel="prev">&laquo; Anterior</a>
        @endif

        {{-- Números de Páginas --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="page-link disabled">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="page-link active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="page-link">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Botón Siguiente --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="page-link" rel="next">Siguiente &raquo;</a>
        @else
            <span class="page-link disabled">Siguiente &raquo;</span>
        @endif
    </nav>
@endif