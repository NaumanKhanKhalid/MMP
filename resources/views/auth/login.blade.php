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
                            <div class="d-flex align-items-center justify-content-center mb-3">
                                <span class="auth-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" id="password">
                                        <path fill="#6446fe"
                                            d="M59,8H5A1,1,0,0,0,4,9V55a1,1,0,0,0,1,1H59a1,1,0,0,0,1-1V9A1,1,0,0,0,59,8ZM58,54H6V10H58Z"
                                            class="color1d1f47 svgShape"></path>
                                        <path fill="#6446fe"
                                            d="M36,35H28a3,3,0,0,1-3-3V27a3,3,0,0,1,3-3h8a3,3,0,0,1,3,3v5A3,3,0,0,1,36,35Zm-8-9a1,1,0,0,0-1,1v5a1,1,0,0,0,1,1h8a1,1,0,0,0,1-1V27a1,1,0,0,0-1-1Z"
                                            class="color0055ff svgShape"></path>
                                        <path fill="#6446fe"
                                            d="M36 26H28a1 1 0 0 1-1-1V24a5 5 0 0 1 10 0v1A1 1 0 0 1 36 26zm-7-2h6a3 3 0 0 0-6 0zM32 31a1 1 0 0 1-1-1V29a1 1 0 0 1 2 0v1A1 1 0 0 1 32 31z"
                                            class="color0055ff svgShape"></path>
                                        <path fill="#6446fe"
                                            d="M59 8H5A1 1 0 0 0 4 9v8a1 1 0 0 0 1 1H20.08a1 1 0 0 0 .63-.22L25.36 14H59a1 1 0 0 0 1-1V9A1 1 0 0 0 59 8zm-1 4H25l-.21 0a1.09 1.09 0 0 0-.42.2L19.73 16H6V10H58zM50 49H14a1 1 0 0 1-1-1V39a1 1 0 0 1 1-1H50a1 1 0 0 1 1 1v9A1 1 0 0 1 50 49zM15 47H49V40H15z"
                                            class="color1d1f47 svgShape"></path>
                                        <circle cx="19.5" cy="43.5" r="1.5" fill="#6446fe"
                                            class="color0055ff svgShape"></circle>
                                        <circle cx="24.5" cy="43.5" r="1.5" fill="#6446fe"
                                            class="color0055ff svgShape"></circle>
                                        <circle cx="29.5" cy="43.5" r="1.5" fill="#6446fe"
                                            class="color0055ff svgShape"></circle>
                                        <circle cx="34.5" cy="43.5" r="1.5" fill="#6446fe"
                                            class="color0055ff svgShape"></circle>
                                        <circle cx="39.5" cy="43.5" r="1.5" fill="#6446fe"
                                            class="color0055ff svgShape"></circle>
                                        <circle cx="44.5" cy="43.5" r="1.5" fill="#6446fe"
                                            class="color0055ff svgShape"></circle>
                                        <path fill="#6446fe"
                                            d="M60 9a1 1 0 0 0-1-1H28.81l2.37-2.37A19.22 19.22 0 0 1 60 31zM35.19 56l-2.37 2.37A19.22 19.22 0 0 1 4 33V55a1 1 0 0 0 1 1z"
                                            opacity=".3" class="color0055ff svgShape"></path>
                                    </svg>
                                </span>
                            </div>
                            <p class="h4 fw-semibold mb-0 text-center">Sign In</p>
                            <p class="mb-3 text-muted fw-normal text-center">Welcome back Jhon !</p>
                            <form class="mt-4" method="POST" action="{{ route('login.post') }}">
                                @csrf
                                <div class="row gy-3">
                                    <div class="col-xl-12">
                                        <label for="signup-firstname" class="form-label text-default">Email</label>
                                        <div class="position-relative">
                                            <input type="email" name="email" class="form-control form-control-lg"
                                                id="signup-firstname" placeholder="Enter Email ID">
                                        </div>
                                    </div>
                                    <div class="col-xl-12 mb-2">
                                        <label for="signin-password" class="form-label text-default d-block">Password<a
                                                href="reset-password-basic.html"
                                                class="float-end  link-danger op-5 fw-medium fs-12">Forget password
                                                ?</a></label>
                                        <div class="position-relative">
                                            <input type="password" name="password" class="form-control form-control-lg" id="signin-password"
                                                placeholder="password">
                                            <a href="javascript:void(0);" class="show-password-button text-muted"
                                                id="button-addon2" onclick="createpassword('signin-password',this)"><i class="ri-eye-off-line align-middle"></i></a>
                                        </div>
                                        <div class="mt-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value=""
                                                    id="defaultCheck1">
                                                <label class="form-check-label text-muted fw-normal fs-12"
                                                    for="defaultCheck1">
                                                    Remember password ?
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-grid mt-4">
                                    <button type="submit" class="btn btn-primary w-100 mt-3">Sign In</button>
                                </div>
                                <div class="text-center mb-0">
                                    <p class="text-muted mt-3 mb-0">Dont have an account? <a
                                            href="{{ route('register.get') }}" class="text-primary">Sign Up</a></p>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
