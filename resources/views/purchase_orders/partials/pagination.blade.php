<div class="d-flex align-items-center flex-wrap overflow-auto">
    <div class="mb-2 mb-sm-0">
        Showing <b>{{ $purchaseOrders->firstItem() ?? 0 }}</b> to <b>{{ $purchaseOrders->lastItem() ?? 0 }}</b> of <b>{{ $purchaseOrders->total() }}</b> entries 
        <small class="text-muted">(Page {{ $purchaseOrders->currentPage() }} of {{ $purchaseOrders->lastPage() }})</small>
        <i class="ri-arrow-right-line ms-2 fw-semibold"></i>
    </div>
    <div class="ms-auto">
        <ul class="pagination mb-0 overflow-auto">
            @if($purchaseOrders->hasPages())
                <!-- Previous Button -->
                <li class="page-item {{ $purchaseOrders->onFirstPage() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $purchaseOrders->previousPageUrl() }}" 
                       {{ $purchaseOrders->onFirstPage() ? 'onclick="return false;"' : '' }}>
                        <i class="ri-arrow-left-line me-1"></i>Previous
                    </a>
                </li>

                <!-- Page Numbers -->
                @php
                    $start = max(1, $purchaseOrders->currentPage() - 2);
                    $end = min($purchaseOrders->lastPage(), $purchaseOrders->currentPage() + 2);
                @endphp
                
                @for($page = $start; $page <= $end; $page++)
                    @if($page == $purchaseOrders->currentPage())
                        <li class="page-item active" aria-current="page">
                            <span class="page-link">{{ $page }}</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $purchaseOrders->url($page) }}">{{ $page }}</a>
                        </li>
                    @endif
                @endfor

                <!-- Next Button -->
                <li class="page-item {{ !$purchaseOrders->hasMorePages() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $purchaseOrders->nextPageUrl() }}" 
                       {{ !$purchaseOrders->hasMorePages() ? 'onclick="return false;"' : '' }}>
                        Next<i class="ri-arrow-right-line ms-1"></i>
                    </a>
                </li>
            @endif
        </ul>
    </div>
</div>

