@if ($paginator->hasPages())
    <ul class="pagination pagination my-3 my-md-4">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="disabled d-none"><a class="btn bg-white shadow-sm btn-hover mx-1"><i class="fas fa-chevron-left"></i></a></li>
        @else
            <li><a class="btn bg-white shadow-sm btn-hover mx-1" href="{{ $paginator->previousPageUrl() }}" rel="prev"><i class="fas fa-chevron-left"></i></a></li>
        @endif

        @if($paginator->currentPage() > 3)
            <li class="hidden-xs"><a  class="btn bg-white shadow-sm btn-hover" href="{{ $paginator->url(1) }}">1</a></li>
        @endif
        @if($paginator->currentPage() > 4)
            <li><span class="font-120 mx-2">...</li>
        @endif
        @foreach(range(1, $paginator->lastPage()) as $i)
            @if($i >= $paginator->currentPage() - 1 && $i <= $paginator->currentPage() + 2)
                @if ($i == $paginator->currentPage())
                    <li><a class="btn bg-white shadow-sm btn-hover mx-1 active">{{ $i }}</a></li>
                @else
                    <li><a href="{{ $paginator->url($i) }}" class="btn bg-white shadow-sm btn-hover mx-1">{{ $i }}</a></li>
                @endif
            @endif
        @endforeach
        @if($paginator->currentPage() < $paginator->lastPage() - 3)
            <li><span class="fw-bold font-120 mx-2">...</li>
        @endif
        @if($paginator->currentPage() < $paginator->lastPage() - 2)
            <li class="hidden-xs"><a class="btn bg-white shadow-sm btn-hover mx-1" href="{{ $paginator->url($paginator->lastPage()) }}">{{ $paginator->lastPage() }}</a></li>
        @endif

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li><a class="btn bg-white shadow-sm btn-hover mx-1" href="{{ $paginator->nextPageUrl() }}" rel="next"><i class="fas fa-chevron-right"></i></a></li>
        @else
        <li class="disabled d-none"><a class="btn bg-white shadow-sm btn-hover mx-1"><i class="fas fa-chevron-right"></i></a></li>
        @endif
    </ul>
@endif