<div class="modal-header bg-success text-white">
    <h5 class="modal-title">
        <i class="ri-user-add-line me-2"></i> Add New User
    </h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
</div>

<form action="{{ route('users.store') }}" method="POST" id="userCreateForm">
    @csrf
    
    <div class="modal-body p-4">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Full Name <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Email Address <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Password <span class="text-danger">*</span></label>
                <input type="password" name="password" class="form-control" required minlength="8">
                <small class="text-muted">Minimum 8 characters</small>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Confirm Password <span class="text-danger">*</span></label>
                <input type="password" name="password_confirmation" class="form-control" required minlength="8">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">User Role <span class="text-danger">*</span></label>
                <select name="role" class="form-control" required id="user_role_select">
                    @foreach(\App\Models\Role::whereIn('name', ['staff', 'manager'])->get() as $role)
                        <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Account Status <span class="text-danger">*</span></label>
                <select name="status" class="form-control" required>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Phone Number</label>
                <input type="text" name="phone" class="form-control">
            </div>
            
            <div class="col-12 mb-3">
                <label class="form-label fw-bold">Notes</label>
                <textarea name="notes" class="form-control" rows="3" placeholder="Additional notes about this user"></textarea>
            </div>
        </div>
    </div>

    <div class="modal-footer bg-light">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
            <i class="ri-close-line me-1"></i> Cancel
        </button>
        <button type="submit" class="btn btn-success">
            <i class="ri-add-line me-1"></i> Add User
        </button>
    </div>
</form>

<script>
$(document).ready(function() {
    // Handle form submission
    $('#userCreateForm').on('submit', function(e) {
        e.preventDefault();
        
        var $form = $(this);
        var submitBtn = $form.find('button[type="submit"]');
        var originalText = submitBtn.html();
        
        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Adding...');
        
        $.ajax({
            url: $form.attr('action'),
            method: 'POST',
            data: $form.serialize(),
            success: function(response) {
                toastr.success('User created successfully!');
                $('#userModal').modal('hide');
                location.reload();
            },
            error: function(xhr) {
                var errorMsg = 'Failed to create user';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    errorMsg = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                }
                toastr.error(errorMsg);
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });
});
</script>
