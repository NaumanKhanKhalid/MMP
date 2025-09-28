<div class="modal fade" id="quickAddModal" tabindex="-1" aria-labelledby="quickAddModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title" id="quickAddModalLabel">Quick Add Product</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <div class="modal-body">
        {{-- Name --}}
        <div class="mb-3">
          <label for="quick-name" class="form-label">Product Name <span class="text-danger">*</span></label>
          <input 
            type="text" 
            name="name" 
            id="quick-name" 
            class="form-control @error('name') is-invalid @enderror" 
            value="{{ old('name') }}" 
            required
            autofocus
          >
          @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        {{-- Brand --}}
        <div class="mb-3">
          <label for="quick-brand" class="form-label">Brand <span class="text-danger">*</span></label>
          <select 
            name="brand_id" 
            id="quick-brand" 
            class="form-select @error('brand_id') is-invalid @enderror" 
            required
          >
            <option value="" disabled selected>-- Select Brand --</option>
            @foreach($brands as $brand)
              <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
            @endforeach
          </select>
          @error('brand_id')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        {{-- Category --}}
        <div class="mb-3">
          <label for="quick-category" class="form-label">Category <span class="text-danger">*</span></label>
          <select 
            name="category_id" 
            id="quick-category" 
            class="form-select @error('category_id') is-invalid @enderror" 
            required
          >
            <option value="" disabled selected>-- Select Category --</option>
            @foreach($categories as $category)
              <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
            @endforeach
          </select>
          @error('category_id')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        {{-- Subcategory --}}
        <div class="mb-3">
          <label for="quick-subcategory" class="form-label">Subcategory <span class="text-danger">*</span></label>
          <select 
            name="subcategory_id" 
            id="quick-subcategory" 
            class="form-select @error('subcategory_id') is-invalid @enderror" 
            required
          >
            <option value="" disabled selected>-- Select Subcategory --</option>
            @foreach($subCategories as $subcategory)
              <option value="{{ $subcategory->id }}" {{ old('subcategory_id') == $subcategory->id ? 'selected' : '' }}>{{ $subcategory->name }}</option>
            @endforeach
          </select>
          @error('subcategory_id')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        {{-- Price Normal --}}
        <div class="mb-3">
          <label for="quick-price-normal" class="form-label">Price (Normal)</label>
          <input 
            type="number" 
            step="0.01" 
            min="0" 
            name="price_normal" 
            id="quick-price-normal" 
            class="form-control @error('price_normal') is-invalid @enderror" 
            value="{{ old('price_normal', 0) }}"
          >
          @error('price_normal')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        {{-- Status --}}
        <div class="mb-3">
          <label for="quick-status" class="form-label">Status <span class="text-danger">*</span></label>
          <select 
            name="status" 
            id="quick-status" 
            class="form-select @error('status') is-invalid @enderror" 
            required
          >
            <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
          </select>
          @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Add Product</button>
      </div>
    </form>
  </div>
</div>
