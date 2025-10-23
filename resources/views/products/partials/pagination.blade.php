<div class="d-flex align-items-center flex-wrap overflow-auto">
    <div class="mb-2 mb-sm-0">
        Showing <b>{{ $products->firstItem() ?? 0 }}</b> to <b>{{ $products->lastItem() ?? 0 }}</b> of <b>{{ $products->total() }}</b> entries 
        <small class="text-muted">(Page {{ $products->currentPage() }} of {{ $products->lastPage() }})</small>
        <i class="ri-arrow-right-line ms-2 fw-semibold"></i>
    </div>
    <div class="ms-auto">
        <ul class="pagination mb-0 overflow-auto">
            @if($products->hasPages())
                <!-- Previous Button -->
                <li class="page-item {{ $products->onFirstPage() ? 'disabled' : '' }}">
                    <a class="page-link" href="javascript:void(0)" 
                       data-page="{{ $products->currentPage() - 1 }}"
                       onclick="if(!$(this).parent().hasClass('disabled')) { loadProductsPage({{ $products->currentPage() - 1 }}); }">
                        <i class="ri-arrow-left-line me-1"></i>Previous
                    </a>
                </li>

                <!-- Page Numbers -->
                @php
                    $start = max(1, $products->currentPage() - 2);
                    $end = min($products->lastPage(), $products->currentPage() + 2);
                @endphp
                
                @for($page = $start; $page <= $end; $page++)
                    @if($page == $products->currentPage())
                        <li class="page-item active" aria-current="page">
                            <span class="page-link">{{ $page }}</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="javascript:void(0)" 
                               data-page="{{ $page }}"
                               onclick="loadProductsPage({{ $page }})">{{ $page }}</a>
                        </li>
                    @endif
                @endfor

                <!-- Next Button -->
                <li class="page-item {{ !$products->hasMorePages() ? 'disabled' : '' }}">
                    <a class="page-link" href="javascript:void(0)" 
                       data-page="{{ $products->currentPage() + 1 }}"
                       onclick="if(!$(this).parent().hasClass('disabled')) { loadProductsPage({{ $products->currentPage() + 1 }}); }">
                        Next<i class="ri-arrow-right-line ms-1"></i>
                    </a>
                </li>
            @endif
        </ul>
    </div>
</div>
