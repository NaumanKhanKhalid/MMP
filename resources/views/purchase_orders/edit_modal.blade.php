<!-- resources/views/purchase_orders/partials/edit_modal.blade.php -->
<form method="POST" action="{{ route('purchase-orders.update', $po->id) }}">
    @csrf
    @method('PUT')
    <div class="modal-header">
        <h5 class="modal-title">
            <i class="bi bi-pencil-square me-2"></i> Edit Purchase Order - {{ $po->po_number }}
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <ul class="nav nav-tabs mb-3" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#edit-basic-{{ $po->id }}">Basic Info</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#edit-items-{{ $po->id }}">Items</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#edit-notes-{{ $po->id }}">Notes</a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="edit-basic-{{ $po->id }}">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Supplier <span class="text-danger">*</span></label>
                        <select name="supplier_id" class="form-select select2-edit-supplier" required>
                            <option value="">-- Select Supplier --</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" @if ($po->supplier_id == $supplier->id) selected @endif>{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">PO Number <span class="text-danger">*</span></label>
                        <input type="text" name="po_number" class="form-control" value="{{ $po->po_number }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Order Date <span class="text-danger">*</span></label>
                        <input type="date" name="order_date" class="form-control" value="{{ $po->order_date }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Expected Date</label>
                        <input type="date" name="expected_date" class="form-control" value="{{ $po->expected_date }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="draft" @if ($po->status == 'draft') selected @endif>Draft</option>
                            <option value="sent" @if ($po->status == 'sent') selected @endif>Sent</option>
                            <option value="partially_received" @if ($po->status == 'partially_received') selected @endif>Partially Received</option>
                            <option value="completed" @if ($po->status == 'completed') selected @endif>Completed</option>
                            <option value="cancelled" @if ($po->status == 'cancelled') selected @endif>Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Total Amount</label>
                        <input type="text" class="form-control bg-light" value="R {{ number_format($po->total_amount, 2) }}" readonly>
                        <small class="text-muted">Total amount is calculated from items below.</small>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="edit-items-{{ $po->id }}">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    Editing items will update the associated PO items.
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered" id="editItemsTable-{{ $po->id }}">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($po->items as $i => $item)
                                <tr>
                                    <td>
                                        <select name="items[{{ $i }}][product_id]" class="form-select select2-edit-product" required>
                                            <option value="">-- Select Product --</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}" @if ($item->product_id == $product->id) selected @endif>{{ $product->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="number" name="items[{{ $i }}][quantity]" class="form-control edit-qty" min="1" value="{{ $item->quantity }}" required></td>
                                    <td><input type="number" name="items[{{ $i }}][unit_price]" class="form-control edit-price" min="0" step="0.01" value="{{ $item->unit_price }}" required></td>
                                    <td class="edit-item-total">R {{ number_format($item->quantity * $item->unit_price, 2) }}</td>
                                    <td><button type="button" class="btn btn-danger-light btn-sm btn-icon remove-edit-row" title="Remove"><i class="ri-delete-bin-line"></i></button></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-end mt-3">
                    <h5>Grand Total: <span id="editGrandTotal-{{ $po->id }}">R {{ number_format($po->total_amount, 2) }}</span></h5>
                    <input type="hidden" name="total_amount" id="editTotalAmountInput-{{ $po->id }}" value="{{ $po->total_amount }}">
                </div>
                <button type="button" class="btn btn-primary mt-2" onclick="addEditRow({{ $po->id }})">+ Add Row</button>
            </div>
            <div class="tab-pane fade" id="edit-notes-{{ $po->id }}">
                <div class="mb-3">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control" rows="5" placeholder="Add any notes or comments...">{{ $po->notes }}</textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
            <i class="ri-close-line"></i> Cancel
        </button>
        <button type="submit" class="btn btn-success-light btn-icon">
            <i class="ri-save-line"></i>
        </button>
    </div>
</form>
<script>
    (function() {
        const poId = {{ $po->id }};
        $('#editPoModal .select2-edit-supplier, #editPoModal .select2-edit-product').select2({
            dropdownParent: $('#editPoModal'),
            width: '100%'
        });
        function calculateEditTotal() {
            let grand = 0;
            $('#editItemsTable-' + poId + ' tbody tr').each(function() {
                let qty = parseFloat($(this).find('.edit-qty').val()) || 0;
                let price = parseFloat($(this).find('.edit-price').val()) || 0;
                let total = qty * price;
                $(this).find('.edit-item-total').text('R ' + total.toFixed(2));
                grand += total;
            });
            $('#editGrandTotal-' + poId).text('R ' + grand.toFixed(2));
            $('#editTotalAmountInput-' + poId).val(grand.toFixed(2));
        }
        $('#editPoModal').on('input', '.edit-qty, .edit-price', calculateEditTotal);
        $('#editPoModal').on('click', '.remove-edit-row', function() {
            if ($('#editItemsTable-' + poId + ' tbody tr').length > 1) {
                $(this).closest('tr').remove();
                calculateEditTotal();
            }
        });
        window.addEditRow = function(poId) {
            let rowIndex = $('#editItemsTable-' + poId + ' tbody tr').length;
            let row = `<tr>
            <td>
                <select name="items[${rowIndex}][product_id]" class="form-select select2-edit-product" required>
                    <option value="">-- Select Product --</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </td>
            <td><input type="number" name="items[${rowIndex}][quantity]" class="form-control edit-qty" min="1" value="1" required></td>
            <td><input type="number" name="items[${rowIndex}][unit_price]" class="form-control edit-price" min="0" step="0.01" value="0" required></td>
            <td class="edit-item-total">R 0.00</td>
            <td><button type="button" class="btn btn-danger-light btn-sm btn-icon remove-edit-row" title="Remove"><i class="ri-delete-bin-line"></i></button></td>
        </tr>`;
            $('#editItemsTable-' + poId + ' tbody').append(row);
            $('#editPoModal .select2-edit-product').select2({
                dropdownParent: $('#editPoModal'),
                width: '100%'
            });
        }
    })();
</script>
