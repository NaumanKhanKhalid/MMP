<div class="d-flex align-items-center flex-wrap overflow-auto">
    <div class="mb-2 mb-sm-0">
        Showing <b>{{ $quotes->firstItem() ?? 0 }}</b> to <b>{{ $quotes->lastItem() ?? 0 }}</b> of <b>{{ $quotes->total() }}</b> entries 
        <small class="text-muted">(Page {{ $quotes->currentPage() }} of {{ $quotes->lastPage() }})</small>
        <i class="ri-arrow-right-line ms-2 fw-semibold"></i>
    </div>
    <div class="ms-auto">
        <ul class="pagination mb-0 overflow-auto">
            @if($quotes->hasPages())
                <!-- Previous Button -->
                <li class="page-item {{ $quotes->onFirstPage() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $quotes->previousPageUrl() }}" 
                       {{ $quotes->onFirstPage() ? 'onclick="return false;"' : '' }}>
                        <i class="ri-arrow-left-line me-1"></i>Previous
                    </a>
                </li>

                <!-- Page Numbers -->
                @php
                    $start = max(1, $quotes->currentPage() - 2);
                    $end = min($quotes->lastPage(), $quotes->currentPage() + 2);
                @endphp
                
                @for($page = $start; $page <= $end; $page++)
                    @if($page == $quotes->currentPage())
                        <li class="page-item active" aria-current="page">
                            <span class="page-link">{{ $page }}</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $quotes->url($page) }}">{{ $page }}</a>
                        </li>
                    @endif
                @endfor

                <!-- Next Button -->
                <li class="page-item {{ !$quotes->hasMorePages() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $quotes->nextPageUrl() }}" 
                       {{ !$quotes->hasMorePages() ? 'onclick="return false;"' : '' }}>
                        Next<i class="ri-arrow-right-line ms-1"></i>
                    </a>
                </li>
            @endif
        </ul>
    </div>
</div>

