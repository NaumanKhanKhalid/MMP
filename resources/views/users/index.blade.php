@extends('layouts.app')

@section('title', 'Users')

@section('content')
    <div class="container-fluid py-4">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light p-2 rounded">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Users</li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Users</h4>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createUserModal">
                <i class="bi bi-plus-circle me-1"></i> Add User
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
                            <th>Role</th>
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
                                <td><span class="badge bg-info-transparent">{{ ucfirst($user->role->name) }}</span></td>
                                <td class="text-end">
                                    <div class="btn-list">
                                        <!-- View Button -->
                                        <a data-bs-toggle="offcanvas" href="#viewUser{{ $user->id }}" role="button"
                                            aria-controls="viewUser{{ $user->id }}"
                                            class="btn btn-sm btn-primary-light btn-icon">
                                            <i class="ri-eye-line"></i>
                                        </a>

                                        <!-- Edit Button -->
                                        <button class="btn btn-sm btn-success-light btn-icon" data-bs-toggle="modal"
                                            data-bs-target="#editUser{{ $user->id }}">
                                            <i class="ri-pencil-line"></i>
                                        </button>

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
                                    <p><strong>Name:</strong> {{ $user->name }}</p>
                                    <p><strong>Email:</strong> {{ $user->email }}</p>
                                    <p><strong>Role:</strong> {{ ucfirst($user->role->name) }}</p>
                                    <hr>
                                </div>
                            </div>

                            <!-- Modal: Edit User -->
                            <div class="modal fade" id="editUser{{ $user->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <form method="POST" action="{{ route('users.update', $user->id) }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit User</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label>Name</label>
                                                    <input type="text" name="name" class="form-control"
                                                        value="{{ $user->name }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label>Email</label>
                                                    <input type="email" name="email" class="form-control"
                                                        value="{{ $user->email }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label>Role</label>
                                                    <select name="role" class="form-select">
                                                        <option value="manager"
                                                            {{ $user->role->name == 'Manager' ? 'selected' : '' }}>Manager
                                                        </option>
                                                        <option value="staff"
                                                            {{ $user->role->name == 'Staff' ? 'selected' : '' }}>Staff
                                                        </option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label>Password <small class="text-muted">(Leave blank to keep
                                                            current)</small></label>
                                                    <input type="password" name="password" class="form-control">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light"
                                                    data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-warning">Update</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Modal: Delete User -->
                            <div class="modal fade" id="deleteUser{{ $user->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <form method="POST" action="{{ route('users.destroy', $user->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Confirm Delete</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to delete <strong>{{ $user->name }}</strong>?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light"
                                                    data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No users found</td>
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

    <!-- Modal: Create User -->
    <div class="modal fade" id="createUserModal" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('users.store') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Role</label>
                            <select name="role" class="form-select">
                                <option value="staff">Staff</option>
                                <option value="manager">Manager</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        @if (session('success'))
            toastr.success("{{ session('success') }}");
        @endif
        @if (session('error'))
            toastr.error("{{ session('error') }}");
        @endif
    </script>
@endpush
