@extends('layouts.main.index')

@section('content')

<template id="attendance-template">
    <div class="text-center mb-4">
        <img src="{{ asset('logo-full.png') }}" alt="Logo" width="200px" class="opacity-75 mb-3">
        <div class="px-2">
            <h4 class="fw-bold text-dark-blue mb-1">Presensi Kehadiran</h4>
            <p class="text-secondary-blue small">{{ $event->title }}</p>
        </div>
    </div>

    <div id="wiga-alert"></div>

    <form action="" method="post" id="WigaFormPage" class="px-2">
        <div class="mb-4">
            <div class="form-group mb-6">
                <label class="form-label fs-6 fw-bold mb-3">Nomor Registrasi</label>
                <div class="input-group input-group-lg">
                    <span class="input-group-text border-2 bg-light text-primary fw-bold" 
                        style="border-radius: 12px 0 0 12px; border-color: #e0eaff; border-right: none;">
                        REG-
                    </span>
                    
                    <input 
                        type="text" 
                        name="reg_code" 
                        class="form-control form-control-lg border-2 shadow-none" 
                        placeholder="Masukkan kode registrasi Anda"
                        style="border-radius: 0 12px 12px 0 !important; border-color: #e0eaff;"
                    >
                </div>
                <div class="d-flex justify-content-between">
                    <div class="form-text text-muted mt-2">Contoh: REG-123456</div>
                    <div data-error="reg_code"></div>
                </div>
            </div>
        </div>
        
        <x-button type="submit" color="primary-blue" size="lg" block class="py-3 shadow-blue w-100 fw-bold" style="border-radius: 12px;">
            Konfirmasi Kehadiran
        </x-button>
    </form>
</template>

<template id="success-attendance">
    <div class="text-center py-4">
        <div class="mb-2">
            <dotlottie-player
                src="{{ asset('images/lottie/success.json') }}"
                background="transparent"
                speed="1"
                style="width: 250px; height: 250px; margin: 0 auto;"
                loop
                autoplay
            ></dotlottie-player>
        </div>
        <h4 class="fw-bold text-dark-blue mt-n3">Berhasil!</h4>
        <p class="text-secondary-blue px-3">Terima kasih. Data kehadiran Anda telah berhasil direkam dalam sistem kami.</p>
        
        <div class="mt-4">
             <a href="/" class="btn btn-outline-soft-blue px-4" style="border-radius: 10px;">Kembali ke Beranda</a>
        </div>
    </div>
</template>

<div class="container-fluid d-flex align-items-center justify-content-center min-vh-100 position-relative overflow-hidden" style="background-color: #f0f4f8;">
    
    <div class="bg-blob shadow-lg"></div>
    <div class="bg-blob-2 shadow-sm"></div>

    <div class="row justify-content-center w-100 position-relative" style="z-index: 2;">
        <div class="col-md-5 col-lg-4">
            
            <widget-login>
                <div class="card border-0 shadow-xl overflow-hidden" style="border-radius: 30px; background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px);">
                    <div class="card-body p-4 p-md-5" id="page--content">
                        <div class="text-center py-5">
                            <div class="spinner-border text-primary-blue mb-3" role="status"></div>
                            <small class="text-muted d-block">Menyiapkan sistem presensi...</small>
                        </div>
                    </div>
                </div>
            </widget-login>

            <div class="mt-4 text-center">
                <p class="text-muted small">
                    Made by ❤️ <a href="https://instagram.com/anteikudevs" target="_blank" class="text-primary-blue fw-bold text-decoration-none">AnteikuDevs</a>
                </p>
            </div>
        </div>
    </div>
</div>

@endsection

@push('style')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap');

    body { font-family: 'Plus Jakarta Sans', sans-serif; }

    :root {
        --primary-blue: #0061ff;
        --dark-blue: #1e293b;
        --secondary-blue: #64748b;
        --soft-blue: #e0eaff;
    }

    .text-dark-blue { color: var(--dark-blue); }
    .text-secondary-blue { color: var(--secondary-blue); }
    .text-primary-blue { color: var(--primary-blue); }
    .bg-soft-blue { background-color: var(--soft-blue); }
    
    .spinner-border { width: 3rem; height: 3rem; }

    /* Custom Button Style */
    .btn-primary-blue {
        background: var(--primary-blue);
        color: white;
        border: none;
        transition: all 0.3s ease;
    }

    .btn-primary-blue:hover {
        background: #0056e0;
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0, 97, 255, 0.2) !important;
        color: white;
    }

    .btn-outline-soft-blue {
        border: 2px solid var(--soft-blue);
        color: var(--primary-blue);
        font-weight: 600;
        transition: all 0.2s ease;
    }
    
    .btn-outline-soft-blue:hover {
        background: var(--soft-blue);
        color: var(--primary-blue);
    }

    /* Blob Decorations */
    .bg-blob {
        position: absolute;
        width: 600px; height: 600px;
        background: radial-gradient(circle, rgba(0, 97, 255, 0.08) 0%, rgba(255, 255, 255, 0) 70%);
        top: -150px; right: -150px; border-radius: 50%;
    }
    .bg-blob-2 {
        position: absolute;
        width: 500px; height: 500px;
        background: radial-gradient(circle, rgba(96, 239, 255, 0.1) 0%, rgba(255, 255, 255, 0) 70%);
        bottom: -150px; left: -150px; border-radius: 50%;
    }

    .shadow-xl { box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1); }
    .shadow-blue { box-shadow: 0 4px 15px rgba(0, 97, 255, 0.3); }

    /* Input focus custom */
    input:focus {
        border-color: var(--primary-blue) !important;
        background-color: #fff !important;
    }
</style>
<script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs" type="module"></script>
@endpush