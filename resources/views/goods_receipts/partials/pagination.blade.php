<div class="d-flex align-items-center flex-wrap overflow-auto">
    <div class="mb-2 mb-sm-0">
        Showing <b>{{ $grns->firstItem() ?? 0 }}</b> to <b>{{ $grns->lastItem() ?? 0 }}</b> of <b>{{ $grns->total() }}</b> entries 
        <small class="text-muted">(Page {{ $grns->currentPage() }} of {{ $grns->lastPage() }})</small>
        <i class="ri-arrow-right-line ms-2 fw-semibold"></i>
    </div>
    <div class="ms-auto">
        <ul class="pagination mb-0 overflow-auto">
            @if($grns->hasPages())
                <!-- Previous Button -->
                <li class="page-item {{ $grns->onFirstPage() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $grns->previousPageUrl() }}" 
                       {{ $grns->onFirstPage() ? 'onclick="return false;"' : '' }}>
                        <i class="ri-arrow-left-line me-1"></i>Previous
                    </a>
                </li>

                <!-- Page Numbers -->
                @php
                    $start = max(1, $grns->currentPage() - 2);
                    $end = min($grns->lastPage(), $grns->currentPage() + 2);
                @endphp
                
                @for($page = $start; $page <= $end; $page++)
                    @if($page == $grns->currentPage())
                        <li class="page-item active" aria-current="page">
                            <span class="page-link">{{ $page }}</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $grns->url($page) }}">{{ $page }}</a>
                        </li>
                    @endif
                @endfor

                <!-- Next Button -->
                <li class="page-item {{ !$grns->hasMorePages() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $grns->nextPageUrl() }}" 
                       {{ !$grns->hasMorePages() ? 'onclick="return false;"' : '' }}>
                        Next<i class="ri-arrow-right-line ms-1"></i>
                    </a>
                </li>
            @endif
        </ul>
    </div>
</div>

