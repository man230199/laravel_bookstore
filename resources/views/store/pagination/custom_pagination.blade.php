<div class="product-pagination">
    <div class="theme-paggination-block">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-xl-6 col-md-6 col-sm-12">
                    @if ($paginator->hasPages())
                        <nav aria-label="Page navigation">
                            <nav>
                                <ul class="pagination">
                                    @if ($paginator->onFirstPage())
                                        <li class="page-item disabled">
                                            <a href="" class="page-link"><i class="fa fa-angle-double-left"></i></a>
                                        </li>
                                        <li class="page-item disabled">
                                            <a href="" class="page-link"><i class="fa fa-angle-left"></i></a>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a href="{{ $paginator->url(1) }}" class="page-link"><i
                                                    class="fa fa-angle-double-left"></i></a>
                                        </li>
                                        <li class="page-item">
                                            <a href="{{ $paginator->previousPageUrl() }}" class="page-link"><i
                                                    class="fa fa-angle-left"></i></a>
                                        </li>
                                    @endif

                                    @foreach ($elements as $element)
                                        @if ($element != '...')
                                            @foreach ($element as $page => $url)
                                                @php
                                                    $active = $page == $paginator->currentPage() ? 'active' : '';
                                                    
                                                @endphp
                                                <li class="page-item {{ $active }}">
                                                    <a class="page-link"
                                                        href="{{ $url }}">{{ $page }}</a>
                                                </li>
                                            @endforeach
                                        @else
                                            <li class="page-item">
                                                <a class="page-link">...</a>
                                            </li>
                                        @endif
                                    @endforeach
                                    @if ($paginator->hasMorePages())
                                        @php
                                            $lastPage = $paginator->lastPage();
                                        @endphp
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $paginator->nextPageUrl() }}"><i
                                                    class="fa fa-angle-right"></i></a>
                                        </li>
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $paginator->url($lastPage) }}"><i
                                                    class="fa fa-angle-double-right"></i></a>
                                        </li>
                                    @else
                                        <li class="page-item disabled">
                                            <a class="page-link" href=""><i class="fa fa-angle-right"></i></a>
                                        </li>
                                        <li class="page-item disabled">
                                            <a class="page-link" href=""><i
                                                    class="fa fa-angle-double-right"></i></a>
                                        </li>
                                    @endif

                                </ul>
                            </nav>
                        </nav>
                    @endif
                </div>

            </div>
            <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="product-search-count-bottom">
                    <h5>Showing Items {{ $paginator->count() }} of {{ $paginator->total() }} Result</h5>
                </div>
            </div>
        </div>
    </div>
</div>
