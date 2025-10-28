
<div class="modal fade" id="editProductModal-<?php echo e($product->id); ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <form method="POST" action="<?php echo e(route('products.update', $product->id)); ?>" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-pencil-square me-2"></i>Edit Product: <?php echo e($product->name); ?>

                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">

                    
                    <div class="mb-4">
                        <h6 class="border-bottom pb-2 mb-3">
                            <i class="bi bi-info-circle me-2"></i>Basic Information
                        </h6>
                            <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Product Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="<?php echo e($product->name); ?>"
                                    required>
                                </div>


                            <div class="col-md-4 mb-3">
                                <label class="form-label">Supplier</label>
                                <select name="supplier_ids"
                                    class="form-select select2-edit-suppliers-<?php echo e($product->id); ?>">
                                    <option value="">Select Supplier</option>
                                    <?php $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($s->id); ?>"
                                            <?php echo e($product->supplier_id == $s->id ? 'selected' : ''); ?>>
                                            <?php echo e($s->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Supplier Code <span class="text-danger">*</span></label>
                                <input type="text" name="supplier_code" class="form-control"
                                    value="<?php echo e($product->supplier_code); ?>" required placeholder="Supplier Code">
                                </div>
                                <div class="col-md-4 mb-3">
                                <label class="form-label">Brand <span class="text-danger">*</span></label>
                                    <select name="brand_id" class="form-select select2-edit-brand-<?php echo e($product->id); ?>"
                                        required>
                                        <option value="">Select Brand</option>
                                        <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($b->id); ?>" <?php if($product->brand_id == $b->id): echo 'selected'; endif; ?>>
                                                <?php echo e($b->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                <label class="form-label">Category <span class="text-danger">*</span></label>
                                    <select name="category_id"
                                        class="form-select select2-edit-category-<?php echo e($product->id); ?>" required>
                                        <option value="">Select Category</option>
                                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($c->id); ?>" <?php if($product->category_id == $c->id): echo 'selected'; endif; ?>>
                                                <?php echo e($c->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Subcategory</label>
                                    <select name="subcategory_id"
                                        class="form-select select2-edit-subcategory-<?php echo e($product->id); ?>">
                                        <option value="">Select Subcategory</option>
                                        <?php $__currentLoopData = $subCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($sc->id); ?>" <?php if($product->subcategory_id == $sc->id): echo 'selected'; endif; ?>>
                                                <?php echo e($sc->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Alternate Barcode (Optional)</label>
                                <input type="text" name="barcode_alternate" class="form-control"
                                    value="<?php echo e($product->barcode_alternate); ?>" placeholder="Manual barcode entry">
                                </div>
                            </div>
                        </div>

                    
                    <div class="mb-4">
                        <h6 class="border-bottom pb-2 mb-3">
                            <i class="bi bi-tag me-2"></i>Pricing
                        </h6>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Normal Price (R)</label>
                                    <input type="number" step="0.01" name="price_normal" class="form-control"
                                    value="<?php echo e($product->price_normal); ?>" placeholder="0.00">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Online Price (R)</label>
                                    <input type="number" step="0.01" name="price_online" class="form-control"
                                    value="<?php echo e($product->price_online); ?>" placeholder="0.00">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Workshop Price (R)</label>
                                    <input type="number" step="0.01" name="price_workshop" class="form-control"
                                    value="<?php echo e($product->price_workshop); ?>" placeholder="0.00">
                                </div>
                            </div>
                        </div>

                    
                    <div class="mb-4">
                        <h6 class="border-bottom pb-2 mb-3">
                            <i class="bi bi-boxes me-2"></i>Inventory Settings
                        </h6>
                            <div class="row">
                            <div class="col-md-4 mb-3">
                                    <label class="form-label">Unit</label>
                                    <select name="unit" class="form-select">
                                        <option value="PCS" <?php if($product->unit == 'PCS'): echo 'selected'; endif; ?>>PCS</option>
                                        <option value="SET" <?php if($product->unit == 'SET'): echo 'selected'; endif; ?>>SET</option>
                                    </select>
                                </div>
                            <div class="col-md-4 mb-3">
                                    <label class="form-label">Bin Location</label>
                                    <input type="text" name="bin_location" class="form-control"
                                    value="<?php echo e($product->bin_location); ?>" placeholder="A-16">
                                </div>
                            <div class="col-md-4 mb-3">
                                    <label class="form-label">Status</label>
                                <select name="status" class="form-select" required>
                                        <option value="active" <?php if($product->status == 'active'): echo 'selected'; endif; ?>>Active</option>
                                        <option value="inactive" <?php if($product->status == 'inactive'): echo 'selected'; endif; ?>>Inactive</option>
                                    </select>
                                </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Reorder Point</label>
                                <input type="number" name="reorder_point" class="form-control"
                                    value="<?php echo e($product->reorder_level); ?>" placeholder="5" min="0">
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-check form-switch h-100" style="padding-top: 18px;">
                                        <input class="form-check-input" type="checkbox" name="allow_negative"
                                            id="edit_allow_negative_<?php echo e($product->id); ?>" value="1"
                                            <?php echo e($product->allow_negative ? 'checked' : ''); ?>>
                                    <label class="form-check-label" for="edit_allow_negative_<?php echo e($product->id); ?>">
                                            <strong>Allow Negative Sale</strong>
                                        <br><small class="text-muted">Permit sales even when stock is zero or
                                            negative</small>
                                        </label>
                                    </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-check form-switch h-100" style="padding-top: 18px;">
                                        <input class="form-check-input" type="checkbox" name="special_order"
                                            id="edit_special_order_<?php echo e($product->id); ?>" value="1"    
                                            <?php echo e($product->special_order ? 'checked' : ''); ?>>
                                        <label class="form-check-label" for="edit_special_order_<?php echo e($product->id); ?>">
                                            <strong>Special Order Only</strong>
                                        <br><small class="text-muted">Mark this product as special order item</small>
                                        </label>
                                </div>
                                </div>
                            </div>
                        </div>

                    
                    <div class="mb-4">
                        <h6 class="border-bottom pb-2 mb-3">
                            <i class="bi bi-car-front me-2"></i>Vehicle Fitment
                        </h6>
                        <div class="alert alert-info alert-sm">
                            <small><i class="bi bi-info-circle me-1"></i>Specify which vehicles this part fits
                                (optional, multiple allowed)</small>
                        </div>
                            <div id="edit-fitments-container-<?php echo e($product->id); ?>">
                                <?php $__currentLoopData = $product->fitments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $fit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="row mb-2 fitment-row border p-2 rounded bg-light align-items-center">
                                        <div class="col-md-3">
                                        <label class="form-label mb-1 small text-muted">Make</label>
                                            <select name="fitments[<?php echo e($i); ?>][make_id]"
                                            class="form-select form-select-sm select2-fitment-make-<?php echo e($product->id); ?>">
                                                <option value="">Select Make</option>
                                                <?php $__currentLoopData = $makes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $make): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($make->id); ?>" <?php if($fit->make_id == $make->id): echo 'selected'; endif; ?>>
                                                        <?php echo e($make->name); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                    <div class="col-md-2">
                                        <label class="form-label mb-1 small text-muted">Model</label>
                                            <select name="fitments[<?php echo e($i); ?>][model_id]"
                                            class="form-select form-select-sm select2-fitment-model-<?php echo e($product->id); ?>">
                                                <option value="">Select Model</option>
                                                <?php $__currentLoopData = $models; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $model): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($model->id); ?>" <?php if($fit->model_id == $model->id): echo 'selected'; endif; ?>>
                                                        <?php echo e($model->name); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                        <label class="form-label mb-1 small text-muted">Engine</label>
                                            <select name="fitments[<?php echo e($i); ?>][engine_id]"
                                            class="form-select form-select-sm select2-fitment-engine-<?php echo e($product->id); ?>">
                                            <option value="">Optional</option>
                                                <?php $__currentLoopData = $engines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $engine): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($engine->id); ?>" <?php if($fit->engine_id == $engine->id): echo 'selected'; endif; ?>>
                                                        <?php echo e($engine->code); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                        <label class="form-label mb-1 small text-muted">Year From</label>
                                        <select name="fitments[<?php echo e($i); ?>][year_start]"
                                            class="form-select form-select-sm select2-fitment-year-start-<?php echo e($product->id); ?>">
                                            <option value="">From</option>
                                            <?php
                                                $currentYear = date('Y');
                                                for ($year = $currentYear + 2; $year >= 1980; $year--) {
                                                    $selected = $fit->year_start == $year ? 'selected' : '';
                                                    echo "<option value=\"{$year}\" {$selected}>{$year}</option>";
                                                }
                                            ?>
                                        </select>
                                        </div>
                                    <div class="col-md-2">
                                        <label class="form-label mb-1 small text-muted">Year To</label>
                                        <select name="fitments[<?php echo e($i); ?>][year_end]"
                                            class="form-select form-select-sm select2-fitment-year-end-<?php echo e($product->id); ?>">
                                            <option value="">To</option>
                                            <?php
                                                $currentYear = date('Y');
                                                for ($year = $currentYear + 2; $year >= 1980; $year--) {
                                                    $selected = $fit->year_end == $year ? 'selected' : '';
                                                    echo "<option value=\"{$year}\" {$selected}>{$year}</option>";
                                                }
                                            ?>
                                        </select>
                                        </div>
                                        <div class="col-md-1">
                                        <label class="form-label mb-1 small text-muted">&nbsp;</label>
                                            <button type="button"
                                            class="btn btn-sm btn-outline-danger w-100 removeFitmentBtn"
                                            title="Remove this fitment">
                                            <i class="bi bi-trash3"></i>
                                            </button>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <div class="mt-3">
                            <button type="button" class="btn btn-outline-primary"
                                id="editAddFitmentBtn-<?php echo e($product->id); ?>">
                                <i class="bi bi-plus-circle me-1"></i> Add Fitment
                            </button>
                        </div>
                    </div>

                    
                    <div class="mb-4">
                        <h6 class="border-bottom pb-2 mb-3">
                            <i class="bi bi-link-45deg me-2"></i>References & Additional Info
                        </h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">OE Numbers</label>
                                <input type="text" name="oe_numbers" class="form-control"
                                    id="edit_oe_numbers_<?php echo e($product->id); ?>" value='<?php echo json_encode($product->oeNumbers->pluck('oe_number'), 15, 512) ?>'
                                    placeholder="Press Enter to add">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Cross References</label>
                                <input type="text" name="cross_refs" class="form-control"
                                    id="edit_cross_refs_<?php echo e($product->id); ?>" value='<?php echo json_encode($product->crossRefs->pluck('cross_ref'), 15, 512) ?>'
                                    placeholder="Press Enter to add">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Product Images</label>
                                <?php if($product->images && $product->images->count() > 0): ?>
                                    <div class="d-flex gap-2 mb-2 flex-wrap">
                                        <?php $__currentLoopData = $product->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="position-relative">
                                                <img src="<?php echo e($img->url); ?>" class="rounded"
                                                    style="width: 80px; height: 80px; object-fit: cover;"
                                                    alt="Product">
                                                <button type="button"
                                                    class="btn btn-sm btn-danger position-absolute top-0 end-0"
                                                    onclick="deleteImage(<?php echo e($img->id); ?>)"
                                                    style="padding: 2px 6px;">
                                                    <i class="bi bi-x"></i>
                                                </button>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                <?php endif; ?>
                                <input type="file" name="images[]" class="form-control" multiple
                                    accept="image/*">
                                <small class="text-muted">Allowed: JPG, JPEG, PNG, WEBP (Max 2MB each)</small>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Notes</label>
                                <textarea name="notes" class="form-control" rows="3" placeholder="Additional notes about this product"><?php echo e($product->notes); ?></textarea>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>Update Product
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>


<template id="editFitmentRowTemplate-<?php echo e($product->id); ?>">
    <div class="row mb-2 fitment-row border p-2 rounded bg-light align-items-center">
        <div class="col-md-3">
            <label class="form-label mb-1 small text-muted">Make</label>
            <select name="fitments[__INDEX__][make_id]"
                class="form-select form-select-sm select2-fitment-make-<?php echo e($product->id); ?>">
                <option value="">Select Make</option>
                <?php $__currentLoopData = $makes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $make): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($make->id); ?>"><?php echo e($make->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label mb-1 small text-muted">Model</label>
            <select name="fitments[__INDEX__][model_id]"
                class="form-select form-select-sm select2-fitment-model-<?php echo e($product->id); ?>">
                <option value="">Select Model</option>
                <?php $__currentLoopData = $models; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $model): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($model->id); ?>"><?php echo e($model->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label mb-1 small text-muted">Engine</label>
            <select name="fitments[__INDEX__][engine_id]"
                class="form-select form-select-sm select2-fitment-engine-<?php echo e($product->id); ?>">
                <option value="">Optional</option>
                <?php $__currentLoopData = $engines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $engine): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($engine->id); ?>"><?php echo e($engine->code); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label mb-1 small text-muted">Year From</label>
            <select name="fitments[__INDEX__][year_start]"
                class="form-select form-select-sm select2-fitment-year-start-<?php echo e($product->id); ?>">
                <option value="">From</option>
                <?php
                    $currentYear = date('Y');
                    for ($year = $currentYear + 2; $year >= 1980; $year--) {
                        echo "<option value=\"{$year}\">{$year}</option>";
                    }
                ?>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label mb-1 small text-muted">Year To</label>
            <select name="fitments[__INDEX__][year_end]"
                class="form-select form-select-sm select2-fitment-year-end-<?php echo e($product->id); ?>">
                <option value="">To</option>
                <?php
                    $currentYear = date('Y');
                    for ($year = $currentYear + 2; $year >= 1980; $year--) {
                        echo "<option value=\"{$year}\">{$year}</option>";
                    }
                ?>
            </select>
        </div>
        <div class="col-md-1">
            <label class="form-label mb-1 small text-muted">&nbsp;</label>
            <button type="button" class="btn btn-sm btn-outline-danger w-100 removeFitmentBtn"
                title="Remove this fitment">
                <i class="bi bi-trash3"></i>
            </button>
        </div>
    </div>
