<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div>
            <h4 class="page-title fw-semibold fs-18 mb-0"><?php echo e($stockCount->count_number); ?> - <?php echo e($stockCount->count_name); ?></h4>
            <p class="fs-13 text-muted mb-0">Count Status: <span class="badge bg-<?php echo e($stockCount->status === 'draft' ? 'secondary' : 'primary'); ?>"><?php echo e(ucfirst($stockCount->status)); ?></span></p>
        </div>
        <div class="d-flex gap-2">
            <?php if($stockCount->isDraft()): ?>
                <button type="button" class="btn btn-primary" onclick="startCounting()">
                    <i class="ri-play-line me-1"></i> Start Counting
                </button>
            <?php endif; ?>
            <?php if($stockCount->isInProgress()): ?>
                <button type="button" class="btn btn-success" onclick="completeCounting()">
                    <i class="ri-check-line me-1"></i> Complete & Review
                </button>
            <?php endif; ?>
            <a href="<?php echo e(route('stock-counts.index')); ?>" class="btn btn-light">
                <i class="ri-arrow-left-line me-1"></i> Back
            </a>
        </div>
    </div>

    <!-- Progress Summary -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card custom-card">
                <div class="card-body">
                    <span class="d-block mb-2 text-muted">Progress</span>
                    <h3 class="mb-0"><?php echo e($stockCount->counted_products); ?>/<?php echo e($stockCount->total_products); ?></h3>
                    <div class="progress mt-2" style="height: 8px;">
                        <div class="progress-bar bg-primary" style="width: <?php echo e($stockCount->progress_percentage); ?>%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card custom-card">
                <div class="card-body">
                    <span class="d-block mb-2 text-muted">Uncounted</span>
                    <h3 class="mb-0"><?php echo e($stockCount->total_products - $stockCount->counted_products); ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card custom-card">
                <div class="card-body">
                    <span class="d-block mb-2 text-muted">Variances</span>
                    <h3 class="mb-0 text-warning"><?php echo e($stockCount->products_with_variance); ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card custom-card">
                <div class="card-body">
                    <span class="d-block mb-2 text-muted">Total Variance Value</span>
                    <h3 class="mb-0 <?php echo e($stockCount->total_variance_value >= 0 ? 'text-success' : 'text-danger'); ?>">
                        R <?php echo e(number_format(abs($stockCount->total_variance_value), 2)); ?>

                        <i class="ri-arrow-<?php echo e($stockCount->total_variance_value >= 0 ? 'up' : 'down'); ?>-line fs-16"></i>
                    </h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Scanner -->
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card custom-card">
                <div class="card-body">
                    <div class="row align-items-end g-3">
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="ri-scan-line me-1"></i> Search / Scan Product (Press F2 to focus)
                            </label>
                            <input type="text" 
                                   id="searchInput" 
                                   class="form-control form-control-lg" 
                                   placeholder="Search by SKU, Name, Barcode, OE Number, Supplier Code..." 
                                   autofocus>
                            <small class="text-muted">
                                <i class="ri-information-line"></i> Searches: SKU | Name | Barcode | OE# | Supplier Code | Brand | Category
                            </small>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Filter View</label>
                            <select id="filterSelect" class="form-select">
                                <option value="all">All Items</option>
                                <option value="uncounted">Uncounted Only</option>
                                <option value="counted">Counted Only</option>
                                <option value="variance">With Variance</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-info w-100" onclick="refreshList()">
                                <i class="ri-refresh-line me-1"></i> Refresh
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Count Items Table -->
    <div class="row">
        <div class="col-md-12">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Products to Count</div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                        <table class="table table-hover mb-0">
                            <thead class="sticky-top bg-white">
                                <tr>
                                    <th width="80">Status</th>
                                    <th width="100">SKU</th>
                                    <th>Product Details</th>
                                    <th width="100">System Qty</th>
                                    <th width="150">Counted Qty</th>
                                    <th width="100">Variance</th>
                                    <th width="80">Action</th>
                                </tr>
                            </thead>
                            <tbody id="itemsTableBody">
                                <?php $__currentLoopData = $stockCount->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr id="row-<?php echo e($item->id); ?>" 
                                    class="<?php echo e($item->is_counted ? 'table-success' : ''); ?>"
                                    data-sku="<?php echo e($item->product->sku ?? ''); ?>"
                                    data-name="<?php echo e($item->product->name ?? ''); ?>"
                                    data-barcode="<?php echo e($item->product->barcode_primary ?? ''); ?>"
                                    data-supplier-code="<?php echo e($item->product->supplier_code ?? ''); ?>"
                                    data-brand="<?php echo e($item->product->brand->name ?? ''); ?>"
                                    data-category="<?php echo e($item->product->category->name ?? ''); ?>"
                                    data-oe-numbers="<?php echo e($item->product->oeNumbers->pluck('oe_number')->implode(',') ?? ''); ?>">
                                    <td>
                                        <?php if($item->is_counted): ?>
                                            <span class="badge bg-success"><i class="ri-check-line"></i> Counted</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary"><i class="ri-time-line"></i> Pending</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="fw-semibold"><?php echo e($item->product->sku); ?></span>
                                    </td>
                                    <td>
                                        <div class="fw-semibold"><?php echo e($item->product->name); ?></div>
                                        <div class="d-flex flex-wrap gap-1 mt-1">
                                        <?php if($item->product->barcode_primary): ?>
                                                <small class="badge bg-light text-dark">
                                                    <i class="ri-barcode-line"></i> <?php echo e($item->product->barcode_primary); ?>

                                                </small>
                                            <?php endif; ?>
                                            <?php if($item->product->supplier_code): ?>
                                                <small class="badge bg-warning-transparent">
                                                    <i class="ri-building-line"></i> <?php echo e($item->product->supplier_code); ?>

                                                </small>
                                            <?php endif; ?>
                                            <?php if($item->product->brand): ?>
                                                <small class="badge bg-primary-transparent">
                                                    <?php echo e($item->product->brand->name); ?>

                                                </small>
                                            <?php endif; ?>
                                        </div>
                                        <?php if($item->product->oeNumbers && $item->product->oeNumbers->count() > 0): ?>
                                            <small class="text-muted d-block mt-1">
                                                <i class="ri-hashtag"></i> OE: <?php echo e($item->product->oeNumbers->take(3)->pluck('oe_number')->implode(', ')); ?>

                                                <?php if($item->product->oeNumbers->count() > 3): ?>
                                                    <span class="badge bg-info-transparent">+<?php echo e($item->product->oeNumbers->count() - 3); ?> more</span>
                                                <?php endif; ?>
                                            </small>
                                        <?php endif; ?>
                                        <?php if($item->product->bin_location): ?>
                                            <small class="text-muted">
                                                <i class="ri-map-pin-line"></i> Bin: <?php echo e($item->product->bin_location); ?>

                                            </small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-info-transparent"><?php echo e(number_format($item->system_qty, 2)); ?></span>
                                    </td>
                                    <td>
                                        <div class="input-group input-group-sm" style="max-width: 200px;">
                                            <button type="button" class="btn btn-outline-secondary" onclick="decrementQty(<?php echo e($item->id); ?>)" title="Decrease (-1)">
                                                <i class="ri-subtract-line"></i>
                                            </button>
                                            <input type="number" 
                                                   class="form-control form-control-sm count-input text-center fw-bold" 
                                                   data-item-id="<?php echo e($item->id); ?>"
                                                   data-system-qty="<?php echo e($item->system_qty); ?>"
                                                   value="<?php echo e($item->counted_qty); ?>"
                                                   step="1" 
                                                   min="0"
                                                   placeholder="0"
                                                   style="font-size: 16px;">
                                            <button type="button" class="btn btn-outline-secondary" onclick="incrementQty(<?php echo e($item->id); ?>)" title="Increase (+1)">
                                                <i class="ri-add-line"></i>
                                            </button>
                                        </div>
                                        <small class="text-muted d-block mt-1">
                                            <i class="ri-information-line"></i> System: <strong class="text-primary"><?php echo e($item->system_qty); ?></strong>
                                        </small>
                                    </td>
                                    <td>
                                        <span id="variance-<?php echo e($item->id); ?>" class="fw-semibold <?php echo e($item->variance_qty > 0 ? 'text-success' : ($item->variance_qty < 0 ? 'text-danger' : 'text-muted')); ?>">
                                            <?php if($item->is_counted): ?>
                                                <?php echo e($item->variance_qty > 0 ? '+' : ''); ?><?php echo e(number_format($item->variance_qty, 2)); ?>

                                            <?php else: ?>
                                                -
                                            <?php endif; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button type="button" class="btn btn-success save-btn" data-item-id="<?php echo e($item->id); ?>" onclick="saveItem(<?php echo e($item->id); ?>)" title="Save Count">
                                                <i class="ri-save-line me-1"></i> Save
                                            </button>
                                            <button type="button" class="btn btn-outline-info" onclick="setSystemQty(<?php echo e($item->id); ?>)" title="Copy System Qty">
                                                <i class="ri-file-copy-line"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Global variables
