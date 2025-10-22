

<div class="modal fade" id="quickAddModal" tabindex="-1" aria-labelledby="quickAddModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="<?php echo e(route('products.quickAdd')); ?>">
            <?php echo csrf_field(); ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="quickAddModalLabel">
                        <i class="bi bi-lightning-fill text-warning me-2"></i>Quick Add Product
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="mb-3">
                        <label for="quick_name" class="form-label">Product Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="quick_name" name="name" required>
                    </div>

                    <div class="mb-3">
                        <label for="quick_price" class="form-label">Selling Price (R) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control" id="quick_price" name="price_normal" required>
                    </div>

                    <div class="mb-3">
                        <label for="quick_qty" class="form-label">Initial Quantity (Optional)</label>
                        <input type="number" class="form-control" id="quick_qty" name="qty" placeholder="Leave blank if no stock">
                        <small class="text-muted">If provided, creates initial stock batch with zero cost.</small>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i>Create Product
                    </button>
                </div>
            </div>
        </form>
    </div>
</div><?php /**PATH C:\xampp\htdocs\MMP\resources\views/products/_quick_add_modal.blade.php ENDPATH**/ ?>