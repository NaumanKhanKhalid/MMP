<!-- Edit Product Modal -->
<div class="modal fade" id="editProductModal-{{ $product->id }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form method="POST" action="{{ route('products.update', $product->id) }}" enctype="multipart/form-data" class="modal-content">
      @csrf
      @method('PUT')
      <div class="modal-header">
        <h5 class="modal-title">Edit Product ({{ $product->name }})</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Name</label>
          <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Brand</label>
          <select name="brand_id" class="form-select">
            @foreach($brands as $brand)
              <option value="{{ $brand->id }}" {{ $brand->id == $product->brand_id ? 'selected' : '' }}>
                {{ $brand->name }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Category</label>
          <select name="category_id" class="form-select">
            @foreach($categories as $cat)
              <option value="{{ $cat->id }}" {{ $cat->id == $product->category_id ? 'selected' : '' }}>
                {{ $cat->name }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Selling Price</label>
          <input type="number" name="price" class="form-control" value="{{ $product->price }}" step="0.01" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Unit</label>
          <select name="unit" class="form-select">
            <option value="PCS" {{ $product->unit == 'PCS' ? 'selected' : '' }}>PCS</option>
            <option value="SET" {{ $product->unit == 'SET' ? 'selected' : '' }}>SET</option>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Update Images</label>
          <input type="file" name="images[]" multiple class="form-control">
          @if($product->images)
            <div class="mt-2">
              @foreach($product->images as $img)
                <img src="{{ asset('storage/'.$img) }}" class="img-thumbnail" width="60">
              @endforeach
            </div>
          @endif
        </div>
      </div>

      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Update Product</button>
      </div>
    </form>
  </div>
</div>