const countId = <?php echo e($stockCount->id); ?>;
let scanTimeout;
let scanBuffer = '';
const SCAN_DELAY = 100; // ms - typical scanner input speed

// Success sound for scanner
function playSuccessSound() {
    try {
        // Simple beep sound using Web Audio API
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        const oscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();
        
        oscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);
        
        oscillator.frequency.value = 800;
        oscillator.type = 'sine';
        
        gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
        gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.1);
        
        oscillator.start(audioContext.currentTime);
        oscillator.stop(audioContext.currentTime + 0.1);
    } catch (e) {
        // Ignore audio errors
        console.log('Audio not available');
    }
}

// Error sound
function playErrorSound() {
    try {
        // Simple error beep using Web Audio API
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        const oscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();
        
        oscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);
        
        oscillator.frequency.value = 400;
        oscillator.type = 'sawtooth';
        
        gainNode.gain.setValueAtTime(0.2, audioContext.currentTime);
        gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.2);
        
        oscillator.start(audioContext.currentTime);
        oscillator.stop(audioContext.currentTime + 0.2);
    } catch (e) {
        console.log('Audio not available');
    }
}

// F2 Hotkey to focus search
document.addEventListener('keydown', function(e) {
    if (e.key === 'F2') {
        e.preventDefault();
        document.getElementById('searchInput').focus();
        document.getElementById('searchInput').select();
    }
});

