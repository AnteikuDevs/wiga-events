<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @if (isset($title) && !empty($title))<title>{{ env('APP_NAME') }} - {{ $title }}</title>@else<title>{{ env('APP_NAME') }} - @yield('title')</title>@endif

    <link href="{{ asset('icon.png') }}" rel="icon">
    <link href="{{ asset('icon.png') }}" rel="apple-touch-icon">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    @stack('style')
</head>
<body>

    <widget-app>

        <widget-main>

            <nav class="navbar navbar-expand-lg fixed-top modern-navbar animate__animated animate__fadeInDown no-print">
                <div class="container">
                    <a class="navbar-brand d-flex align-items-center" href="/">
                        <img src="{{ asset('logo-full-dark.png') }}" alt="Logo" height="40" class="me-2">
                        {{-- <span class="fw-bolder fs-4 text-white tracking-tight">WIGA<span class="text-gradient-purple">EVENTS</span></span> --}}
                    </a>
                    
                    {{-- <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                        <span class="navbar-toggler-icon-custom"></span>
                    </button> --}}

                    {{-- <div class="collapse navbar-collapse" id="navbarNav"> --}}
                        {{-- <ul class="navbar-nav mx-auto">
                            <li class="nav-item">
                                <a class="nav-link active-link px-3" href="/">Beranda</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link px-3" href="#explore">Event</a>
                            </li>
                        </ul> --}}


                        @guest
                        
                        <div class="d-flex align-items-center gap-3 ms-auto">
                            <a href="{{ route('login') }}" class="text-white text-decoration-none fw-bold small">Masuk</a>
                            <a href="{{ route('register') }}" class="btn btn-primary-gradient rounded-pill px-4 py-2 shadow-sm">Mulai Gratis</a>
                        </div>

                        @endguest

                        @auth('web')
                            <div class="d-flex align-items-center gap-3 ms-auto">
                                <a href="{{ route('portal.dashboard') }}" class="btn btn-primary-gradient rounded-pill px-4 py-2 shadow-sm">Masuk Portal</a>
                            </div>
                        @endauth
                    {{-- </div> --}}
                </div>
            </nav>

            @yield('content')
            
        </widget-main>

    </widget-app>
    <widget-script>
        <script src="{{ asset('plugins/jquery.js') }}"></script>
        <script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>
        <script src="{{ asset('plugins/bootstrap.bundle.min.js') }}"></script>
        <script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs" type="module"></script>
        <script src="{{ asset('js/wiga-config.js') }}"></script>
        <script src="{{ asset('js/wiga.js') }}?v={{ time() }}"></script>
        <script>
            window.addEventListener('scroll', function() {
                const nav = document.querySelector('.modern-navbar');
                if (window.scrollY > 50) {
                    nav.classList.add('scrolled');
                } else {
                    nav.classList.remove('scrolled');
                }
            });
        </script>
        @stack('script')
    @if (isset($js) && !empty($js))
        @if(is_array($js))
            @foreach($js as $j)
                <script src="{{ asset('js/'.$j.'.js') }}"></script>
            @endforeach
        @else
            <script src="{{ asset('js/'.$js.'.js') }}"></script>
        @endif
    @endif
    </widget-script>
</body>
</html>