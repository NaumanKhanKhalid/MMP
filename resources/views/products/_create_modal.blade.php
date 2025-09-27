<div class="modal fade" id="createProductModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Add New Product</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="row">
            {{-- Basic Info --}}
            <div class="col-md-6 mb-3">
              <label>Product Name</label>
              <input type="text" name="name" class="form-control" required>
            </div>
            <div class="col-md-3 mb-3">
              <label>Brand</label>
              <select name="brand_id" class="form-select" required>
                <option value="">-- Select --</option>
                @foreach($brands as $brand)
                  <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-3 mb-3">
              <label>Category</label>
              <select name="category_id" class="form-select" required>
                <option value="">-- Select --</option>
                @foreach($categories as $cat)
                  <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
              </select>
            </div>

            {{-- SKU & Barcode --}}
            <div class="col-md-3 mb-3">
              <label>SKU</label>
              <input type="text" name="sku" class="form-control" placeholder="Auto or Manual">
            </div>
            <div class="col-md-3 mb-3">
              <label>Barcode</label>
              <input type="text" name="barcode" class="form-control" placeholder="Auto-generated if empty">
            </div>
            <div class="col-md-3 mb-3">
              <label>Unit</label>
              <select name="unit" class="form-select">
                <option value="PCS">PCS</option>
                <option value="SET">SET</option>
              </select>
            </div>
            <div class="col-md-3 mb-3">
              <label>Bin Location</label>
              <input type="text" name="bin_location" class="form-control" placeholder="e.g. A-16">
            </div>

            {{-- Pricing --}}
            <div class="col-md-4 mb-3">
              <label>Normal Price</label>
              <input type="number" step="0.01" name="price_normal" class="form-control">
            </div>
            <div class="col-md-4 mb-3">
              <label>Online Price</label>
              <input type="number" step="0.01" name="price_online" class="form-control">
            </div>
            <div class="col-md-4 mb-3">
              <label>Workshop Price</label>
              <input type="number" step="0.01" name="price_workshop" class="form-control">
            </div>

            {{-- Stock Controls --}}
            <div class="col-md-3 mb-3">
              <label>Reorder Level</label>
              <input type="number" name="reorder_level" class="form-control">
            </div>
            <div class="col-md-3 mb-3">
              <label>Allow Negative Sale</label>
              <select name="allow_negative_sale" class="form-select">
                <option value="1">Yes</option>
                <option value="0" selected>No</option>
              </select>
            </div>
            <div class="col-md-3 mb-3">
              <label>Special Order Only</label>
              <select name="special_order" class="form-select">
                <option value="1">Yes</option>
                <option value="0" selected>No</option>
              </select>
            </div>
          </div>

          {{-- Vehicle Fitment (Dynamic Add Rows) --}}
          <hr>
          <h6>Vehicle Fitments</h6>
          <div id="fitmentContainer"></div>
          <button type="button" class="btn btn-sm btn-outline-primary" id="addFitmentBtn">+ Add Fitment</button>
          <input type="hidden" name="fitments_json" id="fitmentsJson">

          {{-- OE Numbers --}}
          <div class="mt-4">
            <label>OE Numbers</label>
            <textarea name="oe_numbers" class="form-control" placeholder="Comma separated"></textarea>
          </div>

          {{-- Cross References --}}
          <div class="mt-3">
            <label>Cross References</label>
            <textarea name="cross_refs" class="form-control" placeholder="Comma separated"></textarea>
          </div>

          {{-- Images --}}
          <div class="mt-3">
            <label>Product Images (up to 3)</label>
            <input type="file" name="images[]" class="form-control" multiple accept="image/*">
          </div>

          {{-- Notes --}}
          <div class="mt-3">
            <label>Notes</label>
            <textarea name="notes" class="form-control" rows="2"></textarea>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary" onclick="prepareFitments()">Save Product</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Template for Fitment Row --}}
<template id="fitmentRow">
  <div class="row g-2 fitment-item mb-2">
    <div class="col-md-3">
      <select class="form-select make_id" required>
        <option value="">Make</option>
        @foreach($makes as $make)
          <option value="{{ $make->id }}">{{ $make->name }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-3">
      <select class="form-select model_id" required>
        <option value="">Model</option>
        @foreach($models as $m)
          <option value="{{ $m->id }}">{{ $m->name }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-2">
      <select class="form-select engine_id" required>
        <option value="">Engine</option>
        @foreach($engines as $e)
          <option value="{{ $e->id }}">{{ $e->name }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-2">
      <select class="form-select year_id" required>
        <option value="">Year Range</option>
        
      </select>
    </div>
    <div class="col-md-2">
      <input type="text" class="form-control market" placeholder="Market">
    </div>
    <div class="col-md-1 d-flex align-items-center">
      <button type="button" class="btn btn-sm btn-danger remove-fitment">&times;</button>
    </div>
  </div>
</template>

<script>
document.getElementById('addFitmentBtn').addEventListener('click', function(){
  let tmpl = document.getElementById('fitmentRow').content.cloneNode(true);
  document.getElementById('fitmentContainer').appendChild(tmpl);
});

document.addEventListener('click', function(e){
  if(e.target.classList.contains('remove-fitment')){
    e.target.closest('.fitment-item').remove();
  }
});

function prepareFitments(){
  let data = [];
  document.querySelectorAll('#fitmentContainer .fitment-item').forEach(row=>{
    data.push({
      make_id: row.querySelector('.make_id').value,
      model_id: row.querySelector('.model_id').value,
      engine_id: row.querySelector('.engine_id').value,
      year_id: row.querySelector('.year_id').value,
      market: row.querySelector('.market').value,
    });
  });
  document.getElementById('fitmentsJson').value = JSON.stringify(data);
}
</script>
