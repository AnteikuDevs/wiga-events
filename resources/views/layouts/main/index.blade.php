<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @if (isset($title) && !empty($title))<title>{{ env('APP_NAME') }} - {{ $title }}</title>@else<title>{{ env('APP_NAME') }} - @yield('title')</title>@endif

    <link href="{{ asset('icon.png') }}" rel="icon">
    <link href="{{ asset('icon.png') }}" rel="apple-touch-icon">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/fontawesome/css/all.min.css') }}">
    @stack('style')
</head>
<body>

    <widget-app>

        <widget-main>

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