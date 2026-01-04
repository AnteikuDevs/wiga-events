@extends('layouts.main.index')

@section('content')
<widget-login class="container d-flex align-items-center justify-content-center min-vh-100">
    <div class="row justify-content-center w-100">
        <div class="col-md-6 col-lg-5">
            
            <div class="card border-0 shadow-lg" style="border-radius: 24px; overflow: hidden; background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px);">
                <div class="card-body p-5 text-center">
                    
                    <div class="mb-4 d-flex justify-content-center">
                        <div style="background: #fff5f5; border-radius: 50%; padding: 20px;">
                            <dotlottie-player
                                src="{{ asset('images/lottie/danger.json') }}"
                                background="transparent"
                                speed="1"
                                style="width: 200px; height: 200px;"
                                loop
                                autoplay
                            ></dotlottie-player>
                        </div>
                    </div>

                    <h3 class="fw-bold text-dark mb-2">Link Telah Kadaluarsa</h3>
                    <p class="text-muted mb-4">Maaf, tautan yang Anda akses sudah tidak berlaku atau telah digunakan sebelumnya.</p>
                    
                    <a href="/" class="btn btn-danger btn-lg px-5 w-100" style="border-radius: 12px; font-weight: 600; transition: 0.3s;">
                        Kembali ke Beranda
                    </a>
                </div>
            </div>

            <div class="mt-4 text-center">
                <p class="text-muted small">
                    Made with ❤️ by <a href="https://instagram.com/anteikudevs" target="_blank" class="text-danger fw-bold text-decoration-none">AnteikuDevs</a>
                </p>
            </div>

        </div>
    </div>
</widget-login>
@endsection

@push('style')
<style>
    body {
        /* Background gradient halus agar terlihat modern */
        background: radial-gradient(circle at top right, #fff5f5 0%, #ffffff 100%);
    }
    .btn-danger {
        background-color: #ff4d4d;
        border: none;
        box-shadow: 0 4px 15px rgba(255, 77, 77, 0.3);
    }
    .btn-danger:hover {
        background-color: #e63946;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 77, 77, 0.4);
    }
</style>
<script
  src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs"
  type="module"
></script>
@endpush