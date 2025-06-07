@extends('layouts.main.index')
@section('content')

<div class="container my-5">
    <div class="p-4 p-lg-5 event-container">
        <div class="row g-5 align-items-center" id="event--content">
            <small class="text-muted d-block text-center">Memuat ... </small>
        </div>
    </div>
</div>

<x-modal 
    id="ModalRegister"
    title="Daftar">

    <div id="ModalRegisterAlert"></div>

    <x-forms.input type="text" name="student_id" label="NIM"></x-forms.input>
    <x-forms.input type="text" name="name" label="Nama Lengkap"></x-forms.input>
    <x-forms.input type="text" name="parallel_class" label="Kelas"></x-forms.input>
    {{-- <x-forms.input type="text" name="email" label="Email"></x-forms.input> --}}
    <x-forms.input type="text" name="phone_number" label="Nomor Telepon" placeholder="Contoh: 08123456789"></x-forms.input>

    <x-alert type="info" :dismissible=false>
        <strong>Perhatian:</strong> Pastikan Nomor Telepon yang Anda cantumkan sudah benar. Sertifikat kelulusan/partisipasi akan dikirim melalui nomor telepon ini.
    </x-alert>

    <x-slot name="footer">
        <x-button dismiss-modal color="secondary">Batal</x-button>
        <x-button color="primary" toggle-modal="ModalConfirm">Daftar</x-button>
    </x-slot>

</x-modal>

<x-modal 
    id="ModalConfirm"
    title="Konfirmasi"
    withForm="true">

    <div id="ModalConfirmAlert"></div>

    <x-alert type="warning" dismissible="false">
        <strong>Perhatian:</strong> Apakah data yang anda daftarkan sudah benar?
    </x-alert>

    <x-slot name="footer">
        <x-button dismiss-modal color="secondary" toggle-modal="ModalRegister">Batal</x-button>
        <x-button type="submit" color="success" indicator>Kirim</x-button>
    </x-slot>

</x-modal>
    
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

    .location-text {
        overflow-wrap: break-word;
        word-wrap: break-word; 
        max-width: 55%;
    }

    @media (min-width: 992px) {
        .location-text {
            max-width: 100%;
        }
    }

    .text-break-url {
        overflow-wrap: break-word;
        word-wrap: break-word;
    }
</style>
@endpush