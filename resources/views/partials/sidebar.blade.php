<aside class="app-sidebar sticky" id="sidebar">

    <!-- Start::main-sidebar-header -->
    <div class="main-sidebar-header">
        <a href="index.html" class="header-logo">
            <img src="{{ asset('assets/images/brand-logos/desktop-logo.png') }}" alt="logo" class="desktop-logo">
            <img src="{{ asset('assets/images/brand-logos/toggle-dark.png') }}" alt="logo" class="toggle-dark">
            <img src="{{ asset('assets/images/brand-logos/desktop-dark.png') }}" alt="logo" class="desktop-dark">
            <img src="{{ asset('assets/images/brand-logos/toggle-logo.png') }}" alt="logo" class="toggle-logo">
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
                    <a href="{{ route('dashboard') }}"
                        class="side-menu__item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
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
                <!-- Users & Suppliers -->
                <li class="slide has-sub">
                    <a href="javascript:void(0);"
                        class="side-menu__item {{ request()->routeIs('users.*', 'suppliers.*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 256 256">
                            <rect width="256" height="256" fill="none" />
                            <rect x="48" y="48" width="64" height="64" rx="8" fill="none"
                                stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="16" />
                            <rect x="144" y="48" width="64" height="64" rx="8" fill="none"
                                stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="16" />
                            <rect x="48" y="144" width="64" height="64" rx="8" fill="none"
                                stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="16" />
                            <rect x="144" y="144" width="64" height="64" rx="8" fill="none"
                                stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="16" />
                        </svg>
                        <span class="side-menu__label">Users</span>
                        <i class="ri-arrow-right-s-line side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu child1">
                        <li>
                            <a href="{{ route('users.index') }}"
                                class="side-menu__item {{ request()->routeIs('users.index') ? 'active' : '' }}">Users</a>
                        </li>
                        <li>
                            <a href="{{ route('suppliers.index') }}"
                                class="side-menu__item {{ request()->routeIs('suppliers.index') ? 'active' : '' }}">Suppliers</a>
                        </li>
                    </ul>
                </li>
                <!-- Products -->
                <li class="slide">
                    <a href="{{ route('products.index') }}"
                        class="side-menu__item {{ request()->routeIs('products.index') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 256 256">
                            <rect width="256" height="256" fill="none" />
                            <rect x="32" y="48" width="192" height="160" rx="8" fill="none"
                                stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="16" />
                            <path d="M168,88a40,40,0,0,1-80,0" fill="none" stroke="currentColor"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                        </svg>
                        <span class="side-menu__label">All Products</span>
                    </a>
                </li>
                <!-- Product Fitments -->
                <li class="slide">
                    <a href="{{ route('product.fitments.index') }}"
                        class="side-menu__item {{ request()->routeIs('product.fitments.*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 256 256">
                            <rect width="256" height="256" fill="none" />
                            <path d="M40,200l176-144" fill="none" stroke="currentColor" stroke-linecap="round"
                                stroke-linejoin="round" stroke-width="16" />
                            <rect x="40" y="160" width="176" height="48" rx="8" fill="none"
                                stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="16" />
                        </svg>
                        <span class="side-menu__label">Product Fitments</span>
                    </a>
                </li>
                <!-- Categories -->
                <li class="slide">
                    <a href="{{ route('categories.parents') }}"
                        class="side-menu__item {{ request()->routeIs('categories.parents') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 256 256">
                            <path
                                d="M32 72a16 16 0 0 1 16-16h56l16 16h104a16 16 0 0 1 16 16v96a16 16 0 0 1-16 16H48a16 16 0 0 1-16-16Z"
                                fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="16" />
                        </svg>
                        <span class="side-menu__label">Categories</span>
                    </a>
                </li>
                <!-- Subcategories -->
                <li class="slide">
                    <a href="{{ route('categories.subcategories') }}"
                        class="side-menu__item {{ request()->routeIs('categories.subcategories') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 256 256">
                            <path
                                d="M32 72a16 16 0 0 1 16-16h56l16 16h104a16 16 0 0 1 16 16v96a16 16 0 0 1-16 16H48a16 16 0 0 1-16-16Z"
                                fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="16" />
                        </svg>
                        <span class="side-menu__label">Subcategories</span>
                    </a>
                </li>
                <!-- Brands -->
                <li class="slide">
                    <a href="{{ route('brands.index') }}"
                        class="side-menu__item {{ request()->routeIs('brands.*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 256 256">
                            <path d="M48 40h80l80 80-80 80H48a8 8 0 0 1-8-8V48a8 8 0 0 1 8-8z" fill="none"
                                stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="16" />
                            <circle cx="84" cy="84" r="12" fill="currentColor" />
                        </svg>
                        <span class="side-menu__label">Brands</span>
                    </a>
                </li>
                <!-- Car Makes -->
                <li class="slide">
                    <a href="{{ route('car-makes.index') }}"
                        class="side-menu__item {{ request()->routeIs('car-makes.*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 256 256">
                            <rect width="256" height="256" fill="none" />
                            <rect x="40" y="120" width="176" height="56" rx="8" fill="none"
                                stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="16" />
                            <circle cx="72" cy="180" r="12" fill="currentColor" />
                            <circle cx="184" cy="180" r="12" fill="currentColor" />
                        </svg>
                        <span class="side-menu__label">Car Makes</span>
                    </a>
                </li>
                <!-- Car Models -->
                <li class="slide">
                    <a href="{{ route('car-models.index') }}"
                        class="side-menu__item {{ request()->routeIs('car-models.*') ? 'active' : '' }}">
                      <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 256 256"><rect width="256" height="256" fill="none"></rect><path d="M32,216V56a8,8,0,0,1,8-8H216a8,8,0,0,1,8,8V216l-32-16-32,16-32-16L96,216,64,200Z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></path><line x1="144" y1="112" x2="192" y2="112" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></line><line x1="144" y1="144" x2="192" y2="144" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></line><rect x="64" y="96" width="48" height="64" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></rect></svg>  
                        <span class="side-menu__label">Car Models</span>
                    </a>
                </li>
                <!-- Engines -->
                <li class="slide">
                    <a href="{{ route('engines.index') }}"
                        class="side-menu__item {{ request()->routeIs('engines.*') ? 'active' : '' }}">
                       <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 256 256"><rect width="256" height="256" fill="none"></rect><path d="M32,216V56a8,8,0,0,1,8-8H216a8,8,0,0,1,8,8V216l-32-16-32,16-32-16L96,216,64,200Z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></path><line x1="144" y1="112" x2="192" y2="112" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></line><line x1="144" y1="144" x2="192" y2="144" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></line><rect x="64" y="96" width="48" height="64" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></rect></svg> 
                        <span class="side-menu__label">Engines</span>
                    </a>
                </li>
                <!-- Model Engines -->
                <li class="slide">
                    <a href="{{ route('model.engines.index') }}"
                        class="side-menu__item {{ request()->routeIs('model.engines.*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 256 256">
                            <circle cx="128" cy="128" r="96" fill="none" stroke="currentColor"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                            <circle cx="128" cy="128" r="40" fill="none" stroke="currentColor"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                        </svg>
                        <span class="side-menu__label">Model Engines</span>
                    </a>
                </li>
                <!-- Add more new menu items here as needed -->
            </ul>

            <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191"
                    width="24" height="24" viewBox="0 0 24 24">
                    <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"></path>
                </svg></div>
        </nav>
    </div>
</aside>
