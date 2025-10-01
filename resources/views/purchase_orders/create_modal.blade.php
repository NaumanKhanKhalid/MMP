<!-- resources/views/purchase_orders/partials/create_modal.blade.php -->
<form method="POST" action="{{ route('purchase-orders.store') }}">
    @csrf
    <div class="modal-header">
        <h5 class="modal-title">
            <i class="bi bi-plus-circle me-2"></i> Create Purchase Order
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <ul class="nav nav-tabs mb-3" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#create-basic">Basic Info</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#create-items">Items</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#create-notes">Notes</a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="create-basic">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Supplier <span class="text-danger">*</span></label>
                        <select name="supplier_id" class="form-select select2-create-supplier" required>
                            <option value="">-- Select Supplier --</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Order Date <span class="text-danger">*</span></label>
                        <input type="date" name="order_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Expected Date</label>
                        <input type="date" name="expected_date" class="form-control">
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="create-items">
                <div class="table-responsive">
                    <table class="table table-bordered" id="createItemsTable">
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
                            <tr class="po-item-row">
                                <td>
                                    <select name="items[0][product_id]" class="form-select select2-create-product" required>
                                        <option value="">-- Select Product --</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="number" name="items[0][quantity]" class="form-control create-qty" min="1" value="1" required></td>
                                <td><input type="number" name="items[0][unit_price]" class="form-control create-price" min="0" step="0.01" value="0" required></td>
                                <td class="create-item-total">R 0.00</td>
                                <td><button type="button" class="btn btn-danger btn-sm remove-create-row">Remove</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-end mt-3">
                    <h5>Grand Total: <span id="createGrandTotal">R 0.00</span></h5>
                    <input type="hidden" name="total_amount" id="createTotalAmountInput" value="0">
                </div>
                <button type="button" class="btn btn-primary mt-2" id="addCreateRow">+ Add Row</button>
            </div>
            <div class="tab-pane fade" id="create-notes">
                <div class="mb-3">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control" rows="5" placeholder="Add any notes or comments..."></textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="bi bi-x-circle me-1"></i> Cancel
        </button>
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-circle me-1"></i> Create PO
        </button>
    </div>
</form>
<script>
    (function() {
        $('.select2-create-supplier, .select2-create-product').select2({
            dropdownParent: $('#createPoModal'),
            width: '100%'
        });
        let rowIdx = 1;
        $('#addCreateRow').on('click', function() {
            const table = $('#createItemsTable tbody');
            // Destroy Select2 before cloning
            const firstRow = table.find('.po-item-row').first();
            firstRow.find('select.select2-create-product').select2('destroy');
            const newRow = firstRow.clone();
            // Remove any select2-container markup from the clone
            newRow.find('.select2-container').remove();
            newRow.find('select, input').each(function() {
                const name = $(this).attr('name');
                if (name) {
                    const newName = name.replace(/\d+/, rowIdx);
                    $(this).attr('name', newName);
                    if ($(this).is('select')) {
                        $(this).val('');
                    } else {
                        $(this).val('');
                    }
               }
            });
            newRow.find('.create-item-total').text('R 0.00');
            table.append(newRow);
            // Re-initialize Select2 on all product selects
            $('.select2-create-product').select2({
                dropdownParent: $('#createPoModal'),
                width: '100%'
            });
            rowIdx++;
        });
        $('#createItemsTable').on('click', '.remove-create-row', function() {
            if ($('#createItemsTable .po-item-row').length > 1) {
                $(this).closest('tr').remove();
                calculateCreateTotal();
            }
        });
        function calculateCreateTotal() {
            let grand = 0;
            $('#createItemsTable tbody tr').each(function() {
                let qty = parseFloat($(this).find('.create-qty').val()) || 0;
                let price = parseFloat($(this).find('.create-price').val()) || 0;
                let total = qty * price;
                $(this).find('.create-item-total').text('R ' + total.toFixed(2));
                grand += total;
            });
            $('#createGrandTotal').text('R ' + grand.toFixed(2));
            $('#createTotalAmountInput').val(grand.toFixed(2));
        }
        $('#createItemsTable').on('input', '.create-qty, .create-price', calculateCreateTotal);
    })();
</script>