// Enhanced search with scanner detection - Searches ALL fields
document.getElementById('searchInput').addEventListener('input', function(e) {
    const search = this.value.trim().toLowerCase();
    clearTimeout(scanTimeout);
    
    scanBuffer = search;
    
    // Comprehensive search through all product fields
    document.querySelectorAll('#itemsTableBody tr').forEach(row => {
        const sku = (row.dataset.sku || '').toLowerCase();
        const name = (row.dataset.name || '').toLowerCase();
        const barcode = (row.dataset.barcode || '').toLowerCase();
        const supplierCode = (row.dataset.supplierCode || '').toLowerCase();
        const brand = (row.dataset.brand || '').toLowerCase();
        const category = (row.dataset.category || '').toLowerCase();
        const oeNumbers = (row.dataset.oeNumbers || '').toLowerCase();
        
        // Check if search matches ANY field
        const matches = sku.includes(search) || 
                       name.includes(search) || 
                       barcode.includes(search) || 
                       supplierCode.includes(search) || 
                       brand.includes(search) || 
                       category.includes(search) || 
                       oeNumbers.includes(search);
        
        row.style.display = matches ? '' : 'none';
    });
    
    // Detect scanner input (fast typing)
    scanTimeout = setTimeout(() => {
        if (scanBuffer && scanBuffer.length > 3) {
            handleScan(scanBuffer);
        }
    }, SCAN_DELAY);
});

