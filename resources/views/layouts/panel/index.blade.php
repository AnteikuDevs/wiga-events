
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    @if (isset($title) && !empty($title))<title>{{ env('APP_NAME') }} - {{ $title }}</title>@else<title>{{ env('APP_NAME') }} - @yield('title')</title>@endif

    <link href="{{ asset('icon.png') }}" rel="icon">
    <link href="{{ asset('icon.png') }}" rel="apple-touch-icon">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <link href="{{ asset('panel/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('panel/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('panel/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/fontawesome/css/all.min.css') }}">
</head>
	<body id="kt_body" style="background-image: url({{ asset('panel/media/patterns/header-bg.png') }})" class="header-fixed header-tablet-and-mobile-fixed">

        <div class="page-loader">
            <span class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </span>
        </div>

		<div class="d-flex flex-column flex-root">
			<div class="page d-flex flex-row flex-column-fluid">
				<div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
					@include('layouts.panel.header')
					<div class="toolbar py-5 py-lg-15" id="kt_toolbar">
						<div id="kt_toolbar_container" class="container-xxl d-flex flex-column">
                            @if (isset($title) && !empty($title))
                                <h3 class="text-white fw-bolder fs-2qx me-5">{{ $title }}</h3>
                            @endif
                            @if (isset($breadcrumb) && !empty($breadcrumb))
                            <x-breadcrumb :items="$breadcrumb" />
                            @endif
						</div>
					</div>
					<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
						<div class="flex-row-fluid" id="kt_content">@yield('content')</div>
					</div>
					<div class="footer py-4 d-flex flex-lg-column" id="kt_footer">
						<div class="container-xxl d-flex flex-column flex-md-row align-items-center justify-content-between">
							<div class="text-dark dark:text-light mx-auto">
                                Made by ❤️ <a href="https://instagram.com/anteikudevs" target="_blank" class="text-danger">AnteikuDevs</a>
                            </div>
						</div>
					</div>
				</div>
			</div>
		</div>
        
		<div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
			<span class="svg-icon">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
					<rect opacity="0.5" x="13" y="6" width="13" height="2" rx="1" transform="rotate(90 13 6)" fill="black" />
					<path d="M12.5657 8.56569L16.75 12.75C17.1642 13.1642 17.8358 13.1642 18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25L12.7071 5.70711C12.3166 5.31658 11.6834 5.31658 11.2929 5.70711L5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75C6.16421 13.1642 6.83579 13.1642 7.25 12.75L11.4343 8.56569C11.7467 8.25327 12.2533 8.25327 12.5657 8.56569Z" fill="black" />
				</svg>
			</span>
		</div>

        <widget-script>
            <script src="{{ asset('panel/plugins/global/plugins.bundle.js') }}"></script>
            <script src="{{ asset('panel/plugins/custom/datatables/datatables.bundle.js') }}"></script>
            <script src="{{ asset('panel/js/scripts.bundle.js') }}"></script>
            <script src="{{ asset('panel/js/custom/widgets.js') }}"></script>
            <script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>
            <script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs" type="module"></script>
            <script src="{{ asset('js/wiga-config.js') }}"></script>
            <script src="{{ asset('js/wiga.js') }}?v={{ time() }}"></script>
            <script src="{{ asset('js/'.componentJS('me').'.js') }}?v={{ time() }}"></script>
        @if (isset($js) && !empty($js))
            @if(is_array($js))
                @foreach($js as $j)
                    <script src="{{ asset('js/'.$j.'.js') }}?v={{ time() }}"></script>
                @endforeach
            @else
                <script src="{{ asset('js/'.$js.'.js') }}?v={{ time() }}"></script>
            @endif
        @endif
        </widget-script>
	</body>
</html>