@if ($paginator->hasPages())
<nav class="pagination-nav">
    <a class="page-btn btn-prev" href="#!"><i class="fal fa-long-arrow-left"></i></a>
    <ul class="pagination">
        {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
            
                <li class="page-item disabled"><a class="page-link" href="#"></a></li>
            @else
                <li class="page-item"><a class="page-link" href="{{ $paginator->previousPageUrl() }}"></a></li>
            @endif 


        {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled"><a class="page-link" href="#">{{ $element }}</a></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item"><a class="page-link active" href="#">{{ $page }}</a></li>
                        @else
                           <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item"><a class="page-link" href="{{ $paginator->nextPageUrl() }}"></a></li>
            @else
                <li class="page-item disabled"><a class="page-link" href="#"></a></li>
            @endif  


    </ul>
    <a class="page-btn btn-next" href="#!"><i class="fal fa-long-arrow-right"></i></a>
</nav>

@endif