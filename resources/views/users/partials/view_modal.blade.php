<div class="modal-header bg-primary text-white">
    <h5 class="modal-title">
        <i class="ri-user-line me-2"></i> User Details
    </h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body p-0">
    <!-- User Header Card -->
    <div class="bg-light p-4 border-bottom">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="d-flex align-items-center">
         x`           <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                        style="width: 60px; height: 60px;">
                        <i class="ri-user-line fs-24"></i>
                    </div>
                    <div>
                        <h4 class="mb-1">{{ $user->name }}</h4>
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge bg-info">{{ $user->email }}</span>
                            <span
                                class="badge bg-{{ $user->role->name === 'owner' ? 'danger' : ($user->role->name === 'manager' ? 'warning' : 'success') }}">
                                {{ ucfirst($user->role->name) }}
                            </span>
                            <span class="badge bg-{{ $user->status === 'active' ? 'success' : 'secondary' }}">
                                {{ ucfirst($user->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-end">
                <div class="text-muted">
                    <small>User Since</small><br>
                    <strong>{{ $user->created_at }}</strong>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <ul class="nav nav-tabs nav-fill" id="viewUserTabs-{{ $user->id }}" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="basic-tab-{{ $user->id }}" data-bs-toggle="tab"
                data-bs-target="#view-basic-{{ $user->id }}" type="button" role="tab">
                <i class="ri-user-line me-1"></i> Basic Info
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="contact-tab-{{ $user->id }}" data-bs-toggle="tab"
                data-bs-target="#view-contact-{{ $user->id }}" type="button" role="tab">
                <i class="ri-phone-line me-1"></i> Contact
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="activity-tab-{{ $user->id }}" data-bs-toggle="tab"
                data-bs-target="#view-activity-{{ $user->id }}" type="button" role="tab">
                <i class="ri-time-line me-1"></i> Activity
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content p-4" id="viewUserTabContent-{{ $user->id }}">
        <!-- Basic Info Tab -->
        <div class="tab-pane fade show active" id="view-basic-{{ $user->id }}" role="tabpanel">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-muted">Full Name</label>
                    <p class="form-control-static">{{ $user->name }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-muted">Email Address</label>
                    <p class="form-control-static">
                        <i class="ri-mail-line me-2"></i>{{ $user->email }}
                    </p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-muted">User Role</label>
                    <p class="form-control-static">
                        <span
                            class="badge bg-{{ $user->role->name === 'owner' ? 'danger' : ($user->role->name === 'manager' ? 'warning' : 'success') }}">
                            {{ ucfirst($user->role->name) }}
                        </span>
                    </p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-muted">Account Status</label>
                    <p class="form-control-static">
                        <span class="badge bg-{{ $user->status === 'active' ? 'success' : 'secondary' }}">
                            {{ ucfirst($user->status) }}
                        </span>
                    </p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-muted">Max Discount Allowed</label>
                    <p class="form-control-static">
                        <span class="text-primary fw-bold">{{ $user->max_discount_allowed }}%</span>
                    </p>
                </div>
               
            </div>
        </div>

        <!-- Contact Tab -->
        <div class="tab-pane fade" id="view-contact-{{ $user->id }}" role="tabpanel">
            <div class="row">
                <div class="col-md-6 mb-3">x    
                    <label class="form-label fw-bold text-muted">Phone Number</label>
                    <p class="form-control-static">
                        @if ($user->phone)
                            <i class="ri-phone-line me-2"></i>{{ $user->phone }}
                        @else
                            <span class="text-muted">Not provided</span>
                        @endif
                    </p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-muted">Email Verified</label>
                    <p class="form-control-static">
                        @if ($user->email_verified_at)
                            <span class="badge bg-success"><i class="ri-check-line me-1"></i>Verified</span>
                            <br><small class="text-muted">{{ $user->email_verified_at }}</small>
                        @else
                            <span class="badge bg-warning"><i class="ri-alert-line me-1"></i>Not Verified</span>
                        @endif
                    </p>
                </div>
                @if ($user->notes)
                    <div class="col-12 mb-3">
                        <label class="form-label fw-bold text-muted">Notes</label>
                        <p class="form-control-static">{{ $user->notes }}</p>
                    </div>
                @endif
            </div>
        </div>


        <!-- Activity Tab -->
        <div class="tab-pane fade" id="view-activity-{{ $user->id }}" role="tabpanel">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-muted">Last Login</label>
                    <p class="form-control-static">
                        @if ($user->last_login_at)
                            <i class="ri-time-line me-2"></i>{{ $user->last_login_at->format('M d, Y H:i') }}
                        @else
                            <span class="text-muted">Never logged in</span>
                        @endif
                    </p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-muted">First Login</label>
                    <p class="form-control-static">
                        @if ($user->first_login)
                            <span class="badge bg-warning"><i class="ri-alert-line me-1"></i>Password Change
                                Required</span>
                        @else
                            <span class="badge bg-success"><i class="ri-check-line me-1"></i>Completed</span>
                        @endif
                    </p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-muted">Account Created</label>
                    <p class="form-control-static">
                        <i class="ri-calendar-line me-2"></i>{{ $user->created_at }}
                    </p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-muted">Last Updated</label>
                    <p class="form-control-static">
                        <i class="ri-refresh-line me-2"></i>{{ $user->updated_at->format('M d, Y H:i') }}
                    </p>
                </div>
                @if ($user->isLocked())
                    <div class="col-12 mb-3">
                        <div class="alert alert-danger" role="alert">
                            <i class="ri-lock-line me-2"></i>
                            <strong>Account Locked!</strong> This account is locked until
                            {{ $user->locked_until->format('M d, Y H:i') }} due to {{ $user->login_attempts }} failed
                            login attempts.
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="modal-footer bg-light">
    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
        <i class="ri-close-line me-1"></i> Close
    </button>
    <button type="button" class="btn btn-warning openEditUserModal" data-id="{{ $user->id }}">
        <i class="ri-pencil-line me-1"></i> Edit User
    </button>
</div>
