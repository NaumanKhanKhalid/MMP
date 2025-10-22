<div class="d-flex align-items-center flex-wrap overflow-auto">
    <div class="mb-2 mb-sm-0">
        Showing <b>{{ $suppliers->firstItem() ?? 0 }}</b> to <b>{{ $suppliers->lastItem() ?? 0 }}</b> of <b>{{ $suppliers->total() }}</b> entries <i class="ri-arrow-right-line ms-2 fw-semibold"></i>
    </div>
    <div class="ms-auto">
        <ul class="pagination mb-0 overflow-auto">
            @if($suppliers->hasPages())
                <!-- Previous Button -->
                <li class="page-item {{ $suppliers->onFirstPage() ? 'disabled' : '' }}">
                    <a class="page-link" href="javascript:void(0)" 
                       onclick="if(!$(this).parent().hasClass('disabled')) { loadSuppliersPage({{ $suppliers->currentPage() - 1 }}); }">
                        Previous
                    </a>
                </li>

                <!-- Page Numbers -->
                @php
                    $start = max(1, $suppliers->currentPage() - 2);
                    $end = min($suppliers->lastPage(), $suppliers->currentPage() + 2);
                @endphp
                
                @for($page = $start; $page <= $end; $page++)
                    @if($page == $suppliers->currentPage())
                        <li class="page-item active" aria-current="page">
                            <span class="page-link">{{ $page }}</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="javascript:void(0)" 
                               onclick="loadSuppliersPage({{ $page }})">{{ $page }}</a>
                        </li>
                    @endif
                @endfor

                <!-- Next Button -->
                <li class="page-item {{ !$suppliers->hasMorePages() ? 'disabled' : '' }}">
                    <a class="page-link" href="javascript:void(0)" 
                       onclick="if(!$(this).parent().hasClass('disabled')) { loadSuppliersPage({{ $suppliers->currentPage() + 1 }}); }">
                        Next
                    </a>
                </li>
            @endif
        </ul>
    </div>
</div>
