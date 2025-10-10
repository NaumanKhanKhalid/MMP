<div class="modal-header bg-warning text-dark">
    <h5 class="modal-title">
        <i class="ri-pencil-line me-2"></i> Edit User
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<form action="{{ route('users.update', $user) }}" method="POST" id="userEditForm">
    @csrf
    @method('PUT')
    
    <div class="modal-body p-0">
        <!-- Simple Form - No Tabs -->
        <div class="p-4">

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Full Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Email Address <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">User Role <span class="text-danger">*</span></label>
                    <select name="role_id" class="form-control" required>
                        @foreach(\App\Models\Role::all() as $role)
                            <option value="{{ $role->id }}" @if($user->role_id == $role->id) selected @endif>{{ ucfirst($role->name) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Account Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-control" required>
                        <option value="active" @if($user->status=='active') selected @endif>Active</option>
                        <option value="inactive" @if($user->status=='inactive') selected @endif>Inactive</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Phone Number</label>
                    <input type="text" name="phone" class="form-control" value="{{ $user->phone }}">
                </div>
                
                <div class="col-12 mb-3">
                    <label class="form-label fw-bold">Notes</label>
                    <textarea name="notes" class="form-control" rows="3" placeholder="Additional notes about this user">{{ $user->notes }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer bg-light">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
            <i class="ri-close-line me-1"></i> Cancel
        </button>
        <button type="submit" class="btn btn-warning">
            <i class="ri-save-line me-1"></i> Update User
        </button>
    </div>
</form>
