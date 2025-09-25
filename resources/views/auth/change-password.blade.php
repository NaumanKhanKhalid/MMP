@extends('layouts.authentication')

@section('content')
    <div class="container">
        <div class="row justify-content-center authentication authentication-basic align-items-center h-100">
            <div class="col-xxl-4 col-xl-5 col-lg-5 col-md-6 col-sm-8 col-12">
                <div class="mb-3 d-flex justify-content-center auth-logo">
                    <a href="index.html">
                        <img src="../assets/images/brand-logos/desktop-dark.png" alt="logo" class="desktop-dark">
                    </a>
                </div>
                <div class="card custom-card my-4 border z-3 position-relative">
                    <div class="card-body p-0">
                        <div class="p-5">

                            <p class="h4 fw-semibold mb-0 text-center">Change Password</p>
                            <form class="mt-4" method="POST" action="{{ route('change.password.post') }}">
                                @csrf
                                <div class="row gy-3">
                                    <div class="col-xl-12">
                                        <label for="signup-firstname" class="form-label text-default">New Password</label>
                                        <div class="position-relative">
                                            <input type="password" name="password" class="form-control form-control-lg"
                                                id="signup-firstname" placeholder="Enter New Password">
                                        </div>
                                    </div>
                                    <div class="col-xl-12">
                                        <label for="signup-firstname" class="form-label text-default">Confirm
                                            Password</label>
                                        <div class="position-relative">
                                            <input type="password" name="password_confirmation"
                                                class="form-control form-control-lg" id="signup-firstname"
                                                placeholder="Enter New Password">
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">Update Password</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
