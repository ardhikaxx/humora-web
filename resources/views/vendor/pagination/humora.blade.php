@if ($paginator->hasPages())
    <nav class="admin-pagination" aria-label="Navigasi halaman">
        <ul class="pagination admin-pagination__list mb-0">
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link admin-pagination__ellipsis" aria-hidden="true">&hellip;</span>
                    </li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page">
                                <span class="page-link">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $url }}" aria-label="Halaman {{ $page }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </ul>
    </nav>
@endif
