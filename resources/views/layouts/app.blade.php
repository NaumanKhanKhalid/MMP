@include('partials.head')
<body>
    <div class="page">
        @include('partials.header')
        <div class="app-wrapper d-flex">
            @include('partials.sidebar')
            <main class="main-content app-content flex-fill">
                @yield('content')
            </main>
        </div>
        @include('partials.footer')
    </div>
    @include('partials.scripts')
     <!-- CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css">
    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.js"></script>


    <script>
        @if (session('success'))
            toastr.success("{{ session('success') }}");
        @endif

        @if (session('error'))
            toastr.error("{{ session('error') }}");
        @endif

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                toastr.error("{{ $error }}");
            @endforeach
        @endif
    </script>

</body> 