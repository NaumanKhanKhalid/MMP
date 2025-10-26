<div class="d-flex align-items-center flex-wrap overflow-auto">
    <div class="mb-2 mb-sm-0">
        Showing <b>{{ $invoices->firstItem() ?? 0 }}</b> to <b>{{ $invoices->lastItem() ?? 0 }}</b> of <b>{{ $invoices->total() }}</b> entries 
        <small class="text-muted">(Page {{ $invoices->currentPage() }} of {{ $invoices->lastPage() }})</small>
        <i class="ri-arrow-right-line ms-2 fw-semibold"></i>
    </div>
    <div class="ms-auto">
        <ul class="pagination mb-0 overflow-auto">
            @if($invoices->hasPages())
                <!-- Previous Button -->
                <li class="page-item {{ $invoices->onFirstPage() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $invoices->previousPageUrl() }}" 
                       {{ $invoices->onFirstPage() ? 'onclick="return false;"' : '' }}>
                        <i class="ri-arrow-left-line me-1"></i>Previous
                    </a>
                </li>

                <!-- Page Numbers -->
                @php
                    $start = max(1, $invoices->currentPage() - 2);
                    $end = min($invoices->lastPage(), $invoices->currentPage() + 2);
                @endphp
                
                @for($page = $start; $page <= $end; $page++)
                    @if($page == $invoices->currentPage())
                        <li class="page-item active" aria-current="page">
                            <span class="page-link">{{ $page }}</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $invoices->url($page) }}">{{ $page }}</a>
                        </li>
                    @endif
                @endfor

                <!-- Next Button -->
                <li class="page-item {{ !$invoices->hasMorePages() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $invoices->nextPageUrl() }}" 
                       {{ !$invoices->hasMorePages() ? 'onclick="return false;"' : '' }}>
                        Next<i class="ri-arrow-right-line ms-1"></i>
                    </a>
                </li>
            @endif
        </ul>
    </div>
</div>

