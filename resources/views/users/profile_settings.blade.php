@extends('layouts.app')

@section('title', 'Profile Settings')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h1 class="page-title fw-medium fs-18 mb-2">Profile Settings</h1>
                <div class="">
                    <nav>
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Profile Settings</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Start::row-1 -->
        <div class="row">
            <div class="col-xl-3">
                <div class="card custom-card">
                    <div class="card-body">
                        <ul class="nav flex-column gap-1 nav-pills tab-style-7" role="tablist">
                            <li class="nav-item me-0" role="presentation">
                                <a class="nav-link d-inline-flex w-100 mb-2 bg-light active" id="account"
                                    data-bs-toggle="tab" role="tab" data-bs-target="#account-pane">Account Setting</a>
                            </li>
                            {{-- <li class="nav-item me-0" role="presentation">
                                <a class="nav-link bg-light d-inline-flex w-100 mb-2" id="notification-tab"
                                    data-bs-toggle="tab" data-bs-target="#notification-tab-pane">Notification</a>
                            </li> --}}
                            @if (auth()->user()->role->name === 'Owner')
                                <li class="nav-item me-0" role="presentation">
                                    <a class="nav-link bg-light d-inline-flex w-100 mb-0" id="security-tab"
                                        data-bs-toggle="tab" data-bs-target="#security-tab-pane">Security</a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>

                <!-- Password Change Box -->
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">Change Password</div>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('user.password.update') }}">
                            @csrf
                            <div class="row gy-3">
                                <div class="col-xl-12">
                                    <label class="form-label">Current Password :</label>
                                    <input type="password" class="form-control" name="current_password"
                                        placeholder="Enter Current Password...">
                                </div>
                                <div class="col-xl-12">
                                    <label class="form-label">New Password :</label>
                                    <input type="password" class="form-control" name="password"
                                        placeholder="Enter New Password...">
                                </div>
                                <div class="col-xl-12">
                                    <label class="form-label">Confirm Password :</label>
                                    <input type="password" class="form-control" name="password_confirmation"
                                        placeholder="Confirm Password...">
                                </div>
                            </div>
                            <div class="card-footer text-end mt-3">
                                <button type="submit" class="btn btn-primary btn-wave">Update</button>
                            </div>
                        </form>
                    </div>
                </div>


            </div>

            <!-- Account Details Section -->
            <div class="col-xl-9">
                <div class="card custom-card">
                    <div class="p-3 border-bottom border-top border-block-end-dashed tab-content">
                        <!-- Account Tab -->
                        <div class="tab-pane show active" id="account-pane">
                            <form method="POST" action="{{ route('user.profile.update') }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row gy-3">
                                    <div class="col-xl-12">
                                        <div class="d-flex align-items-start flex-wrap gap-3">
                                            <div>
                                                <span class="avatar avatar-xxl">
                                                    <img src="{{ auth()->user()->avatar ?? asset('assets/images/faces/9.jpg') }}"
                                                        alt="Profile Picture">
                                                </span>
                                            </div>
                                            <div>
                                                <span class="fw-medium d-block mb-2">Profile Picture</span>
                                                <div class="btn-list mb-1">
                                                    <label class="btn btn-sm btn-primary btn-wave mb-0">
                                                        <i class="ri-upload-2-line me-1"></i>Change Image
                                                        <input type="file" name="avatar" class="d-none"
                                                            accept="image/*">
                                                    </label>

                                                    

                                                    @if (auth()->user()->avatar)
                                                        <a href="{{ route('user.avatar.remove') }}"
                                                            class="btn btn-sm btn-danger btn-wave">
                                                            <i class="ri-delete-bin-line me-1"></i>Remove
                                                        </a>
                                                    @endif
                                                </div>
                                                <span class="d-block fs-12 text-muted">
                                                    Use JPEG, PNG, or GIF. Best size: 200x200 pixels. Max 5MB.
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <label class="form-label">User Name :</label>
                                        <input type="text" class="form-control" name="name"
                                            value="{{ auth()->user()->name }}">
                                    </div>
                                    <div class="col-xl-6">
                                        <label class="form-label">Email :</label>
                                        <input type="email" class="form-control" name="email"
                                            value="{{ auth()->user()->email }}">
                                    </div>
                                </div>
                                <div class="card-footer border-top-0 mt-3 text-end">
                                    <button type="submit" class="btn btn-primary btn-wave">Save Changes</button>
                                </div>
                            </form>
                        </div>
                        @if (auth()->user()->role->name === 'Owner')
                            <div class="tab-pane overflow-hidden p-0 border-0" id="security-tab-pane" role="tabpanel"
                                aria-labelledby="security-tab" tabindex="0">

                                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-1">
                                    <div class="fw-semibold d-block fs-15">Security Settings :</div>
                                </div>

                                <div class="d-flex align-items-center justify-content-between mt-3">
                                    <div class="mail-notification-settings">
                                        <p class="fs-14 mb-1 fw-medium">Two-Factor Authentication</p>
                                        <p class="fs-12 mb-0 text-muted">Add an extra layer of security by requiring
                                            verification through your email at login.</p>
                                    </div>

                                    <form method="POST"
                                        action="{{ auth()->user()->two_factor_enabled ? route('two.factor.disable') : route('two.factor.enable') }}">
                                        @csrf
                                        <label class="switch">
                                            <input type="checkbox" onchange="this.form.submit()"
                                                {{ auth()->user()->two_factor_enabled ? 'checked' : '' }}>
                                            <span class="slider round"></span>
                                        </label>
                                    </form>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
        <!--End::row-1 -->
    </div>
@endsection