</template>

<?php $__env->startPush('scripts'); ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let editFitmentIndex<?php echo e($product->id); ?> = <?php echo e($product->fitments->count()); ?>;
            let editOeTagify<?php echo e($product->id); ?>, editCrossTagify<?php echo e($product->id); ?>;

            // Initialize Select2 for edit modal
            function initEditSelect2<?php echo e($product->id); ?>() {
                $('.select2-edit-brand-<?php echo e($product->id); ?>, .select2-edit-category-<?php echo e($product->id); ?>, .select2-edit-subcategory-<?php echo e($product->id); ?>, .select2-edit-suppliers-<?php echo e($product->id); ?>')
                    .select2({
                        dropdownParent: $('#editProductModal-<?php echo e($product->id); ?>'),
                        placeholder: 'Select option',
                        allowClear: true,
                        width: '100%'
                    });

                // Auto-fill supplier code when supplier is selected
                $('.select2-edit-suppliers-<?php echo e($product->id); ?>').on('change', function() {
                    var supplierId = $(this).val();
                    if (supplierId) {
                        var suppliers = <?php echo json_encode($suppliers, 15, 512) ?>;
                        var selectedSupplier = suppliers.find(s => s.id == supplierId);
                        if (selectedSupplier && selectedSupplier.supplier_code) {
                            $('#editProductModal-<?php echo e($product->id); ?> input[name="supplier_code"]').val(selectedSupplier.supplier_code);
                        }
                    } else {
                        $('#editProductModal-<?php echo e($product->id); ?> input[name="supplier_code"]').val('');
                    }
                });

                // Initialize existing fitment selects
                $('.select2-fitment-make-<?php echo e($product->id); ?>, .select2-fitment-model-<?php echo e($product->id); ?>, .select2-fitment-engine-<?php echo e($product->id); ?>, .select2-fitment-year-start-<?php echo e($product->id); ?>, .select2-fitment-year-end-<?php echo e($product->id); ?>')
                    .each(function() {
                        if (!$(this).hasClass('select2-hidden-accessible')) {
                            const $element = $(this);
                            const isYearField = $element.hasClass(
                                'select2-fitment-year-start-<?php echo e($product->id); ?>') || $element.hasClass(
                                'select2-fitment-year-end-<?php echo e($product->id); ?>');

                            if (isYearField) {
                                $element.select2({
                                    dropdownParent: $('#editProductModal-<?php echo e($product->id); ?>'),
                                    width: '100%',
                                    placeholder: $element.find('option:first').text(),
                                    allowClear: true
                                });
                            } else {
                                $element.select2({
                                    dropdownParent: $('#editProductModal-<?php echo e($product->id); ?>'),
                                    width: '100%',
                                    placeholder: $element.find('option:first').text(),
                                    allowClear: true,
                                    tags: true,
                                    createTag: function(params) {
                                        const term = $.trim(params.term);
                                        if (term === '') return null;
                                        return {
                                            id: 'new:' + term,
                                            text: term + ' (Press Enter to add)',
                                            newTag: true
                                        };
                                    }
                                }).on('select2:select', function(e) {
                                    const data = e.params.data;
                                    if (data.newTag) {
                                        const newName = data.text.replace(' (Press Enter to add)', '');
                                        const $select = $(this);
                                        let endpoint = '';

                                        if ($select.hasClass(
                                                'select2-fitment-make-<?php echo e($product->id); ?>')) {
                                            endpoint = '<?php echo e(route('car-makes.quick-add')); ?>';
                                        } else if ($select.hasClass(
                                                'select2-fitment-model-<?php echo e($product->id); ?>')) {
                                            endpoint = '<?php echo e(route('car-models.quick-add')); ?>';
                                        } else if ($select.hasClass(
                                                'select2-fitment-engine-<?php echo e($product->id); ?>')) {
                                            endpoint = '<?php echo e(route('car-engines.quick-add')); ?>';
                                        }

                                        $.ajax({
                                            url: endpoint,
                                            method: 'POST',
                                            data: {
                                                name: newName,
                                                _token: '<?php echo e(csrf_token()); ?>'
                                            },
                                            success: function(response) {
                                                if (response.success) {
                                                    const newOption = new Option(response
                                                        .data.name, response.data.id,
                                                        true, true);
                                                    $select.append(newOption);
                                                    $select.val(response.data.id).trigger(
                                                        'change');
                                                    toastr.success(response.data.name +
                                                        ' added successfully!');
                                                }
                                            },
                                            error: function(xhr) {
                                                toastr.error(
                                                    'Failed to add. Please try again.');
                                                $select.val('').trigger('change');
                                            }
                                        });
                                    }
                                });
                            }
                        }
                    });
            }

            // Initialize Tagify for OE Numbers and Cross Refs
            function initEditTagify<?php echo e($product->id); ?>() {
                const oeInput = document.querySelector('#edit_oe_numbers_<?php echo e($product->id); ?>');
                const crossInput = document.querySelector('#edit_cross_refs_<?php echo e($product->id); ?>');

                if (oeInput && !editOeTagify<?php echo e($product->id); ?>) {
                    editOeTagify<?php echo e($product->id); ?> = new Tagify(oeInput, {
                        delimiters: ",| ",
                        placeholder: "Type and press Enter",
                        dropdown: {
                            enabled: 0
                        }
                    });
                }

                if (crossInput && !editCrossTagify<?php echo e($product->id); ?>) {
                    editCrossTagify<?php echo e($product->id); ?> = new Tagify(crossInput, {
                        delimiters: ",| ",
                        placeholder: "Type and press Enter",
                        dropdown: {
                            enabled: 0
                        }
                    });
                }
            }

            // Add Fitment Row
            document.getElementById('editAddFitmentBtn-<?php echo e($product->id); ?>').addEventListener('click', function() {
                const template = document.getElementById('editFitmentRowTemplate-<?php echo e($product->id); ?>')
                    .innerHTML;
                const html = template.replace(/__INDEX__/g, editFitmentIndex<?php echo e($product->id); ?>);
                document.getElementById('edit-fitments-container-<?php echo e($product->id); ?>').insertAdjacentHTML(
                    'beforeend', html);

                // Initialize Select2 for newly added fitment selects
                $(`.select2-fitment-make-<?php echo e($product->id); ?>, .select2-fitment-model-<?php echo e($product->id); ?>, .select2-fitment-engine-<?php echo e($product->id); ?>, .select2-fitment-year-start-<?php echo e($product->id); ?>, .select2-fitment-year-end-<?php echo e($product->id); ?>`)
                    .each(function() {
                        if (!$(this).hasClass('select2-hidden-accessible')) {
                            const $element = $(this);
                            const isYearField = $element.hasClass(
                                    'select2-fitment-year-start-<?php echo e($product->id); ?>') || $element
                                .hasClass('select2-fitment-year-end-<?php echo e($product->id); ?>');

                            if (isYearField) {
                                $element.select2({
                                    dropdownParent: $(
                                        '#editProductModal-<?php echo e($product->id); ?>'),
                                    width: '100%',
                                    placeholder: $element.find('option:first').text(),
                                    allowClear: true
                                });
                            } else {
                                $element.select2({
                                    dropdownParent: $(
                                        '#editProductModal-<?php echo e($product->id); ?>'),
                                    width: '100%',
                                    placeholder: $element.find('option:first').text(),
                                    allowClear: true,
                                    tags: true,
                                    createTag: function(params) {
                                        const term = $.trim(params.term);
                                        if (term === '') return null;
                                        return {
                                            id: 'new:' + term,
                                            text: term + ' (Press Enter to add)',
                                            newTag: true
                                        };
                                    }
                                }).on('select2:select', function(e) {
                                    const data = e.params.data;
                                    if (data.newTag) {
                                        const newName = data.text.replace(
                                            ' (Press Enter to add)', '');
                                        const $select = $(this);
                                        let endpoint = '';

                                        if ($select.hasClass(
                                                'select2-fitment-make-<?php echo e($product->id); ?>')) {
                                            endpoint = '<?php echo e(route('car-makes.quick-add')); ?>';
                                        } else if ($select.hasClass(
                                                'select2-fitment-model-<?php echo e($product->id); ?>'
                                                )) {
                                            endpoint = '<?php echo e(route('car-models.quick-add')); ?>';
                                        } else if ($select.hasClass(
                                                'select2-fitment-engine-<?php echo e($product->id); ?>'
                                                )) {
                                            endpoint = '<?php echo e(route('car-engines.quick-add')); ?>';
                                        }

                                        $.ajax({
                                            url: endpoint,
                                            method: 'POST',
                                            data: {
                                                name: newName,
                                                _token: '<?php echo e(csrf_token()); ?>'
                                            },
                                            success: function(response) {
                                                if (response.success) {
                                                    const newOption = new Option(
                                                        response.data.name,
                                                        response.data.id, true,
                                                        true);
                                                    $select.append(newOption);
                                                    $select.val(response.data.id)
                                                        .trigger('change');
                                                    toastr.success(response.data
                                                        .name +
                                                        ' added successfully!');
                                                }
                                            },
                                            error: function(xhr) {
                                                toastr.error(
                                                    'Failed to add. Please try again.'
                                                    );
                                                $select.val('').trigger('change');
                                            }
                                        });
                                    }
                                });
                            }
                        }
                    });

                editFitmentIndex<?php echo e($product->id); ?>++;
            });

            // Remove Fitment Row
            document.addEventListener('click', function(e) {
                if (e.target.closest('.removeFitmentBtn')) {
                    e.target.closest('.fitment-row').remove();
                }
            });

            // Modal shown event
            $('#editProductModal-<?php echo e($product->id); ?>').on('shown.bs.modal', function() {
                initEditSelect2<?php echo e($product->id); ?>();
                initEditTagify<?php echo e($product->id); ?>();
            });

            // Bin location validation
            const binInput = document.querySelector(
                '#editProductModal-<?php echo e($product->id); ?> input[name="bin_location"]');
            if (binInput) {
                binInput.addEventListener('blur', function() {
                    const value = this.value.trim().toUpperCase();
                    if (value && !/^[A-Z]-\d+$/.test(value)) {
                        this.classList.add('is-invalid');
                        if (!this.nextElementSibling || !this.nextElementSibling.classList.contains(
                                'invalid-feedback')) {
                            const feedback = document.createElement('div');
                            feedback.className = 'invalid-feedback';
                            feedback.textContent = 'Format should be: Letter-Number (e.g., A-16)';
                            this.parentNode.appendChild(feedback);
                        }
                    } else {
                        this.classList.remove('is-invalid');
                        this.value = value;
                    }
                });
            }
        });
    </script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\xampp\htdocs\MMP\resources\views/products/_edit_modal.blade.php ENDPATH**/ ?>