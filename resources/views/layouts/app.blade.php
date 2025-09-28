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