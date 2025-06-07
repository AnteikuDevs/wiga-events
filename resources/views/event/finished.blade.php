@extends('layouts.main.index')

@section('content')
<widget-login class="container">

    <div class="row justify-content-center gap-3">
        <div class="login-content">
            
            <div class="card login-box-container">
                <div class="card-body">

                    <dotlottie-player
                    src="{{ asset('images/lottie/danger.json') }}"
                    background="transparent"
                    speed="1"
                    style="width: 300px; height: 200px; margin: 0 auto;"
                    loop
                    autoplay
                    ></dotlottie-player>

                    <h4 class="text-center">Maaf, Link telah kadaluarsa</h4>
                    
                </div>
            </div>

        </div>

        <div class="d-flex align-items-center justify-content-center justify-content-md-between flex-column flex-md-row py-3 py-lg-6 container-fluid">
            {{-- <div class="text-dark mx-auto">
                &copy; <a href="https://instagram.com/teguhdevs" target="_blank" class="text-danger"><strong>AnteikuDevs</strong></a>. All rights reserved.
            </div> --}}
            <div class="text-dark dark:text-light mx-auto">
                Made by ❤️ <a href="https://instagram.com/anteikudevs" target="_blank" class="text-danger">AnteikuDevs</a>
            </div>
        </div>
    </div>


</widget-login>
@endsection
@push('style')
<script
  src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs"
  type="module"
></script>
@endpush