@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1>Quotes</h1>
            <button class="btn btn-primary" id="openCreateQuoteModal">
                <i class="bi bi-file-earmark-plus me-1"></i> New Quote
            </button>
        </div>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Quote Number</th>
                    <th>Customer</th>
                    <th>Status</th>
                    <th>Valid Until</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($quotes as $quote)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $quote->quote_number }}</td>
                        <td>{{ $quote->customer_id ?? '-' }}</td>
                        <td>{{ ucfirst($quote->status) }}</td>
                        <td>{{ $quote->valid_until }}</td>
                        <td>{{ $quote->created_at->format('Y-m-d') }}</td>
                        <td>
                            <div class="btn-list d-inline-flex gap-1">
                                <button class="btn btn-sm btn-primary-light btn-icon openViewQuoteModal"
                                    data-id="{{ $quote->id }}" title="View">
                                    <i class="ri-eye-line"></i>
                                </button>
                                <button class="btn btn-sm btn-success-light btn-icon openEditQuoteModal"
                                    data-id="{{ $quote->id }}" title="Edit">
                                    <i class="ri-pencil-line"></i>
                                </button>
                                <a href="{{ route('quotes.print', $quote) }}" target="_blank"
                                    class="btn btn-sm btn-primary-light btn-icon" title="Print">
                                    <i class="bi bi-printer"></i>
                                </a>
                                <form action="{{ route('quotes.destroy', $quote) }}" method="POST"
                                    style="display:inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger-light btn-icon"
                                        onclick="return confirm('Delete this quote?')" title="Delete">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </form>
                                <form action="{{ route('quotes.convert-to-invoice', $quote->id) }}" method="POST"
                                    style="display:inline-block" class="convert-to-invoice-form">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success-light btn-icon"
                                        title="Convert to Invoice">
                                        <i class="bi bi-arrow-right-circle"></i>
                                    </button>
                                </form>
                                <form action="{{ route('quotes.duplicate', $quote->id) }}" method="POST"
                                    style="display:inline-block" class="duplicate-quote-form">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-info-light btn-icon"
                                        title="Duplicate Quote">
                                        <i class="bi bi-files"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $quotes->links() }}

        <!-- Quote Modals -->
        <div class="modal fade" id="quoteModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content" id="quoteModalContent"></div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(function() {
                // Create Quote Modal
                $('#openCreateQuoteModal').on('click', function() {
                    $.get("{{ route('quotes.create') }}", function(html) {
                        $('#quoteModalContent').html(html);
                        $('#quoteModal').modal('show');
                    });
                });

                // View Quote Modal
                $(document).on('click', '.openViewQuoteModal', function() {
                    var id = $(this).data('id');
                    $.get("{{ route('quotes.view-modal', ':id') }}".replace(':id', id), function(html) {
                        $('#quoteModalContent').html(html);
                        $('#quoteModal').modal('show');
                    });
                });

                // Edit Quote Modal
                $(document).on('click', '.openEditQuoteModal', function() {
                    var id = $(this).data('id');
                    $.get("{{ route('quotes.edit-modal', ':id') }}".replace(':id', id), function(html) {
                        $('#quoteModalContent').html(html);
                        $('#quoteModal').modal('show');
                    });
                });
            });
        </script>
    @endpush
@endsection
