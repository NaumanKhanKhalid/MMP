    <!-- Sidebar -->
    <aside class="app-sidebar sticky" id="sidebar">
        <style>
            .side-menu__icon {
                width: 24px !important;
                height: 24px !important;
                min-width: 24px !important;
                min-height: 24px !important;
            }

            .side-menu__icon svg {
                width: 24px !important;
                height: 24px !important;
            }
        </style>

        <!-- Start::main-sidebar-header -->
        <div class="main-sidebar-header">
            <a href="<?php echo e(route('dashboard')); ?>" class="header-logo">
                <img src="<?php echo e(url('public/assets/images/brand-logos/desktop-logo.png')); ?>" alt="logo"
                    class="desktop-logo">
                <img src="<?php echo e(url('public/assets/images/brand-logos/toggle-dark.png')); ?>" alt="logo"
                    class="toggle-dark">
                <img src="<?php echo e(url('public/assets/images/brand-logos/desktop-dark.png')); ?>" alt="logo"
                    class="desktop-dark">
                <img src="<?php echo e(url('public/assets/images/brand-logos/toggle-logo.png')); ?>" alt="logo"
                    class="toggle-logo">
            </a>
        </div>
        <!-- End::main-sidebar-header -->

        <!-- Start::main-sidebar -->
        <div class="main-sidebar" id="sidebar-scroll">

            <!-- Start::nav -->
            <nav class="main-menu-container nav nav-pills flex-column sub-open">

                <div class="slide-left" id="slide-left">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24"
                        viewBox="0 0 24 24">
                        <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"></path>
                    </svg>
                </div>

                <ul class="main-menu">

                    <!-- Dashboard -->
                    <li class="slide">
                        <a href="<?php echo e(route('dashboard')); ?>"
                            class="side-menu__item <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 256 256">
                                <rect width="256" height="256" fill="none" />
                                <path
                                    d="M104,216V152h48v64h64V120a8,8,0,0,0-2.34-5.66l-80-80a8,8,0,0,0-11.32,0l-80,80A8,8,0,0,0,40,120v96Z"
                                    fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="16" />
                            </svg>
                            <span class="side-menu__label">Dashboard</span>
                        </a>
                    </li>

                    <!-- Point of Sale (Expandable) -->
                    <li class="slide has-sub">
                        <a href="javascript:void(0);"
                            class="side-menu__item <?php echo e(request()->routeIs('pos.*', 'invoices.*', 'returns.*', 'payments.*') ? 'active' : ''); ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" width="20"
                                height="20" viewBox="0 0 24 24">
                                <rect x="3" y="7" width="18" height="13" rx="2" ry="2"
                                    stroke="currentColor" fill="none" stroke-width="2" />
                                <path d="M8 7V4h8v3M8 11h8M6 15h2m4 0h6" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <span class="side-menu__label">Point of Sale</span>
                            <i class="ri-arrow-right-s-line side-menu__angle"></i>
                        </a>
                        <ul class="slide-menu child1">
                            <li>
                                <a href="<?php echo e(route('pos.index')); ?>"
                                    class="side-menu__item <?php echo e(request()->routeIs('pos.*') ? 'active' : ''); ?>">
                                    POS Screen
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo e(route('invoices.index')); ?>"
                                    class="side-menu__item <?php echo e(request()->routeIs('invoices.*') ? 'active' : ''); ?>">
                                    Sale History
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo e(route('returns.index')); ?>"
                                    class="side-menu__item <?php echo e(request()->routeIs('returns.*') ? 'active' : ''); ?>">
                                    Return & Credit Notes
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo e(route('payments.index')); ?>"
                                    class="side-menu__item <?php echo e(request()->routeIs('payments.*') ? 'active' : ''); ?>">
                                    Payments
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="slide">
                        <a href="<?php echo e(route('quotes.index')); ?>" class="side-menu__item">
                            <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" width="20"
                                height="20" viewBox="0 0 24 24">
                                <path
                                    d="M9 5h9a3 3 0 0 1 3 3v6a3 3 0 0 1-3 3H13l-4 4v-4H6a3 3 0 0 1-3-3V8a3 3 0 0 1 3-3h3z"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                            <span class="side-menu__label">Quotations</span>
                        </a>
                    </li>

                    <li class="slide">
                        <a href="<?php echo e(route('purchase-orders.index')); ?>"
                            class="side-menu__item <?php echo e(request()->routeIs('purchase-orders.*') ? 'active' : ''); ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" width="20"
                                height="20" viewBox="0 0 24 24">
                                <path d="M9 12h6M9 16h6M12 4v2M17 4v2M7 4v2" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                <rect x="4" y="6" width="16" height="14" rx="2" ry="2"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                            <span class="side-menu__label">Purchase Orders</span>
                        </a>
                    </li>
                    <!-- Products -->
                    <li class="slide">
                        <a href="<?php echo e(route('products.index')); ?>"
                            class="side-menu__item <?php echo e(request()->routeIs('products.index') ? 'active' : ''); ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 256 256">
                                <rect width="256" height="256" fill="none" />
                                <rect x="32" y="48" width="192" height="160" rx="8" fill="none"
                                    stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="16" />
                                <path d="M168,88a40,40,0,0,1-80,0" fill="none" stroke="currentColor"
                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                            </svg>
                            <span class="side-menu__label">Inventory</span>
                        </a>
                    </li>





                    <!-- Main Categories -->

                    <li class="slide">
                        <a href="<?php echo e(route('job-cards.index')); ?>" class="side-menu__item">
                            <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" width="20"
                                height="20" viewBox="0 0 24 24">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" fill="none"
                                    stroke="currentColor" stroke-width="2" />
                                <polyline points="14,2 14,8 20,8" fill="none" stroke="currentColor"
                                    stroke-width="2" />
                                <line x1="16" y1="13" x2="8" y2="13"
                                    stroke="currentColor" stroke-width="2" />
                                <line x1="16" y1="17" x2="8" y2="17"
                                    stroke="currentColor" stroke-width="2" />
                                <polyline points="10,9 9,9 8,9" fill="none" stroke="currentColor"
                                    stroke-width="2" />
                            </svg>
                            <span class="side-menu__label">Workshop</span>
                        </a>
                    </li>
                    <!-- Main Categories -->
                    <li class="slide">
                        <a href="<?php echo e(route('suppliers.index')); ?>"
                            class="side-menu__item <?php echo e(request()->routeIs('suppliers.index') ? 'active' : ''); ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 256 256">
                                <path
                                    d="M32 72a16 16 0 0 1 16-16h56l16 16h104a16 16 0 0 1 16 16v96a16 16 0 0 1-16 16H48a16 16 0 0 1-16-16Z"
                                    fill="none" stroke="currentColor" stroke-linecap="round"
                                    stroke-linejoin="round" stroke-width="16" />
                            </svg>
                            <span class="side-menu__label">Creditors</span>
                        </a>
                    </li>
                    <!-- Main Categories -->
                    <li class="slide">
                        <a href="<?php echo e(route('customers.index')); ?>"
                            class="side-menu__item <?php echo e(request()->routeIs('customers.index') ? 'active' : ''); ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 256 256">
                                <path
                                    d="M32 72a16 16 0 0 1 16-16h56l16 16h104a16 16 0 0 1 16 16v96a16 16 0 0 1-16 16H48a16 16 0 0 1-16-16Z"
                                    fill="none" stroke="currentColor" stroke-linecap="round"
                                    stroke-linejoin="round" stroke-width="16" />
                            </svg>
                            <span class="side-menu__label">Debtors</span>
                        </a>
                    </li>

                    
                    </li>
                    <li class="slide">
                        <a href="<?php echo e(route('goods-receipts.index')); ?>" class="side-menu__item">
                            <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" width="20"
                                height="20" viewBox="0 0 24 24">
                                <path
                                    d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4a2 2 0 0 0 1-1.73zM12 22V12"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                            <span class="side-menu__label">Goods Receipts</span>
                        </a>
                    </li>


                    <li class="slide">
                        <a href="<?php echo e(route('reports.index')); ?>"
                            class="side-menu__item <?php echo e(request()->routeIs('reports.*') ? 'active' : ''); ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" width="20"
                                height="20" viewBox="0 0 24 24">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"
                                    stroke="currentColor" fill="none" stroke-width="2" />
                                <line x1="8" y1="10" x2="16" y2="10"
                                    stroke="currentColor" stroke-width="2" />
                                <line x1="8" y1="14" x2="16" y2="14"
                                    stroke="currentColor" stroke-width="2" />
                                <line x1="8" y1="18" x2="12" y2="18"
                                    stroke="currentColor" stroke-width="2" />
                            </svg>
                            <span class="side-menu__label">Reports</span>
                        </a>
                    </li>
                    <li class="slide">
                        <a href="<?php echo e(route('users.index')); ?>"
                            class="side-menu__item <?php echo e(request()->routeIs('users.*') ? 'active' : ''); ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" width="20"
                                height="20" viewBox="0 0 24 24">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"
                                    stroke="currentColor" fill="none" stroke-width="2" />
                                <line x1="8" y1="10" x2="16" y2="10"
                                    stroke="currentColor" stroke-width="2" />
                                <line x1="8" y1="14" x2="16" y2="14"
                                    stroke="currentColor" stroke-width="2" />
                                <line x1="8" y1="18" x2="12" y2="18"
                                    stroke="currentColor" stroke-width="2" />
                            </svg>
                            <span class="side-menu__label"> Users</span>
                        </a>
                    </li>

                    <!-- Stock Management -->
                    <li class="slide has-sub">
                        <a href="javascript:void(0);"
                            class="side-menu__item <?php echo e(request()->routeIs(
                                'stock-counts.*',
                                'stock-adjustments.*',
                                'categories.*',
                                'brands.*',
                                'product.fitments.*',
                                'car-makes.*',
                                'car-models.*',
                                'engines.*',
                                'users.*',
                            )
                                ? 'active'
                                : ''); ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" width="20"
                                height="20" viewBox="0 0 24 24">
                                <rect x="3" y="3" width="7" height="7" rx="1" stroke="currentColor"
                                    fill="none" stroke-width="2" />
                                <rect x="14" y="3" width="7" height="7" rx="1"
                                    stroke="currentColor" fill="none" stroke-width="2" />
                                <rect x="14" y="14" width="7" height="7" rx="1"
                                    stroke="currentColor" fill="none" stroke-width="2" />
                                <rect x="3" y="14" width="7" height="7" rx="1"
                                    stroke="currentColor" fill="none" stroke-width="2" />
                            </svg>
                            <span class="side-menu__label">Stock Management</span>
                            <i class="ri-arrow-right-s-line side-menu__angle"></i>
                        </a>

                        <ul class="slide-menu child1">

                            <!-- Stock Counts -->
                            <li>
                                <a href="<?php echo e(route('stock-counts.index')); ?>"
                                    class="side-menu__item <?php echo e(request()->routeIs('stock-counts.*') ? 'active' : ''); ?>">
                                    Stock Counts
                                </a>
                            </li>

                            <!-- Stock Adjustments -->
                            <li>
                                <a href="<?php echo e(route('stock-adjustments.index')); ?>"
                                    class="side-menu__item <?php echo e(request()->routeIs('stock-adjustments.*') ? 'active' : ''); ?>">
                                    Stock Adjustments
                                </a>
                            </li>

                            <!-- Categories -->
                            <li>
                                <a href="<?php echo e(route('categories.index')); ?>"
                                    class="side-menu__item <?php echo e(request()->routeIs('categories.index') ? 'active' : ''); ?>">
                                    Categories
                                </a>
                            </li>

                            <!-- Sub-Categories -->
                            <li>
                                <a href="<?php echo e(route('categories.subcategories')); ?>"
                                    class="side-menu__item <?php echo e(request()->routeIs('categories.subcategories') ? 'active' : ''); ?>">
                                    Sub-Categories
                                </a>
                            </li>

                            <!-- Brands -->
                            <li>
                                <a href="<?php echo e(route('brands.index')); ?>"
                                    class="side-menu__item <?php echo e(request()->routeIs('brands.*') ? 'active' : ''); ?>">
                                    Brands
                                </a>
                            </li>

                            <!-- Product Fitments -->
                            <li>
                                <a href="<?php echo e(route('product.fitments.index')); ?>"
                                    class="side-menu__item <?php echo e(request()->routeIs('product.fitments.*') ? 'active' : ''); ?>">
                                    Product Fitments
                                </a>
                            </li>

                            <!-- Users -->


                            <!-- Car Data Submenu -->
                            <li class="slide has-sub">
                                <a href="javascript:void(0);"
                                    class="side-menu__item <?php echo e(request()->routeIs('car-makes.*', 'car-models.*', 'engines.*') ? 'active' : ''); ?>">
                                    Car Data
                                    <i class="ri-arrow-right-s-line side-menu__angle"></i>
                                </a>

                                <ul class="slide-menu child2">
                                    <li><a href="<?php echo e(route('car-makes.index')); ?>"
                                            class="side-menu__item <?php echo e(request()->routeIs('car-makes.*') ? 'active' : ''); ?>">Car
                                            Makes</a></li>
                                    <li><a href="<?php echo e(route('car-models.index')); ?>"
                                            class="side-menu__item <?php echo e(request()->routeIs('car-models.*') ? 'active' : ''); ?>">Car
                                            Models</a></li>
                                    
                                </ul>
                            </li>
                        </ul>
                    </li>

                    <?php if(auth()->user()->role->name === 'Owner'): ?>
                        <li class="slide">
                            <a href="<?php echo e(route('settings.index')); ?>"
                                class="side-menu__item <?php echo e(request()->routeIs('settings.*') ? 'active' : ''); ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" width="20"
                                    height="20" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="3" stroke="currentColor" fill="none"
                                        stroke-width="2" />
                                    <path d="M12 1v6m0 6v6M23 12h-6m-6 0H1" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" />
                                </svg>
                                <span class="side-menu__label">Settings</span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <!-- Stock Management Dropdown -->






                </ul>

                <div class="slide-right" id="slide-right">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24"
                        viewBox="0 0 24 24">
                        <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"></path>
                    </svg>
                </div>

            </nav>
        </div>
    </aside>
<?php /**PATH C:\xampp\htdocs\MMP\resources\views/partials/sidebar.blade.php ENDPATH**/ ?>