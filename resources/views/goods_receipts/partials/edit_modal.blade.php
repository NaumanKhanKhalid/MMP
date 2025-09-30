<!-- resources/views/goods_receipts/partials/edit_modal.blade.php -->
<form method="POST" action="{{ route('goods-receipts.update', $grn->id) }}">
    @csrf
    @method('PUT')
    <div class="modal-header">
        <h5 class="modal-title">
            <i class="bi bi-pencil-square me-2"></i> Edit Goods Receipt - {{ $grn->grn_number }}
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <ul class="nav nav-tabs mb-3" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#edit-basic-{{ $grn->id }}">Basic Info</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#edit-items-{{ $grn->id }}">Items</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#edit-notes-{{ $grn->id }}">Notes</a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="edit-basic-{{ $grn->id }}">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Supplier <span class="text-danger">*</span></label>
                        <select name="supplier_id" class="form-select select2-edit-supplier" required>
                            <option value="">-- Select Supplier --</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" @if ($grn->supplier_id == $supplier->id) selected @endif>
                                    {{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">GRN Number <span class="text-danger">*</span></label>
                        <input type="text" name="grn_number" class="form-control" value="{{ $grn->grn_number }}"
                            required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Received Date <span class="text-danger">*</span></label>
                        <input type="date" name="received_date" class="form-control"
                            value="{{ $grn->received_date }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Invoice Number</label>
                        <input type="text" name="invoice_number" class="form-control"
                            value="{{ $grn->invoice_number }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="pending" @if ($grn->status == 'pending') selected @endif>Pending</option>
                            <option value="completed" @if ($grn->status == 'completed') selected @endif>Completed
                            </option>
                            <option value="cancelled" @if ($grn->status == 'cancelled') selected @endif>Cancelled
                            </option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Total Amount</label>
                        <input type="text" class="form-control bg-light"
                            value="${{ number_format($grn->total_amount, 2) }}" readonly>
                        <small class="text-muted">Total amount is calculated from items below.</small>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="edit-items-{{ $grn->id }}">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    Editing items will update the associated batches and inventory.
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered" id="editItemsTable-{{ $grn->id }}">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Batch Code</th>
                                <th>Quantity</th>
                                <th>Unit Cost</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($grn->batches as $i => $batch)
                                <tr>
                                    <td>
                                        <select name="items[{{ $i }}][product_id]"
                                            class="form-select select2-edit-product" required>
                                            <option value="">-- Select Product --</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}"
                                                    @if ($batch->product_id == $product->id) selected @endif>
                                                    {{ $product->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="items[{{ $i }}][batch_code]"
                                            class="form-control" value="{{ $batch->batch_code }}" required>
                                    </td>
                                    <td>
                                        <input type="number" name="items[{{ $i }}][quantity]"
                                            class="form-control edit-qty" min="1"
                                            value="{{ $batch->qty_received }}" required>
                                    </td>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-text">$</span>
                                                <input type="number" step="0.01"
                                                    name="items[{{ $i }}][purchase_price]"
                                                    class="form-control edit-price" value="{{ $batch->landed_unit_cost }}"
                                                    required>
                                            </div>
                                        </td>
                                    <td class="edit-item-total">
                                        ${{ number_format($batch->qty_received * $batch->landed_unit_cost, 2) }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-danger remove-edit-row">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-end mt-3">
                    <h5>Grand Total: <span
                            id="editGrandTotal-{{ $grn->id }}">${{ number_format($grn->total_amount, 2) }}</span>
                    </h5>
                        <input type="hidden" name="total_amount" id="editTotalAmountInput-{{ $grn->id }}" value="{{ $grn->total_amount }}">
                </div>
                <button type="button" class="btn btn-primary mt-2" onclick="addEditRow({{ $grn->id }})">+ Add
                    Row</button>
            </div>
            <div class="tab-pane fade" id="edit-notes-{{ $grn->id }}">
                <div class="mb-3">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control" rows="5" placeholder="Add any notes or comments...">{{ $grn->notes }}</textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="bi bi-x-circle me-1"></i> Cancel
        </button>
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-circle me-1"></i> Update GRN
        </button>
    </div>
</form>
<script>
    // Reinitialize Select2 and row logic for edit modal
    (function() {
        const grnId = {{ $grn->id }};
        $('#editGrnModal .select2-edit-supplier, #editGrnModal .select2-edit-product').select2({
            dropdownParent: $('#editGrnModal'),
            width: '100%'
        });

        function calculateEditTotal() {
            let grand = 0;
            $('#editItemsTable-' + grnId + ' tbody tr').each(function() {
                let qty = parseFloat($(this).find('.edit-qty').val()) || 0;
                let price = parseFloat($(this).find('.edit-price').val()) || 0;
                let total = qty * price;
                $(this).find('.edit-item-total').text('$' + total.toFixed(2));
                grand += total;
            });
            $('#editGrandTotal-' + grnId).text('$' + grand.toFixed(2));
                $('#editTotalAmountInput-' + grnId).val(grand.toFixed(2));
        }
        $('#editGrnModal').on('input', '.edit-qty, .edit-price', calculateEditTotal);
        $('#editGrnModal').on('click', '.remove-edit-row', function() {
            if ($('#editItemsTable-' + grnId + ' tbody tr').length > 1) {
                $(this).closest('tr').remove();
                calculateEditTotal();
            }
        });
        window.addEditRow = function(grnId) {
            let rowIndex = $('#editItemsTable-' + grnId + ' tbody tr').length;
            let row = `<tr>
            <td>
                <select name="items[${rowIndex}][product_id]" class="form-select select2-edit-product" required>
                    <option value="">-- Select Product --</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <input type="text" name="items[${rowIndex}][batch_code]" class="form-control" value="BATCH-${Date.now()}" required>
            </td>
            <td>
                <input type="number" name="items[${rowIndex}][quantity]" class="form-control edit-qty" min="1" value="1" required>
            </td>
            <td>
                <div class="input-group">
                    <span class="input-group-text">$</span>
                    <input type="number" step="0.01" name="items[${rowIndex}][unit_cost]" class="form-control edit-price" value="0" required>
                </div>
            </td>
            <td class="edit-item-total">$0.00</td>
            <td>
                <button type="button" class="btn btn-sm btn-danger remove-edit-row">
                    <i class="bi bi-x"></i>
                </button>
            </td>
        </tr>`;
            $('#editItemsTable-' + grnId + ' tbody').append(row);
            $('#editGrnModal .select2-edit-product').select2({
                dropdownParent: $('#editGrnModal'),
                width: '100%'
            });
        }
    })();
</script>
