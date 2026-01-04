@extends('layouts.main.home')

@section('content')
<div class="container-fluid d-flex align-items-center justify-content-center min-vh-100 position-relative overflow-hidden main-wrapper pt-5">
    
    <div class="glow-1 no-print"></div>
    <div class="glow-2 no-print"></div>

    <div class="row justify-content-center w-100 position-relative" style="z-index: 2;">
        <div class="col-md-7 col-lg-5 text-center">

            <div class="card border-0 shadow-2xl overflow-hidden glass-card animate__animated animate__zoomIn mt-5" id="content--exported">
                
                <div class="status-header text-white p-4 no-print">
                    <div class="icon-wrap mb-2">
                        <i class="fa {{ $event->registration_fee > 0 && $participant->status != 1 ? 'fa-circle' : 'fa-check' }}"></i>
                    </div>
                    <h4 class="fw-bold mb-0">
                        {{ $event->registration_fee > 0 && $participant->status != 1 ? 'Pending Payment' : 'Registration Verified' }}
                    </h4>
                </div>

                <div class="card-body p-4 p-md-5 text-center" id="ticket-area">
                    <div class="mb-5 ticket--info">
                        <span class="badge-premium mb-2">OFFICIAL E-TICKET</span>
                        <h2 class="fw-bold text-white mb-1 name-display">{{ $participant->name }}</h2>
                        <p class="text-gradient-purple small fw-bold mb-0">{{ strtoupper($event->title) }}</p>
                       @if($participant->attendance)
                            <div class="d-flex align-items-center mt-3 p-2 px-3 rounded-pill mx-auto no-print" 
                                style="background: rgba(40, 199, 111, 0.15); border: 1px solid rgba(40, 199, 111, 0.3); width: fit-content;">
                                <div class="bg-success rounded-circle d-flex align-items-center justify-content-center me-2" 
                                    style="width: 20px; height: 20px;">
                                    <i class="fas fa-check text-white" style="font-size: 10px;"></i>
                                </div>
                                <span class="text-success fw-bold" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                                    TELAH BERPARTISIPASI
                                </span>
                            </div>
                        @endif
                    </div>

                    {{-- LOGIKA 1: PEMBAYARAN --}}
                    @if($event->registration_fee > 0 && $participant->status != 1)
                        <div class="payment-section no-print animate__animated animate__fadeIn">
                            <div class="payment-card-dark p-4 rounded-4 mb-4 text-start">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <span class="text-light small">Total Invoice</span>
                                    <span class="fs-4 fw-bold text-cyan">Rp {{ number_format($event->registration_fee, 0, ',', '.') }}</span>
                                </div>
                                
                                <div class="bank-details p-3 rounded-3 mb-4">
                                    <div class="small text-light mb-1">Transfer ke <b>Bank {{ $event->bank_name }}</b></div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span class="fw-bold fs-5 text-white">{{ $event->bank_account_number }}</span>
                                        <button onclick="$wiga('{{ $event->bank_account_number }}').clipboard('No Rekening');" class="btn btn-copy">Copy</button>
                                    </div>
                                    <div class="small text-light mt-2">A/N: <strong>{{ $event->bank_account_name }}</strong></div>
                                </div>

                                @if($participant->proof_of_payment)
                                    @if($participant->status == 2)
                                        <x-alert type="warning" :dismissible="false" class="border border-warning border-dashed">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-clock text-warning fs-2 me-4"></i>
                                                <div class="d-flex flex-column">
                                                    <strong class="text-dark">Pembayaran Anda ditolak</strong>
                                                    <span class="small">Pembayaran Anda telah ditolak oleh admin, silahkan untuk mengirimkan bukti pembayaran kembali melalui form berikut.</span>
                                                </div>
                                            </div>
                                        </x-alert>

                                        <div id="form-payment">
                                            <div id="alert-message"></div>

                                            <x-forms.input type="upload-image" name="proof_of_payment" label="Bukti Pembayaran" accept="image/*"></x-forms.input>

                                            <div id="payment-status"></div>
                                        </div>

                                        <x-button toggle-modal="ModalConfirm" color="main-gradient" class="w-100 py-3 mt-4">Konfirmasi Pembayaran</x-button>
                                    @else
                                    <x-alert type="warning" :dismissible="false" class="border border-warning border-dashed">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-clock text-warning fs-2 me-4"></i>
                                            <div class="d-flex flex-column">
                                                <strong class="text-dark">Bukti Pembayaran Terkirim</strong>
                                                <span class="small">Bukti pembayaran Anda telah berhasil diunggah. Mohon tunggu, saat ini data Anda sedang dalam <strong>antrean verifikasi</strong> oleh admin.</span>
                                            </div>
                                        </div>
                                    </x-alert>
                                    <x-button color="main-gradient" class="w-100 py-3 mt-4" href="{{ route('event.reg-code.generate', ['code' => $participant->reg_code]) }}">Cek Status</x-button>
                                    @endif

                                @else

                                    <div id="form-payment">
                                        <div id="alert-message"></div>

                                        <x-forms.input type="upload-image" name="proof_of_payment" label="Bukti Pembayaran" accept="image/*"></x-forms.input>

                                        <div id="payment-status"></div>
                                    </div>

                                    <x-button toggle-modal="ModalConfirm" color="main-gradient" class="w-100 py-3 mt-4">Konfirmasi Pembayaran</x-button>

                                @endif

                            </div>
                        </div>

                    {{-- LOGIKA 2: SUDAH LUNAS --}}
                    @else

                        @if($participant->proof_of_payment)
                            <x-alert type="success" :dismissible="false" class="border border-success border-dashed text-start no-print">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-check text-success fs-2 me-4"></i>
                                    <div class="d-flex flex-column">
                                        <strong class="text-dark">Pembayaran Anda Terverifikasi</strong>
                                        <span class="small">Pembayaran Anda telah berhasil diverifikasi oleh admin, silahkan cek status pembayaran Anda melalui tombol berikut.</span>
                                    </div>
                                </div>
                            </x-alert>
                        @endif
                        <div class="qr-placeholder mb-4 animate__animated animate__pulse">
                            <div class="ticket-id-box">
                                <div class="dashed-circle-wrap">
                                    <h1 class="display-5 fw-bold text-white mb-0 tracking-tighter">{{ $participant->reg_code }}</h1>
                                </div>
                            </div>
                        </div>

                        @if ($participant->attendance)
                            <x-alert type="success" :dismissible="false" class="border border-success border-dashed text-start no-print">
                                <div class="d-flex align-items-center">
                                    <div class="d-flex flex-column">
                                        <strong class="text-dark">Anda Sudah Hadir</strong>
                                        <span class="small">Anda telah melakukan presensi kehadiran pada acara ini.</span>
                                    </div>
                                </div>
                            </x-alert>
                        @else

                        <div class="my-2 qr--code">
                            <img src="{{ MyQRCode::render(url('/_reg_/'.$participant->reg_code)) }}" alt="QR">
                        </div>

                        
                        <div class="card my-5 text-start no-print glass-card rounded-4">
                            <div class="card-body">
                                <h7 class="fw-bold text-white mb-1">Lokasi Pelaksanaan ({{ $event->type }})</h7>
                                @if ($event->type == 'online')
                                    <p class="small mb-0"><a href="{{ $event->link }}" target="_blank" class="text-decoration-none text-purple fw-bold">{{ $event->link }}</a></p>
                                @else
                                    <p class="small mb-0">{{ $event->location }}</p>
                                @endif
                            </div>
                        </div>

                        @endif

                        <div class="row g-3 text-start mb-4 info-grid mt-2">
                            <div class="col-6">
                                <label>INSTITUTION</label>
                                <span>{{ $participant->agency }}</span>
                            </div>
                            <div class="col-6 text-end">
                                <label>EVENT DATE</label>
                                <span>{{ $event->date_format }}</span>
                            </div>
                        </div>

                        <div class="d-grid gap-2 no-print">
                            {{-- <button id="btn-download" onclick="downloadTicket()" class="btn btn-main-gradient py-3">
                                <i class="bi bi-download me-2"></i> Save Image
                            </button> --}}
                            @if ($participant->attendance)
                                <button class="btn btn-main-gradient py-3" id="btn-generate-cert">
                                    <i class="fa-solid fa-certificate me-2"></i> Cetak Sertifikat
                                </button>
                            @else
                                <button id="btn-copy-link" class="btn btn-outline-glass py-3 mb-3">
                                    <i class="fa-solid fa-link me-2"></i> Salin Link Bukti Pendaftaran
                                </button>
                                <button onclick="window.print()" class="btn btn-main-gradient py-3">
                                    <i class="fa-solid fa-print me-2"></i> Print PDF
                                </button>
                            @endif

                            
                        </div>
                    @endif
                </div>

                {{-- <div class="notch n-left no-print"></div>
                <div class="notch n-right no-print"></div> --}}
            </div>

            <div class="my-5 no-print">
                <a href="{{ route('event') }}" class="back-link small">
                    <i class="fa-solid fa-arrow-left me-2"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>

