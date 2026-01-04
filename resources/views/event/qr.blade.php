@extends('layouts.main.index')

@section('content')
<div class="container-fluid d-flex align-items-center justify-content-center min-vh-100 position-relative overflow-hidden" style="background-color: #f0f4f8;">
    
    <div class="bg-blob shadow-lg no-print"></div>
    <div class="bg-blob-2 shadow-sm no-print"></div>

    <div class="row justify-content-center w-100 position-relative" style="z-index: 2;">
        <div class="col-md-5 col-lg-4 text-center">
            
            <div class="mb-4">
                <img src="{{ asset('logo-full.png') }}" alt="Logo" width="180px" class="opacity-75">
            </div>

            <div class="card border-0 shadow-xl overflow-hidden print-card" style="border-radius: 30px; background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px);">
                <div class="card-body p-5">
                    <h4 class="fw-bold text-dark-blue mb-1">Scan Kehadiran</h4>
                    <p class="text-secondary-blue small mb-4">{{ $event->title }}</p>

                    <div class="qr-wrapper mb-4">
                        <div class="qr-scanner-line no-print"></div>
                        
                        <div class="qr-frame-edge top-left"></div>
                        <div class="qr-frame-edge top-right"></div>
                        <div class="qr-frame-edge bottom-left"></div>
                        <div class="qr-frame-edge bottom-right"></div>
                        
                        <div class="qr-code-container shadow-sm p-4">
                            {{-- Image QR Code --}}
                            <img src="{{ asset($qr_path)}}" alt="QR Code Kehadiran" width="100%">
                        </div>
                    </div>

                    <div class="alert bg-soft-blue border-0 text-primary-blue small py-2 mb-0 no-print" style="border-radius: 12px;">
                        <i class="bi bi-info-circle me-2"></i> Arahkan kamera Anda ke QR Code
                    </div>

                    <div class="d-grid gap-2 mt-4 no-print">
                        <button onclick="window.print()" class="btn btn-primary-blue py-3 shadow-blue">
                            <i class="bi bi-file-earmark-pdf me-2"></i> Unduh / Cetak PDF
                        </button>
                    </div>
                </div>
            </div>

            <div class="mt-4 no-print">
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
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0, 97, 255, 0.2);
        color: white;
    }

    /* QR Wrapper & Frame */
    .qr-wrapper {
        position: relative;
        padding: 20px;
        display: inline-block;
        background: white;
        border-radius: 20px;
    }

    .qr-scanner-line {
        position: absolute;
        top: 20px;
        left: 20px;
        right: 20px;
        height: 2px;
        background: var(--primary-blue);
        box-shadow: 0 0 15px var(--primary-blue);
        z-index: 10;
        animation: scan 3s linear infinite;
    }

    @keyframes scan {
        0%, 100% { top: 20px; }
        50% { top: calc(100% - 20px); }
    }

    .qr-frame-edge {
        position: absolute;
        width: 30px;
        height: 30px;
        border: 4px solid var(--primary-blue);
        z-index: 5;
    }
    .top-left { top: 0; left: 0; border-right: 0; border-bottom: 0; border-radius: 15px 0 0 0; }
    .top-right { top: 0; right: 0; border-left: 0; border-bottom: 0; border-radius: 0 15px 0 0; }
    .bottom-left { bottom: 0; left: 0; border-right: 0; border-top: 0; border-radius: 0 0 0 15px; }
    .bottom-right { bottom: 0; right: 0; border-left: 0; border-top: 0; border-radius: 0 0 15px 0; }

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

    .qr-code-container image {
        width: 80%;
    }

    /* ==========================================
       CSS KHUSUS PRINT / PDF
    ========================================== */
    @media print {
        @page {
            size: portrait;
            margin: 20mm;
        }
        
        body { background: white !important; }
        .no-print { display: none !important; }
        
        .container-fluid {
            background: white !important;
            padding: 0 !important;
            display: block !important;
        }

        .print-card {
            background: white !important;
            box-shadow: none !important;
            border: none !important;
            backdrop-filter: none !important;
            margin-top: 0 !important;
        }

        .qr-wrapper {
            border: 1px solid #eee !important;
            padding: 30px !important;
        }

        .qr-frame-edge {
            border-color: #333 !important; /* Warna frame lebih gelap saat diprint agar kontras */
        }

        .qr-code-container {
            padding: 0 !important;
        }

        .qr-code-container image {
            width: 450px;
        }

        .text-dark-blue { color: #000 !important; }
        .text-secondary-blue { color: #555 !important; }

        .col-md-5,.col-lg-4 {
            width: 100% !important;
        }
    }
</style>
@endpush