@extends('layouts.app')

@section('title', 'Users')

@section('content')
    <div class="container-fluid py-4">
        <!-- Breadcrumb -->
        {{-- <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light p-2 rounded">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Users</li>
            </ol>
        </nav> --}}

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Users</h4>
            <button class="btn btn-primary" id="openCreateUserModal">
                <i class="ri-add-line me-1"></i> Add User
            </button>
        </div>

        <!-- Users Table -->
        <div class="card shadow-sm">
            <div class="card-body table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Role</th>
                            <th>Last Login</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $user->name }}</td>
                                <td>
                                    <div>
                                        <span class="d-block mb-1"><i
                                                class="ri-mail-line me-2 align-middle fs-14 text-muted"></i>{{ $user->email }}</span>
                                    </div>
                                </td>
                                <td>    
                                    @if($user->phone)
                                        <span class="d-block"><i class="ri-phone-line me-2 align-middle fs-14 text-muted"></i>{{ $user->phone }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td><span class="badge bg-info-transparent">{{ ucfirst($user->role->name) }}</span></td>
                                <td>
                                    @if($user->last_login_at)
                                        <span class="text-muted">{{ $user->last_login_at->format('M d, Y H:i') }}</span>
                                    @else
                                        <span class="text-muted">Never</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($user->status == 'active')
                                        <span class="badge rounded-pill bg-success-transparent">Active</span>
                                    @else
                                        <span class="badge rounded-pill bg-secondary-transparent">Inactive</span>
                                    @endif
                                </td>

                                <td class="text-end">
                                    <div class="btn-list">
                                        <!-- View Button -->
                                        <!-- View Button -->
                                        <button class="btn btn-sm btn-primary-light btn-icon openViewUserModal" data-id="{{ $user->id }}" title="View">
                                            <i class="ri-eye-line"></i>
                                        </button>

                                        <!-- Edit Button -->
                                        <button class="btn btn-sm btn-success-light btn-icon openEditUserModal" data-id="{{ $user->id }}" title="Edit">
                                            <i class="ri-pencil-line"></i>
                                        </button>

                                        <form method="POST" action="{{ route('toggle.user.status', $user->id) }}"
                                            class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="btn btn-sm {{ $user->status === 'active' ? 'btn-warning-light' : 'btn-success-light' }} btn-icon"
                                                title="{{ $user->status === 'active' ? 'Deactivate' : 'Activate' }}">
                                                <i
                                                    class="ri-toggle-{{ $user->status === 'active' ? 'line' : 'fill' }}"></i>
                                            </button>
                                        </form>
                                        <!-- Delete Button -->
                                        <button class="btn btn-sm btn-danger-light btn-icon contact-delete"
                                            data-bs-toggle="modal" data-bs-target="#deleteUser{{ $user->id }}">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </div>
                                </td>



                            </tr>

                            <!-- Offcanvas: View User -->
                            <div class="offcanvas offcanvas-end" tabindex="-1" id="viewUser{{ $user->id }}">
                                <div class="offcanvas-header">
                                    <h5>User Details</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
                                </div>
                                <div class="offcanvas-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <p><strong>Name:</strong> {{ $user->name }}</p>
                                            <p><strong>Email:</strong> {{ $user->email }}</p>
                                            <p><strong>Phone:</strong> {{ $user->phone ?? 'Not provided' }}</p>
                                            <p><strong>Role:</strong> {{ ucfirst($user->role->name) }}</p>
                                        </div>
                                        <div class="col-6">
                                            <p><strong>Status:</strong> 
                                                @if ($user->status == 'active')
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-secondary">Inactive</span>
                                                @endif
                                            </p>
                                            <p><strong>Last Login:</strong> {{ $user->last_login_at ? $user->last_login_at->format('M d, Y H:i') : 'Never' }}</p>
                                            <p><strong>First Login:</strong> {{ $user->first_login ? 'Yes' : 'No' }}</p>
                                        </div>
                                    </div>
                                    @if($user->notes)
                                        <hr>
                                        <p><strong>Notes:</strong></p>
                                        <p class="text-muted">{{ $user->notes }}</p>
                                    @endif
                                </div>
                            </div>

                            <!-- Delete Modal -->
                            <div class="modal fade" id="deleteUser{{ $user->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <form method="POST" action="{{ route('users.destroy', $user->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <div class="modal-content border-0 shadow-lg">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title">
                                                    <i class="ri-delete-bin-line me-2"></i> Delete User
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body p-4">
                                                <!-- User Info Card -->
                                                {{-- <div class="bg-light rounded p-3 mb-4">
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar bg-danger text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                                            <i class="ri-user-line fs-18"></i>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-1">{{ $user->name }}</h6>
                                                            <div class="d-flex align-items-center gap-2">
                                                                <span class="badge bg-info">{{ $user->email }}</span>
                                                                <span class="badge bg-{{ $user->role->name === 'owner' ? 'danger' : ($user->role->name === 'manager' ? 'warning' : 'success') }}">
                                                                    {{ ucfirst($user->role->name) }}
                                                                </span>
                                                                <span class="badge bg-{{ $user->status === 'active' ? 'success' : 'secondary' }}">
                                                                    {{ ucfirst($user->status) }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> --}}
Are you sure you want to delete the user <strong>{{ $user->name }}</strong>?
                                                <!-- Warning Message -->
                                                {{-- <div class="alert alert-warning d-flex align-items-start" role="alert">
                                                    <i class="ri-alert-line me-2 mt-1"></i>
                                                    <div>
                                                        <strong>Warning!</strong> This action cannot be undone. Deleting this user will permanently remove their account and access.
                                                    </div>
                                                </div> --}}

                                                <!-- User Details -->
                                                {{-- <div class="row">
                                                    <div class="col-md-6">
                                                        <h6 class="text-muted mb-2">Account Information</h6>
                                                        <p class="mb-1"><i class="ri-mail-line me-2 text-muted"></i>{{ $user->email }}</p>
                                                        @if($user->phone)
                                                            <p class="mb-1"><i class="ri-phone-line me-2 text-muted"></i>{{ $user->phone }}</p>
                                                        @endif
                                                        <p class="mb-1"><i class="ri-shield-user-line me-2 text-muted"></i>Max Discount: {{ $user->max_discount_allowed }}%</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <h6 class="text-muted mb-2">Account Details</h6>
                                                        <p class="mb-1"><i class="ri-calendar-line me-2 text-muted"></i>Created: {{ $user->created_at->format('M d, Y') }}</p>
                                                        <p class="mb-1"><i class="ri-time-line me-2 text-muted"></i>Last Login: {{ $user->last_login_at ? $user->last_login_at->format('M d, Y') : 'Never' }}</p>
                                                        @if($user->two_factor_enabled)
                                                            <p class="mb-1"><i class="ri-shield-check-line me-2 text-muted"></i>2FA: Enabled</p>
                                                        @endif
                                                    </div>
                                                </div> --}}

                                                <!-- Security Warning -->
                                                {{-- <div class="alert alert-danger mt-3" role="alert">
                                                    <h6 class="alert-heading"><i class="ri-error-warning-line me-2"></i>Security Warning</h6>
                                                    <p class="mb-2">Deleting this user will:</p>
                                                    <ul class="mb-0">
                                                        <li>Remove all their access to the system</li>
                                                        <li>Prevent them from logging in</li>
                                                        <li>Remove their user profile and settings</li>
                                                        <li>Cannot be undone</li>
                                                    </ul>
                                                </div> --}}
                                            </div>
                                            <div class="modal-footer bg-light">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                                    <i class="ri-close-line me-1"></i> Cancel
                                                </button>
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="ri-delete-bin-line me-1"></i> Delete User
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">No users found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <div class="card-footer border-top-0">
                <div class="d-flex align-items-center">
                    <div>
                        Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} entries
                        <i class="bi bi-arrow-right ms-2 fw-medium"></i>
                    </div>
                    <div class="ms-auto">
                        <nav aria-label="Page navigation" class="pagination-style-5">
                            <ul class="pagination mb-0">

                                {{-- Prev Button --}}
                                <li class="page-item {{ $users->onFirstPage() ? 'disabled' : '' }}">
                                    <a class="page-link" href="{{ $users->previousPageUrl() ?? 'javascript:void(0);' }}">
                                        Prev
                                    </a>
                                </li>

                                {{-- Page Numbers (show 2 before & 2 after current) --}}
                                @for ($i = max(1, $users->currentPage() - 2); $i <= min($users->lastPage(), $users->currentPage() + 2); $i++)
                                    <li class="page-item {{ $i == $users->currentPage() ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $users->url($i) }}">{{ $i }}</a>
                                    </li>
                                @endfor

                                {{-- Next Button --}}
                                <li class="page-item {{ !$users->hasMorePages() ? 'disabled' : '' }}">
                                    <a class="page-link text-primary"
                                        href="{{ $users->nextPageUrl() ?? 'javascript:void(0);' }}">
                                        Next
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>


        </div>
    </div>

    <!-- User Modals -->
    <div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content" id="userModalContent"></div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Create User
            $('#openCreateUserModal').on('click', function() {
                $.get("{{ route('users.create-modal') }}", function(html) {
                    $('#userModalContent').html(html);
                    $('#userModal').modal('show');
                });
            });

            // View User
            $(document).on('click', '.openViewUserModal', function() {
                var id = $(this).data('id');
                $.get("{{ route('users.view-modal', ':id') }}".replace(':id', id), function(html) {
                    $('#userModalContent').html(html);
                    $('#userModal').modal('show');
                });
            });

            // Edit User
            $(document).on('click', '.openEditUserModal', function() {
                var id = $(this).data('id');
                $.get("{{ route('users.edit-modal', ':id') }}".replace(':id', id), function(html) {
                    $('#userModalContent').html(html);
                    $('#userModal').modal('show');
                });
            });
        });

    </script>
@endpush
