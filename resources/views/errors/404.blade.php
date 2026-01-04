@extends('layouts.main.index')

@section('title', '404 - Halaman Tidak Ditemukan')

@section('content')
<div class="container-fluid d-flex align-items-center justify-content-center min-vh-100 position-relative overflow-hidden" style="background-color: #f0f4f8;">
    
    <div class="bg-blob shadow-lg"></div>
    <div class="bg-blob-2 shadow-sm"></div>

    <div class="text-center position-relative" style="z-index: 2;">
        <div class="error-container mb-2">
            <h1 class="error-title">404</h1>
        </div>

        <div class="content-card px-4 py-5 bg-white shadow-xl" style="border-radius: 30px; max-width: 500px;">
            <h2 class="fw-bold text-dark-blue mb-3">Halaman Tidak Ditemukan</h2>
            <p class="text-secondary-blue mb-5">
                Opps! Sepertinya Anda telah melangkah ke ruang hampa. Halaman yang Anda cari mungkin telah dipindahkan atau tidak lagi tersedia.
            </p>
            
            <div class="d-flex flex-column gap-3">
                <a href="/" class="btn btn-primary-blue py-3 shadow-blue">
                    <i class="bi bi-house-door me-2"></i>Kembali ke Beranda
                </a>
                <a href="javascript:history.back()" class="btn btn-outline-soft-blue py-2 text-decoration-none">
                    <i class="bi bi-arrow-left me-2"></i>Halaman Sebelumnya
                </a>
            </div>
        </div>

        <div class="mt-5">
            <p class="text-muted small">
                Handcrafted by <a href="https://instagram.com/anteikudevs" target="_blank" class="text-primary-blue fw-bold text-decoration-none">AnteikuDevs</a>
            </p>
        </div>
    </div>
</div>
@endsection

@push('style')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap');

    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    /* Warna Kustom Biru */
    :root {
        --primary-blue: #0061ff;
        --dark-blue: #1e293b;
        --secondary-blue: #64748b;
        --soft-blue: #e0eaff;
    }

    .text-dark-blue { color: var(--dark-blue); }
    .text-secondary-blue { color: var(--secondary-blue); }
    .text-primary-blue { color: var(--primary-blue); }

    /* Angka 404 Besar */
    .error-title {
        font-size: clamp(6rem, 15vw, 10rem);
        font-weight: 800;
        margin: 0;
        background: linear-gradient(135deg, #0061ff 0%, #60efff 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        letter-spacing: -8px;
        filter: drop-shadow(0 10px 10px rgba(0, 97, 255, 0.1));
    }

    /* Kartu Konten */
    .content-card {
        border: 1px solid rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(10px);
        transform: translateY(0);
        transition: all 0.4s ease;
    }

    /* Tombol Utama */
    .btn-primary-blue {
        background: var(--primary-blue);
        color: white;
        border: none;
        border-radius: 15px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-primary-blue:hover {
        background: #0056e0;
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0, 97, 255, 0.3);
        color: white;
    }

    .btn-outline-soft-blue {
        color: var(--primary-blue);
        font-weight: 600;
    }

    /* Elemen Dekoratif (Blob) */
    .bg-blob {
        position: absolute;
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(0, 97, 255, 0.08) 0%, rgba(255, 255, 255, 0) 70%);
        top: -100px;
        right: -100px;
        border-radius: 50%;
    }

    .bg-blob-2 {
        position: absolute;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(96, 239, 255, 0.1) 0%, rgba(255, 255, 255, 0) 70%);
        bottom: -100px;
        left: -100px;
        border-radius: 50%;
    }

    /* Animasi Entry */
    .error-container, .content-card {
        animation: slideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(40px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 576px) {
        .error-title { letter-spacing: -4px; }
    }
</style>
@endpush