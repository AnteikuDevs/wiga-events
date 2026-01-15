@extends('layouts.panel.index')

@section('content')
<div id="wiga-alert"></div>

<div class="row g-7">
    <div class="col-lg-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body text-center pt-15">
                <div class="mb-7">
                    {{-- Form Avatar menggunakan method POST dan enctype untuk upload file --}}
                    <div class="image-input image-input-outline image-input-placeholder" id="profile-avatar-wrapper">
                        <div class="image-input-wrapper w-150px h-150px rounded-circle shadow-sm" 
                             style="background-image: url('{{ asset(auth()->user()->avatar_id ? 'file:'.auth()->user()->avatar_id : 'assets/media/avatars/blank.png') }}')"></div>
                        
                        <label class="btn btn-icon btn-circle btn-active-color-primary w-35px h-35px bg-body shadow" 
                               data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Ubah Foto">
                            <i class="fa-solid fa-pencil fs-7"></i>
                            {{-- Input Avatar sesuai validation di Controller --}}
                            <input type="file" name="avatar" accept=".png, .jpg, .jpeg" />
                            <input type="hidden" name="avatar_remove" />
                        </label>
                    </div>
                </div>

                <h3 class="fw-bold text-gray-900 mb-1">{{ auth()->user()->name }}</h3>
                {{-- Menampilkan Role secara dinamis --}}
                <span class="badge badge-light-primary fw-bold px-4 py-2 mb-7">{{ auth()->user()->role->name ?? 'Pengelola Event' }}</span>
                
                <div class="separator separator-dashed my-5"></div>
                
                <div class="text-start">
                    <div class="d-flex align-items-center mb-4">
                        <div class="symbol symbol-40px symbol-circle bg-light-danger me-4">
                            <span class="symbol-label"><i class="fa-solid fa-building text-danger"></i></span>
                        </div>
                        <div>
                            <div class="text-gray-400 small">Instansi</div>
                            <div class="fw-bold text-gray-800">{{ auth()->user()->agency_name }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card shadow-sm border-0 mb-7">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <i class="fa-solid fa-user-gear fs-2 text-primary me-3"></i>
                    <h3 class="fw-bold">Pengaturan Profil</h3>
                </div>
            </div>
            <form id="form-profile-basic">
                <div class="card-body p-9">
                    <div id="alert-profile"></div>
                    <div class="row g-6">
                        <div class="col-md-6">
                            <x-forms.input type="text" name="name" label="Nama Lengkap" value="{{ auth()->user()->name }}"></x-forms.input>
                        </div>
                        <div class="col-md-6">
                            {{-- Username dibuat Readonly sesuai instruksi (tidak bisa diubah) --}}
                            <x-forms.input type="text" name="username" label="Username" value="{{ auth()->user()->username }}" readonly class="bg-light"></x-forms.input>
                        </div>
                        <div class="col-md-6">
                            <x-forms.input type="email" name="email" label="Email" value="{{ auth()->user()->email }}"></x-forms.input>
                        </div>
                        <div class="col-md-6">
                            <x-forms.input type="text" name="phone_number" label="WhatsApp" value="{{ auth()->user()->phone_number }}"></x-forms.input>
                        </div>
                        <div class="col-12">
                            {{-- Disesuaikan dengan agency_name di Controller --}}
                            <x-forms.input type="text" name="agency_name" label="Nama Instansi" value="{{ auth()->user()->agency_name }}"></x-forms.input>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end py-6 px-9 bg-light-faint rounded-bottom">
                    <x-button type="submit" id="btn-save-profile" color="primary" indicator>Simpan Profil</x-button>
                </div>
            </form>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <i class="fa-solid fa-shield-halved fs-2 text-warning me-3"></i>
                    <h3 class="fw-bold">Keamanan Akun</h3>
                </div>
            </div>
            <form id="form-profile-password">
                <div class="card-body p-9">
                    <div id="alert-password"></div>
                    <div class="row g-6">
                        <div class="col-md-12">
                            {{-- Name sesuai dengan validation di updatePassword --}}
                            <x-forms.input type="password" name="current_password" label="Password Sekarang"></x-forms.input>
                        </div>
                        <div class="col-md-6">
                            <x-forms.input type="password" name="password" label="Password Baru"></x-forms.input>
                        </div>
                        <div class="col-md-6">
                            {{-- Harus menggunakan nama password_confirmation untuk fitur 'confirmed' Laravel --}}
                            <x-forms.input type="password" name="password_confirmation" label="Konfirmasi Password Baru"></x-forms.input>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end py-6 px-9 bg-light-faint rounded-bottom">
                    <x-button type="submit" id="btn-update-password" color="warning" indicator>Update Password</x-button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('style')
<style>
    /* Container Utama Image Upload */
    .image-input {
        position: relative;
        display: inline-block;
        border-radius: 50%;
    }

    /* Lingkaran Pratinjau Foto */
    .image-input .image-input-wrapper {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        background-repeat: no-repeat;
        background-size: cover;
        background-position: center;
        border: 4px solid #ffffff;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
        background-color: #f3f6f9;
    }

    /* Tombol Pensil (Ubah Foto) */
    .image-input [data-kt-image-input-action="change"] {
        cursor: pointer;
        position: absolute;
        right: 5px;
        bottom: 5px;
        z-index: 3;
        transition: all 0.3s ease;
        background-color: #ffffff;
        border: 1px solid #e4e6ef;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .image-input [data-kt-image-input-action="change"]:hover {
        background-color: #7c4dff;
        color: #ffffff !important;
        transform: scale(1.1);
    }

    /* Input File Disembunyikan secara visual */
    .image-input input[type="file"] {
        width: 0 !important;
        height: 0 !important;
        overflow: hidden;
        opacity: 0;
        position: absolute;
    }

    /* Desain Card Modern */
    .card {
        border-radius: 15px;
        border: none;
        transition: all 0.3s ease;
    }

    .card-header {
        background-color: transparent;
        padding-top: 1.5rem;
        padding-bottom: 0;
    }

    /* Background Footer yang Lebih Soft */
    .bg-light-faint {
        background-color: #f9f9fc;
    }

    /* Input Field Focus Glow */
    .form-control-solid {
        background-color: #f5f8fa;
        border: 1px solid transparent;
        transition: all 0.2s ease;
    }

    .form-control-solid:focus {
        background-color: #ffffff;
        border-color: #7c4dff;
        box-shadow: 0 0 10px rgba(124, 77, 255, 0.1);
    }

    /* Icon Decoration */
    .symbol-label i {
        font-size: 1.2rem;
    }
</style>
@endpush