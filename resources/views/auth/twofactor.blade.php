


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

                            <p class="h4 fw-semibold mb-3 text-center">Two-Factor Verification</p>
                            <p class="text-muted text-center">Enter the code sent to your email</p>
                            <form method="POST" action="{{ route('twofactor.post') }}">
                                @csrf
        
                                <div class="row gy-3">
                                    <div class="col-xl-12">
                                        <div class="position-relative">
                                            <input type="text" name="code" class="form-control form-control-lg"
                                                id="signup-firstname" placeholder="1234567">
                                        </div>
                                    </div>
                                   
                                    <button type="submit" class="btn btn-primary w-100">Verify</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
