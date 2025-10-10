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
                <select name="role_id" class="form-control" required id="user_role_select">
                    @foreach(\App\Models\Role::all() as $role)
                        <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
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
    // Show/hide 2FA based on role
    $('#user_role_select').on('change', function() {
        var roleId = $(this).val();
        var roleName = $(this).find('option:selected').text().toLowerCase();
        
        if (roleName === 'owner') {
            $('#create_two_factor_enabled').prop('disabled', false);
            $('label[for="create_two_factor_enabled"]').text('Enable 2FA');
        } else {
            $('#create_two_factor_enabled').prop('disabled', true).prop('checked', false);
            $('label[for="create_two_factor_enabled"]').text('Enable 2FA (Owner Only)');
        }
    });
    
    // Trigger change on page load
    $('#user_role_select').trigger('change');
});
</script>