// Handle barcode scan - Enhanced to search all fields
function handleScan(barcode) {
    const search = barcode.toLowerCase();
    let matchedRow = null;
    let exactMatch = null;
    
    // Find matching product by ANY field
    document.querySelectorAll('#itemsTableBody tr').forEach(row => {
        const sku = (row.dataset.sku || '').toLowerCase();
        const name = (row.dataset.name || '').toLowerCase();
        const barcodeField = (row.dataset.barcode || '').toLowerCase();
        const supplierCode = (row.dataset.supplierCode || '').toLowerCase();
        const oeNumbers = (row.dataset.oeNumbers || '').toLowerCase();
        
        // Priority matching: Exact match first, then contains
        if (sku === search || barcodeField === search || supplierCode === search) {
            exactMatch = row;
        } else if (sku.includes(search) || 
                   name.includes(search) || 
                   barcodeField.includes(search) || 
                   supplierCode.includes(search) || 
                   oeNumbers.includes(search)) {
            if (!matchedRow) {
                matchedRow = row;
            }
        }
    });
    
    // Use exact match if found, otherwise use partial match
    matchedRow = exactMatch || matchedRow;
    
    if (matchedRow) {
        // Scroll to item
        matchedRow.scrollIntoView({ behavior: 'smooth', block: 'center' });
        
        // Highlight the row
        matchedRow.classList.add('table-warning');
        setTimeout(() => matchedRow.classList.remove('table-warning'), 2000);
        
        // Auto-focus and increment the count input
        const input = matchedRow.querySelector('.count-input');
        if (input) {
            const currentValue = parseFloat(input.value) || 0;
            input.value = currentValue + 1;
            input.focus();
            input.select();
            
            // Visual feedback
            input.classList.add('border-success', 'border-3');
            setTimeout(() => {
                input.classList.remove('border-success', 'border-3');
            }, 1000);
            
            // Play success sound
            playSuccessSound();
            
            // Auto-save after 1 second if no more scans
            clearTimeout(window.autoSaveTimeout);
            window.autoSaveTimeout = setTimeout(() => {
                const itemId = input.getAttribute('data-item-id');
                saveItem(itemId, true); // Silent save
            }, 1500);
        }
        
        // Clear search after successful scan
        setTimeout(() => {
            document.getElementById('searchInput').value = '';
            document.getElementById('searchInput').focus();
        }, 300);
    } else {
        // No match found
        playErrorSound();
        document.getElementById('searchInput').classList.add('border-danger');
        setTimeout(() => {
            document.getElementById('searchInput').classList.remove('border-danger');
        }, 1000);
    }
    
    scanBuffer = '';
}

// Filter functionality
document.getElementById('filterSelect').addEventListener('change', function() {
    const filter = this.value;
    document.querySelectorAll('#itemsTableBody tr').forEach(row => {
        const isCounted = row.classList.contains('table-success');
        const hasVariance = row.querySelector('[id^="variance-"]').textContent.trim() !== '-' && 
                           parseFloat(row.querySelector('[id^="variance-"]').textContent) !== 0;
        
        let show = true;
        if (filter === 'uncounted') show = !isCounted;
        else if (filter === 'counted') show = isCounted;
        else if (filter === 'variance') show = hasVariance;
        
        row.style.display = show ? '' : 'none';
    });
});

// Save item count - Global function
window.saveItem = function(itemId, silent = false) {
    const input = document.querySelector(`.count-input[data-item-id="${itemId}"]`);
    const countedQty = input.value;

    if (!countedQty || countedQty < 0) {
        if (!silent) alert('Please enter a valid quantity');
        return;
    }

    fetch('<?php echo e(route("stock-counts.update-item", ["countId" => ":countId", "itemId" => ":itemId"])); ?>'.replace(':countId', countId).replace(':itemId', itemId), {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
        },
        body: JSON.stringify({ counted_qty: countedQty })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update row
            const row = document.getElementById(`row-${itemId}`);
            row.classList.add('table-success');
            
            // Update status badge
            const statusTd = row.querySelector('td:first-child');
            statusTd.innerHTML = '<span class="badge bg-success"><i class="ri-check-line"></i> Counted</span>';
            
            // Update variance display
            const varianceSpan = document.getElementById(`variance-${itemId}`);
            const variance = data.item.variance_qty;
            varianceSpan.textContent = (variance > 0 ? '+' : '') + parseFloat(variance).toFixed(2);
            varianceSpan.className = 'fw-semibold ' + (variance > 0 ? 'text-success' : (variance < 0 ? 'text-danger' : 'text-muted'));
            
            // Update progress summary without reload
            if (!silent) {
            setTimeout(() => location.reload(), 500);
            } else {
                // Update counts in header (fetch updated stats)
                fetch('<?php echo e(route("stock-counts.stats", ":id")); ?>'.replace(':id', countId))
                    .then(r => r.json())
                    .then(stats => {
                        // Update progress display
                        document.querySelector('.col-md-3:nth-child(1) h3').textContent = 
                            `${stats.counted_products}/${stats.total_products}`;
                        document.querySelector('.progress-bar').style.width = `${stats.progress_percentage}%`;
                        
                        document.querySelector('.col-md-3:nth-child(2) h3').textContent = 
                            stats.total_products - stats.counted_products;
                        
                        document.querySelector('.col-md-3:nth-child(3) h3').textContent = 
                            stats.products_with_variance;
                        
                        const varianceElem = document.querySelector('.col-md-3:nth-child(4) h3');
                        const absVariance = Math.abs(stats.total_variance_value);
                        const icon = stats.total_variance_value >= 0 ? 'up' : 'down';
                        varianceElem.innerHTML = `R ${absVariance.toFixed(2)} <i class="ri-arrow-${icon}-line fs-16"></i>`;
                        varianceElem.className = `mb-0 ${stats.total_variance_value >= 0 ? 'text-success' : 'text-danger'}`;
                    });
            }
        } else {
            if (!silent) alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (!silent) alert('Error saving count');
    });
}

