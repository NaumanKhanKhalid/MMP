    <header class="app-header sticky" id="header">

        <!-- Start::main-header-container -->
        <div class="main-header-container container-fluid">

            <!-- Start::header-content-left -->
            <div class="header-content-left">

                <!-- Start::header-element -->
                <div class="header-element">
                    <div class="horizontal-logo">
                        <a href="<?php echo e(route('dashboard')); ?>" class="header-logo">
                            <img src="<?php echo e(url('public/assets/images/brand-logos/desktop-logo.png')); ?>" alt="logo"
                                class="desktop-logo">
                            <img src="<?php echo e(url('public/assets/images/brand-logos/toggle-logo.png')); ?>" alt="logo"
                                class="toggle-logo">
                            <img src="<?php echo e(url('public/assets/images/brand-logos/desktop-dark.png')); ?>" alt="logo"
                                class="desktop-dark">
                            <img src="<?php echo e(url('public/assets/images/brand-logos/toggle-dark.png')); ?>" alt="logo"
                                class="toggle-dark">
                        </a>
                    </div>
                </div>
                <!-- End::header-element -->

                <!-- Start::header-element -->
                <div class="header-element">
                    <!-- Start::header-link -->
                    <a aria-label="Hide Sidebar" class="sidemenu-toggle header-link" data-bs-toggle="sidebar"
                        href="javascript:void(0);">
                        <svg xmlns="http://www.w3.org/2000/svg" class="header-link-icon menu-btn" width="32"
                            height="32" fill="#000000" viewBox="0 0 256 256">
                            <path
                                d="M224,128a8,8,0,0,1-8,8H40a8,8,0,0,1,0-16H216A8,8,0,0,1,224,128ZM40,72H216a8,8,0,0,0,0-16H40a8,8,0,0,0,0,16ZM216,184H40a8,8,0,0,0,0,16H216a8,8,0,0,0,0-16Z">
                            </path>
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" class="header-link-icon menu-btn-close" width="32"
                            height="32" fill="#000000" viewBox="0 0 256 256">
                            <path
                                d="M205.66,194.34a8,8,0,0,1-11.32,11.32L128,139.31,61.66,205.66a8,8,0,0,1-11.32-11.32L116.69,128,50.34,61.66A8,8,0,0,1,61.66,50.34L128,116.69l66.34-66.35a8,8,0,0,1,11.32,11.32L139.31,128Z">
                            </path>
                        </svg>
                    </a>
                    <!-- End::header-link -->
                </div>
                <!-- End::header-element -->


            </div>
            <!-- End::header-content-left -->

            <!-- Start::header-content-right -->
            <ul class="header-content-right">

                <!-- Start::header-element -->
                <li class="header-element d-md-none d-block">
                    <a href="javascript:void(0);" class="header-link" data-bs-toggle="modal"
                        data-bs-target="#header-responsive-search">
                        <!-- Start::header-link-icon -->
                        <i class="bi bi-search header-link-icon"></i>
                        <!-- End::header-link-icon -->
                    </a>
                </li>
                <!-- End::header-element -->





                

                <!-- Start::header-element -->
                <li class="header-element dropdown">
                    <!-- Start::header-link|dropdown-toggle -->
                    <a href="javascript:void(0);" class="header-link dropdown-toggle" id="mainHeaderProfile"
                        data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                        <div class="d-flex align-items-center">
                            <div class="me-xl-2 me-0">
                                <img src="<?php echo e(auth()->user()->avatar ?? asset('assets/images/faces/9.jpg')); ?>" alt="Profile"
                                    class="avatar avatar-sm avatar-rounded">
                            </div>
                            <div class="d-xl-block d-none lh-1">
                                <span class="fw-medium lh-1"><?php echo e(auth()->user()->name); ?></span>
                            </div>
                        </div>
                    </a>
                    <!-- End::header-link|dropdown-toggle -->

                    <ul class="main-header-dropdown dropdown-menu pt-0 overflow-hidden header-profile-dropdown dropdown-menu-end"
                        aria-labelledby="mainHeaderProfile">
                        <li>
                            <div class="py-2 px-3 text-center">
                                <span class="fw-semibold"><?php echo e(auth()->user()->name); ?></span>
                                <span class="d-block fs-12 text-muted"><?php echo e(auth()->user()->email); ?></span>
                                <span class=" mt-1">
                                    <?php echo e(ucfirst(auth()->user()->role->name ?? 'User')); ?>

                                </span>
                            </div>
                        </li>

                        <li>
                            <a class="dropdown-item d-flex align-items-center"
                                href="<?php echo e(route('users.profile.settings')); ?>">
                                <i class="ti ti-user text-primary me-2 fs-16"></i> Profile
                            </a>
                        </li>
                        <!-- Logout -->
                        <li class="py-2 px-3">
                            <form action="<?php echo e(route('logout')); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-primary btn-sm w-100">Log Out</button>
                            </form>
                        </li>
                    </ul>
                </li>

                <!-- End::header-element -->

                
                <!-- End::header-element -->

            </ul>
            <!-- End::header-content-right -->

        </div>
        <!-- End::main-header-container -->

    </header>
<?php /**PATH C:\xampp\htdocs\MMP\resources\views/partials/header.blade.php ENDPATH**/ ?>