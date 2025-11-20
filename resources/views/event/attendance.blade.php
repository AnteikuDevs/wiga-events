@extends('layouts.main.index')
@section('content')

<template id="attendance-template">
    <div class="text-center mb-3">
        <img src="{{ asset('logo-full.png') }}" alt="" width="300px">
    </div>
    <div class="text-center my-4">
        <h5 class="mb-0">Kehadiran Acara</h5>
        <p class="mb-0">{{ $event->title }}</p>
    </div>
    <div id="wiga-alert"></div>
    <form action="" method="post" id="WigaFormPage">
        <x-forms.input type="text" name="student_id" label="Email"></x-forms.input>
        <x-button type="submit" color="gradient-primary" size="" block>Hadir</x-button>
    </form>
</template>

<template id="success-attendance">
    <dotlottie-player
        src="{{ asset('images/lottie/success.json') }}"
        background="transparent"
        speed="1"
        style="width: 300px; height: 200px; margin: 0 auto;"
        loop
        autoplay
        ></dotlottie-player>

        <h4 class="text-center">Terima Kasih <br> Anda telah melakukan kehadiran</h4>
</template>

<widget-login class="container">

    <div class="row justify-content-center gap-3">
        <div class="login-content">
            
            <div class="card login-box-container">
                <div class="card-body" id="page--content">
                    <small class="text-muted d-block text-center">Memuat ...</small>
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
<style>
    .event-container {
        background: linear-gradient(135deg, #ffffff 0%, #f7f8fa 100%);
        border-radius: 20px;
        border: 1px solid #e9ecef;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.07);
        overflow: hidden; /* Penting untuk menjaga border-radius pada gambar */
    }
    
    .event-image {
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }

    .event-image:hover {
        transform: scale(1.03);
    }

    .event-title {
        font-weight: 800; /* Extra bold */
        color: #2c3e50;
    }
    
    .info-card {
        background-color: rgba(233, 236, 239, 0.5);
        border-radius: 12px;
        padding: 1.25rem;
    }

    .info-item i {
        color: #4a90e2; /* Warna ikon yang lebih lembut */
    }
    
    .btn-gradient {
        background-image: linear-gradient(45deg, #3d84f2 0%, #6f42c1 100%);
        border: none;
        color: white;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(61, 132, 242, 0.4);
    }

    .btn-gradient:hover,
    .btn-gradient:active,
    .btn-gradient:focus {
        color: white !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 7px 20px rgba(111, 66, 193, 0.4) !important;
        background-image: linear-gradient(45deg, #3d84f2 0%, #6f42c1 100%) !important;
    }
    
    /* Pewarnaan badge yang lebih modern */
    .badge.bg-success-light { background-color: rgba(25, 135, 84, 0.1); color: #0f5132; }
    .badge.bg-danger-light { background-color: rgba(220, 53, 69, 0.1); color: #842029; }
    .badge.bg-secondary-light { background-color: rgba(108, 117, 125, 0.1); color: #41464b; }
</style>
<script
  src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs"
  type="module"
></script>
@endpush