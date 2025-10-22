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
                                        <button class="btn btn-sm btn-danger-light btn-icon openDeleteUserModal"
                                            data-id="{{ $user->id }}" data-name="{{ $user->name }}" title="Delete">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </div>
                                </td>



                            </tr>
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

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="ri-delete-bin-line me-2"></i>Delete User
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete the user <strong id="deleteUserName"></strong>?</p>
                    <p class="text-muted">This action can be undone by restoring from trash.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteUserForm" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="ri-delete-bin-line me-1"></i>Delete
                        </button>
                    </form>
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

            // Delete User
            $(document).on('click', '.openDeleteUserModal', function() {
                var id = $(this).data('id');
                var name = $(this).data('name');
                $('#deleteUserName').text(name);
                $('#deleteUserForm').attr('action', "{{ route('users.destroy', ':id') }}".replace(':id', id));
                $('#deleteUserModal').modal('show');
            });

            // Handle Delete Form Submission
            $(document).on('submit', '#deleteUserForm', function(e) {
                e.preventDefault();
                var $form = $(this);
                var $button = $form.find('button[type="submit"]');
                
                $button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Deleting...');
                
                $.ajax({
                    url: $form.attr('action'),
                    method: 'POST',
                    data: $form.serialize(),
                    success: function(response) {
                        $('#deleteUserModal').modal('hide');
                        toastr.success(response.message || 'User deleted successfully!');
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    },
                    error: function(xhr) {
                        var error = xhr.responseJSON?.message || 'Failed to delete user';
                        toastr.error(error);
                        $button.prop('disabled', false).html('<i class="ri-delete-bin-line me-1"></i>Delete');
                    }
                });
            });
        });

    </script>
@endpush
