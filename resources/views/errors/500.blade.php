@extends('layouts.main.index')

@section('title', '500 - Server Error')

@section('content')
<div class="container-fluid d-flex align-items-center justify-content-center min-vh-100 position-relative overflow-hidden" style="background-color: #f0f4f8;">
    
    <div class="text-center position-relative" style="z-index: 2;">
        <div class="server-icon-wrapper mb-4">
            <div class="server-pulse"></div>
            <i class="bi bi-cpu text-primary-blue" style="font-size: 4rem;"></i>
        </div>

        <div class="content-card px-4 py-5 bg-white shadow-xl" style="border-radius: 30px; max-width: 550px;">
            <h1 class="fw-black text-dark-blue display-4 mb-2">500</h1>
            <h3 class="fw-bold text-dark-blue mb-3">Kesalahan Server Internal</h3>
            
            <p class="text-secondary-blue mb-5">
                Tim teknis kami sedang menangani masalah ini. Sepertinya server kami sedang mengalami sedikit gangguan teknis. Harap coba beberapa saat lagi.
            </p>
            
            <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                <a href="{{ url()->current() }}" class="btn btn-primary-blue py-3 px-4 shadow-blue">
                    <i class="bi bi-arrow-clockwise me-2"></i>Muat Ulang Halaman
                </a>
                <a href="/" class="btn btn-outline-soft-blue py-3 px-4">
                    Ke Beranda Utama
                </a>
            </div>
        </div>

        <div class="mt-5">
            <p class="text-muted small">
                Jika masalah berlanjut, hubungi <a href="teguhsugiarto.webdev@gmail.com" class="text-primary-blue fw-bold text-decoration-none">Technical Support</a>
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

    :root {
        --primary-blue: #0061ff;
        --dark-blue: #1e293b;
        --secondary-blue: #64748b;
        --soft-blue: #e0eaff;
    }

    .fw-black { font-weight: 800; }
    .text-dark-blue { color: var(--dark-blue); }
    .text-secondary-blue { color: var(--secondary-blue); }
    .text-primary-blue { color: var(--primary-blue); }

    /* Card & Animation */
    .content-card {
        border: 1px solid rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(10px);
        animation: scaleIn 0.5s cubic-bezier(0.16, 1, 0.3, 1);
    }

    /* Icon Server dengan Animasi Pulse */
    .server-icon-wrapper {
        position: relative;
        display: inline-block;
        padding: 20px;
    }

    .server-pulse {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 80px;
        height: 80px;
        background: rgba(0, 97, 255, 0.1);
        border-radius: 50%;
        animation: pulseAnimation 2s infinite;
    }

    @keyframes pulseAnimation {
        0% { transform: translate(-50%, -50%) scale(1); opacity: 1; }
        100% { transform: translate(-50%, -50%) scale(2); opacity: 0; }
    }

    /* Buttons */
    .btn-primary-blue {
        background: var(--primary-blue);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-primary-blue:hover {
        background: #0056e0;
        transform: translateY(-2px);
        box-shadow: 0 8px 15px rgba(0, 97, 255, 0.3);
        color: white;
    }

    .btn-outline-soft-blue {
        background: white;
        color: var(--primary-blue);
        border: 2px solid var(--soft-blue);
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-outline-soft-blue:hover {
        background: var(--soft-blue);
        border-color: var(--soft-blue);
    }

    /* Decorative Background */
    .bg-blob-tech {
        position: absolute;
        width: 600px;
        height: 600px;
        background: radial-gradient(circle, rgba(0, 97, 255, 0.05) 0%, rgba(240, 244, 248, 0) 70%);
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 1;
    }

    @keyframes scaleIn {
        from { opacity: 0; transform: scale(0.9); }
        to { opacity: 1; transform: scale(1); }
    }
</style>
@endpush