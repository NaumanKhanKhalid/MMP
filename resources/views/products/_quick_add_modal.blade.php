<!-- Quick Add Product Modal -->
<div class="modal fade" id="quickAddProductModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('products.quickAdd') }}" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Quick Add Product</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Name</label>
          <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Selling Price</label>
          <input type="number" name="price" class="form-control" step="0.01" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Quantity</label>
          <input type="number" name="qty" class="form-control" value="0" required>
        </div>
      </div>

      <div class="modal-footer">
        <button type="submit" class="btn btn-success">Quick Add</button>
      </div>
    </form>
  </div>
</div>
