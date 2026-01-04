@extends('layouts.main.home')
@section('content')

<div class="container py-5 min-vh-100 detail-event-wrapper">
    <div class="row mb-5 mt-5 animate__animated animate__fadeIn">
        <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 custom-breadcrumb-dark">
                    <li class="breadcrumb-item"><a href="/">Beranda</a></li>
                    <li class="breadcrumb-item active">Detail Event</li>
                </ol>
            </nav>
           
        </div>
    </div>

    <div class="event-card-wrapper animate__animated animate__fadeInUp">
        <div class="event-container-dark overflow-hidden p-4 p-lg-5">
            <div class="row g-4 lg-g-5" id="event--content">
                <div class="col-12 py-5 text-center">
                    <div class="loader-custom-purple mx-auto mb-4"></div>
                    <p class="text-secondary fw-medium fs-5">Menyiapkan pengalaman event terbaik...</p>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-5 pt-5 text-center border-top-glass">
        <p class="text-light small">
            Crafted with ❤️ by <a href="https://instagram.com/anteikudevs" target="_blank" class="footer-link-purple">AnteikuDevs</a>
        </p>
    </div>
</div>

<x-modal id="ModalRegister" title="Pendaftaran Peserta" class="modal-modern-dark">
    <div class="modal-body-custom px-3">
        <div class="text-center mb-4">
            <h4 class="fw-bold text-white mb-1">Lengkapi Data Diri</h4>
            <p class="text-muted small">Pastikan data sesuai untuk kebutuhan sertifikat digital</p>
        </div>
        
        <div id="ModalRegisterAlert"></div>
        
        <div class="row g-3">
            <div class="col-12">
                <x-forms.input type="text" name="name" label="Nama Lengkap" class="form-control-dark" placeholder="Contoh: Teguh Sugiarto"></x-forms.input>
            </div>
            <div class="col-12">
                <x-forms.input type="text" name="agency" label="Instansi" class="form-control-dark" placeholder="Contoh: ITB Widya Gama Lumajang"></x-forms.input>
            </div>
            <div class="col-md-6">
                <x-forms.input type="email" name="email" label="Email Aktif" class="form-control-dark" placeholder="nama@email.com"></x-forms.input>
            </div>
            <div class="col-md-6">
                <x-forms.input type="text" name="phone_number" label="WhatsApp" class="form-control-dark" placeholder="0812xxxx"></x-forms.input>
            </div>
        </div>

        <div class="info-banner-dark mt-4">
            <div class="d-flex align-items-center">
                <i class="fas fa-shield-alt fs-4 me-3 text-purple"></i>
                <span class="small">Sertifikat digital akan dikirim otomatis ke WhatsApp Anda setelah event selesai.</span>
            </div>
        </div>
    </div>

    <x-slot name="footer">
        <div class="d-flex w-100 gap-3 px-3 pb-3">
            <x-button dismiss-modal color="outline-light" class="rounded-pill w-50">Batal</x-button>
            <x-button color="primary-gradient" toggle-modal="ModalConfirm" class="rounded-pill w-50 shadow-glow">Lanjutkan</x-button>
        </div>
    </x-slot>
</x-modal>

<x-modal id="ModalConfirm" title="Konfirmasi" class="modal-modern-dark">
    <form class="modal-body-custom px-3">
        <div id="ModalConfirmAlert"></div>

        <div class="confirmation-box text-center p-4 rounded-4 bg-glass-dark border border-warning-subtle">
            <i class="fas fa-exclamation-triangle text-warning display-4 mb-3"></i>
            <h5 class="text-white fw-bold">Periksa Kembali Data Anda</h5>
            <p class="text-muted small mb-0">Apakah data yang Anda masukkan sudah benar? Data ini akan digunakan untuk pencetakan e-sertifikat.</p>
        </div>

        <div class="d-flex w-100 gap-3 mt-4">
            <x-button toggle-modal="ModalRegister" color="outline-light" class="rounded-pill w-50">Kembali</x-button>
            <x-button color="primary-gradient" class="rounded-pill w-50 shadow-glow" type="submit">
                <span class="indicator-label">Daftar Sekarang</span>
                <span class="indicator-progress">
                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                </span>
            </x-button>
        </div>
    </form>
</x-modal>

@endsection