<x-modal id="ModalConfirm" title="Konfirmasi" class="modal-modern-dark">
    <form class="modal-body-custom px-3">
        <div id="ModalConfirmAlert"></div>

        <div class="confirmation-box text-center p-4 rounded-4 bg-glass-dark border border-warning-subtle">
            <i class="fas fa-exclamation-triangle text-warning display-4 mb-3"></i>
            <h5 class="text-white fw-bold mb-2">Konfirmasi Bukti Bayar</h5>
            <p class="text-muted small mb-4">
                Pastikan bukti transfer yang Anda unggah sudah benar, terlihat jelas, dan sesuai dengan nominal pendaftaran.
            </p>
        </div>

        <div class="d-flex w-100 gap-3 mt-4">
            <x-button dismiss-modal color="outline-light" class="rounded-pill w-50">Kembali</x-button>
            <x-button color="primary-gradient" class="rounded-pill w-50 shadow-glow" type="submit">
                <span class="indicator-label">Kirim Sekarang</span>
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
    @import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;500;700&family=Plus+Jakarta+Sans:wght@400;600;800&display=swap');

    :root {
        --bg-dark: #020617;
        --card-dark: #0f172a;
         --card-bg: rgb(15, 17, 33);
         --glass-border: rgba(255, 255, 255, 0.08);
        --accent-purple: #8b5cf6;
        --accent-blue: #3b82f6;
        --accent-cyan: #22d3ee;
        --text-muted: #94a3b8;
        --glass: rgba(30, 41, 59, 0.7);
    }

    body { 
        background-color: var(--bg-dark); 
        color: #f8fafc;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .main-wrapper {
        background: radial-gradient(circle at 50% 50%, #1e1b4b 0%, #020617 100%);
    }

    .modal-content {
        background-color: var(--card-bg);
        border: 1px solid var(--glass-border);
        border-radius: 30px;
        color: white;
    }

    /* Glow Effects */
    .glow-1 {
        position: absolute; width: 500px; height: 500px;
        background: radial-gradient(circle, rgba(139, 92, 246, 0.15) 0%, transparent 70%);
        top: -10%; left: -10%; z-index: 1;
    }
    .glow-2 {
        position: absolute; width: 600px; height: 600px;
        background: radial-gradient(circle, rgba(59, 130, 246, 0.1) 0%, transparent 70%);
        bottom: -10%; right: -10%; z-index: 1;
    }

    /* Modern Glass Card */
    .glass-card {
        background: var(--glass) !important;
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        border-radius: 40px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    }

    .status-header {
        background: linear-gradient(90deg, rgba(139, 92, 246, 0.2), rgba(59, 130, 246, 0.2));
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    .icon-wrap i {
        font-size: 2.5rem;
        background: linear-gradient(to right, #a78bfa, #60a5fa);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .name-display { letter-spacing: -0.02em; font-family: 'Space Grotesk', sans-serif; }
    
    .text-gradient-purple {
        background: linear-gradient(to right, #c084fc, #6366f1);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .badge-premium {
        display: inline-block;
        padding: 4px 12px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 100px;
        font-size: 10px;
        letter-spacing: 2px;
        color: var(--text-muted);
    }

    /* Ticket Notch Dark */
    .notch {
        position: absolute; width: 40px; height: 40px;
        background: transparent; border-radius: 50%;
        top: 72%; z-index: 10;
        border: 1px solid rgba(255, 255, 255, 0.05);
    }
    .n-left { left: -20px; box-shadow: inset -10px 0 15px rgba(0,0,0,0.3); }
    .n-right { right: -20px; box-shadow: inset 10px 0 15px rgba(0,0,0,0.3); }
    
    .ticket-line {
        position: absolute; top: 75.5%; left: 25px; right: 25px;
        border-top: 2px dashed rgba(255,255,255,0.1);
    }

    /* Payment Section */
    .payment-card-dark {
        background: rgba(15, 23, 42, 0.6);
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    .bank-details { background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.05); }
    .text-cyan { color: var(--accent-cyan); }

    .btn-copy {
        background: rgba(34, 211, 238, 0.1);
        color: var(--accent-cyan);
        border: none; padding: 2px 12px; border-radius: 8px; font-size: 12px;
    }

    .upload-zone {
        border: 2px dashed rgba(139, 92, 246, 0.3);
        border-radius: 20px;
        transition: 0.3s;
    }
    .upload-zone:hover { border-color: var(--accent-purple); background: rgba(139, 92, 246, 0.05); }
    .file-input { display: none; }

    /* Buttons */
    .btn-main-gradient {
        background: linear-gradient(135deg, var(--accent-purple) 0%, var(--accent-blue) 100%);
        color: white; border: none; border-radius: 16px;
        font-weight: 700; transition: all 0.3s;
        box-shadow: 0 10px 20px -5px rgba(139, 92, 246, 0.5);
    }
    .btn-main-gradient:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 30px -5px rgba(139, 92, 246, 0.6);
        color: white;
    }

    .btn-outline-glass {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: white; border-radius: 16px; transition: 0.3s;
    }
    .btn-outline-glass:hover { background: rgba(255, 255, 255, 0.08); color: white; }

    .info-grid label { display: block; font-size: 9px; color: var(--text-muted); letter-spacing: 1px; margin-bottom: 4px; }
    .info-grid span { color: white; font-weight: 600; font-size: 14px; }

    .back-link { color: var(--text-muted); text-decoration: none; transition: 0.3s; }
    .back-link:hover { color: white; }

    .alert-modern {
        background: rgba(34, 211, 238, 0.1);
        color: var(--accent-cyan);
        padding: 10px; border-radius: 12px; font-size: 12px;
        border: 1px solid rgba(34, 211, 238, 0.2);
    }

    .filter-white { filter: brightness(0) invert(1); }
    .text-purple {
    color: var(--accent-purple) !important;
}

.dropzone-area {
    background: var(--card-bg) !important;
    border: 1px solid var(--glass-border) !important;
    border-radius: 20px !important;
    color: white !important;
    padding: 20px !important;
    text-align: center !important;
}

.wiga-uploader-container .text-dark {
    color: #fff !important;
}
.wiga-uploader-container .text-muted {
    color: #fff !important;
}

.wiga-uploader-container .fa-solid.fa-cloud-arrow-up {
    color: var(--accent-purple) !important;
}

.wiga-uploader-container .file-item-card {
    background: var(--card-bg) !important;
    border: 1px solid var(--glass-border) !important;
    border-radius: 20px !important;
    color: white !important;
}

.wiga-uploader-container .delete-btn {
    color: #fff !important;
}

.qr--code {
    background: var(--card-bg) !important;
    border: 1px solid var(--glass-border) !important;
    border-radius: 20px !important;
    color: white !important;
    padding: 20px !important;
    text-align: center !important;
    width: 60% !important;
    margin-bottom: 1rem;
    margin-left: auto;
    margin-right: auto;
}

.qr--code img {
    width: 100% !important;
}

@media print {

    @page {
        margin: 0;
        /* Ukuran Kustom: Lebar 80mm, Tinggi 150mm (contoh Tiket) */
        size: 60mm 150mm; 
    }


    .qr-placeholder .display-3 {
        font-size: 1.5rem !important;
    }

    .glass-card {
        width: 100% !important;
        height: 100% !important;
        border: none !important;
        box-shadow: none !important;
        background: white !important; /* Browser biasanya mematikan background-color gelap saat print */
        color: black !important;
    }
    
    .no-print {
        display: none !important;
    }

    body { background: white !important; color: black !important; margin: 0;
        padding: 0;}
    .glass-card { background: white !important; border: 1px solid #ddd !important; box-shadow: none !important; color: black !important; }
    .text-white, .name-display, .display-3 { color: black !important; }
    .text-gradient-purple { background: unset !important; color: black !important; -webkit-background-clip: unset !important;
        -webkit-text-fill-color: unset !important;font-size:11px !important }
    .info-grid label { color: black !important; font-size: 8px !important; }
    .info-grid span { color: black !important; font-size: 10px !important; }
    .container-fluid {
        padding: 0 !important;
        padding-top: 0 !important;
    }

    .ticket--info {
        margin-bottom: 10px !important;
    }

    .col-md-7,.col-lg-5 {
        width: 100% !important;
        /* padding: 10px !important; */
    }

    .qr--code {
        padding: 0 !important;
        width: 50% !important;
    }

    .card-body {
        padding: 10px !important;
    }

    .card {
        margin: 0 !important;
        border-radius: 0 !important;
        border: 0 !important;
    }
}
</style>
@endpush