<div class="d-flex align-items-center flex-wrap overflow-auto">
    <div class="mb-2 mb-sm-0">
        Showing <b>{{ $customers->firstItem() ?? 0 }}</b> to <b>{{ $customers->lastItem() ?? 0 }}</b> of <b>{{ $customers->total() }}</b> entries
        <i class="bi bi-arrow-right ms-2 fw-semibold"></i>
    </div>
    <div class="ms-auto">
        <ul class="pagination mb-0 overflow-auto">
            {{-- Previous Button --}}
            @if ($customers->onFirstPage())
                <li class="page-item disabled">
                    <a class="page-link">Previous</a>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="javascript:void(0);" onclick="loadCustomersPage({{ $customers->currentPage() - 1 }})">Previous</a>
                </li>
            @endif

            {{-- Page Numbers --}}
            @foreach ($customers->getUrlRange(1, $customers->lastPage()) as $page => $url)
                <li class="page-item {{ $page == $customers->currentPage() ? 'active' : '' }}">
                    <a class="page-link" href="javascript:void(0);" onclick="loadCustomersPage({{ $page }})">{{ $page }}</a>
                </li>
            @endforeach

            {{-- Next Button --}}
            @if ($customers->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="javascript:void(0);" onclick="loadCustomersPage({{ $customers->currentPage() + 1 }})">Next</a>
                </li>
            @else
                <li class="page-item disabled">
                    <a class="page-link">Next</a>
                </li>
            @endif
        </ul>
    </div>
</div>