// Handle Enter key on inputs
document.querySelectorAll('.count-input').forEach(input => {
    input.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            const itemId = this.getAttribute('data-item-id');
            saveItem(itemId);
        }
    });
});

// Start counting - Global function
window.startCounting = function() {
    fetch('<?php echo e(route("stock-counts.start", ":id")); ?>'.replace(':id', countId), {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message);
        }
    });
}

// Complete counting - Global function
window.completeCounting = function() {
    fetch('<?php echo e(route("stock-counts.complete", ":id")); ?>'.replace(':id', countId), {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            const url = '<?php echo e(route("stock-counts.variance-report", ":id")); ?>'.replace(':id', countId);
            window.location.href = url;
        } else {
            alert(data.message);
        }
    });
}

// Refresh list - Global function
window.refreshList = function() {
    location.reload();
}

// Increment quantity - Global function
window.incrementQty = function(itemId) {
    const input = document.querySelector(`.count-input[data-item-id="${itemId}"]`);
    const currentVal = parseFloat(input.value) || 0;
    input.value = currentVal + 1;
    
    // Auto-highlight changed value
    input.classList.add('border-success');
    input.classList.add('bg-success-subtle');
    
    // Update variance preview
    updateVariancePreview(itemId);
}

// Decrement quantity - Global function
window.decrementQty = function(itemId) {
    const input = document.querySelector(`.count-input[data-item-id="${itemId}"]`);
    const currentVal = parseFloat(input.value) || 0;
    if (currentVal > 0) {
        input.value = currentVal - 1;
        
        // Auto-highlight changed value
        input.classList.add('border-warning');
        input.classList.add('bg-warning-subtle');
        
        // Update variance preview
        updateVariancePreview(itemId);
    }
}

// Set to system quantity - Global function
window.setSystemQty = function(itemId) {
    const input = document.querySelector(`.count-input[data-item-id="${itemId}"]`);
    const systemQty = input.getAttribute('data-system-qty');
    input.value = systemQty;
    
    // Highlight
    input.classList.add('border-info');
    input.classList.add('bg-info-subtle');
    
    // Update variance preview
    updateVariancePreview(itemId);
    
    // Show tooltip
    const btn = event.target.closest('button');
    if (btn) {
        const originalTitle = btn.getAttribute('title');
        btn.setAttribute('title', '✓ Copied!');
        setTimeout(() => {
            btn.setAttribute('title', originalTitle);
        }, 1000);
    }
}

// Update variance preview (live calculation)
function updateVariancePreview(itemId) {
    const input = document.querySelector(`.count-input[data-item-id="${itemId}"]`);
    const systemQty = parseFloat(input.getAttribute('data-system-qty')) || 0;
    const countedQty = parseFloat(input.value) || 0;
    const variance = countedQty - systemQty;
    
    const varianceSpan = document.getElementById(`variance-${itemId}`);
    if (varianceSpan) {
        varianceSpan.textContent = (variance > 0 ? '+' : '') + variance.toFixed(2);
        varianceSpan.className = 'fw-semibold ' + (variance > 0 ? 'text-success' : (variance < 0 ? 'text-danger' : 'text-muted'));
    }
}

// Add input event listener for live variance calculation
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.count-input').forEach(input => {
        input.addEventListener('input', function() {
            const itemId = this.getAttribute('data-item-id');
            updateVariancePreview(itemId);
        });
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\MMP\resources\views/stock-counts/count.blade.php ENDPATH**/ ?>