@push('style')
<style>
    :root {
        --deep-bg: #05060f;
        --card-bg: #0f1121;
        --accent-purple: #7c4dff;
        --accent-blue: #448aff;
        --glass-border: rgba(255, 255, 255, 0.08);
        --primary-gradient: linear-gradient(135deg, #7c4dff 0%, #448aff 100%);
    }

    body {
        background-color: var(--deep-bg);
        color: #f8f9fa;
    }

    /* Custom Breadcrumb Dark */
    .custom-breadcrumb-dark {
        background: rgba(255, 255, 255, 0.05);
        padding: 10px 25px;
        border-radius: 100px;
        backdrop-filter: blur(10px);
        border: 1px solid var(--glass-border);
    }
    .custom-breadcrumb-dark a { color: var(--accent-blue); text-decoration: none; }
    .custom-breadcrumb-dark .breadcrumb-item.active { color: #fff; }

    /* Status Badge Modern */
    .status-badge-modern {
        background: rgba(16, 185, 129, 0.1);
        border: 1px solid rgba(16, 185, 129, 0.4);
        padding: 8px 20px;
        border-radius: 100px;
        color: #10b981;
        display: flex;
        align-items: center;
    }

    /* Event Container Dark */
    .event-container-dark {
        background: var(--card-bg);
        border-radius: 40px;
        border: 1px solid var(--glass-border);
        box-shadow: 0 40px 100px -20px rgba(0, 0, 0, 0.5);
    }

    /* Loader */
    .loader-custom-purple {
        width: 50px; height: 50px;
        border: 4px solid rgba(124, 77, 255, 0.1);
        border-left-color: var(--accent-purple);
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    /* Modal Styling Dark */
    .modal-modern-dark .modal-content {
        background-color: var(--card-bg);
        border: 1px solid var(--glass-border);
        border-radius: 30px;
        color: white;
    }
    
    .form-control-dark {
        background: rgba(255, 255, 255, 0.05) !important;
        border: 1px solid var(--glass-border) !important;
        color: white !important;
        border-radius: 12px !important;
        /* padding: 12px 15px !important; */
    }
    
    .form-control-dark:focus {
        border-color: var(--accent-purple) !important;
        box-shadow: 0 0 0 4px rgba(124, 77, 255, 0.1) !important;
    }

    /* Info Banner Dark */
    .info-banner-dark {
        background: rgba(68, 138, 255, 0.05);
        border-left: 4px solid var(--accent-blue);
        padding: 20px;
        border-radius: 15px;
        color: #ccd6f6;
    }

    .bg-glass-dark { background: rgba(0,0,0,0.2); }
    .text-purple { color: var(--accent-purple); }

    /* Buttons */
    .btn-primary-gradient {
        background: var(--primary-gradient);
        border: none;
        color: white;
        font-weight: 600;
        transition: 0.3s;
    }
    .btn-primary-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(124, 77, 255, 0.4);
        color: white;
    }
    .shadow-glow { box-shadow: 0 0 15px rgba(124, 77, 255, 0.2); }

    .border-top-glass { border-top: 1px solid var(--glass-border); }
    .footer-link-purple { color: #ff4d4d; font-weight: 700; text-decoration: none; }

    @keyframes spin { 100% { transform: rotate(360deg); } }
    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4); }
        70% { box-shadow: 0 0 0 10px rgba(16, 185, 129, 0); }
        100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }
    .pulse-icon {
        width: 8px; height: 8px; background: #10b981; border-radius: 50%;
        margin-right: 12px; animation: pulse 2s infinite;
    }

    /* Styling untuk Info Card di dalam Detail Event */
.info-card-dark {
    background: rgba(255, 255, 255, 0.03) !important;
    border: 1px solid rgba(255, 255, 255, 0.08) !important;
    backdrop-filter: blur(10px);
}

.info-item i {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(124, 77, 255, 0.1) !important;
    border-radius: 12px;
    color: var(--accent-purple) !important;
    font-size: 1.1rem;
}

/* Quota Badge Modern */
.quota-badge {
    padding: 6px 16px;
    border-radius: 50px;
    font-size: 0.8rem;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    margin-right: 10px;
}
.quota-badge.limited { background: rgba(68, 138, 255, 0.15); color: var(--accent-blue); border: 1px solid var(--accent-blue); }
.quota-badge.full { background: rgba(239, 68, 68, 0.15); color: #ef4444; border: 1px solid #ef4444; }
.quota-badge.unlimited { background: rgba(168, 85, 247, 0.15); color: #a855f7; border: 1px solid #a855f7; }

/* Progress Bar Custom */
.progress-container-dark {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 20px;
    overflow: hidden;
}

/* Typography Enhancements */
.text-break-url {
    word-break: break-all;
    color: var(--accent-blue);
    text-decoration: none;
}
.text-break-url:hover { text-decoration: underline; }

.event-title-gradient {
    font-weight: 800;
    line-height: 1.1;
    color: #fff;
    text-shadow: 0 10px 20px rgba(0,0,0,0.3);
}

/* Action Button */
.btn-gradient-glow {
    background: var(--primary-gradient);
    border: none;
    color: white;
    font-weight: 700;
    transition: all 0.4s;
    box-shadow: 0 10px 25px rgba(124, 77, 255, 0.3);
}
.btn-gradient-glow:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(124, 77, 255, 0.5);
    color: #fff;
}


</style>
@endpush