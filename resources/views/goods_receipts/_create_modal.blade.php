{{-- resources/views/goods-receipts/partials/_create_modal.blade.php --}}

<div class="modal fade" id="createGoodsReceiptModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <form method="POST" action="{{ route('goods-receipts.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-plus-circle me-2"></i> New Goods Receipt
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    {{-- Tab Navigation --}}
                    <ul class="nav nav-tabs mb-3" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#grn-basic">Basic Info</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#grn-items">Items</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#grn-notes">Notes</a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        {{-- Basic Info --}}
                        <div class="tab-pane fade show active" id="grn-basic">
                            <div class="row">
                                <div class="col-md-4 mb-3" id="grn-supplier-group">
                                    <label class="form-label">Supplier <span class="text-danger">*</span></label>
                                    <select name="supplier_id" id="grnSupplierSelect"
                                        class="form-select select2-grn-supplier" required>
                                        <option value="">-- Select Supplier --</option>
                                        @foreach ($suppliers as $s)
                                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Linked PO</label>
                                    <select name="purchase_order_id" id="grnPoSelect"
                                        class="form-select select2-grn-po">
                                        <option value="">-- Select PO (optional) --</option>
                                        @foreach ($purchaseOrders as $po)
                                            <option value="{{ $po['id'] }}"
                                                data-supplier="{{ $po['supplier']->id ?? '' }}"
                                                data-items='@json($po['items'])'>
                                                {{ $po['po_number'] }} ({{ $po['supplier']->name ?? '-' }})
                                            </option>
                                        @endforeach

                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Received Date <span class="text-danger">*</span></label>
                                    <input type="date" name="received_date" class="form-control"
                                        value="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">GRN Number <span class="text-danger">*</span></label>
                                    <input type="text" name="grn_number" class="form-control"
                                        placeholder="Enter GRN Number" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Invoice Number</label>
                                    <input type="text" name="invoice_number" class="form-control"
                                        placeholder="Invoice Number">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Status <span class="text-danger">*</span></label>
                                    <select name="status" class="form-select" required>
                                        <option value="pending">Pending</option>
                                        <option value="completed" selected>Completed</option>
                                        <option value="cancelled">Cancelled</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Total Amount <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" name="total_amount" id="grnTotalAmountInput"
                                        class="form-control" value="0.00" readonly required>
                                </div>
                            </div>
                        </div>

                        {{-- Items Tab --}}
                        <div class="tab-pane fade" id="grn-items">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="grnItemsTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width:40%">Product</th>
                                            <th style="width:15%">Quantity</th>
                                            <th style="width:20%">Purchase Price</th>
                                            <th style="width:15%">Total</th>
                                            <th style="width:10%">
                                                <button type="button" class="btn btn-sm btn-success" id="addGrnRow">
                                                    <i class="bi bi-plus"></i>
                                                </button>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <select name="items[0][product_id]"
                                                    class="form-select select2-grn-product" required>
                                                    <option value="">-- Select Product --</option>
                                                    @foreach ($products as $p)
                                                        <option value="{{ $p->id }}">{{ $p->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" name="items[0][quantity]"
                                                    class="form-control grn-qty" min="1" value="1"
                                                    required>
                                            </td>
                                            <td>
                                                <input type="number" step="0.01" name="items[0][purchase_price]"
                                                    class="form-control grn-price" value="0" required>
                                            </td>
                                            <td class="grn-item-total">0.00</td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-danger removeGrnRow">
                                                    <i class="bi bi-x"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-end mt-3">
                                <h5>Grand Total: <span id="grnGrandTotal">0.00</span></h5>
                            </div>
                        </div>

                        {{-- Notes --}}
                        <div class="tab-pane fade" id="grn-notes">
                            <div class="mb-3">
                                <label class="form-label">Notes</label>
                                <textarea name="notes" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i> Save GRN
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let grnRowIndex = 1;

            function initGrnSelect2() {
                $('.select2-grn-supplier, .select2-grn-product, .select2-grn-po').select2({
                    dropdownParent: $('#createGoodsReceiptModal'),
                    width: '100%',
                    allowClear: true,
                    placeholder: 'Select option'
                });
            }

            // PO selection logic: auto-fill items and supplier, hide supplier if PO selected
            $('#grnPoSelect').on('change', function() {
                var poId = $(this).val();
                if (poId) {
                    var selected = $(this).find('option:selected');
                    var supplierId = selected.data('supplier');
                    var items = selected.data('items');
                    // Hide supplier select, set value
                    $('#grn-supplier-group').hide();
                    $('#grnSupplierSelect').val(supplierId).trigger('change');
                    // Fill items table
                    if (items && items.length) {
                        var tbody = $('#grnItemsTable tbody');
                        tbody.html('');
                        items.forEach(function(item, idx) {
                            var options = '';
                            @foreach ($products as $p)
                                options += `<option value="{{ $p->id }}" ${item.product_id=={{ $p->id }}?'selected':''}>{{ $p->name }}</option>`;
                            @endforeach
                            var row = `<tr>
                                <td>
                                    <select name="items[${idx}][product_id]" class="form-select select2-grn-product" required>
                                        <option value="">-- Select Product --</option>
                                        ${options}
                                    </select>
                                </td>
                                <td><input type="number" name="items[${idx}][quantity]" class="form-control grn-qty" min="1" value="${item.quantity}" required></td>
                                <td><input type="number" step="0.01" name="items[${idx}][purchase_price]" class="form-control grn-price" value="${item.unit_price}" required></td>
                                <td class="grn-item-total">0.00</td>
                                <td><button type="button" class="btn btn-sm btn-danger removeGrnRow"><i class="bi bi-x"></i></button></td>
                            </tr>`;
                            tbody.append(row);
                        });
                        initGrnSelect2();
                        calculateGrnTotal();
                    }
                } else {
                    // Show supplier select, clear items
                    $('#grn-supplier-group').show();
                    $('#grnSupplierSelect').val('').trigger('change');
                    $('#grnItemsTable tbody').html('');
                    grnRowIndex = 1;
                    var options = '';
                    @foreach ($products as $p)
                        options += `<option value="{{ $p->id }}">{{ $p->name }}</option>`;
                    @endforeach
                    var row = `<tr>
                        <td>
                            <select name="items[0][product_id]" class="form-select select2-grn-product" required>
                                <option value="">-- Select Product --</option>
                                ${options}
                            </select>
                        </td>
                        <td><input type="number" name="items[0][quantity]" class="form-control grn-qty" min="1" value="1" required></td>
                        <td><input type="number" step="0.01" name="items[0][purchase_price]" class="form-control grn-price" value="0" required></td>
                        <td class="grn-item-total">0.00</td>
                        <td><button type="button" class="btn btn-sm btn-danger removeGrnRow"><i class="bi bi-x"></i></button></td>
                    </tr>`;
                    $('#grnItemsTable tbody').append(row);
                    initGrnSelect2();
                    calculateGrnTotal();
                }
            });

            // Add row
            $('#addGrnRow').on('click', function() {
                let row = `
          <tr>
            <td>
              <select name="items[${grnRowIndex}][product_id]" class="form-select select2-grn-product" required>
                <option value="">-- Select Product --</option>
                @foreach ($products as $p)
                  <option value="{{ $p->id }}">{{ $p->name }}</option>
                @endforeach
              </select>
            </td>
            <td><input type="number" name="items[${grnRowIndex}][quantity]" class="form-control grn-qty" min="1" value="1" required></td>
            <td><input type="number" step="0.01" name="items[${grnRowIndex}][purchase_price]" class="form-control grn-price" value="0" required></td>
            <td class="grn-item-total">0.00</td>
            <td><button type="button" class="btn btn-sm btn-danger removeGrnRow"><i class="bi bi-x"></i></button></td>
          </tr>`;
                $('#grnItemsTable tbody').append(row);
                initGrnSelect2();
                grnRowIndex++;
                calculateGrnTotal();
            });

            // Remove row
            $(document).on('click', '.removeGrnRow', function() {
                $(this).closest('tr').remove();
                calculateGrnTotal();
            });

            // Calculate totals
            $(document).on('input', '.grn-qty, .grn-price', calculateGrnTotal);

            function calculateGrnTotal() {
                let grand = 0;
                $('#grnItemsTable tbody tr').each(function() {
                    let qty = parseFloat($(this).find('.grn-qty').val()) || 0;
                    let price = parseFloat($(this).find('.grn-price').val()) || 0;
                    let total = qty * price;
                    $(this).find('.grn-item-total').text(total.toFixed(2));
                    grand += total;
                });
                $('#grnGrandTotal').text(grand.toFixed(2));
                $('#grnTotalAmountInput').val(grand.toFixed(2));
            }

            // Modal events
            $('#createGoodsReceiptModal').on('shown.bs.modal', function() {
                initGrnSelect2();
            });

            $('#createGoodsReceiptModal').on('hidden.bs.modal', function() {
                $(this).find('form')[0].reset();
                $('#grnItemsTable tbody').html('');
                $('#grnGrandTotal').text('0.00');
                grnRowIndex = 1;
                // Reset tab
                $('.nav-tabs a:first').tab('show');
            });
        });
    </script>
@endpush
